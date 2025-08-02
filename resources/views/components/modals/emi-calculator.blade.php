<!-- EMI Calculator Modal Component -->
@if(true) {{-- hasFeature('emi_calculator') --}}
<div id="emiModal" class="enhanced-modal">
    <div class="modal-content" style="width: 500px;">
        <div class="flex justify-between items-center p-4 border-b">
            <h3 class="text-xl font-bold text-gray-800">🧮 EMI Calculator</h3>
            <button onclick="closeEMIModal()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <div class="form-group">
                    <label class="form-label">Plot Price (₹)</label>
                    <input type="number" id="emiPlotPrice" class="form-input" placeholder="1500000">
                </div>
                <div class="form-group">
                    <label class="form-label">Down Payment (₹)</label>
                    <input type="number" id="emiDownPayment" class="form-input" placeholder="300000">
                </div>
                <div class="form-group">
                    <label class="form-label">Loan Tenure (Years)</label>
                    <select id="emiTenure" class="form-input">
                        <option value="5">5 Years</option>
                        <option value="10" selected>10 Years</option>
                        <option value="15">15 Years</option>
                        <option value="20">20 Years</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Interest Rate (%)</label>
                    <input type="number" id="emiInterestRate" class="form-input" value="8.5" step="0.1">
                </div>
                <button onclick="calculateEMI()" class="btn-enhanced btn-primary-enhanced w-full">
                    Calculate EMI
                </button>
                <div id="emiResult" class="hidden bg-green-50 p-4 rounded-lg">
                    <!-- EMI result will be shown here -->
                </div>
            </div>
        </div>
    </div>
</div>
@endif
