<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

         $middleware->redirectGuestsTo(function ($request) {
        // Adapte le préfixe '/admin' à celui que tu utilises réellement pour le backoffice
        // if ($request->is('admin/*')) {
        //     return route('admin.login'); // ta route de connexion admin existante
        // }
        if ($request->is('livreur/*')) {
             return route('Livreur-Login'); // ta route de connexion livreur existante
         }
        if ($request->is('client/*')) {
             // ta route de connexion client existante
             return route('Login-Client'); // connexion client
         }
        if ($request->is('fournisseur/*')) {
             // ta route de connexion fournisseur existante
             return route('Fournisseur-Login'); // connexion fournisseur
         }
    });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();
