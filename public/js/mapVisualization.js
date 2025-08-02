// Map visualization class for Sathenapally layout
class SathenappallyMapVisualization {
    constructor(svgElementId) {
        this.svgElement = document.getElementById(svgElementId);
        this.pathData = null;
        this.loadPathData();
        this.plotData = [
            {
                id: 'NW1',
                block: 'North-West',
                polygonIndex: 6, // Index in the polygons array from paths_new.js
                price: 850000,
                area: 1800,
                status: 'available',
                color: '#e6f3e6'
            },
            {
                id: 'NW2',
                block: 'North-West',
                polygonIndex: 7,
                price: 780000,
                area: 1600,
                status: 'available',
                color: '#e6f3e6'
            },
            {
                id: 'C1',
                block: 'Center',
                polygonIndex: 18, // Center area polygons
                price: 1200000,
                area: 2200,
                status: 'available',
                color: '#ffe6e6'
            },
            {
                id: 'C2',
                block: 'Center',
                polygonIndex: 19,
                price: 1250000,
                area: 2200,
                status: 'available',
                color: '#ffe6e6'
            },
            {
                id: 'C3',
                block: 'Center',
                polygonIndex: 20,
                price: 1180000,
                area: 2200,
                status: 'booked',
                color: '#cccccc'
            },
            {
                id: 'E1',
                block: 'East',
                polygonIndex: 32,
                price: 950000,
                area: 1900,
                status: 'available',
                color: '#e6e6ff'
            },
            {
                id: 'S1',
                block: 'South',
                polygonIndex: 24, // Southern area
                price: 1100000,
                area: 2100,
                status: 'available',
                color: '#fff2e6'
            },
            {
                id: 'S2',
                block: 'South',
                polygonIndex: 25,
                price: 1150000,
                area: 2100,
                status: 'available',
                color: '#fff2e6'
            },
            {
                id: 'COMM1',
                block: 'Commercial',
                polygonIndex: 25, // Large commercial area
                price: 5000000,
                area: 8500,
                status: 'available',
                color: '#ffffcc'
            }
        ];
    }
    
    async loadPathData() {
        try {
            // Load the path data from the paths_new.js file
            const response = await fetch('/js/paths_new.js');
            const text = await response.text();
            // Extract pathData object from the file
            const pathDataMatch = text.match(/const pathData = ({[\s\S]*?});/);
            if (pathDataMatch) {
                this.pathData = eval('(' + pathDataMatch[1] + ')');
            }
        } catch (error) {
            console.error('Failed to load path data:', error);
            // Fallback to default data structure
            this.pathData = {
                viewPort: { x1: 0, y1: 0, x2: 1122.6667, y2: 793.33331 },
                polygons: []
            };
        }
        this.init();
    }
    
    init() {
        this.createSVGStructure();
        this.renderBoundaryPaths();
        this.renderPlots();
        this.attachEventListeners();
    }
    
    createSVGStructure() {
        // Set up the main SVG structure
        this.svgElement.innerHTML = `
            <defs>
                <style>
                    .plot-boundary { cursor: pointer; transition: all 0.3s ease; }
                    .plot-boundary:hover { stroke-width: 4; filter: brightness(1.1); }
                    .road-line { stroke: #666; stroke-width: 2; fill: none; }
                    .boundary-line { stroke: #000; stroke-width: 3; fill: none; }
                </style>
            </defs>
            <rect x="0" y="0" width="100%" height="100%" fill="#f0f8f0"/>
            <g id="boundaries"></g>
            <g id="roads"></g>
            <g id="plots"></g>
            <g id="labels"></g>
        `;
    }
    
    renderBoundaryPaths() {
        const boundariesGroup = this.svgElement.querySelector('#boundaries');
        const roadsGroup = this.svgElement.querySelector('#roads');
        
        // Main boundary from the path data
        if (this.pathData.polygons && this.pathData.polygons.length > 0) {
            // Use the main boundary polygon (usually the first large one)
            const mainBoundary = this.pathData.polygons[1]; // Second polygon is usually the main boundary
            if (mainBoundary && mainBoundary.length > 0) {
                const pathString = this.polygonToPath(mainBoundary, 0.1, 100, 100);
                
                const boundaryPath = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                boundaryPath.setAttribute('d', pathString);
                boundaryPath.setAttribute('class', 'boundary-line');
                boundaryPath.setAttribute('fill', 'none');
                boundaryPath.setAttribute('stroke', '#000');
                boundaryPath.setAttribute('stroke-width', '2');
                
                boundariesGroup.appendChild(boundaryPath);
            }
        }
        
        // Add road network
        this.renderRoadNetwork(roadsGroup);
    }
    
