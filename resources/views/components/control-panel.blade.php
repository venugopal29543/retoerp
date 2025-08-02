<!-- Enhanced Single-Line Control Panel Component -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-4 p-3">
    <div class="flex flex-wrap items-center justify-between gap-3">
        
        <!-- Left Section - Search & Filters -->
        <div class="flex items-center gap-3 flex-1 min-w-0">
            <!-- Search Input with Icon -->
            <div class="relative flex-1 max-w-xs">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" 
                       id="plotSearchInput" 
                       class="block w-full pl-10 pr-3 py-2 text-sm border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-colors" 
                       placeholder="Search plots..."
                       onkeyup="searchPlot(this.value)">
            </div>
            
            <!-- Filter Dropdown -->
            <div class="relative">
                <select id="statusFilter" 
                        class="appearance-none bg-white border border-gray-300 rounded-lg px-4 py-2 text-sm pr-8 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                        onchange="filterPlots(this.value)">
                    <option value="all">All Status</option>
                    <option value="available">Available</option>
                    <option value="blocked">Blocked</option>
                    <option value="booked">Booked</option>
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex items-center gap-2">
                <button onclick="highlightSearchResult()" 
                        class="px-3 py-2 bg-blue-500 text-white text-sm rounded-lg hover:bg-blue-600 transition-colors flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Find
                </button>
                <button onclick="clearSearch()" 
                        class="px-3 py-2 bg-gray-500 text-white text-sm rounded-lg hover:bg-gray-600 transition-colors flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Clear
                </button>
            </div>
        </div>
        
        <!-- Right Section - Compact Stats -->
        <div class="flex items-center gap-4 text-sm font-medium">
            <!-- Available -->
            <div class="flex items-center gap-1.5 px-3 py-1.5 bg-green-50 text-green-700 rounded-full">
                <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                <span class="hidden sm:inline">Available:</span>
                <span id="availableCount" class="font-bold">11</span>
            </div>
            
            <!-- Blocked -->
            <div class="flex items-center gap-1.5 px-3 py-1.5 bg-yellow-50 text-yellow-700 rounded-full">
                <div class="w-2 h-2 bg-yellow-500 rounded-full"></div>
                <span class="hidden sm:inline">Blocked:</span>
                <span id="blockedCount" class="font-bold">0</span>
            </div>
            
            <!-- Booked -->
            <div class="flex items-center gap-1.5 px-3 py-1.5 bg-red-50 text-red-700 rounded-full">
                <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                <span class="hidden sm:inline">Booked:</span>
                <span id="bookedCount" class="font-bold">2</span>
            </div>
            
            <!-- Total -->
            <div class="flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 text-gray-700 rounded-full border">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <span class="hidden sm:inline">Total:</span>
                <span id="totalCount" class="font-bold">13</span>
            </div>
        </div>
    </div>
    
    <!-- Mobile-Only Stats Row -->
    <div class="sm:hidden mt-3 pt-3 border-t border-gray-200">
        <div class="grid grid-cols-4 gap-2 text-xs">
            <div class="text-center">
                <div class="w-3 h-3 bg-green-500 rounded-full mx-auto mb-1"></div>
                <div class="text-gray-600">Available</div>
                <div class="font-bold text-green-700" id="availableCountMobile">11</div>
            </div>
            <div class="text-center">
                <div class="w-3 h-3 bg-yellow-500 rounded-full mx-auto mb-1"></div>
                <div class="text-gray-600">Blocked</div>
                <div class="font-bold text-yellow-700" id="blockedCountMobile">0</div>
            </div>
            <div class="text-center">
                <div class="w-3 h-3 bg-red-500 rounded-full mx-auto mb-1"></div>
                <div class="text-gray-600">Booked</div>
                <div class="font-bold text-red-700" id="bookedCountMobile">2</div>
            </div>
            <div class="text-center">
                <div class="w-3 h-3 bg-gray-500 rounded-full mx-auto mb-1"></div>
                <div class="text-gray-600">Total</div>
                <div class="font-bold text-gray-700" id="totalCountMobile">13</div>
            </div>
        </div>
    </div>
</div>
