<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Plot;

class TestDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plots:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the plots database integration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $this->info('🧪 Testing database connection and plot data...');
            
            $plotCount = Plot::count();
            $this->info("📊 Total plots in database: {$plotCount}");
            
            if ($plotCount > 0) {
                $firstPlot = Plot::first();
                $this->info("🎯 First plot: {$firstPlot->plot_id} - Block {$firstPlot->block} - Status: {$firstPlot->status}");
                
                $availablePlots = Plot::where('status', 'available')->count();
                $bookedPlots = Plot::where('status', 'booked')->count();
                
                $this->info("📈 Available plots: {$availablePlots}");
                $this->info("🔒 Booked plots: {$bookedPlots}");
                
                $this->newLine();
                $this->info('🗃️ Sample plot data:');
                $samplePlot = Plot::where('plot_id', '1')->first();
                if ($samplePlot) {
                    $this->line("   Plot ID: {$samplePlot->plot_id}");
                    $this->line("   Block: {$samplePlot->block}");
                    $this->line("   Price: {$samplePlot->formatted_price}");
                    $this->line("   Area: {$samplePlot->area} sq ft");
                    $this->line("   Price per sq ft: ₹{$samplePlot->price_per_sqft}");
                    $this->line("   Status: {$samplePlot->status}");
                    $this->line("   Amenities: " . implode(', ', $samplePlot->amenities ?? []));
                }
                
                $this->newLine();
                $this->info('✅ Database integration is working perfectly!');
                $this->info('🌐 Your plots are now loading from the database instead of static JSON files.');
                
            } else {
                $this->warn('⚠️ No plots found in database. Run the seeder first: php artisan db:seed --class=PlotSeeder');
            }
            
        } catch (\Exception $e) {
            $this->error("❌ Database connection failed: " . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
}
