<?php

namespace App\Console\Commands;

use Exception;
use App\Client\ResourceClient;
use App\Services\PeopleService;
use App\Services\PlanetService;
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

    public function __construct(
        private PeopleService $peopleService,
        private PlanetService $planetService
    ) {
        parent::__construct();
    }

    public function handle()
    {

        $resources = [
            'people' => $this->peopleService,
            'planets' => $this->planetService
        ];

        foreach($resources as $resource => $service) {
            $this->migrateResource($resource, $service);
        }

        $this->info('All resources pulled from star-wars api and migrated to database');
    }

    private function migrateResource($resource, $service) {
        $this->info('Fetching ' . $resource . ' data...');
        try {
            $resourceArr = ResourceClient::getResource($resource);
            $this->info($resource . ' data fetched');
        } catch (Exception $ex) {
            $this->error('Exception during data fetching: ' . $ex->getMessage());
            return;
        }
        $this->info('Writing' . $resource . 'data to database');
        $service->store($resourceArr);
        $this->info($resource . ' data stored');
    }
}
