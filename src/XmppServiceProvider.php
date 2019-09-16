<?php

declare(strict_types=1);

namespace OpenDialogAi\Xmpp;

use Illuminate\Support\Facades\Log;
use OpenDialogAi\Core\LoggingHelper;
use Illuminate\Support\ServiceProvider;
use OpenDialogAi\ResponseEngine\Service\ResponseEngineService;
use OpenDialogAi\ResponseEngine\Service\ResponseEngineServiceInterface;
use OpenDialogAi\Xmpp\Http\Requests\IncomingXmppMessage;
use OpenDialogAi\Core\Http\Middleware\RequestLoggerMiddleware;
use OpenDialogAi\SensorEngine\Contracts\IncomingMessageInterface;

class XmppServiceProvider extends ServiceProvider
{
    /**
     * @var string $requestId
     */
    private $requestId;

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/opendialog-xmpp.php' => base_path('config/opendialog/xmpp.php')
        ], 'opendialog-config');

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        $this->requestId = uniqid('od-', true);
        $this->app->when(RequestLoggerMiddleware::class)
            ->needs('$requestId')
            ->give($this->requestId);

        Log::pushProcessor(LoggingHelper::getLogUserIdProcessor($this->requestId));
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/opendialog-xmpp.php',
            'opendialog.xmpp'
        );

        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        $this->app->bind(IncomingMessageInterface::class, IncomingXmppMessage::class);

        $this->app->bind(ResponseEngineServiceInterface::class, function () {
            $service = new ResponseEngineService();
            return $service;
        });
    }
}
