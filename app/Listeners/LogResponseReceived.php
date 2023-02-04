<?php

namespace App\Listeners;

use App\Models\LogRequest;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Client\Events\ResponseReceived;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Http\Client\Events;

class LogResponseReceived
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \Illuminate\Http\Client\Events\ResponseReceived  $event
     * @return void
     */
    public function handle(ResponseReceived $event)
    {
        LogRequest::create([
            'requestDateTime' => date("Y-m-d H:i:s"),
            'requestMethod' => $event->request->method(),
            'requestUrl' => $event->request->url(),
            'responseCode' => $event->response->status(),
            'responseBody' => (string)$event->response->body(),
            'responseTime' => $event->response->transferStats->getTransferTime(),
        ]);
    }
}
