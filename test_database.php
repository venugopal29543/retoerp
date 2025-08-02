<?php

use App\Models\Plot;

// Test database connection
try {
    $plotCount = Plot::count();
    echo "✅ Database connection successful!\n";
    echo "📊 Total plots in database: {$plotCount}\n";
    
    if ($plotCount > 0) {
        $firstPlot = Plot::first();
        echo "🎯 First plot: {$firstPlot->plot_id} - Block {$firstPlot->block} - Status: {$firstPlot->status}\n";
        
        $availablePlots = Plot::where('status', 'available')->count();
        $bookedPlots = Plot::where('status', 'booked')->count();
        
        echo "📈 Available plots: {$availablePlots}\n";
        echo "🔒 Booked plots: {$bookedPlots}\n";
        
        echo "\n🗃️ Sample plot data:\n";
        $samplePlot = Plot::where('plot_id', '1')->first();
        if ($samplePlot) {
            echo "   Plot ID: {$samplePlot->plot_id}\n";
            echo "   Block: {$samplePlot->block}\n";
            echo "   Price: {$samplePlot->formatted_price}\n";
            echo "   Area: {$samplePlot->area} sq ft\n";
            echo "   Price per sq ft: ₹{$samplePlot->price_per_sqft}\n";
            echo "   Status: {$samplePlot->status}\n";
            echo "   Amenities: " . implode(', ', $samplePlot->amenities ?? []) . "\n";
        }
    } else {
        echo "⚠️ No plots found in database. Run the seeder first.\n";
    }
    
} catch (Exception $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "\n";
}