    renderRoadNetwork(roadsGroup) {
        // Create road lines based on the layout
        const roads = [
            { x1: 50, y1: 150, x2: 1000, y2: 150 }, // Top horizontal road
            { x1: 50, y1: 300, x2: 1000, y2: 300 }, // Middle horizontal road
            { x1: 50, y1: 450, x2: 1000, y2: 450 }, // Bottom horizontal road
            { x1: 200, y1: 50, x2: 200, y2: 600 },  // Left vertical road
            { x1: 400, y1: 50, x2: 400, y2: 600 },  // Center vertical road
            { x1: 700, y1: 50, x2: 700, y2: 600 },  // Right vertical road
        ];
        
        roads.forEach(road => {
            const line = document.createElementNS('http://www.w3.org/2000/svg', 'line');
            line.setAttribute('x1', road.x1);
            line.setAttribute('y1', road.y1);
            line.setAttribute('x2', road.x2);
            line.setAttribute('y2', road.y2);
            line.setAttribute('class', 'road-line');
            roadsGroup.appendChild(line);
        });
    }
    
    renderPlots() {
        const plotsGroup = this.svgElement.querySelector('#plots');
        const labelsGroup = this.svgElement.querySelector('#labels');
        
        this.plotData.forEach((plot, index) => {
            // Create plot polygon using path data if available
            let polygon;
            
            if (plot.polygonIndex < this.pathData.polygons.length) {
                const polygonPoints = this.pathData.polygons[plot.polygonIndex];
                if (polygonPoints && polygonPoints.length > 0) {
                    polygon = this.createPolygonFromPath(polygonPoints, plot, 0.05, 100, 100);
                }
            }
            
            // Fallback to rectangular plots if polygon data not available
            if (!polygon) {
                polygon = this.createRectangularPlot(plot, index);
            }
            
            plotsGroup.appendChild(polygon);
            
            // Add plot label
            const label = this.createPlotLabel(plot, polygon);
            labelsGroup.appendChild(label);
        });
    }
    
    createPolygonFromPath(polygonPoints, plotData, scale = 0.1, offsetX = 100, offsetY = 100) {
        const polygon = document.createElementNS('http://www.w3.org/2000/svg', 'polygon');
        
        const points = polygonPoints.map(point => 
            `${point.x * scale + offsetX},${point.y * scale + offsetY}`
        ).join(' ');
        
        polygon.setAttribute('points', points);
        polygon.setAttribute('fill', plotData.status === 'booked' ? '#cccccc' : plotData.color);
        polygon.setAttribute('stroke', '#333');
        polygon.setAttribute('stroke-width', '2');
        polygon.setAttribute('class', 'plot-boundary');
        
        // Add data attributes
        polygon.setAttribute('data-plot', plotData.id);
        polygon.setAttribute('data-block', plotData.block);
        polygon.setAttribute('data-price', plotData.price);
        polygon.setAttribute('data-area', plotData.area);
        polygon.setAttribute('data-status', plotData.status);
        
        return polygon;
    }
    
    createRectangularPlot(plotData, index) {
        const rect = document.createElementNS('http://www.w3.org/2000/svg', 'rect');
        
        // Calculate position based on index and layout
        const plotsPerRow = 3;
        const plotWidth = 150;
        const plotHeight = 100;
        const startX = 220;
        const startY = 160;
        const spacing = 20;
        
        const row = Math.floor(index / plotsPerRow);
        const col = index % plotsPerRow;
        
        const x = startX + col * (plotWidth + spacing);
        const y = startY + row * (plotHeight + spacing);
        
        rect.setAttribute('x', x);
        rect.setAttribute('y', y);
        rect.setAttribute('width', plotWidth);
        rect.setAttribute('height', plotHeight);
        rect.setAttribute('fill', plotData.status === 'booked' ? '#cccccc' : plotData.color);
        rect.setAttribute('stroke', '#333');
        rect.setAttribute('stroke-width', '2');
        rect.setAttribute('class', 'plot-boundary');
        
        // Add data attributes
        rect.setAttribute('data-plot', plotData.id);
        rect.setAttribute('data-block', plotData.block);
        rect.setAttribute('data-price', plotData.price);
        rect.setAttribute('data-area', plotData.area);
        rect.setAttribute('data-status', plotData.status);
        
        return rect;
    }
    
