<?php

declare(strict_types=1);

namespace OpenDialogAi\Xmpp\Tests;

use OpenDialogAi\ActionEngine\ActionEngineServiceProvider;
use OpenDialogAi\ContextEngine\ContextEngineServiceProvider;
use OpenDialogAi\ConversationBuilder\ConversationBuilderServiceProvider;
use OpenDialogAi\ConversationEngine\ConversationEngineServiceProvider;
use OpenDialogAi\ConversationLog\ConversationLogServiceProvider;
use OpenDialogAi\Core\CoreServiceProvider;
use OpenDialogAi\InterpreterEngine\InterpreterEngineServiceProvider;
use OpenDialogAi\ResponseEngine\ResponseEngineServiceProvider;
use OpenDialogAi\SensorEngine\SensorEngineServiceProvider;
use OpenDialogAi\Xmpp\XmppServiceProvider;

class TestCase extends \OpenDialogAi\Core\Tests\TestCase
{
    protected function setUp() :void
    {
        parent::setUp();

        try {
            $env = parse_ini_file(__DIR__ . '/../.env');
            if (isset($env['DGRAPH_URL'])) {
                $this->app['config']->set('opendialog.core.DGRAPH_URL', $env['DGRAPH_URL']);
            }
        } catch (\Exception $e) {
            //
        }
    }

    /**
     * Overrides core package providers
     */
    public function getPackageProviders($app)
    {
        return [
            XmppServiceProvider::class,
            CoreServiceProvider::class,
            ActionEngineServiceProvider::class,
            ConversationBuilderServiceProvider::class,
            ConversationEngineServiceProvider::class,
            ConversationLogServiceProvider::class,
            ResponseEngineServiceProvider::class,
            ContextEngineServiceProvider::class,
            InterpreterEngineServiceProvider::class,
            SensorEngineServiceProvider::class,
        ];
    }
}
