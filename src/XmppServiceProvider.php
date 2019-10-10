<?php

namespace OpenDialogAi\Xmpp;

use Illuminate\Support\ServiceProvider;
use OpenDialogAi\Xmpp\Communications\Adapters\CamelAdapter;
use OpenDialogAi\Xmpp\Communications\CommunicationServiceInterface;
use OpenDialogAi\Xmpp\Communications\Service\CommunicationService;

class XmppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/opendialog-xmpp.php' => base_path('config/opendialog/xmpp.php')
        ], 'opendialog-config');

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/opendialog-xmpp.php',
            'opendialog.xmpp'
        );

        // Merge in XMPP sensor
        $this->mergeConfigFrom(
            __DIR__ . '/../config/opendialog-sensorengine.php',
            'opendialog.sensor_engine'
        );

        // Merge in XMPP formatter
        $this->mergeConfigFrom(
            __DIR__ . '/../config/opendialog-responseengine.php',
            'opendialog.response_engine'
        );

        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        $this->app->singleton(CommunicationServiceInterface::class, function () {
            $service = new CommunicationService(new CamelAdapter());

            $service->build([
                'url' => config('opendialog.xmpp.communications.camel.url'),
                'port' => config('opendialog.xmpp.communications.camel.port'),
                'protocol' => config('opendialog.xmpp.communications.camel.protocol'),
                'endpoint' => config('opendialog.xmpp.communications.camel.endpoint'),
            ]);

            return $service;
        });
    }
}
