<?php

namespace App\Console\Commands;

use App\Models\VehicleMake;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GrabModel extends Command
{

    const RAPIDAPI_KEY = "3ed77a07a5msh223dc148c26fedbp1bf066jsnb0d03016690c";
    const RAPIDAPI_HOST = "car-api2.p.rapidapi.com";
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:grab-model';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Grab Vehicle models from rapidapi 2015-2020 only allowed for free tier';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $makes = VehicleMake::whereIn('year', range(2020, 2015))->get();

        $progressBar = $this->output->createProgressBar(count($makes));

        $progressBar->start();

        foreach ($makes as $make) {
            $response = Http::withHeaders([
                'x-rapidapi-key'  => self::RAPIDAPI_KEY,
                'x-rapidapi-host' => self::RAPIDAPI_HOST,
            ])->get('https://car-api2.p.rapidapi.com/api/models', [
                'direction' => 'asc',
                'sort'      => 'id',
                'year'      => $make->year,
                'make_id'   => $make->reference_id
            ]);

            if ($response->successful()) {
                Log::info('Car model fetched', [
                    'year' => $make->year,
                    'make_id' => $make->reference_id,
                    'data' => $response->json()['data'],
                ]);
            } else {
                Log::error('Failed fetching car model', [
                    'year'   => $make->year,
                    'make_id' => $make->reference_id,
                    'status' => $response->status(),
                ]);
            }

            sleep(1); // ⏱ 1 second delay
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);
        $this->info('Car model fetch completed successfully.');

        return Command::SUCCESS;
    }
}
