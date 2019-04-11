<?php

namespace avaa\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class BecarioAdmin
{
    /*
    Middleware creado para que los becarios, coordinadores y directivos tengan acceso 
    a las rutas del módulo de seguimiento.

    Se llama BecarioAdmin, siendo Admin igual Coordinador o Directivo.

    Puse admin para reducir el nombre y evitar que el Middleware se llamara
    BecarioCoordDirect
    */
    protected $auth;

    public function __construct(Guard $auth)
    {
        $this->auth=$auth;
    }

    public function handle($request, Closure $next)
    {
        if(($this->auth->user()->esCoordinador()) || ($this->auth->user()->esDirectivo()) || ($this->auth->user()->esBecario()))
            return $next($request);
        else
            return abort(404,'Acceso Denegado');
    }
}
