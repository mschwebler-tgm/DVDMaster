<?php

namespace App\Service\Facades;

use Illuminate\Support\Facades\Facade;

class ContentTransformer extends Facade
{
    protected static function getFacadeAccessor()
    {
        return '\App\Service\Transformer\ContentTransformer';
    }
}