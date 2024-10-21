<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

final class AzureOpenAI extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'azure-openai';
    }
}
