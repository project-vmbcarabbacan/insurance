<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ClearProductCache extends Command
{
    // The name and signature of the console command.
    protected $signature = 'cache:clear-product {code}';

    // The console command description.
    protected $description = 'Clear the cache for a specific insurance product by code';

    // Execute the console command.
    public function handle()
    {
        $code = $this->argument('code');  // Get the product code argument

        // Clear the cache for the specified product code
        Cache::forget("insurance_product_{$code}");

        $this->info("Cache cleared for product code: {$code}");
    }
}
