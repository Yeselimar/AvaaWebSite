<?php

namespace avaa\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class Editor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */


    protected $auth;

    public function __construct(Guard $auth)
    {
        $this->auth=$auth;
    }

    public function handle($request, Closure $next)
    {
        //si es verdadero sigue con  la petición
        if($this->auth->user()->rol==='editor')
            return $next($request);
        else
            return abort(404,'Acceso Denegado');
    }
}
