<?php

declare(strict_types=1);

namespace OpenDialogAI\Example;

use Illuminate\Support\ServiceProvider;

class ExampleServiceProvider extends ServiceProvider
{
    public function boot()
    {
        //
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/config/opendialog-example.php',
            'opendialog.example'
        );
    }
}
