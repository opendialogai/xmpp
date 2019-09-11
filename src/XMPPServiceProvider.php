<?php

declare(strict_types=1);

namespace OpenDialogAI\XMPP;

use Illuminate\Support\ServiceProvider;

class XMPPServiceProvider extends ServiceProvider
{
    public function boot()
    {
        //
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/config/opendialog-xmpp.php',
            'opendialog.xmpp'
        );
    }
}
