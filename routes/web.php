<?php

use OpenDialogAi\Core\Http\Middleware\RequestLoggerMiddleware;

/**
 * All Requests need to pass the Request Logger Middleware
 */
Route::namespace('OpenDialogAi\Xmpp\Http\Controllers')->group(function () {
    Route::post('/incoming/xmpp', 'IncomingController@receive')
        ->name('incoming.xmpp')
        ->middleware(RequestLoggerMiddleware::class);
});
