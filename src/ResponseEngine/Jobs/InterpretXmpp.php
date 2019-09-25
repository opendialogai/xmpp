<?php

declare(strict_types=1);

namespace OpenDialogAi\Xmpp\ResponseEngine\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use OpenDialogAi\Xmpp\Utterances\Xmpp\TextUtterance;

class InterpretXmpp implements ShouldQueue
{
    use Queueable;
    use Dispatchable;
    use InteractsWithQueue;

    /**
     * @var TextUtterance
     */
    protected $utterance;

    /**
     * Create a new job instance.
     *
     * @param  TextUtterance  $utterance
     * @return void
     */
    public function __construct(TextUtterance $utterance)
    {
        $this->utterance = $utterance;
    }

    public function handle()
    {
        Log::debug('Interpreting XMPP request.');
    }
}
