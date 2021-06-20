<?php

namespace App\Providers;

use _128Na\Simutrans\Makeobj\Driver\MakeobjDriver;
use _128Na\Simutrans\Makeobj\Makeobj;
use App\Services\Makeobj\MakeobjService;
use App\Services\Makeobj\MakeobjServiceInterface;
use Illuminate\Support\ServiceProvider;

class DependencyInjectionProvider extends ServiceProvider
{
    public $bindings = [
        MakeobjServiceInterface::class => MakeobjService::class,
    ];

    public function register()
    {
        $this->app->singleton(Makeobj::class, function ($app) {
            return new Makeobj(new MakeobjDriver(config('makeobj.path')));
        });
    }
}
