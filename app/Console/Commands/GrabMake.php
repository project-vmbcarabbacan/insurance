<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GrabMake extends Command
{

    const RAPIDAPI_KEY = "3ed77a07a5msh223dc148c26fedbp1bf066jsnb0d03016690c";
    const RAPIDAPI_HOST = "car-api2.p.rapidapi.com";
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:grab-make';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Grab Vehicle make from rapidapi';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $years = range(2027, 2000);
        $progressBar = $this->output->createProgressBar(count($years));

        $progressBar->start();

        foreach ($years as $year) {
            $response = Http::withHeaders([
                'x-rapidapi-key'  => self::RAPIDAPI_KEY,
                'x-rapidapi-host' => self::RAPIDAPI_HOST,
            ])->get('https://car-api2.p.rapidapi.com/api/makes', [
                'direction' => 'asc',
                'sort'      => 'id',
                'year'      => $year,
            ]);

            if ($response->successful()) {
                Log::info('Car makes fetched', [
                    'year' => $year,
                    'data' => $response->json()['data'],
                ]);
            } else {
                Log::error('Failed fetching car makes', [
                    'year'   => $year,
                    'status' => $response->status(),
                ]);
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
