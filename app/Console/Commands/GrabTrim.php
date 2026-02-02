<?php

namespace App\Console\Commands;

use App\Models\VehicleMake;
use App\Models\VehicleModel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GrabTrim extends Command
{

    const RAPIDAPI_KEY = "3ed77a07a5msh223dc148c26fedbp1bf066jsnb0d03016690c";
    const RAPIDAPI_HOST = "car-api2.p.rapidapi.com";
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:grab-trim';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Grab Vehicle trim from rapidapi';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $models = VehicleModel::whereBetween('id', [2173, 2215])->get();
        $progressBar = $this->output->createProgressBar(count($models));

        $progressBar->start();

        foreach ($models as $model) {
            $response = Http::withHeaders([
                'x-rapidapi-key'  => self::RAPIDAPI_KEY,
                'x-rapidapi-host' => self::RAPIDAPI_HOST,
            ])->get('https://car-api2.p.rapidapi.com/api/trims', [
                'direction' => 'asc',
                'sort'      => 'id',
                'year' => $model->year,
                'make_model_id' => $model->reference_id,
                'verbose' => 'yes',
                'make_id' => $model->vehicle_make_id
            ]);

            if ($response->successful()) {
                Log::info('Car trims fetched', [
                    'year' => $model->year,
                    'make_model_id' => $model->reference_id,
                    'make_id' => $model->vehicle_make_id,
                    'data' => $response->json()['data'],
                ]);
            } else {
                Log::error('Failed fetching car trims', [
                    'year' => $model->year,
                    'make_model_id' => $model->reference_id,
                    'make_id' => $model->vehicle_make_id,
                    'status' => $response->status(),
                ]);
                exit;
            }

            sleep(1); // ⏱ 1 second delay
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);
        $this->info('Car makes fetch completed successfully.');

        return Command::SUCCESS;
    }
}
