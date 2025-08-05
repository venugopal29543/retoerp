<!-- Mobile Bottom Action Fragment -->
<div class="mobile-bottom-bar fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 shadow-lg md:hidden z-50" 
     x-data="{ activeTab: 'map', showFeatures: false }" 
     style="position: fixed !important; bottom: 0 !important; z-index: 9999 !important;">
    
    <!-- Bottom Action Bar -->
    <div class="flex justify-around items-center py-3 px-2">
        
        <!-- Map View Button -->
        <button @click="activeTab = 'map'; showFeatures = false; scrollToMap()" 
                :class="{ 'text-blue-600 bg-blue-50': activeTab === 'map', 'text-gray-600': activeTab !== 'map' }"
                class="flex flex-col items-center p-2 rounded-lg transition-colors min-w-0 flex-1">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-1.447-.894L15 4m0 13V4m0 0L9 7"/>
            </svg>
            <span class="text-xs font-medium">Map</span>
        </button>

        <!-- Search Button -->
        <button @click="activeTab = 'search'; showFeatures = false; toggleSearch()" 
                :class="{ 'text-green-600 bg-green-50': activeTab === 'search', 'text-gray-600': activeTab !== 'search' }"
                class="flex flex-col items-center p-2 rounded-lg transition-colors min-w-0 flex-1">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <span class="text-xs font-medium">Search</span>
        </button>

        <!-- Features Menu (9-dot style) -->
        <button @click="activeTab = 'features'; showFeatures = !showFeatures" 
                :class="{ 'text-purple-600 bg-purple-50': activeTab === 'features', 'text-gray-600': activeTab !== 'features' }"
                class="flex flex-col items-center p-2 rounded-lg transition-colors min-w-0 flex-1">
            <!-- 9-dot menu like Gmail -->
            <div class="grid grid-cols-3 gap-1 w-6 h-6 mb-1">
                <div class="w-1.5 h-1.5 bg-current rounded-full"></div>
                <div class="w-1.5 h-1.5 bg-current rounded-full"></div>
                <div class="w-1.5 h-1.5 bg-current rounded-full"></div>
                <div class="w-1.5 h-1.5 bg-current rounded-full"></div>
                <div class="w-1.5 h-1.5 bg-current rounded-full"></div>
                <div class="w-1.5 h-1.5 bg-current rounded-full"></div>
                <div class="w-1.5 h-1.5 bg-current rounded-full"></div>
                <div class="w-1.5 h-1.5 bg-current rounded-full"></div>
                <div class="w-1.5 h-1.5 bg-current rounded-full"></div>
            </div>
            <span class="text-xs font-medium">Apps</span>
        </button>

        <!-- Notifications -->
        <button @click="activeTab = 'notifications'; showFeatures = false; openNotifications()" 
                :class="{ 'text-red-600 bg-red-50': activeTab === 'notifications', 'text-gray-600': activeTab !== 'notifications' }"
                class="flex flex-col items-center p-2 rounded-lg transition-colors min-w-0 flex-1 relative">
            <svg class="w-5 h-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM9 9h3v3h-3V9zM15 9h3v3h-3V9zM3 9h3v3H3V9zM9 15h3v3h-3v-3zM15 15h3v3h-3v-3zM3 15h3v3H3v-3z"/>
            </svg>
            <!-- Notification badge -->
            <div class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full flex items-center justify-center">
                <span class="text-xs text-white font-bold">3</span>
            </div>
            <span class="text-xs font-medium">Alerts</span>
        </button>

        <!-- Profile/Settings -->
        <button @click="activeTab = 'profile'; showFeatures = false; toggleProfile()" 
                :class="{ 'text-indigo-600 bg-indigo-50': activeTab === 'profile', 'text-gray-600': activeTab !== 'profile' }"
                class="flex flex-col items-center p-2 rounded-lg transition-colors min-w-0 flex-1">
            <svg class="w-5 h-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <span class="text-xs font-medium">Profile</span>
        </button>
    </div>

    <!-- Feature Apps Panel -->
    <div x-show="showFeatures" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-4"
         class="absolute bottom-full left-0 right-0 bg-white border-t border-gray-200 shadow-lg max-h-96 overflow-y-auto"
         @click.away="showFeatures = false">
        
        <div class="p-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Apps & Features</h3>
                <button @click="showFeatures = false" class="p-1 text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <!-- Features Grid -->
            <div class="grid grid-cols-3 gap-4">
                <!-- EMI Calculator -->
                <button onclick="openEMICalculator(); closeFeaturesPanel()" 
                        class="flex flex-col items-center p-4 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl hover:from-blue-100 hover:to-blue-200 transition-all transform hover:scale-105">
                    <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center mb-2 shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-gray-700 text-center">EMI Calculator</span>
                </button>
                
                <!-- Reports -->
                <button onclick="openReports(); closeFeaturesPanel()" 
                        class="flex flex-col items-center p-4 bg-gradient-to-br from-green-50 to-green-100 rounded-xl hover:from-green-100 hover:to-green-200 transition-all transform hover:scale-105">
                    <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center mb-2 shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-gray-700 text-center">Reports</span>
                </button>
                
                <!-- Customers -->
                <button onclick="openCustomers(); closeFeaturesPanel()" 
                        class="flex flex-col items-center p-4 bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl hover:from-purple-100 hover:to-purple-200 transition-all transform hover:scale-105">
                    <div class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center mb-2 shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-gray-700 text-center">Customers</span>
                </button>
                
                <!-- Analytics -->
                <button onclick="openAnalytics(); closeFeaturesPanel()" 
                        class="flex flex-col items-center p-4 bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl hover:from-orange-100 hover:to-orange-200 transition-all transform hover:scale-105">
                    <div class="w-12 h-12 bg-orange-500 rounded-full flex items-center justify-center mb-2 shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-gray-700 text-center">Analytics</span>
                </button>
                
                <!-- Export -->
                <button onclick="exportData(); closeFeaturesPanel()" 
                        class="flex flex-col items-center p-4 bg-gradient-to-br from-teal-50 to-teal-100 rounded-xl hover:from-teal-100 hover:to-teal-200 transition-all transform hover:scale-105">
                    <div class="w-12 h-12 bg-teal-500 rounded-full flex items-center justify-center mb-2 shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-gray-700 text-center">Export</span>
                </button>
                
                <!-- Print -->
                <button onclick="printLayout(); closeFeaturesPanel()" 
                        class="flex flex-col items-center p-4 bg-gradient-to-br from-pink-50 to-pink-100 rounded-xl hover:from-pink-100 hover:to-pink-200 transition-all transform hover:scale-105">
                    <div class="w-12 h-12 bg-pink-500 rounded-full flex items-center justify-center mb-2 shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-gray-700 text-center">Print</span>
                </button>
                
                <!-- Settings -->
                <button onclick="openSettings(); closeFeaturesPanel()" 
                        class="flex flex-col items-center p-4 bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl hover:from-gray-100 hover:to-gray-200 transition-all transform hover:scale-105">
                    <div class="w-12 h-12 bg-gray-500 rounded-full flex items-center justify-center mb-2 shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-gray-700 text-center">Settings</span>
                </button>
                
                <!-- Help -->
                <button onclick="showHelp(); closeFeaturesPanel()" 
                        class="flex flex-col items-center p-4 bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-xl hover:from-indigo-100 hover:to-indigo-200 transition-all transform hover:scale-105">
                    <div class="w-12 h-12 bg-indigo-500 rounded-full flex items-center justify-center mb-2 shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-gray-700 text-center">Help</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Mobile Bottom Bar JavaScript Functions -->
