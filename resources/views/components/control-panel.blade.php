<!-- Control Panel Component -->
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
                        <option value="blocked">Blocked Only</option>
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
                    <span class="status-indicator status-blocked"></span>
                    Blocked: <span id="blockedCount" class="font-bold ml-1">0</span>
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
