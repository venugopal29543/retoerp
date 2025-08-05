<!-- Mobile Bottom Bar Debug Version - Bright and Visible -->
<div class="fixed bottom-0 left-0 right-0 bg-blue-600 border-t-4 border-blue-800 shadow-2xl md:hidden" 
     style="z-index: 99999 !important; position: fixed !important; bottom: 0 !important;">
    
    <!-- Bottom Action Bar -->
    <div class="flex justify-around items-center py-4 px-2">
        
        <!-- Map Button -->
        <button onclick="alert('Map clicked!')" 
                class="flex flex-col items-center p-3 text-white hover:bg-blue-700 rounded-lg transition-colors">
            <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-1.447-.894L15 4m0 13V4m0 0L9 7"/>
            </svg>
            <span class="text-sm font-bold">Map</span>
        </button>

        <!-- Search Button -->
        <button onclick="alert('Search clicked!')" 
                class="flex flex-col items-center p-3 text-white hover:bg-blue-700 rounded-lg transition-colors">
            <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <span class="text-sm font-bold">Search</span>
        </button>

        <!-- Apps Button -->
        <button onclick="alert('Apps clicked!')" 
                class="flex flex-col items-center p-3 text-white hover:bg-blue-700 rounded-lg transition-colors">
            <!-- 9-dot grid -->
            <div class="grid grid-cols-3 gap-1 w-8 h-8 mb-2">
                <div class="w-2 h-2 bg-white rounded-full"></div>
                <div class="w-2 h-2 bg-white rounded-full"></div>
                <div class="w-2 h-2 bg-white rounded-full"></div>
                <div class="w-2 h-2 bg-white rounded-full"></div>
                <div class="w-2 h-2 bg-white rounded-full"></div>
                <div class="w-2 h-2 bg-white rounded-full"></div>
                <div class="w-2 h-2 bg-white rounded-full"></div>
                <div class="w-2 h-2 bg-white rounded-full"></div>
                <div class="w-2 h-2 bg-white rounded-full"></div>
            </div>
            <span class="text-sm font-bold">Apps</span>
        </button>

        <!-- Notifications Button -->
        <button onclick="alert('Notifications clicked!')" 
                class="flex flex-col items-center p-3 text-white hover:bg-blue-700 rounded-lg transition-colors relative">
            <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zm-3-13l1.8 7.154a1 1 0 00.97.846h4.459M7 8a5 5 0 0110 0v2a8 8 0 002 5H5a8 8 0 002-5V8z"/>
            </svg>
            <!-- Badge -->
            <div class="absolute -top-1 -right-1 w-6 h-6 bg-red-500 rounded-full flex items-center justify-center">
                <span class="text-xs text-white font-bold">3</span>
            </div>
            <span class="text-sm font-bold">Alerts</span>
        </button>

        <!-- Profile Button -->
        <button onclick="alert('Profile clicked!')" 
                class="flex flex-col items-center p-3 text-white hover:bg-blue-700 rounded-lg transition-colors">
            <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <span class="text-sm font-bold">Profile</span>
        </button>
    </div>
</div>

<!-- Add bottom padding to body to prevent content overlap -->
<style>
@media (max-width: 767px) {
    body {
        padding-bottom: 100px !important;
    }
}
</style>
