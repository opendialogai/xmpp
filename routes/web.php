<?php

use OpenDialogAi\Core\Http\Middleware\RequestLoggerMiddleware;

/**
 * All Requests need to pass the Request Logger Middleware
 */
Route::namespace('OpenDialogAi\Xmpp\Http\Controllers')->group(function () {
    Route::get('/incoming/test', 'IncomingController@test')
        ->name('incoming.test')
        ->middleware(RequestLoggerMiddleware::class);

    Route::post('/incoming/xmpp', 'IncomingController@receive')
        ->name('incoming.xmpp')
        ->middleware(RequestLoggerMiddleware::class);
});
