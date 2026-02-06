<?php

namespace App\Console\Commands;

use App\Models\VehicleTrim;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GrabTrimDetails extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'app:grab-trim-details';

    /**
     * The console command description.
     */
    protected $description = 'Grab vehicle trim details and store JSON (append-only)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $trims = VehicleTrim::query()->select('id', 'reference_id')->where('id', '>', 6604)->get();

        $count = 0;
        foreach ($trims as $trim) {
            $count++;
            $response = Http::get(
                "https://carapi.app/api/trims/v2/{$trim->reference_id}"
            );

            if (! $response->successful()) {
                Log::error('Failed fetching car trim', [
                    'trim_id' => $trim->id,
                    'reference_id' => $trim->reference_id,
                    'status'  => $response->status(),
                ]);
                continue; // ⛔ never exit the whole command
            }

            $this->storeTrimResponse($response);

            // ⏱ Rate safety
            if ($count > 60) {
                sleep(60);
                $count = 0;
            } else {
                sleep(2);
            }
        }

        $this->info('Car trim fetch completed successfully.');

        return Command::SUCCESS;
    }

    /**
     * Store API response in JSON file (APPEND ONLY).
     * - Creates file if missing
     * - Never updates existing records
     */
    private function storeTrimResponse($response): void
    {
        $directory = 'car-api';
        $filePath  = "{$directory}/trims.json";

        // Ensure directory exists
        Storage::makeDirectory($directory);

        // Safely extract payload
        $payload = $response->json('data') ?? $response->json();

        if (! is_array($payload)) {
            Log::warning('Invalid API payload structure');
            return;
        }

        // Normalize to array
        $newItems = isset($payload['id']) ? [$payload] : $payload;

        // Load existing records
        $existing = [];

        if (Storage::exists($filePath)) {
            $existing = json_decode(Storage::get($filePath), true) ?? [];
        }

        // Build ID lookup (append-only protection)
        $existingIds = array_column($existing, 'id');

        foreach ($newItems as $item) {
            if (! isset($item['id'])) {
                continue;
            }

            // ⛔ DO NOT UPDATE existing records
            if (in_array($item['id'], $existingIds, true)) {
                continue;
            }

            // ✅ Append new record
            $existing[] = $item;
        }

        // Atomic write
        Storage::put(
            $filePath,
            json_encode(
                $existing,
                JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
            )
        );
    }
}
