<?php

namespace Modules\Pwa\Facades;

use Illuminate\Support\Facades\Facade;

class Pwa extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \Modules\Pwa\Pwa::class;
    }
}
