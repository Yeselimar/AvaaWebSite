<?php

namespace avaa\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \avaa\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        \avaa\Http\Middleware\TrustProxies::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \avaa\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \avaa\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            'throttle:60,1',
            'bindings',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \avaa\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'admin' => \avaa\Http\Middleware\Admin::class,
        'editor' => \avaa\Http\Middleware\Editor::class,
        'becario' => \avaa\Http\Middleware\Becario::class,
        'coordinador' => \avaa\Http\Middleware\Coordinador::class,
        'directivo' => \avaa\Http\Middleware\Directivo::class,
        'mentor' => \avaa\Http\Middleware\Mentor::class,
        'postulante_becario' => \avaa\Http\Middleware\PostulanteBecario::class,
        'postulante_mentor' => \avaa\Http\Middleware\PostulanteMentor::class,
        'compartido_direc_coord' => \avaa\Http\Middleware\CompartidoDirecCoord::class,
        'compartido_mentor_becario' => \avaa\Http\Middleware\CompartidoMentorBecario::class,
        'admin_becario' => \avaa\Http\Middleware\BecarioAdmin::class,//Middleware para Becario, Coordinador, Directivo.
        'entrevistador' => \avaa\Http\Middleware\Entrevistador::class,
        'coordinador_directivo' => \avaa\Http\Middleware\CoordinadorDirectivo::class,

    ];
}
