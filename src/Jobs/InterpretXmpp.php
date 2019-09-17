<?php

declare(strict_types=1);

namespace OpenDialogAi\Xmpp\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use OpenDialogAi\SensorEngine\SensorInterface;
use OpenDialogAi\Xmpp\SensorEngine\Sensors\XmppSensor;

class InterpretXmpp implements ShouldQueue
{
    use Queueable;
    use Dispatchable;
    use InteractsWithQueue;

    /**
     * @var SensorInterface
     */
    public $sensor;

    /**
     * Create a new job instance.
     *
     * @param  array  $request
     * @return void
     */
    public function __construct()
    {
        $this->sensor = new XmppSensor();
    }

    public function handle()
    {
        Log::debug('Interpreting XMPP request.');
        $this->sensor->interpret(request());
    }
}
