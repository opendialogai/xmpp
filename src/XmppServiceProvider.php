<?php

declare(strict_types=1);

namespace OpenDialogAi\Xmpp;

use Illuminate\Support\ServiceProvider;
use OpenDialogAi\SensorEngine\SensorInterface;
use OpenDialogAi\SensorEngine\Service\SensorService;
use OpenDialogAi\ResponseEngine\Service\ResponseEngineService;
use OpenDialogAi\SensorEngine\Contracts\IncomingMessageInterface;
use OpenDialogAi\Xmpp\SensorEngine\Http\Requests\IncomingXmppMessage;
use OpenDialogAi\ResponseEngine\Service\ResponseEngineServiceInterface;

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

        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        $this->app->bind(SensorInterface::class, function () {
            $sensorEngine = new SensorService();
            $sensorEngine->registerAvailableSensors();
            return $sensorEngine;
        });

        $this->app->bind(IncomingMessageInterface::class, IncomingXmppMessage::class);

        $this->app->bind(ResponseEngineServiceInterface::class, function () {
            $service = new ResponseEngineService();
            return $service;
        });
    }
}
