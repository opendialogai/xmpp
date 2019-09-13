<?php

declare(strict_types=1);

namespace OpenDialogAi\Xmpp\Jobs;

use Illuminate\Http\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class InterpretXmpp implements ShouldQueue
{
    use Queueable;
    use Dispatchable;
    use InteractsWithQueue;

    protected $request;

    /**
     * Create a new job instance.
     *
     * @param  array  $request
     * @return void
     */
    public function __construct(array $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        // interpret utterance
    }
}
