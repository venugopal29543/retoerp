<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SATTENAPALLI Layout Plan - Plot Booking System</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        /* Simplified plot styles - no animations for better performance */
        .plot, .plot-shape, .polygon-boundary {
            cursor: pointer;
            stroke-width: 2;
        }
        
        .plot-selected, .plot-shape-selected {
            stroke: #ff0000;
            stroke-width: 4;
        }
        
        .plot-available, .plot-shape-available {
            fill: rgba(40,167,69,0.4) !important;
            stroke: rgba(40,167,69,0.9) !important;
            stroke-width: 2;
        }
        
        .plot-booked, .plot-shape-booked {
            fill: rgba(220,53,69,0.4) !important;
            stroke: rgba(220,53,69,0.9) !important;
            stroke-width: 2;
        }
        
        /* Highlighted plot styles */
        .plot-highlighted {
            stroke: #007bff !important;
            stroke-width: 4 !important;
            fill-opacity: 0.8 !important;
        }
        
        /* Search result highlighting */
        .plot-search-result {
            stroke: #ffc107 !important;
            stroke-width: 5 !important;
            fill: rgba(255, 193, 7, 0.6) !important;
        }
        
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
        
        /* Compact layout */
        .layout-container {
            max-width: 100%;
            margin: 0;
            background: #ffffff;
            border-radius: 8px;
            padding: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .road-network {
            stroke: #666;
            stroke-width: 3;
            opacity: 0.7;
        }
        
        .boundary-outline {
            stroke: #000;
            stroke-width: 3;
            fill: none;
            opacity: 0.8;
        }

        #bookingForm {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            border: 2px solid #333;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            min-width: 300px;
        }
        
        #overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
        
        .layout-container {
            max-width: 100%;
            margin: 0 auto;
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .zoom-controls {
            position: absolute;
            top: 10px;
            right: 10px;
            display: flex;
            flex-direction: column;
            gap: 5px;
            z-index: 100;
        }
        
        .zoom-btn {
            background: white;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-weight: bold;
        }
        
        .zoom-btn:hover {
            background: #f0f0f0;
        }
        
        /* SVG layout specific styles */
        #layoutContainer {
            position: relative;
            width: 100%;
            transition: transform 0.3s ease;
        }
        
        #layoutContainer img {
            width: 100%;
            height: auto;
            display: block;
        }
        
        #layoutSVG {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: auto;
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-blue-600 text-white p-4">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold">SATTENAPALLI Layout Plan</h1>
            <div class="space-x-4">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="bg-blue-500 px-4 py-2 rounded hover:bg-blue-700">Dashboard</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="bg-red-500 px-4 py-2 rounded hover:bg-red-700">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="bg-green-500 px-4 py-2 rounded hover:bg-green-700">Login</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="bg-purple-500 px-4 py-2 rounded hover:bg-purple-700">Register</a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-full mx-auto p-2">
        <!-- Compact Header -->
        <div class="compact-header text-center">
            <h2 class="text-2xl font-bold text-gray-800 mb-1">SATTENAPALLI Layout - Plot Booking</h2>
        </div>

        <!-- Control Panel -->
        <div class="control-panel mb-4">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                <!-- Search and Filters -->
                <div class="lg:col-span-2">
                    <div class="search-container">
                        <input type="text" 
                               id="plotSearchInput" 
                               class="search-input" 
                               placeholder="Search plot (e.g., 1, 2, A1)"
                               onkeyup="searchPlot(this.value)">
                        <button class="btn-primary" onclick="highlightSearchResult()">Search</button>
                        <button class="btn-secondary" onclick="clearSearch()">Clear</button>
                        
                        <div class="filter-group ml-4">
                            <label class="text-sm font-medium">Filter:</label>
                            <select id="statusFilter" class="search-input" onchange="filterPlots(this.value)">
                                <option value="all">All Plots</option>
                                <option value="available">Available Only</option>
                                <option value="booked">Booked Only</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Stats -->
                <div class="text-right">
                    <div class="flex justify-end items-center gap-4 text-sm">
                        <div class="flex items-center">
                            <span class="status-indicator status-available"></span>
                            Available: <span id="availableCount" class="font-bold ml-1">11</span>
                        </div>
                        <div class="flex items-center">
                            <span class="status-indicator status-booked"></span>
                            Booked: <span id="bookedCount" class="font-bold ml-1">2</span>
                        </div>
                        <div class="text-gray-600">
                            Total: <span id="totalCount" class="font-bold">13</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Layout Container -->
        <div class="layout-container relative">
            <!-- Zoom Controls -->
            <div class="zoom-controls">
                <button class="zoom-btn" onclick="zoomIn()">+</button>
                <button class="zoom-btn" onclick="zoomOut()">-</button>
                <button class="zoom-btn" onclick="resetZoom()" title="Reset Zoom">⌂</button>
            </div>
            
            <div class="w-full overflow-auto">
    <!-- Layout container with image background and SVG overlay -->
    <div id="layoutContainer" style="position: relative; width: 100%; height: auto;">
        
        <!-- Background SVG layout (inline from resources/views/images) -->
        <div style="width: 100%; border:1px solid #ccc;">
