<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>RetoERP - Admin Dashboard</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- DataTables for better grid display -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.tailwindcss.min.js"></script>
    
    <!-- XLSX Export -->
    <script src="https://unpkg.com/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
    
    <!-- PDF Export (jsPDF) -->
    <script src="https://unpkg.com/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
    <script src="https://unpkg.com/jspdf-autotable@3.5.25/dist/jspdf.plugin.autotable.min.js"></script>
    
    <style>
        /* Modern Dashboard Styles */
        .dashboard-container {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .stats-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }
        
        .stats-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }
        
        .grid-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .action-btn {
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
        }
        
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
        
        .btn-success {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            border: none;
        }
        
        .btn-success:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(79, 172, 254, 0.4);
        }
        
        .btn-export {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            color: white;
            border: none;
        }
        
        .btn-export:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(250, 112, 154, 0.4);
        }
        
        /* Status badges */
        .status-available {
            background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: capitalize;
        }
        
        .status-booked {
            background: linear-gradient(135deg, #ef4444 0%, #f87171 100%);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: capitalize;
        }
        
        .status-blocked {
            background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: capitalize;
        }
        
        /* DataTables customization */
        .dataTables_wrapper {
            font-size: 14px;
        }
        
        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #d1d5db;
            border-radius: 6px;
            padding: 6px 12px;
            font-size: 14px;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 6px 12px;
            margin: 0 2px;
            border-radius: 6px;
            border: 1px solid #d1d5db;
            font-size: 14px;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white !important;
            border-color: #667eea;
        }
        
        table.dataTable thead th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: 600;
            padding: 12px 8px;
            font-size: 14px;
        }
        
        table.dataTable tbody td {
            padding: 10px 8px;
            font-size: 13px;
            vertical-align: middle;
        }
        
        table.dataTable tbody tr:hover {
            background-color: rgba(102, 126, 234, 0.05);
        }
        
        /* Sync indicator */
        .sync-indicator {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            background: rgba(34, 197, 94, 0.95);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(34, 197, 94, 0.4);
            opacity: 0;
            transition: all 0.3s ease;
        }
        
        .sync-indicator.show {
            opacity: 1;
        }
        
        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr 1fr;
                gap: 12px;
            }
            
            .action-toolbar {
                flex-direction: column;
                gap: 8px;
            }
            
            .action-btn {
                width: 100%;
                justify-content: center;
            }
            
            table.dataTable {
                font-size: 12px;
            }
            
            table.dataTable thead th,
            table.dataTable tbody td {
                padding: 8px 4px;
            }
        }
        
        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
        
        /* Custom scrollbar */
        .grid-container::-webkit-scrollbar {
            width: 6px;
        }
        
        .grid-container::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        .grid-container::-webkit-scrollbar-thumb {
            background: #667eea;
            border-radius: 3px;
        }
        
        .grid-container::-webkit-scrollbar-thumb:hover {
            background: #5a67d8;
        }
    </style>
