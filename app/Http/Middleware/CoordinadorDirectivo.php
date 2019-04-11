<?php

namespace avaa\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class CoordinadorDirectivo
{   
    protected $auth;
   
    public function __construct(Guard $auth)
    {
        $this->auth=$auth;
    }

    public function handle($request, Closure $next)
    {
        if(($this->auth->user()->esCoordinador()) || ($this->auth->user()->esDirectivo()))
            return $next($request);
        else
            return abort(404,'Acceso Denegado');
    }
}
