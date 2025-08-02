<!-- Plot Layout Component -->
<div class="layout-container relative">
    <!-- Zoom Controls -->
    <div class="zoom-controls">
        <button class="zoom-btn" onclick="zoomIn()">+</button>
        <button class="zoom-btn" onclick="zoomOut()">-</button>
        <button class="zoom-btn" onclick="resetZoom()" title="Reset Zoom">⌂</button>
    </div>
    
    <div class="w-full overflow-auto">
        <!-- Layout container with image background and SVG overlay -->
        <div id="layoutContainer" style="position: relative; width: 100%; height: auto;">
            
            <!-- Background SVG layout -->
            <div style="width: 100%; border:1px solid #ccc;">
                <img src="{{ asset('/images/sathhenapally.svg') }}" alt="SATTENAPALLI Layout Plan 2" style="width: 100%; border:1px solid #ccc;">
            </div>

            <!-- Interactive SVG overlay -->
            <svg id="layoutSVG"
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 1122.6667 793.33331"
                style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none;">
                
                <!-- Title -->
                <text x="561" y="30"
                    text-anchor="middle"
                    font-family="Arial"
                    font-size="24"
                    font-weight="bold"
                    fill="#333"
                    style="text-shadow: 2px 2px 4px rgba(255,255,255,0.8);">
                    SATTENAPALLY LAYOUT - INTERACTIVE PLOT BOOKING
                </text>

                <!-- Interactive plots group -->
                <g id="plotsGroup"
                    transform="matrix(0,-1.3333333,-1.3333333,0,1122.6667,793.33333)"
                    style="pointer-events: all;">
                    
                    <g transform="matrix(0.12,0,0,0.12,2,2)">
                        <!-- Sample plots - these will be dynamically loaded -->
                        <polygon id="plot-1" 
                            points="684,3526 998,3504 1012,3852 698,3874"
                            fill="#dff0d8" 
                            stroke="#5cb85c" 
                            stroke-width="3"
                            data-status="available"
                            onclick="handlePlotClick('1')"
                            onmouseover="highlightPlot(this)"
                            onmouseout="unhighlightPlot(this)"
                            style="cursor: pointer;">
                        </polygon>
                        
                        <polygon id="plot-2" 
                            points="2901,3233 3056,3233 3056,3659 2901,3659"
                            fill="#dff0d8" 
                            stroke="#5cb85c" 
                            stroke-width="3"
                            data-status="available"
                            onclick="handlePlotClick('2')"
                            onmouseover="highlightPlot(this)"
                            onmouseout="unhighlightPlot(this)"
                            style="cursor: pointer;">
                        </polygon>
                        
                        <polygon id="plot-3" 
                            points="3056,3233 3172,3233 3172,3659 3056,3659"
                            fill="#dff0d8" 
                            stroke="#5cb85c" 
                            stroke-width="3"
                            data-status="available"
                            onclick="handlePlotClick('3')"
                            onmouseover="highlightPlot(this)"
                            onmouseout="unhighlightPlot(this)"
                            style="cursor: pointer;">
                        </polygon>
                        
                        <polygon id="plot-4" 
                            points="3172,3233 3288,3233 3288,3659 3172,3659"
                            fill="#f0ad4e" 
                            stroke="#d58512" 
                            stroke-width="3"
                            data-status="booked"
                            onclick="handlePlotClick('4')"
                            onmouseover="highlightPlot(this)"
                            onmouseout="unhighlightPlot(this)"
                            style="cursor: pointer;">
                        </polygon>
                        
                        <polygon id="plot-5" 
                            points="3288,3233 3404,3233 3404,3659 3288,3659"
                            fill="#dff0d8" 
                            stroke="#5cb85c" 
                            stroke-width="3"
                            data-status="available"
                            onclick="handlePlotClick('5')"
                            onmouseover="highlightPlot(this)"
                            onmouseout="unhighlightPlot(this)"
                            style="cursor: pointer;">
                        </polygon>
                        
                        <polygon id="plot-6" 
                            points="3984,3233 4100,3233 4100,3659 3984,3659"
                            fill="#dff0d8" 
                            stroke="#5cb85c" 
                            stroke-width="3"
                            data-status="available"
                            onclick="handlePlotClick('6')"
                            onmouseover="highlightPlot(this)"
                            onmouseout="unhighlightPlot(this)"
                            style="cursor: pointer;">
                        </polygon>
                        
                        <polygon id="plot-7" 
                            points="2668,5507 2894,5512 2891,5666 2674,5662"
                            fill="#dff0d8" 
                            stroke="#5cb85c" 
                            stroke-width="3"
                            data-status="available"
                            onclick="handlePlotClick('7')"
                            onmouseover="highlightPlot(this)"
                            onmouseout="unhighlightPlot(this)"
                            style="cursor: pointer;">
                        </polygon>
                        
                        <polygon id="plot-8" 
                            points="2894,5512 3049,5515 3045,5670 2891,5666"
                            fill="#dff0d8" 
                            stroke="#5cb85c" 
                            stroke-width="3"
                            data-status="available"
                            onclick="handlePlotClick('8')"
                            onmouseover="highlightPlot(this)"
                            onmouseout="unhighlightPlot(this)"
                            style="cursor: pointer;">
                        </polygon>
                        
                        <!-- Plot labels -->
                        <text x="850" y="3700" text-anchor="middle" font-family="Arial" font-size="60" fill="#333" style="pointer-events: none;">1</text>
                        <text x="2978" y="3446" text-anchor="middle" font-family="Arial" font-size="60" fill="#333" style="pointer-events: none;">2</text>
                        <text x="3114" y="3446" text-anchor="middle" font-family="Arial" font-size="60" fill="#333" style="pointer-events: none;">3</text>
                        <text x="3230" y="3446" text-anchor="middle" font-family="Arial" font-size="60" fill="#333" style="pointer-events: none;">4</text>
                        <text x="3346" y="3446" text-anchor="middle" font-family="Arial" font-size="60" fill="#333" style="pointer-events: none;">5</text>
                        <text x="4042" y="3446" text-anchor="middle" font-family="Arial" font-size="60" fill="#333" style="pointer-events: none;">6</text>
                        <text x="2781" y="5590" text-anchor="middle" font-family="Arial" font-size="60" fill="#333" style="pointer-events: none;">7</text>
                        <text x="2972" y="5593" text-anchor="middle" font-family="Arial" font-size="60" fill="#333" style="pointer-events: none;">8</text>
                    </g>
                </g>

                <!-- Message box -->
                <g id="overlayElements">
                    <rect x="50" y="60" width="200" height="30" fill="rgba(255,255,255,0.9)" stroke="#333" stroke-width="1" rx="5"/>
                    <text x="150" y="80" text-anchor="middle" font-family="Arial" font-size="14" fill="#333">
                        Click on plots to view details
                    </text>
                </g>
            </svg>
        </div>
    </div>
</div>
