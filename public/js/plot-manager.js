// Plot Management JavaScript Module
class PlotManager {
    constructor() {
        this.currentZoom = 1;
        this.svgElement = null;
        this.currentSelectedPlot = null;
        this.plotImages = [
            'https://via.placeholder.com/600x300/10b981/ffffff?text=Plot+View+1',
            'https://via.placeholder.com/600x300/3b82f6/ffffff?text=Plot+View+2', 
            'https://via.placeholder.com/600x300/8b5cf6/ffffff?text=Plot+View+3'
        ];
        
        this.init();
    }

    init() {
        this.svgElement = document.getElementById('layoutSVG');
        this.initializePlotInteraction();
        this.loadDynamicPlots();
    }

    // Initialize plot interaction
    initializePlotInteraction() {
        console.log('🎯 Initializing plot interaction system...');
        
        if (!this.svgElement) {
            console.error('❌ SVG element not found');
            return;
        }

        // Add event listeners for plot interaction
        this.svgElement.addEventListener('click', this.handleClick.bind(this));
        this.svgElement.addEventListener('mouseover', this.handleHover.bind(this));
        this.svgElement.addEventListener('mouseout', this.handleOut.bind(this));
    }

    // Handle hover events
    handleHover(event) {
        const target = event.target;
        if (target.classList.contains('plot') || target.classList.contains('plot-shape')) {
            target.style.filter = 'brightness(1.1) drop-shadow(2px 2px 4px rgba(0,0,0,0.3))';
            target.style.cursor = 'pointer';
        }
    }

    // Handle mouse out events
    handleOut(event) {
        const target = event.target;
        if (target.classList.contains('plot') || target.classList.contains('plot-shape')) {
            target.style.filter = '';
            target.style.cursor = 'default';
        }
    }

    // Handle click events
    handleClick(event) {
        const target = event.target;
        if (target.classList.contains('plot') || target.classList.contains('plot-shape')) {
            const plotId = target.getAttribute('data-plot-id') || target.id;
            const block = target.getAttribute('data-block') || 'A';
            const price = target.getAttribute('data-price') || '₹15,00,000';
            const area = target.getAttribute('data-area') || '1200 sq ft';
            const status = target.getAttribute('data-status') || 'available';
            
            this.openEnhancedModal(plotId, block, price, area, status);
        }
    }

    // Open Enhanced Modal
    async openEnhancedModal(plotId, block = 'A', price = '₹15,00,000', area = '1200 sq ft', status = 'available') {
        console.log(`🏠 Opening modal for Plot ${plotId}`);
        
        this.currentSelectedPlot = plotId;
        
        // Show the modal first
        const modal = document.getElementById('enhancedModal');
        modal.style.display = 'flex';
        
        // Show loading indicator
        const loadingOverlay = document.getElementById('modalLoading');
        const modalContent = document.getElementById('modalContent');
        if (loadingOverlay && modalContent) {
            loadingOverlay.style.display = 'flex';
            modalContent.style.opacity = '0.5';
        }
        
        try {
            // Fetch plot details from API
            console.log(`📡 Fetching plot ${plotId} details from API...`);
            const response = await fetch(`/api/plots/${plotId}`);
            
            if (!response.ok) {
                throw new Error(`API Error: ${response.status}`);
            }
            
            const plotData = await response.json();
            console.log(`✅ Plot data loaded:`, plotData);
            
            // Use API data if available, otherwise fallback to parameters
            const plot = plotData.data || {
                plot_id: plotId,
                block: block,
                price: price,
                area: area,
                status: status,
                display_name: `Plot ${plotId}`,
                formatted_price: price,
                price_per_sqft: '₹1,250',
                amenities: ['Water Supply', 'Electricity', 'Road Access']
            };
            
            // Update modal with dynamic data
            await this.updateModalWithPlotData(plot);
            
        } catch (error) {
            console.warn(`⚠️ API fetch failed, using fallback data:`, error);
            // Fallback to static data if API fails
            const fallbackPlot = {
                plot_id: plotId,
                block: block,
                price: price,
                area: area,
                status: status,
                display_name: `Plot ${plotId}`,
                formatted_price: price,
                price_per_sqft: '₹1,250',
                amenities: ['Water Supply', 'Electricity', 'Road Access']
            };
            await this.updateModalWithPlotData(fallbackPlot);
        }
        
        // Hide loading indicator
        if (loadingOverlay && modalContent) {
            loadingOverlay.style.display = 'none';
            modalContent.style.opacity = '1';
        }
        
        // Switch to gallery tab by default
        this.switchTab('gallery');
    }

