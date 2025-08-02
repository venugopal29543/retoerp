<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SATTENAPALLI Layout Plan - Plot Booking System</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('css/plot-layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modals.css') }}">
    <link rel="stylesheet" href="{{ asset('css/forms.css') }}">
    
    <!-- Vite Assets (uncomment when running npm run dev) -->
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/plot-layout.css', 'resources/css/modals.css', 'resources/css/forms.css', 'resources/js/plot-manager.js', 'resources/js/role-manager.js', 'resources/js/modal-manager.js']) --}}
    
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
        
        /* Basic modal styles for fallback */
        .enhanced-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            padding: 20px;
            box-sizing: border-box;
        }
        
        .modal-content {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            max-width: 900px;
            width: 100%;
            max-height: 90vh;
            overflow: hidden;
            position: relative;
        }
        
        /* Mobile responsive adjustments */
        @media (max-width: 768px) {
            .enhanced-modal {
                padding: 10px;
                align-items: flex-start;
                padding-top: 20px;
            }
            
            .modal-content {
                width: 100%;
                max-width: 100%;
                max-height: 95vh;
                margin: 0;
                border-radius: 10px;
            }
            
            .modal-header {
                padding: 1rem !important;
                flex-direction: column;
                text-align: center;
                gap: 0.5rem;
            }
            
            .modal-header h3 {
                font-size: 1.25rem !important;
                margin: 0;
            }
            
            .modal-close {
                position: absolute;
                top: 10px;
                right: 10px;
                width: 30px !important;
                height: 30px !important;
                font-size: 1.5rem !important;
            }
        }
        
        @media (max-width: 480px) {
            .modal-content {
                border-radius: 0;
                max-height: 100vh;
                height: 100vh;
            }
            
            .enhanced-modal {
                padding: 0;
                align-items: stretch;
            }
        }
        
        .modal-tabs {
            display: flex;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        .modal-tab {
            padding: 1rem;
            background: none;
            border: none;
            cursor: pointer;
            font-weight: 500;
            color: #64748b;
            border-bottom: 3px solid transparent;
            white-space: nowrap;
            flex-shrink: 0;
            min-width: fit-content;
        }
        
        .modal-tab.active {
            color: #3b82f6;
            border-bottom-color: #3b82f6;
        }
        
        .tab-content {
            display: none;
            padding: 1.5rem;
            overflow-y: auto;
            max-height: calc(90vh - 200px);
        }
        
        .tab-content.active {
            display: block;
        }
        
        /* Mobile tab adjustments */
        @media (max-width: 768px) {
            .modal-tabs {
                border-bottom: 1px solid #e2e8f0;
                justify-content: space-around;
            }
            
            .modal-tab {
                padding: 0.75rem 0.5rem;
                font-size: 0.875rem;
                text-align: center;
                flex: 1;
                min-width: 0;
            }
            
            .modal-tab span {
                display: block;
                margin-bottom: 0.25rem;
            }
            
            .modal-tab .tab-text {
                font-size: 0.75rem;
            }
            
            .tab-content {
                padding: 1rem;
                max-height: calc(95vh - 140px);
            }
        }
        
        @media (max-width: 480px) {
            .modal-tab {
                padding: 0.5rem 0.25rem;
                font-size: 0.75rem;
            }
            
            .modal-tab span {
                font-size: 1rem;
            }
            
            .modal-tab .tab-text {
                display: none;
            }
            
            .tab-content {
                padding: 0.75rem;
                max-height: calc(100vh - 120px);
            }
        }
        
        /* Plot interaction styles */
        .plot-highlighted {
            fill: #cce5ff !important;
            stroke: #007bff !important;
            stroke-width: 4 !important;
        }
        
        /* Zoom controls */
        .zoom-controls {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 10;
            display: flex;
            gap: 5px;
        }
        
        .zoom-btn {
            background: #fff;
            border: 1px solid #ddd;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }
        
        .zoom-btn:hover {
            background: #f0f0f0;
        }
        
        /* Mobile zoom controls */
        @media (max-width: 768px) {
            .zoom-controls {
                top: 5px;
                right: 5px;
                gap: 3px;
            }
            
            .zoom-btn {
                padding: 6px 10px;
                font-size: 0.875rem;
            }
        }
        
        @media (max-width: 480px) {
            .zoom-controls {
                flex-direction: column;
                gap: 2px;
            }
            
            .zoom-btn {
                padding: 4px 8px;
                font-size: 0.75rem;
                min-width: 30px;
            }
        }
        
        /* SVG Layout mobile adjustments */
        @media (max-width: 768px) {
            .layout-container {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            
            #layoutContainer {
                min-width: 600px;
            }
        }
        
        @media (max-width: 480px) {
            #layoutContainer {
                min-width: 500px;
            }
        }
        
        /* Additional classes for enhanced modal */
        .hidden {
            display: none !important;
        }
        
        .rounded-15 {
            border-radius: 15px;
        }
        
        .space-y-2 > * + * {
            margin-top: 0.5rem;
        }
        
        .flex-2 {
            flex: 2;
        }
        
        /* Gallery and video enhancements */
        .gallery-container {
            text-align: center;
        }
        
        .gallery-main-image {
            max-width: 100%;
            height: auto;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .gallery-thumbnails {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 1rem;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        .gallery-thumbnail {
            width: 80px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            cursor: pointer;
            border: 3px solid transparent;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }
        
        .gallery-thumbnail:hover,
        .gallery-thumbnail.active {
            border-color: #3b82f6;
            transform: scale(1.1);
        }
        
        /* Mobile gallery adjustments */
        @media (max-width: 768px) {
            .gallery-thumbnails {
                gap: 0.5rem;
                padding: 0 1rem;
            }
            
            .gallery-thumbnail {
                width: 60px;
                height: 45px;
            }
            
            .gallery-main-image {
                border-radius: 10px;
            }
        }
        
        @media (max-width: 480px) {
            .gallery-thumbnails {
                justify-content: flex-start;
                padding-left: 0;
            }
            
            .gallery-thumbnail {
                width: 50px;
                height: 38px;
            }
        }
        
        /* Form grid improvements */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }
        
        .plot-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }
        
        .plot-info-item {
            text-align: center;
        }
        
        .plot-info-label {
            font-size: 0.875rem;
            opacity: 0.8;
            margin-bottom: 0.25rem;
        }
        
        .plot-info-value {
            font-size: 1.125rem;
            font-weight: 700;
        }
        
        /* Mobile form adjustments */
        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .plot-info-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 0.75rem;
            }
            
            .search-container {
                flex-direction: column;
                align-items: stretch;
                gap: 0.75rem;
            }
            
            .filter-group {
                justify-content: center;
            }
            
            .search-input {
                min-width: 100%;
            }
        }
        
        @media (max-width: 480px) {
            .plot-info-grid {
                grid-template-columns: 1fr;
            }
            
            .control-panel {
                padding: 0.75rem;
            }
            
            .btn-primary, .btn-secondary {
                width: 100%;
                margin-bottom: 0.5rem;
            }
            
            .filter-group {
                flex-direction: column;
                align-items: stretch;
            }
        }
        
        /* Property details grid */
        .property-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .property-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 1rem;
            text-align: center;
        }
        
        .property-card.price-highlight {
            background: linear-gradient(135deg, #dbeafe 0%, #eff6ff 100%);
            border-color: #3b82f6;
        }
        
        .property-label {
            font-size: 0.875rem;
            color: #6b7280;
            margin-bottom: 0.5rem;
        }
        
        .property-value {
            font-size: 1.125rem;
            font-weight: 600;
            color: #1f2937;
        }
        
        /* Mobile property grid adjustments */
        @media (max-width: 768px) {
            .property-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 0.75rem;
            }
            
            .property-card {
                padding: 0.75rem;
            }
            
            .property-label {
                font-size: 0.75rem;
            }
            
            .property-value {
                font-size: 1rem;
            }
        }
        
        @media (max-width: 480px) {
            .property-grid {
                grid-template-columns: 1fr;
                gap: 0.5rem;
            }
            
            .property-card {
                padding: 0.5rem;
                display: flex;
                justify-content: space-between;
                align-items: center;
                text-align: left;
            }
            
            .property-label {
                margin-bottom: 0;
            }
        }
        
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .status-badge.status-available {
            background-color: #dcfce7;
            color: #166534;
        }
        
        .status-badge.status-booked {
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        .status-badge.status-blocked {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        /* Amenities styling */
        .amenities-list {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        
        .amenity-tag {
            background: #f3f4f6;
            border: 1px solid #d1d5db;
            border-radius: 20px;
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
            color: #374151;
        }
        
        /* Mobile amenities adjustments */
        @media (max-width: 768px) {
            .amenities-list {
                gap: 0.375rem;
            }
            
            .amenity-tag {
                padding: 0.25rem 0.5rem;
                font-size: 0.75rem;
            }
        }
        
        @media (max-width: 480px) {
            .amenities-list {
                gap: 0.25rem;
            }
            
            .amenity-tag {
                padding: 0.2rem 0.4rem;
                font-size: 0.7rem;
            }
        }
        
        /* Video container */
        .video-container {
            position: relative;
            width: 100%;
            height: 0;
            padding-bottom: 56.25%; /* 16:9 aspect ratio */
        }
        
        .video-iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none;
            border-radius: 15px;
        }
        
        /* Mobile video adjustments */
        @media (max-width: 768px) {
            .video-iframe {
                border-radius: 10px;
            }
        }
        
        @media (max-width: 480px) {
            .video-iframe {
                border-radius: 5px;
            }
        }
        
        /* Modal header */
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .modal-close {
            background: none;
            border: none;
            color: white;
            font-size: 2rem;
            cursor: pointer;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: background-color 0.3s;
        }
        
        .modal-close:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }
        
        /* Loading indicator */
        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.95);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1001;
            border-radius: 15px;
        }
        
        .loading-spinner {
            text-align: center;
        }
        
        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3b82f6;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
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
        // Initialize modules when page loads
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🚀 Initializing RetoERP Plot Management System...');
            
            // Wait for scripts to load
            setTimeout(() => {
                // Initialize plot manager if available
                if (typeof PlotManager !== 'undefined') {
                    window.plotManager = new PlotManager();
                    console.log('✅ PlotManager initialized');
                } else {
                    console.warn('⚠️ PlotManager not loaded');
                }
                
                // Initialize role manager if available  
                if (typeof RoleManager !== 'undefined') {
                    window.roleManager = new RoleManager();
                    console.log('✅ RoleManager initialized');
                } else {
                    console.warn('⚠️ RoleManager not loaded');
                }
                
                // Initialize modal manager if available
                if (typeof ModalManager !== 'undefined') {
                    window.modalManager = new ModalManager();
                    console.log('✅ ModalManager initialized');
                } else {
                    console.warn('⚠️ ModalManager not loaded');
                }
                
                console.log('🎯 System initialization complete!');
                
                // Test core functionality
                testSystemFeatures();
            }, 500);
        });
        
        // Test system features
        function testSystemFeatures() {
            console.log('🧪 Testing system features...');
            
            // Test plot interaction
            const plots = document.querySelectorAll('[id^="plot-"]');
            console.log(`📊 Found ${plots.length} interactive plots`);
            
            // Test search input
            const searchInput = document.getElementById('plotSearchInput');
            console.log(`🔍 Search input: ${searchInput ? 'Found' : 'Missing'}`);
            
            // Test filter dropdown
            const filterSelect = document.getElementById('statusFilter');
            console.log(`🔧 Filter dropdown: ${filterSelect ? 'Found' : 'Missing'}`);
            
            // Test modal
            const modal = document.getElementById('enhancedModal');
            console.log(`🗂️ Enhanced modal: ${modal ? 'Found' : 'Missing'}`);
            
            console.log('✅ Feature test complete!');
        }
        
        // Simple plot click handlers for SVG elements
        function handlePlotClick(plotId) {
            console.log(`🏠 Plot ${plotId} clicked`);
            
            try {
                if (window.plotManager && window.plotManager.openEnhancedModal) {
                    window.plotManager.openEnhancedModal(plotId, 'A', '₹15,00,000', '1200 sq ft', 'available');
                    console.log(`✅ Enhanced modal opened for plot ${plotId}`);
                } else {
                    console.warn('⚠️ PlotManager not available, using fallback');
                    // Fallback for when modules aren't loaded
                    const modal = document.getElementById('enhancedModal');
                    if (modal) {
                        modal.style.display = 'flex';
                        const titleElement = document.getElementById('modalPlotTitle');
                        if (titleElement) {
                            titleElement.textContent = `Plot ${plotId} Details`;
                        }
                        console.log(`✅ Fallback modal opened for plot ${plotId}`);
                    } else {
                        console.error('❌ Modal element not found');
                        alert(`Plot ${plotId} details would open here`);
                    }
                }
            } catch (error) {
                console.error('❌ Error opening plot modal:', error);
                alert(`Error opening details for Plot ${plotId}`);
            }
        }

        function highlightPlot(el) {
            el.setAttribute("fill", "#cce5ff");
            el.setAttribute("stroke", "#007bff");
            el.setAttribute("stroke-width", "5");
        }

        function unhighlightPlot(el) {
            // Reset to original color based on status
            const status = el.getAttribute('data-status') || 'available';
            if (status === 'booked') {
                el.setAttribute("fill", "#f0ad4e");
                el.setAttribute("stroke", "#d58512");
            } else {
                el.setAttribute("fill", "#dff0d8");
                el.setAttribute("stroke", "#5cb85c");
            }
            el.setAttribute("stroke-width", "3");
        }

        // Search and filter functions
        function searchPlot(searchTerm) {
            if (window.plotManager) {
                window.plotManager.searchPlots(searchTerm);
            }
        }

        function highlightSearchResult() {
            const searchTerm = document.getElementById('plotSearchInput').value;
            if (window.plotManager) {
                window.plotManager.searchPlots(searchTerm);
            }
        }

        function clearSearch() {
            if (window.plotManager) {
                window.plotManager.clearSearch();
            }
            // Clear the search input
            const searchInput = document.getElementById('plotSearchInput');
            if (searchInput) {
                searchInput.value = '';
            }
        }

        function filterPlots(status) {
            if (window.plotManager) {
                window.plotManager.filterPlots(status);
            }
        }
        
        function filterByStatus(status) {
            if (window.plotManager) {
                window.plotManager.filterPlots(status);
            }
        }
        
        // Modal management functions
        function closeEnhancedModal() {
            if (window.modalManager) {
                window.modalManager.closeEnhancedModal();
            } else {
                const modal = document.getElementById('enhancedModal');
                if (modal) {
                    modal.style.display = 'none';
                }
            }
        }
        
        // Zoom functions
        function zoomIn() {
            const container = document.getElementById('layoutContainer');
            if (container) {
                const currentScale = container.style.transform.match(/scale\(([\d.]+)\)/);
                const scale = currentScale ? parseFloat(currentScale[1]) * 1.2 : 1.2;
                container.style.transform = `scale(${scale})`;
            }
        }
        
        function zoomOut() {
            const container = document.getElementById('layoutContainer');
            if (container) {
                const currentScale = container.style.transform.match(/scale\(([\d.]+)\)/);
                const scale = currentScale ? parseFloat(currentScale[1]) / 1.2 : 0.8;
                container.style.transform = `scale(${scale})`;
            }
        }
        
        function resetZoom() {
            const container = document.getElementById('layoutContainer');
            if (container) {
                container.style.transform = 'scale(1)';
            }
        }
    </script>
</body>
</html>
