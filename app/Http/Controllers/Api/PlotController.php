<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plot;
use Illuminate\Http\JsonResponse;

class PlotController extends Controller
{
    /**
     * Display a listing of all plots with layout structure
     */
    public function index(): JsonResponse
    {
        try {
            $plots = Plot::orderBy('plot_id')->get();
            
            // Calculate statistics
            $totalPlots = $plots->count();
            $availablePlots = $plots->where('status', 'available')->count();
            $bookedPlots = $plots->where('status', 'booked')->count();
            
            // Structure the response similar to the original JSON
            $response = [
                'layouts' => [
                    [
                        'name' => 'Sathenapally Main Layout',
                        'id' => 'main_layout',
                        'description' => 'Primary residential layout with premium plots',
                        'plots' => $plots->map(function ($plot) {
                            return [
                                'id' => $plot->plot_id,
                                'displayName' => $plot->display_name,
                                'block' => $plot->block,
                                'coordinates' => $plot->coordinates,
                                'price' => $plot->price,
                                'area' => $plot->area,
                                'status' => $plot->status,
                                'amenities' => $plot->amenities,
                                'layout' => $plot->layout,
                                'pricePerSqft' => $plot->price_per_sqft,
                                'formattedPrice' => $plot->formatted_price
                            ];
                        })->values()
                    ]
                ],
                'metadata' => [
                    'version' => '1.0',
                    'lastUpdated' => now()->format('Y-m-d'),
                    'totalPlots' => $totalPlots,
                    'availablePlots' => $availablePlots,
                    'bookedPlots' => $bookedPlots,
                    'coordinateSystem' => 'SVG',
                    'transformMatrix' => 'matrix(0.12,0,0,0.12,2,2)'
                ]
            ];
            
            return response()->json($response);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch plots',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get plots by status
     */
    public function getByStatus(string $status): JsonResponse
    {
        try {
            $plots = Plot::where('status', $status)->orderBy('plot_id')->get();
            
            return response()->json([
                'plots' => $plots,
                'count' => $plots->count()
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch plots by status',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get plots by block
     */
    public function getByBlock(string $block): JsonResponse
    {
        try {
            $plots = Plot::where('block', $block)->orderBy('plot_id')->get();
            
            return response()->json([
                'plots' => $plots,
                'count' => $plots->count()
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch plots by block',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $plotId): JsonResponse
    {
        try {
            $plot = Plot::where('plot_id', $plotId)->firstOrFail();
            
            return response()->json([
                'plot' => [
                    'id' => $plot->plot_id,
                    'displayName' => $plot->display_name,
                    'block' => $plot->block,
                    'coordinates' => $plot->coordinates,
                    'price' => $plot->price,
                    'area' => $plot->area,
                    'status' => $plot->status,
                    'amenities' => $plot->amenities,
                    'layout' => $plot->layout,
                    'pricePerSqft' => $plot->price_per_sqft,
                    'formattedPrice' => $plot->formatted_price
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Plot not found',
                'message' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $plotId): JsonResponse
    {
        try {
            $plot = Plot::where('plot_id', $plotId)->firstOrFail();
            
            $validatedData = $request->validate([
                'status' => 'sometimes|in:available,booked,reserved,sold',
                'price' => 'sometimes|numeric|min:0',
                'area' => 'sometimes|integer|min:1',
                'amenities' => 'sometimes|array',
                'description' => 'sometimes|string|nullable'
            ]);
            
            $plot->update($validatedData);
            
            return response()->json([
                'message' => 'Plot updated successfully',
                'plot' => $plot
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to update plot',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get plot statistics
     */
    public function statistics(): JsonResponse
    {
        try {
            $stats = [
                'total' => Plot::count(),
                'available' => Plot::available()->count(),
                'booked' => Plot::booked()->count(),
                'reserved' => Plot::where('status', 'reserved')->count(),
                'sold' => Plot::where('status', 'sold')->count(),
                'by_block' => Plot::select('block')
                    ->selectRaw('count(*) as total')
                    ->selectRaw('sum(case when status = "available" then 1 else 0 end) as available')
                    ->selectRaw('sum(case when status = "booked" then 1 else 0 end) as booked')
                    ->groupBy('block')
                    ->get(),
                'price_range' => [
                    'min' => Plot::min('price'),
                    'max' => Plot::max('price'),
                    'average' => Plot::avg('price')
                ]
            ];
            
            return response()->json($stats);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch statistics',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
