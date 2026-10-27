<?php

namespace App\Providers;

use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\Response;
use Dedoc\Scramble\Support\Generator\Schema;
use Dedoc\Scramble\Support\Generator\Types\ObjectType;
use Dedoc\Scramble\Support\Generator\Types\StringType;
use Illuminate\Support\ServiceProvider;
use Throwable;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Відповіді, які Scramble не може вивести з коду (бізнес-правила й firstOrFail()).
        Scramble::afterOpenApiGenerated(function (OpenApi $openApi) {
            $extra = [
                ['delete', 'categories/{category}', 409, 'Конфлікт: категорія містить продукти.'],
                ['get', 'catalog/{slug}', 404, 'Продукт не знайдено.'],
            ];

            foreach ($openApi->paths as $path) {
                foreach ($path->operations as $operation) {
                    foreach ($extra as [$method, $uri, $code, $text]) {
                        if (strtolower($operation->method) === $method
                            && ltrim($path->path, '/') === $uri) {
                            $operation->addResponse($this->messageResponse($code, $text));
                        }
                    }
                }
            }
        });
    }

    private function messageResponse(int $code, string $text): Response
    {
        $response = Response::make($code)->setDescription($text);

        try {
            $type = (new ObjectType())->addProperty('message', new StringType());
            $response->setContent('application/json', Schema::fromType($type));
        } catch (Throwable) {
            // без схеми: достатньо статусу й опису
        }

        return $response;
    }
}