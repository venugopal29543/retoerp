<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SATTENAPALLI Layout Plan - Plot Booking System</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom CSS Components -->
    <link rel="stylesheet" href="{{ asset('css/plot-layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modals.css') }}">
    <link rel="stylesheet" href="{{ asset('css/forms.css') }}">
    
    <style>
        /* Compact UI styles */
        .compact-header {
            padding: 0.5rem 0;
            margin-bottom: 0.5rem;
        }
        
        .control-panel {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 1rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        .search-container {
            display: flex;
            gap: 0.5rem;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .filter-group {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }
        
        /* Input and button styles */
        .search-input {
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            font-size: 0.875rem;
            min-width: 150px;
        }
        
        .btn-primary {
            background-color: #3b82f6;
            color: white;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 4px;
            font-size: 0.875rem;
            cursor: pointer;
        }
        
        .btn-secondary {
            background-color: #6b7280;
            color: white;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 4px;
            font-size: 0.875rem;
            cursor: pointer;
        }
        
        .btn-primary:hover, .btn-secondary:hover {
            opacity: 0.9;
        }
        
        /* Status indicators */
        .status-indicator {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 0.5rem;
        }
        
        .status-available {
            background-color: #10b981;
        }
        
        .status-booked {
            background-color: #ef4444;
        }
        
        .status-blocked {
            background-color: #f59e0b;
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Navigation Header Component -->
    <x-layout.header />

    <!-- Main Content -->
    <div class="max-w-full mx-auto p-2">
        <!-- Compact Header -->
        <div class="compact-header text-center">
            <h2 class="text-2xl font-bold text-gray-800 mb-1">SATTENAPALLI Layout - Plot Booking</h2>
        </div>

        <!-- Control Panel Component -->
        <x-control-panel />

        <!-- Plot Layout Component -->
        <x-plot-layout />

        <!-- Statistics Cards Component -->
        <x-statistics-cards />
    </div>

    <!-- Modal Components -->
    <x-modals.plot-details />
    <x-modals.emi-calculator />

    <!-- JavaScript Modules -->
    <script src="{{ asset('js/plot-manager.js') }}"></script>
    <script src="{{ asset('js/role-manager.js') }}"></script>
    <script src="{{ asset('js/modal-manager.js') }}"></script>
    
    <script>
        // Simple plot click handlers for SVG elements
        function handlePlotClick(plotId) {
            if (window.plotManager) {
                window.plotManager.openEnhancedModal(plotId, 'A', '₹15,00,000', '1200 sq ft', 'available');
            } else {
                alert("Plot clicked: " + plotId);
            }
        }

        function highlightPlot(el) {
            el.setAttribute("fill", "#cce5ff");
        }

        function unhighlightPlot(el) {
            el.setAttribute("fill", "#dff0d8");
        }

        // Search and filter functions
        function highlightSearchResult() {
            const searchTerm = document.getElementById('plotSearchInput').value;
            if (window.plotManager) {
                window.plotManager.searchPlots(searchTerm);
            }
        }

        function clearHighlight() {
            if (window.plotManager) {
                window.plotManager.clearSearch();
            }
        }

        function filterByStatus(status) {
            if (window.plotManager) {
                window.plotManager.filterPlots(status);
            }
        }
    </script>
</body>
</html>
