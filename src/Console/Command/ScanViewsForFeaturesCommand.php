<?php

namespace LaravelFeature\Console\Command;

use Illuminate\Console\Command;
use LaravelFeature\Service\FeaturesViewScanner;

class ScanViewsForFeaturesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'feature:scan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scan project views to find new features.';

    /**
     * @var FeaturesViewScanner
     */
    private $service;


    /**
     * Create a new command instance.
     */
    public function __construct(FeaturesViewScanner $service)
    {
        parent::__construct();

        $this->service = $service;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $features = $this->service->scan();
        $areEnabledByDefault = config('features.scanned_default_enabled');

        if (count($features) === 0) {
            $this->warn('No features were found in the project views!');
            return;
        }

        $this->info(count($features) . ' features found in views:');

        foreach ($features as $feature) {
            $this->getOutput()->writeln('- ' . $feature);
        }

        $this->info('All the new features were added to the database with the '
            . ($areEnabledByDefault ? 'ENABLED' : 'disabled') .
            ' status by default. Nothing changed for the already present ones.');

    }
}
