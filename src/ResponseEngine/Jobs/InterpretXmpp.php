<?php

declare(strict_types=1);

namespace OpenDialogAi\Xmpp\ResponseEngine\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use OpenDialogAi\SensorEngine\SensorInterface;
use OpenDialogAi\Xmpp\DataTransferObjects\XmppDTO;
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
     * @var XmppDTO
     */
    private $dto;

    /**
     * Create a new job instance.
     *
     * @param  array  $request
     * @return void
     */
    public function __construct(XmppDTO $dto)
    {
        $this->dto = $dto;
        $this->sensor = new XmppSensor();
    }

    public function handle()
    {
        Log::debug('Interpreting XMPP request.');
        $this->sensor->interpret($this->dto);
    }
}