<script>
function scrollToMap() {
    const mapContainer = document.getElementById('mapContainer') || document.querySelector('.map-container');
    if (mapContainer) {
        mapContainer.scrollIntoView({ behavior: 'smooth' });
    }
}

function toggleSearch() {
    const searchInput = document.getElementById('searchInput') || document.querySelector('input[type="search"]');
    if (searchInput) {
        searchInput.focus();
        searchInput.scrollIntoView({ behavior: 'smooth' });
    }
}

function openNotifications() {
    // Show notifications modal or panel
    console.log('Opening notifications...');
    alert('Notifications:\n• 3 new plot inquiries\n• Payment reminder for Plot #125\n• Monthly report ready');
}

function toggleProfile() {
    // Show profile/settings modal
    console.log('Opening profile...');
    alert('Profile Settings:\n• Update personal info\n• Change password\n• Notification preferences\n• Account settings');
}

function closeFeaturesPanel() {
    // Close the features panel
    const component = document.querySelector('[x-data*="showFeatures"]');
    if (component && component.__x) {
        component.__x.$data.showFeatures = false;
    }
}

// Feature Functions
function openReports() {
    console.log('Opening Reports...');
    alert('Reports Module:\n• Sales reports\n• Plot status summary\n• Customer analytics\n• Revenue dashboard');
}

function openCustomers() {
    console.log('Opening Customer Management...');
    alert('Customer Management:\n• Customer database\n• Contact history\n• Lead tracking\n• Follow-up reminders');
}

function openAnalytics() {
    console.log('Opening Analytics...');
    alert('Analytics Dashboard:\n• Plot booking trends\n• Revenue analytics\n• Customer insights\n• Performance metrics');
}

function exportData() {
    console.log('Exporting Data...');
    alert('Export Options:\n• Excel spreadsheet\n• PDF report\n• CSV data\n• Image layouts');
}

function printLayout() {
    console.log('Printing Layout...');
    window.print();
}

function openSettings() {
    console.log('Opening Settings...');
    alert('System Settings:\n• User preferences\n• Display options\n• Notification settings\n• System configuration');
}

function showHelp() {
    console.log('Opening Help...');
    alert('Help & Support:\n• User guide\n• Video tutorials\n• FAQ section\n• Contact support');
}

// Filter functionality
function toggleFilter() {
    const filterPanel = document.getElementById('filterPanel') || document.querySelector('.filter-panel');
    if (filterPanel) {
        filterPanel.classList.toggle('hidden');
        filterPanel.scrollIntoView({ behavior: 'smooth' });
    }
}
</script>
