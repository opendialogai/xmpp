<?php

declare(strict_types=1);

namespace OpenDialogAI\XMPP;

use Illuminate\Support\Facades\Log;
use OpenDialogAi\Core\LoggingHelper;
use Illuminate\Support\ServiceProvider;
use OpenDialogAi\Core\Http\Middleware\RequestLoggerMiddleware;

class XMPPServiceProvider extends ServiceProvider
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
            __DIR__ . '/config/opendialog-xmpp.php',
            'opendialog.xmpp'
        );
    }
}
