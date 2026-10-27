<?php

use App\Exceptions\CategoryNotEmpty;
use App\Models\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Для шляхів api/* усі помилки (зокрема 422) повертаються як JSON.
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request, Throwable $e) => $request->is('api/*') || $request->expectsJson()
        );

        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if (! $request->is('api/*')) {
                return null;
            }

            $previous = $e->getPrevious();
            $message = match (true) {
                $previous instanceof ModelNotFoundException && $previous->getModel() === Category::class => 'Категорію не знайдено.',
                $previous instanceof ModelNotFoundException => 'Продукт не знайдено.',
                default => 'Маршрут не знайдено.',
            };

            return response()->json(['message' => $message], 404);
        });

        // Бізнес-правило: категорію з продуктами видаляти не можна (409 Conflict).
        $exceptions->render(function (CategoryNotEmpty $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json(['message' => $e->getMessage()], 409);
            }

            return null;
        });
    })->create();