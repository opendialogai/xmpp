<?php

declare(strict_types=1);

namespace OpenDialogAi\XMPP\Tests;

class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * @var bool Whether DGraph has been initialised or not
     */
    private $dgraphInitialised = false;

    protected function setUp(): void
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

        if (!defined('LARAVEL_START')) {
            define('LARAVEL_START', microtime(true));
        }

        $this->artisan('migrate', [
            '--database' => 'testbench'
        ]);
    }

    /**
     * Sets a config value to the app
     *
     * @param $configName
     * @param $config
     */
    public function setConfigValue($configName, $config)
    {
        $this->app['config']->set($configName, $config);
    }

    protected function getEnvironmentSetUp($app)
    {
        # Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    public function getPackageProviders($app)
    {
        return [
            \OpenDialogAi\Xmpp\XmppServiceProvider::class
        ];
    }
}
