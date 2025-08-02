<!-- Role Switcher Component -->
<div class="relative">
    <button id="roleSwitcherBtn" class="flex items-center space-x-1 bg-blue-700 px-3 py-2 rounded hover:bg-blue-800 transition-colors" onclick="toggleRoleSwitcher()">
        <span class="text-sm">👤</span>
        <span class="text-sm">Switch Role</span>
        <span class="text-xs">▼</span>
    </button>
    
    <!-- Role Switcher Dropdown -->
    <div id="roleSwitcher" class="hidden absolute right-0 top-full mt-2 w-80 bg-white rounded-lg shadow-xl border z-50">
        <div class="p-4">
            <h3 class="text-gray-800 font-semibold mb-3">Your Roles Across Projects</h3>
            <div class="space-y-2 max-h-60 overflow-y-auto">
                <!-- Demo data - this would be dynamic from backend -->
                <div class="role-item p-3 rounded-lg border hover:bg-gray-50 cursor-pointer" onclick="switchRole('agent', 'Sattenapalli Project', 1)">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="font-medium text-gray-800">Sales Agent</div>
                            <div class="text-sm text-gray-600">Sattenapalli Project</div>
                            <div class="text-xs text-green-600">• Currently Active</div>
                        </div>
                        <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">Plot Sales</span>
                    </div>
                </div>
                
                <div class="role-item p-3 rounded-lg border hover:bg-gray-50 cursor-pointer" onclick="switchRole('sales_manager', 'Green Valley Apartments', 2)">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="font-medium text-gray-800">Sales Manager</div>
                            <div class="text-sm text-gray-600">Green Valley Apartments</div>
                            <div class="text-xs text-gray-500">• Available</div>
                        </div>
                        <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">Apartments</span>
                    </div>
                </div>
                
                <div class="role-item p-3 rounded-lg border hover:bg-gray-50 cursor-pointer" onclick="switchRole('marketing_executive', 'Sunrise Farmlands', 3)">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="font-medium text-gray-800">Marketing Executive</div>
                            <div class="text-sm text-gray-600">Sunrise Farmlands</div>
                            <div class="text-xs text-gray-500">• Available</div>
                        </div>
                        <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">Farm Land</span>
                    </div>
                </div>
                
                <div class="role-item p-3 rounded-lg border hover:bg-gray-50 cursor-pointer" onclick="switchRole('project_manager', 'Elite Villas', 4)">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="font-medium text-gray-800">Project Manager</div>
                            <div class="text-sm text-gray-600">Elite Villas</div>
                            <div class="text-xs text-gray-500">• Available</div>
                        </div>
                        <span class="text-xs bg-purple-100 text-purple-800 px-2 py-1 rounded">Villas</span>
                    </div>
                </div>
            </div>
            
            <div class="mt-3 pt-3 border-t">
                <div class="text-xs text-gray-500 text-center">
                    💡 Click any role to switch context and see different permissions
                </div>
            </div>
        </div>
    </div>
</div>