</head>
<body class="dashboard-container">
    <!-- Real-time sync indicator -->
        <!-- Real-time sync indicator -->
    <div id="syncIndicator" class="sync-indicator">
        ✅ Data synchronized with Welcome Page
    </div>

    <!-- Dashboard Header -->
    <div class="glass-effect">
        <div class="max-w-7xl mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-white">RetoERP Admin Dashboard</h1>
                    <p class="text-blue-100 text-sm mt-1">Manage plots, properties, and real estate operations</p>
                </div>
                <div class="flex items-center space-x-3">
                    <!-- Current Role Display -->
                    <div class="bg-white bg-opacity-20 rounded-lg px-3 py-1">
                        <span class="text-white text-sm">Role:</span>
                        <span class="text-white font-semibold ml-1" id="currentRole">Super Admin</span>
                    </div>
                    
                    <!-- User Profile -->
                    <div class="bg-white bg-opacity-20 rounded-lg px-3 py-1">
                        <span class="text-white font-semibold text-sm">{{ Auth::user()->name ?? 'Admin User' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Dashboard Content -->
    <div class="max-w-7xl mx-auto px-4 py-6">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6 stats-grid">
            <div class="stats-card p-4">
                <div class="flex items-center">
                    <div class="p-2 rounded-full bg-blue-500 bg-opacity-20">
                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-gray-600 text-sm">Available Plots</p>
                        <p class="text-xl font-bold text-gray-900" id="availableCount">-</p>
                    </div>
                </div>
            </div>
            
            <div class="stats-card p-4">
                <div class="flex items-center">
                    <div class="p-2 rounded-full bg-green-500 bg-opacity-20">
                        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-gray-600 text-sm">Booked Plots</p>
                        <p class="text-xl font-bold text-gray-900" id="bookedCount">-</p>
                    </div>
                </div>
            </div>
            
            <div class="stats-card p-4">
                <div class="flex items-center">
                    <div class="p-2 rounded-full bg-yellow-500 bg-opacity-20">
                        <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-gray-600 text-sm">Blocked Plots</p>
                        <p class="text-xl font-bold text-gray-900" id="blockedCount">-</p>
                    </div>
                </div>
            </div>
            
            <div class="stats-card p-4">
                <div class="flex items-center">
                    <div class="p-2 rounded-full bg-purple-500 bg-opacity-20">
                        <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-gray-600 text-sm">Total Revenue</p>
                        <p class="text-xl font-bold text-gray-900" id="totalRevenue">-</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Toolbar -->
        <div class="bg-white rounded-lg p-4 mb-6 shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-3 action-toolbar">
                <div class="flex flex-wrap items-center gap-2">
                    <button class="action-btn btn-primary" onclick="refreshData()">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Refresh Data
                    </button>
                    <button class="action-btn btn-success" onclick="addNewPlot()">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add New Plot
                    </button>
                </div>
                
                <div class="flex flex-wrap items-center gap-2">
                    <button class="action-btn btn-export" onclick="exportToExcel()">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Export Excel
                    </button>
                    <button class="action-btn btn-export" onclick="exportToPDF()">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Export PDF
                    </button>
                    <button class="action-btn btn-primary" onclick="toggleWelcomePageSync()">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                        View Welcome Page
                    </button>
                </div>
            </div>
        </div>

        <!-- Plots Data Grid -->
        <div class="grid-container">
            <div class="p-4 bg-gray-50 border-b">
                <h3 class="text-lg font-semibold text-gray-800">Plots Management</h3>
                <p class="text-sm text-gray-600">Manage all plot information with inline editing and real-time sync</p>
            </div>
            <div class="p-4">
                <table id="plotsTable" class="display table-auto w-full">
                    <thead>
                        <tr>
                            <th>Plot ID</th>
                            <th>Display Name</th>
                            <th>Block</th>
                            <th>Status</th>
                            <th>Price (₹)</th>
                            <th>Area (sq ft)</th>
                            <th>Layout</th>
                            <th>Amenities</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be loaded via JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        class DashboardManager {
            constructor() {
                this.plotsData = [];
                this.dataTable = null;
                this.isConnectedToWelcomePage = false;
                
                this.initializeDashboard();
            }

            async initializeDashboard() {
                console.log('🚀 Initializing Dashboard Manager...');
                
                try {
                    // Load plots data
                    await this.loadData();
                    
                    // Initialize DataTable
                    this.initializeDataTable();
                    
                    // Setup real-time sync
                    this.setupRealTimeSync();
                    
                    console.log('✅ Dashboard initialized successfully');
                    
                } catch (error) {
                    console.error('❌ Error initializing dashboard:', error);
                    this.showNotification('Error initializing dashboard', 'error');
                }
            }

            async loadData() {
                try {
                    console.log('📊 Loading plots data...');
                    
                    // Fetch plots data from JSON file
                    const response = await fetch('/data/plots.json');
                    const data = await response.json();
                    
                    // Transform data for table
                    this.plotsData = [];
                    
                    data.layouts.forEach(layout => {
                        layout.plots.forEach(plot => {
                            this.plotsData.push({
                                id: plot.id,
                                displayName: plot.displayName,
                                block: plot.block,
                                status: plot.status || 'available',
                                price: plot.price || 0,
                                area: plot.area || 0,
                                layout: plot.layout || layout.name,
                                amenities: plot.amenities || [],
                                coordinates: plot.coordinates || []
                            });
                        });
                    });
                    
                    // Update statistics
                    this.updateStatistics();
                    
                    console.log(`✅ Loaded ${this.plotsData.length} plots successfully`);
                    
                } catch (error) {
                    console.error('❌ Error loading plots data:', error);
                    throw error;
                }
            }

            initializeDataTable() {
                const tableData = this.plotsData.map(plot => [
                    plot.id,
                    plot.displayName,
                    plot.block,
                    this.renderStatusBadge(plot.status),
                    this.formatPrice(plot.price),
                    this.formatArea(plot.area),
                    plot.layout,
                    this.renderAmenities(plot.amenities),
                    this.renderActions(plot.id)
                ]);

                this.dataTable = $('#plotsTable').DataTable({
                    data: tableData,
                    pageLength: 25,
                    responsive: true,
                    order: [[0, 'asc']],
                    columnDefs: [
                        { 
                            targets: [3, 7, 8], 
                            orderable: false 
                        },
                        {
                            targets: [4],
                            type: 'num-fmt'
                        }
                    ],
                    language: {
                        search: "Search plots:",
                        lengthMenu: "Show _MENU_ plots per page",
                        info: "Showing _START_ to _END_ of _TOTAL_ plots",
                        emptyTable: "No plots data available"
                    },
                    dom: 'Bfrtip',
                    buttons: []
                });

                console.log('✅ DataTable initialized successfully');
            }

            renderStatusBadge(status) {
                const statusClass = `status-${status}`;
                return `<span class="${statusClass}">${status.charAt(0).toUpperCase() + status.slice(1)}</span>`;
            }

            formatPrice(price) {
                return `₹${Number(price).toLocaleString('en-IN')}`;
            }

            formatArea(area) {
                return `${Number(area).toLocaleString()} sq ft`;
            }

            renderAmenities(amenities) {
                if (Array.isArray(amenities)) {
                    return amenities.map(amenity => 
                        `<span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full mr-1 mb-1">${amenity}</span>`
                    ).join('');
                }
                return amenities || '';
            }

            renderActions(plotId) {
                return `
                    <div class="flex gap-1">
                        <button onclick="dashboardManager.editPlot('${plotId}')" 
                                class="bg-blue-500 text-white px-2 py-1 rounded text-xs hover:bg-blue-600 transition-colors">
                            Edit
                        </button>
                        <button onclick="dashboardManager.updatePlotStatus('${plotId}')" 
                                class="bg-green-500 text-white px-2 py-1 rounded text-xs hover:bg-green-600 transition-colors">
                            Status
                        </button>
                        <button onclick="dashboardManager.deletePlot('${plotId}')" 
                                class="bg-red-500 text-white px-2 py-1 rounded text-xs hover:bg-red-600 transition-colors">
                            Delete
                        </button>
                    </div>
                `;
            }

            updateStatistics() {
                const available = this.plotsData.filter(plot => plot.status === 'available').length;
                const booked = this.plotsData.filter(plot => plot.status === 'booked').length;
                const blocked = this.plotsData.filter(plot => plot.status === 'blocked').length;
                
                const totalRevenue = this.plotsData
                    .filter(plot => plot.status === 'booked')
                    .reduce((sum, plot) => sum + (plot.price || 0), 0);

                document.getElementById('availableCount').textContent = available;
                document.getElementById('bookedCount').textContent = booked;
                document.getElementById('blockedCount').textContent = blocked;
                document.getElementById('totalRevenue').textContent = `₹${totalRevenue.toLocaleString('en-IN')}`;
            }

            async updatePlotStatus(plotId) {
                const plot = this.plotsData.find(p => p.id === plotId);
                if (!plot) return;

                const currentStatus = plot.status;
                const statuses = ['available', 'booked', 'blocked'];
                const currentIndex = statuses.indexOf(currentStatus);
                const nextStatus = statuses[(currentIndex + 1) % statuses.length];

                try {
                    // Show loading state
                    this.showNotification('Updating plot status...', 'info');

                    // Save to database via API
                    const response = await fetch('/api/plots/update', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            plotId: plotId,
                            status: nextStatus
                        })
                    });

                    if (!response.ok) {
                        throw new Error('Failed to update plot status');
                    }

                    const result = await response.json();
                    
                    if (result.success) {
                        // Update local data
                        plot.status = nextStatus;

                        // Update table row
                        const rowIndex = this.plotsData.findIndex(p => p.id === plotId);
                        if (rowIndex !== -1) {
                            this.dataTable.cell(rowIndex, 3).data(this.renderStatusBadge(nextStatus));
                        }

                        // Update statistics
                        this.updateStatistics();

                        // Sync with welcome page
                        await this.syncWithWelcomePage(plotId, { status: nextStatus });

                        // Show success notification
                        this.showSyncNotification();
                        this.showNotification(`Plot ${plotId} status permanently updated to ${nextStatus}`, 'success');
                    } else {
                        throw new Error(result.message || 'Failed to update plot status');
                    }

                } catch (error) {
                    console.error('❌ Error updating plot status:', error);
                    this.showNotification(`Error updating plot status: ${error.message}`, 'error');
                }
            }

            async syncWithWelcomePage(plotId, changes) {
                // Real-time sync using localStorage + custom events
                const syncData = {
                    type: 'plot_update',
                    plotId: plotId,
                    changes: changes,
                    timestamp: Date.now()
                };
                
                // Store in localStorage for cross-tab communication
                localStorage.setItem('plot_sync', JSON.stringify(syncData));
                
                // Dispatch custom event for same-tab communication
                window.dispatchEvent(new CustomEvent('plotUpdate', {
                    detail: syncData
                }));
                
                console.log('🔄 Synced with welcome page:', syncData);
            }

            setupRealTimeSync() {
                // Listen for storage changes (cross-tab sync)
                window.addEventListener('storage', (event) => {
                    if (event.key === 'welcome_to_dashboard_sync') {
                        const syncData = JSON.parse(event.newValue);
                        this.handleWelcomePageUpdate(syncData);
                    }
                });
                
                // Listen for same-tab updates
                window.addEventListener('welcomePageUpdate', (event) => {
                    this.handleWelcomePageUpdate(event.detail);
                });
                
                console.log('🔄 Real-time sync setup completed');
            }

            handleWelcomePageUpdate(syncData) {
                console.log('📥 Received update from welcome page:', syncData);
                
                if (syncData.type === 'plot_interaction') {
                    // Highlight the plot in table
                    const plotIndex = this.plotsData.findIndex(p => p.id === syncData.plotId);
                    if (plotIndex !== -1 && this.dataTable) {
                        // Scroll to and highlight the row
                        this.dataTable.rows().deselect();
                        this.dataTable.row(plotIndex).select();
                        
                        // Scroll to the row
                        const page = Math.floor(plotIndex / this.dataTable.page.len());
                        this.dataTable.page(page).draw('page');
                    }
                }
            }

            showSyncNotification() {
                const indicator = document.getElementById('syncIndicator');
                indicator.classList.add('show');
                
                setTimeout(() => {
                    indicator.classList.remove('show');
                }, 3000);
            }

            showNotification(message, type = 'success') {
                // Create notification element
                const notification = document.createElement('div');
                let bgColor;
                switch(type) {
                    case 'success':
                        bgColor = 'bg-green-500';
                        break;
                    case 'error':
                        bgColor = 'bg-red-500';
                        break;
                    case 'info':
                        bgColor = 'bg-blue-500';
                        break;
                    default:
                        bgColor = 'bg-gray-500';
                }
                
                notification.className = `fixed top-20 right-20 z-50 p-3 rounded-lg shadow-lg ${bgColor} text-white text-sm`;
                notification.textContent = message;
                
                document.body.appendChild(notification);
                
                setTimeout(() => {
                    if (document.body.contains(notification)) {
                        document.body.removeChild(notification);
                    }
                }, 3000);
            }

            // Export functions
            exportToExcel() {
                console.log('📊 Exporting to Excel...');
                
                const ws = XLSX.utils.json_to_sheet(this.plotsData.map(plot => ({
                    'Plot ID': plot.id,
                    'Display Name': plot.displayName,
                    'Block': plot.block,
                    'Status': plot.status,
                    'Price (₹)': plot.price,
                    'Area (sq ft)': plot.area,
                    'Layout': plot.layout,
                    'Amenities': Array.isArray(plot.amenities) ? plot.amenities.join(', ') : plot.amenities
                })));
                
                const wb = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(wb, ws, 'Plots Data');
                
                XLSX.writeFile(wb, `plots_data_${new Date().toISOString().split('T')[0]}.xlsx`);
                
                this.showNotification('Excel file exported successfully!');
            }

            exportToPDF() {
                console.log('📄 Exporting to PDF...');
                
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF();
                
                // Add title
                doc.setFontSize(20);
                doc.text('RetoERP - Plots Data Report', 20, 20);
                
                // Add timestamp
                doc.setFontSize(12);
                doc.text(`Generated on: ${new Date().toLocaleString()}`, 20, 35);
                
                // Add statistics
                doc.text(`Total Plots: ${this.plotsData.length}`, 20, 50);
                doc.text(`Available: ${this.plotsData.filter(p => p.status === 'available').length}`, 20, 60);
                doc.text(`Booked: ${this.plotsData.filter(p => p.status === 'booked').length}`, 20, 70);
                doc.text(`Blocked: ${this.plotsData.filter(p => p.status === 'blocked').length}`, 20, 80);
                
                // Add table data using autoTable
                const tableData = this.plotsData.map(plot => [
                    plot.id,
                    plot.displayName,
                    plot.block,
                    plot.status,
                    `₹${plot.price?.toLocaleString()}`,
                    `${plot.area} sq ft`,
                    plot.layout
                ]);

                doc.autoTable({
                    head: [['Plot ID', 'Name', 'Block', 'Status', 'Price', 'Area', 'Layout']],
                    body: tableData,
                    startY: 90,
                    styles: { fontSize: 8 },
                    headStyles: { fillColor: [102, 126, 234] }
                });
                
                doc.save(`plots_report_${new Date().toISOString().split('T')[0]}.pdf`);
                
                this.showNotification('PDF report exported successfully!');
            }

            // CRUD operations
            async editPlot(plotId) {
                const plot = this.plotsData.find(p => p.id === plotId);
                if (plot) {
                    const newName = prompt('Enter new display name:', plot.displayName);
                    if (newName && newName !== plot.displayName) {
                        try {
                            // Show loading state
                            this.showNotification('Updating plot...', 'info');

                            // Save to database via API
                            const response = await fetch('/api/plots/update', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: JSON.stringify({
                                    plotId: plotId,
                                    displayName: newName
                                })
                            });

                            if (!response.ok) {
                                throw new Error('Failed to update plot');
                            }

                            const result = await response.json();
                            
                            if (result.success) {
                                // Update local data
                                plot.displayName = newName;
                                
                                // Update table
                                const rowIndex = this.plotsData.findIndex(p => p.id === plotId);
                                this.dataTable.cell(rowIndex, 1).data(newName);
                                
                                // Sync changes
                                this.syncWithWelcomePage(plotId, { displayName: newName });
                                this.showNotification(`Plot ${plotId} permanently updated!`, 'success');
                            } else {
                                throw new Error(result.message || 'Failed to update plot');
                            }

                        } catch (error) {
                            console.error('❌ Error updating plot:', error);
                            this.showNotification(`Error updating plot: ${error.message}`, 'error');
                        }
                    }
                }
            }

            async deletePlot(plotId) {
                if (confirm(`Are you sure you want to delete plot ${plotId}? This action cannot be undone.`)) {
                    try {
                        // Show loading state
                        this.showNotification('Deleting plot...', 'info');

                        // Delete from database via API
                        const response = await fetch('/api/plots/delete', {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                plotId: plotId
                            })
                        });

                        if (!response.ok) {
                            throw new Error('Failed to delete plot');
                        }

                        const result = await response.json();
                        
                        if (result.success) {
                            // Remove from data
                            const plotIndex = this.plotsData.findIndex(p => p.id === plotId);
                            if (plotIndex !== -1) {
                                this.plotsData.splice(plotIndex, 1);
                                
                                // Remove from table
                                this.dataTable.row(plotIndex).remove().draw();
                                
                                // Update statistics
                                this.updateStatistics();
                                
                                this.showNotification(`Plot ${plotId} permanently deleted!`, 'success');
                            }
                        } else {
                            throw new Error(result.message || 'Failed to delete plot');
                        }

                    } catch (error) {
                        console.error('❌ Error deleting plot:', error);
                        this.showNotification(`Error deleting plot: ${error.message}`, 'error');
                    }
                }
            }
        }

        // Global functions
        window.refreshData = function() {
            if (window.dashboardManager) {
                window.dashboardManager.loadData().then(() => {
                    window.dashboardManager.dataTable.clear();
                    const tableData = window.dashboardManager.plotsData.map(plot => [
                        plot.id,
                        plot.displayName,
                        plot.block,
                        window.dashboardManager.renderStatusBadge(plot.status),
                        window.dashboardManager.formatPrice(plot.price),
                        window.dashboardManager.formatArea(plot.area),
                        plot.layout,
                        window.dashboardManager.renderAmenities(plot.amenities),
                        window.dashboardManager.renderActions(plot.id)
                    ]);
                    window.dashboardManager.dataTable.rows.add(tableData).draw();
                    window.dashboardManager.showNotification('Data refreshed successfully!');
                });
            }
        };

        window.addNewPlot = async function() {
            try {
                // Show loading state
                if (window.dashboardManager) {
                    window.dashboardManager.showNotification('Creating new plot...', 'info');
                }

                const newId = (Math.max(...window.dashboardManager.plotsData.map(p => parseInt(p.id))) + 1).toString();
                const newPlot = {
                    id: newId,
                    displayName: newId,
                    block: 'A',
                    status: 'available',
                    price: 1000000,
                    area: 1500,
                    layout: 'Sathenapally Main',
                    amenities: ['Water', 'Electricity', 'Road Access']
                };

                // Save to database via API
                const response = await fetch('/api/plots/create', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(newPlot)
                });

                if (!response.ok) {
                    throw new Error('Failed to create plot');
                }

                const result = await response.json();
                
                if (result.success) {
                    // Add to local data
                    window.dashboardManager.plotsData.push(newPlot);
                    
                    const newRowData = [
                        newPlot.id,
                        newPlot.displayName,
                        newPlot.block,
                        window.dashboardManager.renderStatusBadge(newPlot.status),
                        window.dashboardManager.formatPrice(newPlot.price),
                        window.dashboardManager.formatArea(newPlot.area),
                        newPlot.layout,
                        window.dashboardManager.renderAmenities(newPlot.amenities),
                        window.dashboardManager.renderActions(newPlot.id)
                    ];
                    
                    window.dashboardManager.dataTable.row.add(newRowData).draw();
                    window.dashboardManager.updateStatistics();
                    window.dashboardManager.showNotification('New plot permanently created!', 'success');
                } else {
                    throw new Error(result.message || 'Failed to create plot');
                }

            } catch (error) {
                console.error('❌ Error creating plot:', error);
                if (window.dashboardManager) {
                    window.dashboardManager.showNotification(`Error creating plot: ${error.message}`, 'error');
                }
            }
        };

        window.exportToExcel = function() {
            if (window.dashboardManager) {
                window.dashboardManager.exportToExcel();
            }
        };

        window.exportToPDF = function() {
            if (window.dashboardManager) {
                window.dashboardManager.exportToPDF();
            }
        };

        window.toggleWelcomePageSync = function() {
            window.open('/welcome', '_blank');
        };

        // Initialize dashboard when page loads
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🚀 Initializing RetoERP Dashboard...');
            window.dashboardManager = new DashboardManager();
        });
    </script>
</body>
</html>
