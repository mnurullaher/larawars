<?php

namespace App\Console\Commands;

use App\Services\Interfaces\ResourceService;
use Exception;
use App\Client\ResourceClient;
use App\Services\PeopleService;
use App\Services\PlanetService;
use App\Services\StarshipService;
use App\Services\VehicleService;
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
        private PlanetService $planetService,
        private VehicleService $vehicleService,
        private StarshipService $starshipService
    ) {
        parent::__construct();
    }

    public function handle()
    {

        $resources = [
            'people' => $this->peopleService,
            'planets' => $this->planetService,
            'vehicles' => $this->vehicleService,
            'starships' => $this->starshipService
        ];

        foreach($resources as $resource => $service) {
            $this->migrateResource($resource, $service);
        }

        $this->info('All resources pulled from star-wars api and migrated to database');
    }

    private function migrateResource(string $resource, ResourceService $service): void {
        $this->info('Fetching ' . $resource . ' data...');
        try {
            $resourceArr = ResourceClient::getResource($resource);
            $this->info($resource . ' data fetched');
        } catch (Exception $ex) {
            $this->error('Exception during data fetching: ' . $ex->getMessage());
            return;
        }
        $this->info('Writing ' . $resource . ' data to database');
        $service->store($resourceArr);
        $this->info($resource . ' data stored');
    }
}
