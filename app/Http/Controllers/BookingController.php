<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plot;
use Illuminate\Http\JsonResponse;

class BookingController extends Controller
{
    /**
     * Submit a plot booking request
     */
    public function submitBooking(Request $request): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'plot_id' => 'required|string',
                'customer_name' => 'required|string|max:255',
                'customer_email' => 'required|email|max:255',
                'customer_phone' => 'required|string|max:20',
                'customer_message' => 'nullable|string|max:1000'
            ]);

            // Find the plot
            $plot = Plot::where('plot_id', $validatedData['plot_id'])->first();
            
            if (!$plot) {
                return response()->json([
                    'success' => false,
                    'message' => 'Plot not found'
                ], 404);
            }

            if ($plot->status !== 'available') {
                return response()->json([
                    'success' => false,
                    'message' => 'Plot is not available for booking'
                ], 400);
            }

            // For demo purposes, we'll just mark the plot as booked
            // In a real application, you'd create a booking record and handle payment
            $plot->update(['status' => 'booked']);

            // Here you could also:
            // - Send email notifications
            // - Create a booking record
            // - Initialize payment process
            // - Log the booking activity

            return response()->json([
                'success' => true,
                'message' => "Booking request submitted for Plot {$plot->plot_id}! We will contact you shortly.",
                'plot' => [
                    'id' => $plot->plot_id,
                    'block' => $plot->block,
                    'price' => $plot->formatted_price,
                    'status' => $plot->status
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Booking submission failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel a plot booking (for demo)
     */
    public function cancelBooking(Request $request, string $plotId): JsonResponse
    {
        try {
            $plot = Plot::where('plot_id', $plotId)->first();
            
            if (!$plot) {
                return response()->json([
                    'success' => false,
                    'message' => 'Plot not found'
                ], 404);
            }

            if ($plot->status !== 'booked') {
                return response()->json([
                    'success' => false,
                    'message' => 'Plot is not currently booked'
                ], 400);
            }

            $plot->update(['status' => 'available']);

            return response()->json([
                'success' => true,
                'message' => "Plot {$plot->plot_id} booking has been cancelled and is now available.",
                'plot' => [
                    'id' => $plot->plot_id,
                    'status' => $plot->status
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Booking cancellation failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
