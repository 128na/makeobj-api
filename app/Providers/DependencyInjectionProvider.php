<?php

namespace App\Providers;

use _128Na\Simutrans\Makeobj\Makeobj;
use App\Services\File\FileService;
use App\Services\File\FileServiceInterface;
use App\Services\Makeobj\MakeobjService;
use App\Services\Makeobj\MakeobjServiceInterface;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;

class DependencyInjectionProvider extends ServiceProvider
{
    public $bindings = [
        MakeobjServiceInterface::class => MakeobjService::class,
        FileServiceInterface::class => FileService::class,
    ];

    public function register()
    {
        $this->app->singleton(Makeobj::class, function ($app) {
            return new Makeobj(config('makeobj.os'), config('makeobj.path'));
        });

        $this->app->bind(FilesystemAdapter::class, function ($app) {
            return Storage::disk('public');
        });
    }
}