<img src="{{ asset('/images/sathhenapally.svg') }}" alt="SATTENAPALLI Layout Plan 2" style="width: 100%; border:1px solid #ccc;">

         </div>

        <!-- Interactive SVG overlay -->
        <svg id="layoutSVG"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 1122.6667 793.33331"
            style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none;">
            
            <!-- Title -->
            <text x="561" y="30"
                text-anchor="middle"
                font-family="Arial"
                font-size="24"
                font-weight="bold"
                fill="#333"
                style="text-shadow: 2px 2px 4px rgba(255,255,255,0.8);">
                SATTENAPALLY LAYOUT - INTERACTIVE PLOT BOOKING
            </text>

            <!-- Interactive plots group -->
            <g id="plotsGroup"
                transform="matrix(0,-1.3333333,-1.3333333,0,1122.6667,793.33333)"
                style="pointer-events: all;">
                
                <g transform="matrix(0.12,0,0,0.12,2,2)">
                    <!-- Sample plot -->
                    <rect x="100" y="100" width="150" height="80"
                        fill="#dff0d8"
                        stroke="#333"
                        stroke-width="2"
                        class="plot"
                        data-plot-id="A1"
                        onclick="handlePlotClick('A1')"
                        onmouseover="highlightPlot(this)"
                        onmouseout="unhighlightPlot(this)" />
                    
                    <text x="175" y="145"
                        font-size="14"
                        text-anchor="middle"
                        fill="#000">
                        Plot A1
                    </text>
                </g>
            </g>

            <!-- Message box -->
            <g id="overlayElements">
                <rect x="50" y="60" width="200" height="30" fill="rgba(255,255,255,0.9)" stroke="#333" stroke-width="1" rx="5"/>
                <text x="150" y="80" text-anchor="middle" font-family="Arial" font-size="14" fill="#333">
                    Click plots to view details
                </text>
            </g>
        </svg>
    </div>
</div>

<script>
    function handlePlotClick(plotId) {
        alert("Plot clicked: " + plotId);
        // You can show modal form here
    }

    function highlightPlot(el) {
        el.setAttribute("fill", "#cce5ff");
    }

    function unhighlightPlot(el) {
        el.setAttribute("fill", "#dff0d8");
    }
