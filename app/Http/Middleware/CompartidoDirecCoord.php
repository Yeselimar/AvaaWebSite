<?php

namespace avaa\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class CompartidoDirecCoord
{
    protected $auth;

    public function __construct(Guard $auth)
    {
        $this->auth=$auth;
    }

    public function handle($request, Closure $next)
    {
        //si es verdadero sigue con  la petición
        //revisar el entrevistador
        if(($this->auth->user()->rol==='coordinador') || ($this->auth->user()->rol==='directivo') || ($this->auth->user()->rol==='entrevistador'))
            return $next($request);
        else
            return abort(404,'Acceso Denegado');
    }
}
