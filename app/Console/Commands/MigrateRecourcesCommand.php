<?php

namespace App\Console\Commands;

use App\Models\People;
use App\Services\Interfaces\StorableResource;
use Exception;
use App\Client\ResourceClient;
use App\Services\PeopleService;
use App\Services\PlanetService;
use App\Services\StarshipService;
use App\Services\VehicleService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

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
            try {
                $this->migrateResource($resource, $service);
            } catch (Exception $ex) {
                $this->error('An exception occurred during data fetching: ' . $ex->getMessage());
                return;
            }
        }
        $this->info('All resources pulled from star-wars api and migrated to database');

        $peopleWithStarships = [
            'Han Solo' => 'Millennium Falcon',
            'Luke Skywalker' => 'Star Destroyer',
            'Leia Organa' => 'CR90 corvette',
            'Darth Vader' => 'Death Star'
        ];
        $this->equipWithStarShip($peopleWithStarships);

        $peopleWithVehicles = [
            'Anakin Skywalker' => 'AT-AT',
            'Boba Fett' => 'LAAT/i',
            'Yoda' => 'AT-TE',
            'Qui-Gon Jinn' => 'Vulture Droid'
        ];
        $this->equipWithVehicle($peopleWithVehicles);

        $planetsWithForce = [
            'Tatooine',
            'Yavin IV',
            'Dagobah',
            'Endor',
            'Naboo',
            'Eriadu',
            'Dantooine'
        ];
        $this->info('Equipping planets with force');
        $this->planetService->equipWithForce($planetsWithForce);
        $this->info('Force equipping of planets completed');

        $this->info('Updating planet population');
        foreach ($this->planetService->getALlPlanets() as $planet) {
            $planet->population = intval($planet->population) + count($planet->immigrants);
            $planet->update();
        }
        $this->info('Planet population updating completed');

    }

    private function migrateResource(string $resource, StorableResource $service): void {
        $this->info('Fetching ' . $resource . ' data...');
        try {
            $resourceArr = ResourceClient::getResource($resource);
            $this->info($resource . ' data fetched');
        } catch (Exception $ex) {
            throw $ex;
        }
        $this->info('Writing ' . $resource . ' data to database');
        $service->store($resourceArr);
        $this->info($resource . ' data stored');
    }

    private function equipWithStarShip($peopleWithStarships):void {
        $this->info('Equipping people with starships');
        foreach ($peopleWithStarships as $person => $starship) {
            $this->starshipService->attachToPerson($person, $starship);
        }
        $this->info('Starship equipping completed');
    }

    public function equipWithVehicle($peopleWithVehicles):void {
        $this->info('Equipping people with vehicles');
        foreach ($peopleWithVehicles as $person => $vehicle) {
            $this->vehicleService->attachToPerson($person, $vehicle);
        }
        $this->info('Vehicle equipping completed');
    }
}
