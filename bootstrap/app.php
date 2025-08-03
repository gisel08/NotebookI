<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule; // <-- ¡Esta importación sigue siendo CRUCIAL!


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Tu configuración de middleware actual
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Tu configuración de excepciones actual
    })
    // =========================================================================
    // ¡¡¡ ESTA ES LA SECCIÓN CLAVE Y CORRECTA PARA LARAVEL 11 !!!
    // =========================================================================

    // 1. Carga tus comandos usando withCommands().
    //    Este método espera un ARRAY de rutas de directorios donde están tus comandos.
    //    El directorio por defecto es 'app/Console/Commands', así que lo especificamos.
    ->withCommands([ // <-- ¡Recibe un ARRAY!
        __DIR__.'/Commands', // Esto cargará los comandos de app/Console/Commands
    ])
    // 2. Define la programación de tareas con el método `withSchedule()`.
    //    Este método recibe un Closure que toma la instancia de $schedule.
    ->withSchedule(function (Schedule $schedule) { // <-- ¡ESTE es el método correcto para programar!
        $schedule->command('tasks:update-priorities')->everyThirtyMinutes();
        // Para probar rápidamente durante el desarrollo (cada minuto):
        // $schedule->command('tasks:update-priorities')->everyMinute();
    })
    ->create();