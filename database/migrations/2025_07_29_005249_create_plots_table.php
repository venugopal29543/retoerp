<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('plots', function (Blueprint $table) {
            $table->id();
            $table->string('plot_id')->unique(); // Plot identifier (1, 2, 3, etc.)
            $table->string('display_name'); // Display name for the plot
            $table->string('block'); // Block letter (A, B, C, D)
            $table->json('coordinates'); // Store coordinates as JSON
            $table->decimal('price', 15, 2); // Plot price
            $table->integer('area'); // Area in square feet
            $table->enum('status', ['available', 'booked', 'reserved', 'sold'])->default('available');
            $table->json('amenities')->nullable(); // Store amenities as JSON array
            $table->string('layout')->default('Sathenapally Main'); // Layout name
            $table->text('description')->nullable(); // Plot description
            $table->string('layout_id')->default('main_layout'); // Layout identifier
            $table->timestamps();
            
            // Add indexes for better performance
            $table->index(['status']);
            $table->index(['block']);
            $table->index(['layout_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plots');
    }
};