</script>

        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-8">
            <div class="bg-white p-4 rounded-lg shadow">
                <h3 class="font-semibold text-lg text-green-600">Available Plots</h3>
                <p class="text-2xl font-bold" id="availableCount">11</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow">
                <h3 class="font-semibold text-lg text-red-600">Booked Plots</h3>
                <p class="text-2xl font-bold" id="bookedCount">2</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow">
                <h3 class="font-semibold text-lg text-blue-600">Total Plots</h3>
                <p class="text-2xl font-bold" id="totalCount">13</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow">
                <h3 class="font-semibold text-lg text-purple-600">Price Range</h3>
                <p class="text-lg font-bold">₹7.5L - ₹50L</p>
            </div>
        </div>
    </div>

    <!-- Overlay for modal -->
    <div id="overlay"></div>

    <!-- Booking Form Modal -->
    <div id="bookingForm">
        <h3 class="text-xl font-bold mb-4">Plot Booking Details</h3>
        <div id="plotDetails"></div>
        <div class="mt-4 space-y-2">
            <input type="text" id="customerName" placeholder="Your Name" class="w-full p-2 border rounded">
            <input type="email" id="customerEmail" placeholder="Email Address" class="w-full p-2 border rounded">
            <input type="tel" id="customerPhone" placeholder="Phone Number" class="w-full p-2 border rounded">
            <textarea id="customerMessage" placeholder="Additional Message" class="w-full p-2 border rounded" rows="3"></textarea>
        </div>
        <div class="flex justify-end space-x-2 mt-4">
            <button onclick="closeBooking()" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-700">Cancel</button>
            <button onclick="submitBooking()" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-700">Book Plot</button>
        </div>
    </div>

    <script>
        let currentZoom = 1;
        let svgElement;
        
        document.addEventListener('DOMContentLoaded', function() {
            svgElement = document.getElementById('layoutSVG');
            
            // Initialize plot interaction and load plots dynamically
            initializePlotInteraction();
            loadDynamicPlots(); // This will try dynamic loading first, then fallback to static
            
            // Close modal when clicking overlay
            const overlay = document.getElementById('overlay');
            if (overlay) {
                overlay.addEventListener('click', closeBooking);
            }
        });
        
        // Initialize plot interaction
        function initializePlotInteraction() {
            const plots = document.querySelectorAll('#plotsGroup path, #plotsGroup polygon, .plot');
            plots.forEach(plot => {
                plot.addEventListener('mouseover', handleHover);
                plot.addEventListener('mouseout', handleOut);
                plot.addEventListener('click', handleClick);
            });
        }
        
        // Handle hover events - simple highlight without movement
        function handleHover(event) {
            const plot = event.target;
            const plotId = plot.dataset.plot || plot.id || 'Unknown';
            const block = plot.dataset.block || 'Unknown';
            const price = plot.dataset.price || '0';
            const area = plot.dataset.area || '0';
            const status = plot.dataset.status || 'available';
            
            const plotInfo = document.getElementById('plotInfo');
            if (plotInfo) {
                plotInfo.innerHTML = `
                    <h3 class="font-semibold text-lg mb-2">Plot ${plotId} - Block ${block}</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div><strong>Price:</strong> ₹${parseInt(price).toLocaleString('en-IN')}</div>
                        <div><strong>Area:</strong> ${area} sq ft</div>
                        <div>
                            <strong>Status:</strong> 
                            <span class="${status === 'available' ? 'text-green-600' : 'text-red-600'} font-semibold">
                                ${status.charAt(0).toUpperCase() + status.slice(1)}
                            </span>
                        </div>
                        <div><strong>Price per sq ft:</strong> ₹${price > 0 ? Math.round(parseInt(price) / parseInt(area)) : 'N/A'}</div>
                    </div>
                    <p class="text-sm text-blue-600 mt-2">✓ Interactive plot from Sathenapally layout</p>
                `;
            }
            
            // Simple visual hover effect - just border highlight, no movement
            plot.classList.add('plot-hover');
            plot.style.stroke = '#007bff';
            plot.style.strokeWidth = '6';
            plot.style.filter = 'brightness(1.2)';
        }
        
        // Handle mouse out events - remove simple hover effects
        function handleOut(event) {
            const plot = event.target;
            const plotId = plot.dataset.plot || plot.id || 'Unknown';
            const plotInfo = document.getElementById('plotInfo');
            
            if (plotInfo) {
                plotInfo.innerHTML = `
                    <h3 class="font-semibold text-lg mb-2">Plot Information</h3>
                    <p class="text-gray-600">Hover over any plot to see details here. Click to book.</p>
                `;
            }
            
            // Remove hover effect and restore original styling
            plot.classList.remove('plot-hover');
            plot.style.stroke = '';
            plot.style.strokeWidth = '';
            plot.style.filter = '';
        }
        
        // Handle click events
        function handleClick(event) {
            const plot = event.target;
            const plotId = plot.dataset.plot || plot.id || 'Unknown';
            const block = plot.dataset.block || 'Unknown';
            const price = plot.dataset.price || '0';
            const area = plot.dataset.area || '0';
            const status = plot.dataset.status || 'available';
            
            if (status === 'booked') {
                alert('This plot is already booked!');
                return;
            }
            
            // Show booking form
            const bookingForm = document.getElementById('bookingForm');
            const overlay = document.getElementById('overlay');
            const plotDetails = document.getElementById('plotDetails');
            
            if (plotDetails && bookingForm && overlay) {
                plotDetails.innerHTML = `
                    <div class="bg-gray-100 p-3 rounded">
                        <h4 class="font-semibold">Plot ${plotId} - Block ${block}</h4>
                        <p><strong>Price:</strong> ₹${parseInt(price).toLocaleString('en-IN')}</p>
                        <p><strong>Area:</strong> ${area} sq ft</p>
                        <p><strong>Price per sq ft:</strong> ₹${price > 0 ? Math.round(parseInt(price) / parseInt(area)) : 'N/A'}</p>
                    </div>
                `;
                
                overlay.style.display = 'block';
                bookingForm.style.display = 'block';
                
                // Store selected plot for booking
                window.selectedPlot = plot;
            } else {
                // Fallback alert
                alert(`Plot ${plotId} selected!\nBlock: ${block}\nPrice: ₹${parseInt(price).toLocaleString('en-IN')}\nArea: ${area} sq ft`);
            }
        }
        
        // Dynamic plot loading system
        async function loadDynamicPlots() {
            try {
                // Try to load from JSON file first
                const response = await fetch('/api/plots');
                if (response.ok) {
                    const plotsData = await response.json();
                    generateDynamicPlots(plotsData);
                    
                    // Update UI with metadata
                    const lastUpdatedEl = document.getElementById('lastUpdated');
                    if (lastUpdatedEl && plotsData.metadata) {
                        lastUpdatedEl.textContent = plotsData.metadata.lastUpdated || 'Unknown';
                    }
                    
                    console.log('✅ Dynamic plots loaded successfully from API');
                } else {
                    throw new Error('API not available');
                }
            } catch (error) {
                console.log('⚠️ API loading failed, using static plots:', error.message);
                
                // Update UI to show fallback
                const lastUpdatedEl = document.getElementById('lastUpdated');
                if (lastUpdatedEl) {
                    lastUpdatedEl.textContent = 'Static Data';
                }
                
                const dataSourceEl = document.getElementById('dataSource');
                if (dataSourceEl) {
                    dataSourceEl.textContent = 'Static/Fallback';
                    dataSourceEl.className = 'text-sm text-orange-600';
                }
                
                // Fallback to static data
                loadStaticPlots();
            }
        }
        
        // Generate plots dynamically from data
        function generateDynamicPlots(plotsData) {
            const plotsGroup = document.querySelector('#plotsGroup g');
            if (!plotsGroup) {
                console.error('Plots group not found');
                return;
            }
            
            // Clear existing plots
            plotsGroup.innerHTML = '';
            
            plotsData.layouts.forEach((layout, layoutIndex) => {
                layout.plots.forEach((plot, plotIndex) => {
                    createPlotFromData(plot, plotsGroup, layoutIndex, plotIndex);
                });
            });
            
            console.log(`Dynamically loaded ${plotsData.layouts.reduce((sum, layout) => sum + layout.plots.length, 0)} plots`);
            updateStatistics();
        }
        
        // Create individual plot from data
        function createPlotFromData(plotData, plotsGroup, layoutIndex, plotIndex) {
            if (!plotData.coordinates || plotData.coordinates.length < 3) return;
            
            const polygon = document.createElementNS('http://www.w3.org/2000/svg', 'polygon');
            
            // Set polygon attributes
            polygon.setAttribute('id', `plot_${plotData.id || `${layoutIndex}_${plotIndex}`}`);
            polygon.setAttribute('points', plotData.coordinates.map(p => `${p.x},${p.y}`).join(' '));
            
            // Set data attributes
            polygon.setAttribute('data-plot', plotData.id || `${layoutIndex + 1}_${plotIndex + 1}`);
            polygon.setAttribute('data-block', plotData.block || String.fromCharCode(65 + layoutIndex));
            polygon.setAttribute('data-price', plotData.price || 1000000);
            polygon.setAttribute('data-area', plotData.area || 2000);
            polygon.setAttribute('data-status', plotData.status || 'available');
            polygon.setAttribute('data-layout', plotData.layout || `Layout_${layoutIndex + 1}`);
            
            // Set styles based on status
            if (plotData.status === 'booked') {
                polygon.setAttribute('fill', 'rgba(220,53,69,0.4)');
                polygon.setAttribute('stroke', 'rgba(220,53,69,0.9)');
                polygon.setAttribute('stroke-width', '3');
                polygon.setAttribute('class', 'polygon-boundary plot-booked');
            } else {
                polygon.setAttribute('fill', 'rgba(40,167,69,0.4)');
                polygon.setAttribute('stroke', 'rgba(40,167,69,0.9)');
                polygon.setAttribute('stroke-width', '3');
                polygon.setAttribute('class', 'polygon-boundary plot-available');
            }
            
            // Add event listeners
            polygon.addEventListener('mouseover', handleHover);
            polygon.addEventListener('mouseout', handleOut);
            polygon.addEventListener('click', handleClick);
            
            plotsGroup.appendChild(polygon);
            
            // Add plot label
            addPlotLabel(plotData, plotsGroup);
        }
        
        // Add plot label
        function addPlotLabel(plotData, plotsGroup) {
            const plotLabel = document.createElementNS('http://www.w3.org/2000/svg', 'text');
            
            // Calculate center point
            const points = plotData.coordinates;
            const centerX = points.reduce((sum, p) => sum + p.x, 0) / points.length;
            const centerY = points.reduce((sum, p) => sum + p.y, 0) / points.length;
            
            plotLabel.setAttribute('x', centerX);
            plotLabel.setAttribute('y', centerY + 20);
            plotLabel.setAttribute('text-anchor', 'middle');
            plotLabel.setAttribute('font-family', 'Arial, sans-serif');
            plotLabel.setAttribute('font-size', '60');
            plotLabel.setAttribute('font-weight', 'bold');
            plotLabel.setAttribute('fill', plotData.status === 'booked' ? '#dc3545' : '#28a745');
            plotLabel.setAttribute('stroke', '#ffffff');
            plotLabel.setAttribute('stroke-width', '4');
            plotLabel.setAttribute('paint-order', 'stroke');
            plotLabel.setAttribute('data-plot-label', plotData.id);
            plotLabel.style.pointerEvents = 'none';
            plotLabel.textContent = plotData.displayName || plotData.id;
            
            plotsGroup.appendChild(plotLabel);
        }
        
        // Load static plots (fallback)
        function loadStaticPlots() {
            addCustomPlots(); // Your existing function
        }
        
        // Create 3D shadow effect for plots
        function create3DShadow(plot) {
            const plotId = plot.dataset.plot;
            const existingShadow = document.getElementById(`shadow_${plotId}`);
            
            if (existingShadow) {
                existingShadow.remove();
            }
            
            // Create shadow polygon
            const shadowPolygon = plot.cloneNode(true);
            shadowPolygon.setAttribute('id', `shadow_${plotId}`);
            
            // Style the shadow
            shadowPolygon.setAttribute('fill', 'rgba(0,0,0,0.3)');
            shadowPolygon.setAttribute('stroke', 'rgba(0,0,0,0.4)');
            shadowPolygon.setAttribute('stroke-width', '2');
            shadowPolygon.style.transform = 'translate(6px, 6px) scale(1.02)';
            shadowPolygon.style.filter = 'blur(3px)';
            shadowPolygon.style.pointerEvents = 'none';
            shadowPolygon.style.zIndex = '-1';
            
            // Insert shadow behind the original plot
            plot.parentNode.insertBefore(shadowPolygon, plot);
        }
        
        // Remove 3D shadow effect
        function remove3DShadow(plot) {
            const plotId = plot.dataset.plot;
            const shadowElement = document.getElementById(`shadow_${plotId}`);
            
            if (shadowElement) {
                shadowElement.remove();
            }
        }
        
        // Add custom plots with polygon data
        function addCustomPlots() {
            // Custom plot data from paths_new.js structure
            const customPlotData = [
                // Individual plots with simple coordinates
                [{ "x": 684, "y": 3526 }, { "x": 998, "y": 3504 }, { "x": 1012, "y": 3852 }, { "x": 698, "y": 3874 }],
                [{ "x": 2901, "y": 3233 }, { "x": 3056, "y": 3233 }, { "x": 3056, "y": 3659 }, { "x": 2901, "y": 3659 }],
                [{ "x": 3056, "y": 3233 }, { "x": 3172, "y": 3233 }, { "x": 3172, "y": 3659 }, { "x": 3056, "y": 3659 }],
                [{ "x": 3172, "y": 3233 }, { "x": 3288, "y": 3233 }, { "x": 3288, "y": 3659 }, { "x": 3172, "y": 3659 }],
                [{ "x": 3288, "y": 3233 }, { "x": 3404, "y": 3233 }, { "x": 3404, "y": 3659 }, { "x": 3288, "y": 3659 }],
                [{ "x": 3984, "y": 3233 }, { "x": 4100, "y": 3233 }, { "x": 4100, "y": 3659 }, { "x": 3984, "y": 3659 }],
                [{ "x": 2668, "y": 5507 }, { "x": 2894, "y": 5512 }, { "x": 2891, "y": 5666 }, { "x": 2674, "y": 5662 }],
                [{ "x": 2894, "y": 5512 }, { "x": 3049, "y": 5515 }, { "x": 3045, "y": 5670 }, { "x": 2891, "y": 5666 }],
                [{ "x": 2901, "y": 3814 }, { "x": 3056, "y": 3814 }, { "x": 3056, "y": 4240 }, { "x": 2901, "y": 4240 }],
                [{ "x": 3056, "y": 3814 }, { "x": 3172, "y": 3814 }, { "x": 3172, "y": 4240 }, { "x": 3056, "y": 4240 }],
                [{ "x": 3172, "y": 3814 }, { "x": 3288, "y": 3814 }, { "x": 3288, "y": 4240 }, { "x": 3172, "y": 4240 }],
                [{ "x": 4100, "y": 3233 }, { "x": 4829, "y": 3233 }, { "x": 4829, "y": 5554 }, { "x": 4100, "y": 5554 }]
            ];
            
            const plotsGroup = document.querySelector('#plotsGroup g');
            if (!plotsGroup) {
                console.error('Plots group not found');
                return;
            }
            
            // Plot information for each polygon - using simple sequential numbers
            const plotInfo = [
                // Plot numbering starts from 1 for easy identification
                { id: '1', block: 'A', price: 850000, area: 1800, status: 'available' },
                { id: '2', block: 'A', price: 1200000, area: 2200, status: 'available' },
                { id: '3', block: 'A', price: 1250000, area: 2200, status: 'available' },
                { id: '4', block: 'A', price: 1180000, area: 2200, status: 'booked' },
                { id: '5', block: 'A', price: 1300000, area: 2200, status: 'available' },
                { id: '6', block: 'B', price: 950000, area: 1900, status: 'available' },
                { id: '7', block: 'B', price: 1100000, area: 2100, status: 'available' },
                { id: '8', block: 'B', price: 1150000, area: 2100, status: 'available' },
                { id: '9', block: 'C', price: 1350000, area: 2300, status: 'available' },
                { id: '10', block: 'C', price: 1400000, area: 2300, status: 'available' },
                { id: '11', block: 'C', price: 1380000, area: 2300, status: 'booked' },
                { id: '12', block: 'D', price: 5000000, area: 8500, status: 'available' }
            ];
            
            customPlotData.forEach((plotPoints, index) => {
                if (plotPoints.length > 2) {
                    const polygon = document.createElementNS('http://www.w3.org/2000/svg', 'polygon');
                    
                    // Use simple sequential numbering - will be easy for hundreds of layouts
                    const plotNumber = index + 1;
                    const info = plotInfo[index] || { 
                        id: plotNumber.toString(), 
                        block: String.fromCharCode(65 + Math.floor(plotNumber / 10)), // A, B, C, etc.
                        price: 1000000, 
                        area: 2000, 
                        status: 'available' 
                    };
                    
                    polygon.setAttribute('id', `plot_${plotNumber}`);
                    polygon.setAttribute('points', plotPoints.map(p => `${p.x},${p.y}`).join(' '));
                    
                    // Set attributes with simple plot numbers
                    polygon.setAttribute('data-plot', info.id);
                    polygon.setAttribute('data-block', info.block);
                    polygon.setAttribute('data-price', info.price);
                    polygon.setAttribute('data-area', info.area);
                    polygon.setAttribute('data-status', info.status);
                    
                    // Set styles based on status with visible background colors
                    if (info.status === 'booked') {
                        // Booked plots - red/gray background
                        polygon.setAttribute('stroke', 'rgba(220,53,69,0.8)'); // Red border
                        polygon.setAttribute('stroke-width', '3');
                        polygon.setAttribute('fill', 'rgba(220,53,69,0.3)'); // Light red background
                        polygon.setAttribute('fill-opacity', '0.6');
                        polygon.setAttribute('class', 'polygon-boundary plot-booked');
                    } else {
                        // Available plots - green background
                        polygon.setAttribute('stroke', 'rgba(40,167,69,0.8)'); // Green border
                        polygon.setAttribute('stroke-width', '3');
                        polygon.setAttribute('fill', 'rgba(40,167,69,0.2)'); // Light green background
                        polygon.setAttribute('fill-opacity', '0.5');
                        polygon.setAttribute('class', 'polygon-boundary plot-available');
                    }
                    
                    // Add interaction
                    polygon.addEventListener('mouseover', handleHover);
                    polygon.addEventListener('mouseout', handleOut);
                    polygon.addEventListener('click', handleClick);
                    
                    plotsGroup.appendChild(polygon);
                    
                    // Add plot number label on the plot with 3D styling
                    const plotLabel = document.createElementNS('http://www.w3.org/2000/svg', 'text');
                    
                    // Calculate center point of the polygon for label placement
                    const points = plotPoints;
                    const centerX = points.reduce((sum, p) => sum + p.x, 0) / points.length;
                    const centerY = points.reduce((sum, p) => sum + p.y, 0) / points.length;
                    
                    plotLabel.setAttribute('x', centerX);
                    plotLabel.setAttribute('y', centerY + 20); // Slight offset for better readability
                    plotLabel.setAttribute('text-anchor', 'middle');
                    plotLabel.setAttribute('font-family', 'Arial, sans-serif');
                    plotLabel.setAttribute('font-size', '60'); // Larger font for visibility
                    plotLabel.setAttribute('font-weight', 'bold');
                    plotLabel.setAttribute('fill', info.status === 'booked' ? '#dc3545' : '#28a745');
                    plotLabel.setAttribute('stroke', '#ffffff');
                    plotLabel.setAttribute('stroke-width', '8');
                    plotLabel.setAttribute('paint-order', 'stroke');
                    plotLabel.setAttribute('data-plot-label', info.id); // For easy selection
                    plotLabel.setAttribute('class', 'plot-label-3d'); // Add 3D class
                    plotLabel.style.pointerEvents = 'none'; // Don't interfere with polygon clicks
                    plotLabel.style.filter = 'drop-shadow(2px 2px 4px rgba(0,0,0,0.5))';
                    plotLabel.textContent = info.id;
                    
                    plotsGroup.appendChild(plotLabel);
                }
            });
            
            console.log(`Added ${customPlotData.length} plots with simple numbering (1-${customPlotData.length})`);
        }
        
        // Utility functions for dynamic plot management
        
        // Reload plots from server
        async function reloadPlots() {
            console.log('Reloading plots...');
            await loadDynamicPlots();
            setTimeout(() => {
                initializePlotInteraction();
            }, 100);
        }
        
        // Add new plot dynamically
        function addNewPlot(plotData) {
            const plotsGroup = document.querySelector('#plotsGroup g');
            if (!plotsGroup) return;
            
            createPlotFromData(plotData, plotsGroup, 0, 0);
            initializePlotInteraction();
            updateStatistics();
        }
        
        // Update plot status
        function updatePlotStatus(plotId, newStatus) {
            const plot = document.querySelector(`[data-plot="${plotId}"]`);
            if (plot) {
                plot.setAttribute('data-status', newStatus);
                
                // Update visual styling
                if (newStatus === 'booked') {
                    plot.setAttribute('fill', 'rgba(220,53,69,0.4)');
                    plot.setAttribute('stroke', 'rgba(220,53,69,0.9)');
                    plot.setAttribute('class', 'polygon-boundary plot-booked');
                } else {
                    plot.setAttribute('fill', 'rgba(40,167,69,0.4)');
                    plot.setAttribute('stroke', 'rgba(40,167,69,0.9)');
                    plot.setAttribute('class', 'polygon-boundary plot-available');
                }
                
                updateStatistics();
            }
        }
        
        // Generate plots from coordinate arrays (for bulk import)
        function generatePlotsFromCoordinateArray(coordinatesArray, options = {}) {
            const defaultOptions = {
                layoutName: 'Generated Layout',
                startingPlotNumber: 1,
                basePrice: 1000000,
                priceIncrement: 50000,
                baseArea: 2000,
                areaIncrement: 100,
                defaultStatus: 'available',
                blockSize: 20 // plots per block
            };
            
            const config = { ...defaultOptions, ...options };
            const plots = [];
            
            coordinatesArray.forEach((coords, index) => {
                const plotNumber = config.startingPlotNumber + index;
                const blockIndex = Math.floor(index / config.blockSize);
                
                plots.push({
                    id: plotNumber.toString(),
                    displayName: plotNumber.toString(),
                    block: String.fromCharCode(65 + blockIndex), // A, B, C...
                    coordinates: coords,
                    price: config.basePrice + (index * config.priceIncrement),
                    area: config.baseArea + (index * config.areaIncrement),
                    status: Math.random() > 0.85 ? 'booked' : config.defaultStatus,
                    layout: config.layoutName,
                    amenities: ['Water', 'Electricity', 'Road Access']
                });
            });
            
            return plots;
        }
        
        // Load plots from CSV data
        function loadPlotsFromCSV(csvData) {
            const lines = csvData.split('\n');
            const headers = lines[0].split(',');
            const plots = [];
            
            for (let i = 1; i < lines.length; i++) {
                const values = lines[i].split(',');
                if (values.length >= headers.length) {
                    const plot = {};
                    headers.forEach((header, index) => {
                        plot[header.trim()] = values[index].trim();
                    });
                    
                    // Parse coordinates if they exist
                    if (plot.coordinates) {
                        try {
                            plot.coordinates = JSON.parse(plot.coordinates);
                        } catch (e) {
                            console.error('Invalid coordinates format for plot:', plot.id);
                        }
                    }
                    
                    plots.push(plot);
                }
            }
            
            return plots;
        }
        
        // Zoom functionality
        function zoomIn() {
            currentZoom = Math.min(currentZoom * 1.2, 3);
            updateZoom();
        }
        
        function zoomOut() {
            currentZoom = Math.max(currentZoom / 1.2, 0.5);
            updateZoom();
        }
        
        function resetZoom() {
            currentZoom = 1;
            updateZoom();
        }
        
        function updateZoom() {
            const layoutContainer = document.getElementById('layoutContainer');
            if (layoutContainer) {
                layoutContainer.style.transform = `scale(${currentZoom})`;
                layoutContainer.style.transformOrigin = 'center center';
            }
        }
        
        function updateStatistics() {
            const plots = document.querySelectorAll('[data-status]');
            let availableCount = 0;
            let bookedCount = 0;
            
            plots.forEach(plot => {
                const status = plot.dataset.status;
                if (status === 'available') {
                    availableCount++;
                } else if (status === 'booked') {
                    bookedCount++;
                }
            });
            
            const availableEl = document.getElementById('availableCount');
            const bookedEl = document.getElementById('bookedCount');
            const totalEl = document.getElementById('totalCount');
            
            if (availableEl) availableEl.textContent = availableCount;
            if (bookedEl) bookedEl.textContent = bookedCount;
            if (totalEl) totalEl.textContent = availableCount + bookedCount;
        }

        function closeBooking() {
            const overlay = document.getElementById('overlay');
            const bookingForm = document.getElementById('bookingForm');
            
            if (overlay) overlay.style.display = 'none';
            if (bookingForm) bookingForm.style.display = 'none';
            
            window.selectedPlot = null;
            
            // Clear form
            const customerName = document.getElementById('customerName');
            const customerEmail = document.getElementById('customerEmail');
            const customerPhone = document.getElementById('customerPhone');
            const customerMessage = document.getElementById('customerMessage');
            
            if (customerName) customerName.value = '';
            if (customerEmail) customerEmail.value = '';
            if (customerPhone) customerPhone.value = '';
            if (customerMessage) customerMessage.value = '';
        }

        async function submitBooking() {
            const name = document.getElementById('customerName')?.value;
            const email = document.getElementById('customerEmail')?.value;
            const phone = document.getElementById('customerPhone')?.value;
            const message = document.getElementById('customerMessage')?.value;
            
            if (!name || !email || !phone) {
                alert('Please fill in all required fields (Name, Email, Phone)');
                return;
            }

            if (window.selectedPlot) {
                const plotId = window.selectedPlot.dataset.plot;
                
                try {
                    // Show loading state
                    const submitBtn = event.target;
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Submitting...';
                    
                    // Submit booking to API
                    const response = await fetch('/api/booking/submit', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                        },
                        body: JSON.stringify({
                            plot_id: plotId,
                            customer_name: name,
                            customer_email: email,
                            customer_phone: phone,
                            customer_message: message
                        })
                    });
                    
                    const result = await response.json();
                    
                    if (result.success) {
                        alert(`${result.message}`);
                        
                        // Update plot status in UI
                        window.selectedPlot.dataset.status = 'booked';
                        window.selectedPlot.setAttribute('fill', 'rgba(220,53,69,0.4)');
                        window.selectedPlot.setAttribute('stroke', 'rgba(220,53,69,0.9)');
                        window.selectedPlot.setAttribute('class', 'plot polygon-boundary plot-booked');
                        
                        // Update plot label color
                        const plotLabel = document.querySelector(`[data-plot-label="${plotId}"]`);
                        if (plotLabel) {
                            plotLabel.setAttribute('fill', '#dc3545');
                        }
                        
                        // Update statistics
                        updateStatistics();
                        
                        // Close booking form
                        closeBooking();
                        
                        // Close plot info panel
                        closePlotInfo();
                        
                    } else {
                        alert(`Booking failed: ${result.message}`);
                    }
                    
                } catch (error) {
                    console.error('Booking submission error:', error);
                    alert('Booking submission failed. Please try again.');
                } finally {
                    // Reset button state
                    const submitBtn = document.querySelector('#bookingForm button[onclick="submitBooking()"]');
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Book Plot';
                    }
                }
            }
        }
        
        // File upload and export functions
        function handleFileUpload(event) {
            const file = event.target.files[0];
            if (!file) return;
            
            const reader = new FileReader();
            reader.onload = function(e) {
                try {
                    if (file.name.endsWith('.json')) {
                        const plotsData = JSON.parse(e.target.result);
                        generateDynamicPlots(plotsData);
                    } else if (file.name.endsWith('.csv')) {
                        const plots = loadPlotsFromCSV(e.target.result);
                        const plotsData = {
                            layouts: [{
                                name: 'Imported Layout',
                                plots: plots
                            }]
                        };
                        generateDynamicPlots(plotsData);
                    }
                    alert('Plot data imported successfully!');
                } catch (error) {
                    alert('Error importing file: ' + error.message);
                }
            };
            reader.readAsText(file);
        }
        
        function exportPlotData() {
            const plots = [];
            document.querySelectorAll('#plotsGroup polygon[data-plot]').forEach(plot => {
                const points = plot.getAttribute('points').split(' ').map(point => {
                    const [x, y] = point.split(',');
                    return { x: parseFloat(x), y: parseFloat(y) };
                });
                
                plots.push({
                    id: plot.dataset.plot,
                    block: plot.dataset.block,
                    coordinates: points,
                    price: parseInt(plot.dataset.price),
                    area: parseInt(plot.dataset.area),
                    status: plot.dataset.status,
                    layout: plot.dataset.layout || 'Current Layout'
                });
            });
            
            const exportData = {
                layouts: [{
                    name: 'Exported Layout',
                    plots: plots
                }],
                metadata: {
                    exportDate: new Date().toISOString(),
                    totalPlots: plots.length
                }
            };
            
            const blob = new Blob([JSON.stringify(exportData, null, 2)], { type: 'application/json' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'plots_export.json';
            a.click();
            URL.revokeObjectURL(url);
        }
        
        // Add global functions
        window.openBookingModal = function(plot) {
            if (plot && plot.click) {
                plot.click();
            }
        };
        
        // Expose functions globally for easy access
        window.reloadPlots = reloadPlots;
        window.addNewPlot = addNewPlot;
        window.updatePlotStatus = updatePlotStatus;
        window.generatePlotsFromCoordinateArray = generatePlotsFromCoordinateArray;
        window.exportPlotData = exportPlotData;
        window.handleFileUpload = handleFileUpload;
    </script>
</body>
</html>
