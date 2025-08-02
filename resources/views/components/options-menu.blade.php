<!-- Options Menu Component -->
<div class="relative">
    <button id="optionsMenuBtn" class="grid grid-cols-3 gap-1 p-2 rounded hover:bg-blue-700 transition-colors" onclick="toggleOptionsMenu()">
        <div class="w-1.5 h-1.5 bg-white rounded-full"></div>
        <div class="w-1.5 h-1.5 bg-white rounded-full"></div>
        <div class="w-1.5 h-1.5 bg-white rounded-full"></div>
        <div class="w-1.5 h-1.5 bg-white rounded-full"></div>
        <div class="w-1.5 h-1.5 bg-white rounded-full"></div>
        <div class="w-1.5 h-1.5 bg-white rounded-full"></div>
        <div class="w-1.5 h-1.5 bg-white rounded-full"></div>
        <div class="w-1.5 h-1.5 bg-white rounded-full"></div>
        <div class="w-1.5 h-1.5 bg-white rounded-full"></div>
    </button>
    
    <!-- Options Dropdown -->
    <div id="optionsMenu" class="hidden absolute right-0 top-full mt-2 w-80 bg-white rounded-lg shadow-xl border z-50">
        <div class="p-4">
            <h3 class="text-gray-800 font-semibold mb-3">Quick Tools</h3>
            <div class="grid grid-cols-3 gap-3">
                @if(true) {{-- hasFeature('emi_calculator') --}}
                <button onclick="openEMICalculator()" class="flex flex-col items-center p-3 rounded-lg hover:bg-gray-100 text-gray-700">
                    <span class="text-2xl mb-1">🧮</span>
                    <span class="text-xs">EMI Calculator</span>
                </button>
                @endif
                
                @if(true) {{-- hasFeature('location_map') --}}
                <button onclick="openLocationMap()" class="flex flex-col items-center p-3 rounded-lg hover:bg-gray-100 text-gray-700">
                    <span class="text-2xl mb-1">📍</span>
                    <span class="text-xs">Location Map</span>
                </button>
                @endif
                
                @if(true) {{-- hasFeature('plot_comparison') --}}
                <button onclick="openComparison()" class="flex flex-col items-center p-3 rounded-lg hover:bg-gray-100 text-gray-700">
                    <span class="text-2xl mb-1">⚖️</span>
                    <span class="text-xs">Compare Plots</span>
                </button>
                @endif
                
                @if(true) {{-- hasFeature('whatsapp_support') --}}
                <button onclick="openSupport()" class="flex flex-col items-center p-3 rounded-lg hover:bg-gray-100 text-gray-700">
                    <span class="text-2xl mb-1">📞</span>
                    <span class="text-xs">Support</span>
                </button>
                @endif
                
                @if(true) {{-- hasFeature('customer_portal') --}}
                <button onclick="openMyBookings()" class="flex flex-col items-center p-3 rounded-lg hover:bg-gray-100 text-gray-700">
                    <span class="text-2xl mb-1">📋</span>
                    <span class="text-xs">My Bookings</span>
                </button>
                @endif
                
                <button onclick="openHelp()" class="flex flex-col items-center p-3 rounded-lg hover:bg-gray-100 text-gray-700">
                    <span class="text-2xl mb-1">❓</span>
                    <span class="text-xs">Help & FAQ</span>
                </button>
            </div>
        </div>
    </div>
</div>