    // Update modal with plot data from API
    async updateModalWithPlotData(plot) {
        console.log(`🔄 Updating modal with plot data:`, plot);
        
        // Update modal title
        const modalTitle = document.getElementById('modalPlotTitle');
        if (modalTitle) {
            modalTitle.textContent = `${plot.display_name || `Plot ${plot.plot_id}`} - Block ${plot.block}`;
        }
        
        // Update plot info in booking tab
        const plotInfo = document.getElementById('selectedPlotInfo');
        if (plotInfo) {
            plotInfo.innerHTML = `
                <div class="bg-white p-4 rounded-lg border-2 border-blue-200 mb-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <h4 class="font-semibold text-blue-800">${plot.display_name || `Plot ${plot.plot_id}`}</h4>
                            <p class="text-sm text-gray-600">Block ${plot.block}</p>
                            <p class="text-lg font-bold text-green-600">${plot.formatted_price || plot.price}</p>
                            <p class="text-sm text-gray-500">Per Sq Ft: ${plot.price_per_sqft || '₹1,250'}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600">Area: ${plot.area} sq ft</p>
                            <p class="text-sm text-gray-600">Dimensions: ${plot.dimensions || '30\' × 40\''}</p>
                            <p class="text-sm capitalize">
                                Status: <span class="font-semibold ${plot.status === 'available' ? 'text-green-600' : 'text-red-600'}">${plot.status}</span>
                            </p>
                        </div>
                    </div>
                </div>
            `;
        }
        
        // Update property details in Details tab
        this.updatePropertyDetails(plot);
    }

    // Update property details grid
    updatePropertyDetails(plot) {
        const detailsTab = document.getElementById('details-tab');
        if (!detailsTab) return;
        
        const propertyGrid = detailsTab.querySelector('.property-grid');
        if (propertyGrid) {
            propertyGrid.innerHTML = `
                <div class="property-card price-highlight">
                    <div class="property-label">Price</div>
                    <div class="property-value">${plot.formatted_price || plot.price}</div>
                </div>
                <div class="property-card">
                    <div class="property-label">Area</div>
                    <div class="property-value">${plot.area} sq ft</div>
                </div>
                <div class="property-card">
                    <div class="property-label">Dimensions</div>
                    <div class="property-value">${plot.dimensions || '30\' × 40\''}</div>
                </div>
                <div class="property-card">
                    <div class="property-label">Status</div>
                    <div class="property-value">
                        <span class="status-badge status-${plot.status}">${plot.status.charAt(0).toUpperCase() + plot.status.slice(1)}</span>
                    </div>
                </div>
                <div class="property-card">
                    <div class="property-label">Block</div>
                    <div class="property-value">Block ${plot.block}</div>
                </div>
                <div class="property-card">
                    <div class="property-label">Plot Type</div>
                    <div class="property-value">${plot.plot_type || 'Residential'}</div>
                </div>
                <div class="property-card">
                    <div class="property-label">Facing</div>
                    <div class="property-value">${plot.facing || 'East Facing'}</div>
                </div>
                <div class="property-card">
                    <div class="property-label">Per Sq Ft</div>
                    <div class="property-value">${plot.price_per_sqft || '₹1,250'}</div>
                </div>
            `;
        }
        
        // Update amenities if available
        const amenitiesList = detailsTab.querySelector('.amenities-list');
        if (amenitiesList && plot.amenities) {
            const amenityIcons = {
                'Water Supply': '💧',
                'Electricity': '⚡',
                'Road Access': '🛣️',
                'Park View': '🏞️',
                'Security': '🔒',
                'Parking': '🚗',
                'Drainage': '🌡️',
                'Street Lights': '📶'
            };
            
            amenitiesList.innerHTML = plot.amenities.map(amenity => 
                `<span class="amenity-tag">${amenityIcons[amenity] || '✓'} ${amenity}</span>`
            ).join('');
        }
    }

