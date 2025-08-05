<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PlotSyncController extends Controller
{
    /**
     * Update plot data and sync across sessions
     */
    public function updatePlot(Request $request, $plotId = null)
    {
        try {
            // Handle both PUT /api/sync/plots/{plotId} and POST /api/plots/update
            $plotId = $plotId ?? $request->input('plotId');
            
            $validated = $request->validate([
                'plotId' => 'required_without:' . ($plotId ? 'plotId' : 'invalid'),
                'status' => 'sometimes|in:available,booked,blocked',
                'price' => 'sometimes|numeric|min:0',
                'area' => 'sometimes|numeric|min:0',
                'displayName' => 'sometimes|string|max:50',
                'block' => 'sometimes|string|max:10',
                'amenities' => 'sometimes|array',
                'amenities.*' => 'string'
            ]);

            // Use plotId from route parameter or request body
            if (!$plotId) {
                $plotId = $validated['plotId'];
            }

            // Load current plots data
            $plotsData = $this->loadPlotsData();
            
            // Find and update the plot
            $plotUpdated = false;
            $updatedPlot = null;
            
            foreach ($plotsData['layouts'] as &$layout) {
                foreach ($layout['plots'] as &$plot) {
                    if ($plot['id'] === $plotId) {
                        // Update plot data
                        foreach ($validated as $key => $value) {
                            if ($key !== 'plotId') {
                                $plot[$key] = $value;
                            }
                        }
                        $updatedPlot = $plot;
                        $plotUpdated = true;
                        break 2;
                    }
                }
            }
            
            if (!$plotUpdated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Plot not found'
                ], 404);
            }
            
            // Save updated data
            $this->savePlotsData($plotsData);
            
            // Broadcast sync event (in real app, use WebSockets/Pusher)
            $syncData = [
                'type' => 'plot_update',
                'plotId' => $plotId,
                'changes' => $validated,
                'timestamp' => now()->toISOString()
            ];
            
            return response()->json([
                'success' => true,
                'message' => 'Plot updated permanently in database',
                'plot' => $updatedPlot,
                'sync' => $syncData
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update plot: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a plot
     */
    public function deletePlot(Request $request)
    {
        try {
            $validated = $request->validate([
                'plotId' => 'required|string'
            ]);

            $plotId = $validated['plotId'];

            // Load current plots data
            $plotsData = $this->loadPlotsData();
            
            // Find and delete the plot
            $plotDeleted = false;
            
            foreach ($plotsData['layouts'] as &$layout) {
                foreach ($layout['plots'] as $index => $plot) {
                    if ($plot['id'] === $plotId) {
                        // Remove the plot
                        array_splice($layout['plots'], $index, 1);
                        $plotDeleted = true;
                        break 2;
                    }
                }
            }
            
            if (!$plotDeleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Plot not found'
                ], 404);
            }
            
            // Save updated data
            $this->savePlotsData($plotsData);
            
            return response()->json([
                'success' => true,
                'message' => 'Plot deleted permanently from database'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete plot: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new plot
     */
    public function createPlot(Request $request)
    {
        try {
            $validated = $request->validate([
                'id' => 'required|string|unique_plot_id', // Custom validation rule needed
                'displayName' => 'required|string|max:50',
                'block' => 'required|string|max:10',
                'status' => 'sometimes|in:available,booked,blocked',
                'price' => 'sometimes|numeric|min:0',
                'area' => 'sometimes|numeric|min:0',
                'layout' => 'required|string|max:100',
                'amenities' => 'sometimes|array',
                'amenities.*' => 'string',
                'coordinates' => 'sometimes|array'
            ]);

            // Set defaults
            $validated['status'] = $validated['status'] ?? 'available';
            $validated['price'] = $validated['price'] ?? 1000000;
            $validated['area'] = $validated['area'] ?? 1500;
            $validated['amenities'] = $validated['amenities'] ?? ['Water', 'Electricity', 'Road Access'];
            $validated['coordinates'] = $validated['coordinates'] ?? [];

            // Load current plots data
            $plotsData = $this->loadPlotsData();
            
            // Find the layout or create it
            $layoutFound = false;
            foreach ($plotsData['layouts'] as &$layout) {
                if ($layout['name'] === $validated['layout']) {
                    $layout['plots'][] = $validated;
                    $layoutFound = true;
                    break;
                }
            }
            
            // If layout not found, create a new one
            if (!$layoutFound) {
                $plotsData['layouts'][] = [
                    'name' => $validated['layout'],
                    'plots' => [$validated]
                ];
            }
            
            // Save updated data
            $this->savePlotsData($plotsData);
            
            return response()->json([
                'success' => true,
                'message' => 'Plot created successfully',
                'plot' => $validated
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create plot: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get current plot statistics
     */
    public function getStatistics()
    {
        try {
            $plotsData = $this->loadPlotsData();
            
            $stats = [
                'available' => 0,
                'booked' => 0,
                'blocked' => 0,
                'total' => 0
            ];
            
            foreach ($plotsData['layouts'] as $layout) {
                foreach ($layout['plots'] as $plot) {
                    $stats['total']++;
                    $status = $plot['status'] ?? 'available';
                    if (isset($stats[$status])) {
                        $stats[$status]++;
                    }
                }
            }
            
            return response()->json([
                'success' => true,
                'statistics' => $stats
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get statistics: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get all plots data
     */
    public function getAllPlots()
    {
        try {
            $plotsData = $this->loadPlotsData();
            
            $allPlots = [];
            foreach ($plotsData['layouts'] as $layout) {
                foreach ($layout['plots'] as $plot) {
                    $plot['layout'] = $plot['layout'] ?? $layout['name'];
                    $allPlots[] = $plot;
                }
            }
            
            return response()->json([
                'success' => true,
                'plots' => $allPlots,
                'total' => count($allPlots)
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get plots: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Load plots data from JSON file
     */
    private function loadPlotsData()
    {
        $jsonPath = public_path('data/plots.json');
        
        if (!file_exists($jsonPath)) {
            throw new \Exception('Plots data file not found');
        }
        
        $jsonContent = file_get_contents($jsonPath);
        $data = json_decode($jsonContent, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Invalid JSON in plots data file');
        }
        
        return $data;
    }
    
    /**
     * Save plots data to JSON file
     */
    private function savePlotsData($data)
    {
        $jsonPath = public_path('data/plots.json');
        
        $jsonContent = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        
        if (file_put_contents($jsonPath, $jsonContent) === false) {
            throw new \Exception('Failed to save plots data');
        }
    }
}
