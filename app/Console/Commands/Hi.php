<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Hi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:hi 
                            {name? : The name of the person to greet} 
                            {--lastName= : The last name of the person to greet} 
                            {--uppercase : Convert the greeting to uppercase}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Say hi to the world';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $lastName = $this->option('lastName');
        $uppercase = $this->option('uppercase');
        $message = $name ? 'Hi, ' . $name . ($lastName ? ' ' . $lastName : '') . '!' : 'Hi, world!';
        if ($uppercase) {
            $message = strtoupper($message);
        }
        $this->info($message);
    }
}
    