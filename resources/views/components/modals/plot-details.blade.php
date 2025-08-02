<!-- Plot Details Modal Component -->
<div id="enhancedModal" class="enhanced-modal">
    <div class="modal-content" style="width: 900px;">
        <!-- Loading Indicator -->
        <div id="modalLoading" class="loading-overlay" style="display: none;">
            <div class="loading-spinner">
                <div class="spinner"></div>
                <p class="mt-4 text-gray-600">Loading plot details...</p>
            </div>
        </div>
        
        <!-- Modal Content -->
        <div id="modalContent">
            <!-- Modal Header -->
            <div class="modal-header">
                <div>
                    <h3 class="text-2xl font-bold" id="modalPlotTitle">Plot Details</h3>
                    <p class="text-blue-100 text-sm mt-1">Premium residential plot in SATTENAPALLI Layout</p>
                </div>
                <button onclick="closeEnhancedModal()" class="modal-close" aria-label="Close modal">&times;</button>
            </div>
        <!-- Modal Tabs -->
        <div class="modal-tabs">
            <button class="modal-tab active" onclick="switchTab('gallery')" aria-label="View gallery"><span>🖼️</span> <span class="tab-text">Gallery</span></button>
            <button class="modal-tab" onclick="switchTab('video')" aria-label="View video tour"><span>🎥</span> <span class="tab-text">Video</span></button>
            <button class="modal-tab" onclick="switchTab('details')" aria-label="View details"><span>📋</span> <span class="tab-text">Details</span></button>
            <button class="modal-tab" onclick="switchTab('booking')" aria-label="Book now"><span>📝</span> <span class="tab-text">Book</span></button>
        </div>
        <!-- Gallery Tab -->
        <div id="gallery-tab" class="tab-content active">
            <div class="gallery-container">
                <img id="mainGalleryImage" src="https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=1000&q=90" alt="Plot Gallery" class="gallery-main-image">
                <div class="gallery-thumbnails">
                    <img src="https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=200&q=80" class="gallery-thumbnail active" onclick="changeGalleryImage(this, 0)">
                    <img src="https://images.unsplash.com/photo-1564013799919-ab600027ffc6?auto=format&fit=crop&w=200&q=80" class="gallery-thumbnail" onclick="changeGalleryImage(this, 1)">
                    <img src="https://images.unsplash.com/photo-1582407947304-fd86f028f716?auto=format&fit=crop&w=200&q=80" class="gallery-thumbnail" onclick="changeGalleryImage(this, 2)">
                </div>
                <div class="text-center mt-6">
                    <p class="text-gray-600 text-lg">📸 Professional photos showing plot boundaries, surrounding area, and road access</p>
                    <div class="flex justify-center gap-4 mt-4 text-sm text-gray-500">
                        <span>✓ Drone Photography</span>
                        <span>✓ Site Boundaries</span>
                        <span>✓ Infrastructure Views</span>
                    </div>
                </div>
            </div>
        </div>
        <!-- Video Tab -->
        <div id="video-tab" class="tab-content">
            <div class="video-container">
                <iframe class="video-iframe" src="https://www.youtube.com/embed/zumJJUL_ruM?si=xyz123" title="Plot Video Tour" allowfullscreen></iframe>
            </div>
            <div class="text-center mt-6">
                <h4 class="text-xl font-bold text-gray-800 mb-3">🎬 Virtual Plot Tour</h4>
                <p class="text-gray-600 mb-4">Take a comprehensive virtual tour of the plot and surrounding amenities. See the exact location, road connectivity, and future development plans.</p>
                <div class="flex justify-center gap-6 text-sm text-gray-500">
                    <span>🎯 360° View</span>
                    <span>🛣️ Road Access</span>
                    <span>🏗️ Development Plans</span>
                    <span>🌳 Amenities</span>
                </div>
            </div>
        </div>
        <!-- Details Tab -->
        <div id="details-tab" class="tab-content">
            <div class="property-grid">
                <div class="property-card price-highlight"><div class="property-label">Price</div><div class="property-value">₹15,00,000</div></div>
                <div class="property-card"><div class="property-label">Area</div><div class="property-value">1,200 sq ft</div></div>
                <div class="property-card"><div class="property-label">Dimensions</div><div class="property-value">30' × 40'</div></div>
                <div class="property-card"><div class="property-label">Status</div><div class="property-value"><span class="status-badge status-available">Available</span></div></div>
                <div class="property-card"><div class="property-label">Block</div><div class="property-value">Block A</div></div>
                <div class="property-card"><div class="property-label">Plot Type</div><div class="property-value">Residential</div></div>
                <div class="property-card"><div class="property-label">Facing</div><div class="property-value">East Facing</div></div>
                <div class="property-card"><div class="property-label">Per Sq Ft</div><div class="property-value">₹1,250</div></div>
            </div>
            <div class="bg-gray-50 p-6 rounded-15 mb-6">
                <h4 class="text-lg font-bold text-gray-800 mb-4">🏆 Premium Amenities</h4>
                <div class="amenities-list">
                    <span class="amenity-tag">💧 Water Supply</span>
                    <span class="amenity-tag">⚡ Electricity</span>
                    <span class="amenity-tag">🛣️ Road Access</span>
                    <span class="amenity-tag">🏞️ Park View</span>
                    <span class="amenity-tag">🔒 Security</span>
                    <span class="amenity-tag">🚗 Parking</span>
                    <span class="amenity-tag">🌡️ Drainage</span>
                    <span class="amenity-tag">📶 Street Lights</span>
                </div>
            </div>
            <div class="bg-blue-50 p-6 rounded-15">
                <h4 class="text-lg font-bold text-gray-800 mb-4">📍 Location Highlights</h4>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div class="flex items-center gap-2"><span class="text-blue-600">🏫</span><span>Schools within 2km</span></div>
                    <div class="flex items-center gap-2"><span class="text-green-600">🏥</span><span>Hospital - 1.5km</span></div>
                    <div class="flex items-center gap-2"><span class="text-purple-600">🛒</span><span>Shopping Complex - 1km</span></div>
                    <div class="flex items-center gap-2"><span class="text-orange-600">🚌</span><span>Bus Stop - 500m</span></div>
                </div>
            </div>
        </div>
        <!-- Booking Tab -->
        <div id="booking-tab" class="tab-content">
            <div id="selectedPlotInfo"></div>
            <x-forms.booking-form />
        </div>
        </div> <!-- End Modal Content -->
    </div>
</div>
