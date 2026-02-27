<?php

namespace App\ExternalService\Listeners;

use App\ExternalService\Events\DataGet;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogDataGet
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(DataGet $event): void
    {
        // Aquí puedes agregar la lógica para registrar el evento, por ejemplo, escribir en un archivo de log
        Log::info('DataGet event was triggered');
        Log::info('Data received from API:', $event->data);
    }
}
