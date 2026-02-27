<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogUserRegistered implements ShouldQueue
{
    use InteractsWithQueue;
    /**
     * Create the event listener.
     */
    public $tries = 3;
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserRegistered $event): void
    {
        $this->release(1);
        //Log::info('User registered: ', ["id"=>$event->user->id, "email"=>$event->user->email]);
        throw new Exception("Ocurrio un error al registrar el usuario en el log {$this->attempts()}");
    }

    public function failed(UserRegistered $event, $exception): void
    {
        Log::critical('Error al registrar el usuario en el log: ' . $exception->getMessage(), ["id"=>$event->user->id, "email"=>$event->user->email]);
    }
}
