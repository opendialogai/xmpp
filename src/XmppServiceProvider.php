<?php

declare(strict_types=1);

namespace OpenDialogAi\Xmpp;

use Illuminate\Support\ServiceProvider;
use OpenDialogAi\ConversationEngine\ConversationEngineInterface;
use OpenDialogAi\ConversationLog\Service\ConversationLogService;
use OpenDialogAi\Core\Controllers\OpenDialogController;
use OpenDialogAi\SensorEngine\SensorInterface;
use OpenDialogAi\SensorEngine\Service\SensorService;
use OpenDialogAi\ResponseEngine\Service\ResponseEngineService;
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

        $this->app->bind(SensorInterface::class, function () {
            $sensorEngine = new SensorService();
            $sensorEngine->registerAvailableSensors();
            return $sensorEngine;
        });

        $this->app->bind(ResponseEngineServiceInterface::class, function () {
            $service = new ResponseEngineService();
            $service->registerAvailableFormatters();
            return $service;
        });

        $this->app->singleton(OpenDialogController::class, function () {
            $odController = new OpenDialogController();

            $odController->setConversationLogService($this->app->make(ConversationLogService::class));
            $odController->setConversationEngine($this->app->make(ConversationEngineInterface::class));
            $odController->setResponseEngine($this->app->make(ResponseEngineServiceInterface::class));

            return $odController;
        });
    }
}
