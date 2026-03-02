<?php

namespace App\Console\Commands;

use App\ExternalService\ApiService;
use Illuminate\Console\Command;

class ApiInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:api-info';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consulta el Api tercero';

    public function __construct(protected ApiService $apiService)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $jsonString = json_encode($this->apiService->getData());
        $this->info('Data from API:');
        $this->info('ID: ' . $jsonString);

    }
}