    createPlotLabel(plotData, plotElement) {
        const text = document.createElementNS('http://www.w3.org/2000/svg', 'text');
        
        // Get center point of the plot
        let centerX, centerY;
        
        if (plotElement.tagName === 'polygon') {
            const bbox = plotElement.getBBox();
            centerX = bbox.x + bbox.width / 2;
            centerY = bbox.y + bbox.height / 2;
        } else {
            centerX = parseFloat(plotElement.getAttribute('x')) + parseFloat(plotElement.getAttribute('width')) / 2;
            centerY = parseFloat(plotElement.getAttribute('y')) + parseFloat(plotElement.getAttribute('height')) / 2;
        }
        
        text.setAttribute('x', centerX);
        text.setAttribute('y', centerY);
        text.setAttribute('text-anchor', 'middle');
        text.setAttribute('dominant-baseline', 'middle');
        text.setAttribute('font-family', 'Arial');
        text.setAttribute('font-size', '12');
        text.setAttribute('font-weight', 'bold');
        text.setAttribute('fill', plotData.status === 'booked' ? '#666' : '#333');
        text.textContent = plotData.id;
        
        return text;
    }
    
    polygonToPath(points, scale = 1, offsetX = 0, offsetY = 0) {
        if (!points || points.length === 0) return '';
        
        let pathString = `M ${points[0].x * scale + offsetX} ${points[0].y * scale + offsetY}`;
        
        for (let i = 1; i < points.length; i++) {
            pathString += ` L ${points[i].x * scale + offsetX} ${points[i].y * scale + offsetY}`;
        }
        
        pathString += ' Z'; // Close the path
        return pathString;
    }
    
    attachEventListeners() {
        const plots = this.svgElement.querySelectorAll('.plot-boundary');
        
        plots.forEach(plot => {
            plot.addEventListener('mouseenter', this.handlePlotHover.bind(this));
            plot.addEventListener('mouseleave', this.handlePlotLeave.bind(this));
            plot.addEventListener('click', this.handlePlotClick.bind(this));
        });
    }
    
    handlePlotHover(event) {
        const plot = event.target;
        const plotInfo = document.getElementById('plotInfo');
        
        if (plotInfo) {
            const plotId = plot.dataset.plot;
            const block = plot.dataset.block;
            const price = plot.dataset.price;
            const area = plot.dataset.area;
            const status = plot.dataset.status;
            
            plotInfo.innerHTML = `
                <h3 class="font-semibold text-lg mb-2">Plot ${plotId} - ${block} Block</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div><strong>Price:</strong> ₹${parseInt(price).toLocaleString('en-IN')}</div>
                    <div><strong>Area:</strong> ${area} sq ft</div>
                    <div>
                        <strong>Status:</strong> 
                        <span class="${status === 'available' ? 'text-green-600' : 'text-red-600'} font-semibold">
                            ${status.charAt(0).toUpperCase() + status.slice(1)}
                        </span>
                    </div>
                    <div><strong>Price per sq ft:</strong> ₹${Math.round(parseInt(price) / parseInt(area))}</div>
                </div>
            `;
        }
        
        // Visual hover effect
        plot.style.stroke = '#007bff';
        plot.style.strokeWidth = '4';
        plot.style.filter = 'brightness(1.1)';
    }
    
    handlePlotLeave(event) {
        const plot = event.target;
        const plotInfo = document.getElementById('plotInfo');
        
        if (plotInfo) {
            plotInfo.innerHTML = `
                <h3 class="font-semibold text-lg mb-2">Plot Information</h3>
                <p class="text-gray-600">Hover over any plot to see details here. Click to book.</p>
            `;
        }
        
        // Remove hover effect
        plot.style.stroke = '#333';
        plot.style.strokeWidth = '2';
        plot.style.filter = '';
    }
    
    handlePlotClick(event) {
        const plot = event.target;
        const status = plot.dataset.status;
        
        if (status === 'booked') {
            alert('This plot is already booked!');
            return;
        }
        
        // Trigger booking modal
        if (window.openBookingModal) {
            window.openBookingModal(plot);
        }
    }
}

// Export for use in other scripts
window.SathenappallyMapVisualization = SathenappallyMapVisualization;
