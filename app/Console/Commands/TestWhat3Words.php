<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\What3WordsService;

class TestWhat3Words extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:what3words';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test What3Words Indonesian integration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing What3Words Indonesian integration...');

        // Test coordinates: Jakarta area
        $latitude = -6.2088;
        $longitude = 106.8456;

        $this->info("Coordinates: {$latitude}, {$longitude}");

        $service = new What3WordsService();

        // Test conversion to words
        $words = $service->convertToWords($latitude, $longitude);
        if ($words) {
            $this->info("What3Words (Indonesian): {$words}");

            // Test reverse conversion
            $coordinates = $service->convertToCoordinates($words);
            if ($coordinates) {
                $this->info("Reverse conversion: {$coordinates['latitude']}, {$coordinates['longitude']}");
            } else {
                $this->error("Reverse conversion failed");
            }
        } else {
            $this->error("Conversion failed");
        }

        $this->info('Test completed.');

        return 0;
    }
}