    // Switch modal tabs
    switchTab(tabName) {
        // Hide all tabs
        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.classList.remove('active');
        });
        
        // Remove active class from all tab buttons
        document.querySelectorAll('.modal-tab').forEach(btn => {
            btn.classList.remove('active');
        });
        
        // Show selected tab
        const selectedTab = document.getElementById(`${tabName}-tab`);
        if (selectedTab) {
            selectedTab.classList.add('active');
        }
        
        // Add active class to selected button
        const selectedBtn = document.querySelector(`[onclick="switchTab('${tabName}')"]`);
        if (selectedBtn) {
            selectedBtn.classList.add('active');
        }
    }

    // Dynamic plot loading
    async loadDynamicPlots() {
        try {
            console.log('📊 Loading plot data...');
            
            // Fetch directly from JSON file to get latest updates
            const response = await fetch('/data/plots.json?cache=' + Date.now());
            if (response.ok) {
                const plotsData = await response.json();
                this.processAndStorePlotData(plotsData);
                this.generateDynamicPlots(plotsData);
            } else {
                throw new Error('JSON file not available');
            }
        } catch (error) {
            console.log('⚠️ Plot data not available, loading static plots');
            this.loadStaticPlots();
        }
    }

    // Process and store plot data for sync
    processAndStorePlotData(plotsData) {
        // Store the plot data globally for sync operations
        window.currentPlotsData = plotsData;
        
        // Update plot status from the latest data
        if (plotsData.layouts) {
            plotsData.layouts.forEach(layout => {
                layout.plots.forEach(plot => {
                    const plotElement = document.getElementById(`plot-${plot.id}`);
                    if (plotElement) {
                        // Update data attributes
                        plotElement.setAttribute('data-status', plot.status || 'available');
                        plotElement.setAttribute('data-price', plot.price || 0);
                        plotElement.setAttribute('data-area', plot.area || 0);
                        plotElement.setAttribute('data-display-name', plot.displayName || plot.id);
                        
                        // Update visual appearance based on current status
                        this.updatePlotVisualAppearance(plotElement, plot.status || 'available');
                    }
                });
            });
        }
    }

    // Update plot visual appearance
    updatePlotVisualAppearance(plotElement, status) {
        switch (status) {
            case 'available':
                plotElement.setAttribute('fill', '#dff0d8');
                plotElement.setAttribute('stroke', '#5cb85c');
                break;
            case 'booked':
                plotElement.setAttribute('fill', '#f0ad4e');
                plotElement.setAttribute('stroke', '#d58512');
                break;
            case 'blocked':
                plotElement.setAttribute('fill', '#d9534f');
                plotElement.setAttribute('stroke', '#c9302c');
                break;
            default:
                plotElement.setAttribute('fill', '#dff0d8');
                plotElement.setAttribute('stroke', '#5cb85c');
        }
    }

    // Generate plots from data
    generateDynamicPlots(plotsData) {
        console.log('🏗️ Generating dynamic plots...');
        // Implementation for dynamic plot generation
    }

    // Load static plots (fallback)
    loadStaticPlots() {
        console.log('📦 Loading static plots as fallback...');
        // Implementation for static plot loading
    }

    // Zoom functionality
    zoomIn() {
        this.currentZoom = Math.min(this.currentZoom * 1.2, 3);
        this.updateZoom();
    }

    zoomOut() {
        this.currentZoom = Math.max(this.currentZoom / 1.2, 0.5);
        this.updateZoom();
    }

    resetZoom() {
        this.currentZoom = 1;
        this.updateZoom();
    }

    updateZoom() {
        const container = document.getElementById('layoutContainer');
        if (container) {
            container.style.transform = `scale(${this.currentZoom})`;
        }
    }

    // Search functionality
    searchPlots(searchTerm) {
        console.log(`🔍 Searching for: ${searchTerm}`);
        this.clearSearch(); // Clear previous highlights
        
        if (!searchTerm) return;
        
        // Find and highlight matching plots
        const plots = document.querySelectorAll('[id^="plot-"]');
        plots.forEach(plot => {
            const plotId = plot.id.replace('plot-', '');
            if (plotId.toLowerCase().includes(searchTerm.toLowerCase())) {
                plot.style.fill = '#ffeb3b';
                plot.style.stroke = '#ff9800';
                plot.style.strokeWidth = '5';
            }
        });
    }

    // Clear search highlights
    clearSearch() {
        console.log('🧹 Clearing search highlights');
        const plots = document.querySelectorAll('[id^="plot-"]');
        plots.forEach(plot => {
            // Reset to original colors based on status
            if (plot.getAttribute('data-status') === 'booked') {
                plot.style.fill = '#f0ad4e';
                plot.style.stroke = '#d58512';
            } else {
                plot.style.fill = '#dff0d8';
                plot.style.stroke = '#5cb85c';
            }
            plot.style.strokeWidth = '3';
        });
    }

    // Filter functionality
    filterPlots(status) {
        console.log(`🔧 Filtering by status: ${status}`);
        const plots = document.querySelectorAll('[id^="plot-"]');
        
        plots.forEach(plot => {
            const plotStatus = plot.getAttribute('data-status') || 'available';
            
            if (status === 'all') {
                plot.style.opacity = '1';
                plot.style.pointerEvents = 'all';
            } else if (plotStatus === status) {
                plot.style.opacity = '1';
                plot.style.pointerEvents = 'all';
            } else {
                plot.style.opacity = '0.3';
                plot.style.pointerEvents = 'none';
            }
        });
    }

    // Public method to refresh plot data
    async refreshPlotData() {
        console.log('🔄 Refreshing plot data...');
        try {
            await this.loadDynamicPlots();
            console.log('✅ Plot data refreshed successfully');
            return true;
        } catch (error) {
            console.error('❌ Error refreshing plot data:', error);
            return false;
        }
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.plotManager = new PlotManager();
});

// Export functions for global access
window.zoomIn = () => window.plotManager.zoomIn();
window.zoomOut = () => window.plotManager.zoomOut();
window.resetZoom = () => window.plotManager.resetZoom();
window.searchPlots = (term) => window.plotManager.searchPlots(term);
window.clearSearch = () => window.plotManager.clearSearch();
window.filterPlots = (status) => window.plotManager.filterPlots(status);
window.switchTab = (tabName) => window.plotManager.switchTab(tabName);
