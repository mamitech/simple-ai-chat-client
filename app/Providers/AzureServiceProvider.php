<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use OpenAI;
use OpenAI\Client;
use OpenAI\Laravel\Exceptions\ApiKeyIsMissing;

class AzureServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('azure-openai', static function (): Client {
            $apiKey = config('openai.azure_api_key');
            $deployment = config('openai.azure_deployment');

            if (!is_string($apiKey)) {
                throw ApiKeyIsMissing::create();
            }

            return OpenAI::factory()
                ->withBaseUri('https://ai-chat-eastus.openai.azure.com/openai/deployments/' . $deployment)
                ->withHttpHeader('api-key', $apiKey)
                ->withQueryParam('api-version', '2024-08-01-preview')
                ->make();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
