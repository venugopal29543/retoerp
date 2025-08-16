<!-- Plot visualization container with improved responsive layout -->
<div class="plot-visualization-section bg-white rounded-lg shadow-lg p-4 mb-6">
    <h2 class="text-xl font-semibold mb-4">Plot Layout Visualization</h2>
    
    <!-- SVG plot layout container -->
    <div class="plot-visualization overflow-x-auto mb-4">
        <!-- Your existing plot SVG layout goes here -->
        <div id="plot-layout-container" class="w-full h-auto"></div>
    </div>
    
    <!-- Filter and status indicators container with responsive classes -->
    <div class="filter-status-container mt-4">
        <!-- Search and filter controls -->
        <div class="search-filter-controls mb-3">
            <div class="flex flex-wrap gap-2 filter-buttons">
                <input type="search" placeholder="Search plots..." class="border rounded px-3 py-2 w-full sm:w-auto">
                <select class="border rounded px-3 py-2 w-full sm:w-auto">
                    <option>All Status</option>
                    <option>Available</option>
                    <option>Booked</option>
                    <option>Blocked</option>
                </select>
                <button class="bg-blue-500 text-white px-4 py-2 rounded">Find</button>
            </div>
        </div>
        
        <!-- Status indicators -->
        <div class="status-indicators grid grid-cols-2 sm:grid-cols-4 gap-3">
            <!-- Available -->
            <div class="status-card bg-green-100 p-3 rounded-lg text-center">
                <div class="flex justify-center">
                    <span class="inline-block w-3 h-3 bg-green-500 rounded-full mr-2 mt-1"></span>
                    <span>Available</span>
                </div>
                <div class="text-lg font-bold text-green-600" id="available-count">5</div>
            </div>
            
            <!-- Blocked -->
            <div class="status-card bg-yellow-100 p-3 rounded-lg text-center">
                <div class="flex justify-center">
                    <span class="inline-block w-3 h-3 bg-yellow-500 rounded-full mr-2 mt-1"></span>
                    <span>Blocked</span>
                </div>
                <div class="text-lg font-bold text-yellow-600" id="blocked-count">0</div>
            </div>
            
            <!-- Booked -->
            <div class="status-card bg-red-100 p-3 rounded-lg text-center">
                <div class="flex justify-center">
                    <span class="inline-block w-3 h-3 bg-red-500 rounded-full mr-2 mt-1"></span>
                    <span>Booked</span>
                </div>
                <div class="text-lg font-bold text-red-600" id="booked-count">3</div>
            </div>
            
            <!-- Total -->
            <div class="status-card bg-gray-100 p-3 rounded-lg text-center">
                <div class="flex justify-center">
                    <span class="inline-block w-3 h-3 bg-gray-500 rounded-full mr-2 mt-1"></span>
                    <span>Total</span>
                </div>
                <div class="text-lg font-bold text-gray-600" id="total-count">8</div>
            </div>
        </div>
    </div>
</div>
