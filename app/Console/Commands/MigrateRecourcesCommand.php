<?php

namespace App\Console\Commands;

use Exception;
use App\Client\ResourceClient;
use App\Services\PeopleService;
use Illuminate\Console\Command;

class MigrateRecourcesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'resources:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate resources to database';

    public function __construct(private PeopleService $peopleService)
    {
        parent::__construct();
    }
    
    public function handle()
    {
        $this->info('Fetching people data...');
        try {
            $peopleArr = ResourceClient::getResource('people');
            $this->info('People data fetched');
        } catch (Exception $ex) {
            $this->error('Exception during data fetching: ' . $ex->getMessage());
            return;
        }

        $this->info('Writing people data to database');
        $this->peopleService->store($peopleArr);

        $this->info('All resources pulled from star-wars api and migrated to database');
    }
}
