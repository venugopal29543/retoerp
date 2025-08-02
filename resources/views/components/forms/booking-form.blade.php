<!-- Enhanced Booking Form Component -->
<div class="plot-info-card">
    <h4 class="text-xl font-bold mb-2">🏡 Selected Plot Information</h4>
    <div class="plot-info-grid">
        <div class="plot-info-item">
            <div class="plot-info-label">Plot ID</div>
            <div class="plot-info-value" id="selectedPlotId">A-001</div>
        </div>
        <div class="plot-info-item">
            <div class="plot-info-label">Price</div>
            <div class="plot-info-value" id="selectedPlotPrice">₹15,00,000</div>
        </div>
        <div class="plot-info-item">
            <div class="plot-info-label">Area</div>
            <div class="plot-info-value" id="selectedPlotArea">1,200 sq ft</div>
        </div>
        <div class="plot-info-item">
            <div class="plot-info-label">Block</div>
            <div class="plot-info-value" id="selectedPlotBlock">Block A</div>
        </div>
    </div>
</div>

<form class="enhanced-form" onsubmit="handleBookingSubmit(event)">
    <h4 class="text-xl font-bold text-gray-800 mb-6">📝 Customer Information</h4>
    
    <div class="form-grid">
        <div class="form-group">
            <label class="form-label">👤 Full Name *</label>
            <input type="text" id="customerName" name="customer_name" 
                   class="form-input" placeholder="Enter your full name" required>
        </div>
        
        <div class="form-group">
            <label class="form-label">📱 Mobile Number *</label>
            <input type="tel" id="customerMobile" name="customer_mobile" 
                   class="form-input" placeholder="+91 98765 43210" 
                   pattern="[+]?[0-9]{10,15}" required>
        </div>
        
        <div class="form-group">
            <label class="form-label">📧 Email Address</label>
            <input type="email" id="customerEmail" name="customer_email" 
                   class="form-input" placeholder="your.email@example.com">
        </div>
        
        <div class="form-group">
            <label class="form-label">🏠 Address</label>
            <input type="text" id="customerAddress" name="customer_address" 
                   class="form-input" placeholder="Current residential address">
        </div>
    </div>
    
    <h4 class="text-lg font-bold text-gray-800 mb-4 mt-8">💰 Payment Information</h4>
    
    <div class="form-grid">
        <div class="form-group">
            <label class="form-label">💳 Payment Method *</label>
            <select id="paymentMethod" name="payment_method" class="form-select" required 
                    onchange="togglePaymentDetails()">
                <option value="">Select payment method</option>
                <option value="full_payment">Full Payment (₹15,00,000)</option>
                <option value="token_payment">Token Amount (₹50,000)</option>
                <option value="emi_payment">EMI Payment Plan</option>
                <option value="installment">Custom Installments</option>
            </select>
        </div>
        
        <div class="form-group">
            <label class="form-label">💵 Initial Amount *</label>
            <input type="number" id="initialAmount" name="initial_amount" 
                   class="form-input" placeholder="50000" min="50000" required>
        </div>
        
        <div class="form-group">
            <label class="form-label">📅 Preferred Payment Date</label>
            <input type="date" id="paymentDate" name="payment_date" 
                   class="form-input" min="{{ date('Y-m-d') }}">
        </div>
        
        <div class="form-group">
            <label class="form-label">🎯 Lead Source</label>
            <select id="leadSource" name="lead_source" class="form-select">
                <option value="">How did you hear about us?</option>
                <option value="website">Website</option>
                <option value="referral">Friend Referral</option>
                <option value="advertisement">Advertisement</option>
                <option value="social_media">Social Media</option>
                <option value="broker">Real Estate Broker</option>
                <option value="other">Other</option>
            </select>
        </div>
    </div>
    
    <!-- EMI Details (hidden by default) -->
    <div id="emiDetails" class="hidden bg-blue-50 p-6 rounded-15 mb-6">
        <h5 class="text-lg font-bold text-gray-800 mb-4">📊 EMI Payment Plan</h5>
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">🕐 Loan Tenure (Months)</label>
                <select id="loanTenure" name="loan_tenure" class="form-select" onchange="calculateEMI()">
                    <option value="12">12 Months</option>
                    <option value="24">24 Months</option>
                    <option value="36">36 Months</option>
                    <option value="48">48 Months</option>
                    <option value="60">60 Months</option>
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label">📈 Interest Rate (%)</label>
                <input type="number" id="interestRate" name="interest_rate" 
                       class="form-input" value="12" min="8" max="20" step="0.5" onchange="calculateEMI()">
            </div>
            
            <div class="form-group">
                <label class="form-label">💰 Calculated EMI</label>
                <input type="text" id="calculatedEMI" class="form-input" readonly 
                       placeholder="EMI will be calculated automatically">
            </div>
        </div>
    </div>
    
    <div class="form-group">
        <label class="form-label">💬 Special Requirements / Comments</label>
        <textarea id="specialRequirements" name="special_requirements" 
                  class="form-input" rows="4" 
                  placeholder="Any specific requirements, preferred registration date, or other comments..."></textarea>
    </div>
    
    <!-- Agreement and Terms -->
    <div class="bg-gray-50 p-6 rounded-15 mb-6">
        <div class="flex items-start gap-3 mb-4">
            <input type="checkbox" id="agreeTerms" name="agree_terms" 
                   class="mt-1 w-5 h-5 text-blue-600" required>
            <label for="agreeTerms" class="text-sm text-gray-700">
                I agree to the <a href="#" class="text-blue-600 font-semibold">Terms & Conditions</a> 
                and <a href="#" class="text-blue-600 font-semibold">Privacy Policy</a>. 
                I understand that this booking is subject to verification and approval.
            </label>
        </div>
        
        <div class="flex items-start gap-3">
            <input type="checkbox" id="agreeContact" name="agree_contact" 
                   class="mt-1 w-5 h-5 text-blue-600">
            <label for="agreeContact" class="text-sm text-gray-700">
                I consent to being contacted by the sales team via phone, email, or SMS 
                regarding this plot booking and related services.
            </label>
        </div>
    </div>
    
    <!-- Action Buttons -->
    <div class="flex gap-4">
        <button type="button" onclick="calculateCosts()" class="btn-secondary flex-1">
            🧮 Calculate Total Cost
        </button>
        <button type="submit" class="btn-submit flex-2">
            🚀 Submit Booking Request
        </button>
    </div>
    
    <!-- Cost Breakdown (hidden by default) -->
    <div id="costBreakdown" class="hidden bg-green-50 p-6 rounded-15 mt-6">
        <h5 class="text-lg font-bold text-gray-800 mb-4">💰 Cost Breakdown</h5>
        <div class="space-y-2 text-sm">
            <div class="flex justify-between">
                <span>Plot Price:</span>
                <span class="font-semibold">₹15,00,000</span>
            </div>
            <div class="flex justify-between">
                <span>Registration Charges (2%):</span>
                <span class="font-semibold">₹30,000</span>
            </div>
            <div class="flex justify-between">
                <span>Documentation Fee:</span>
                <span class="font-semibold">₹5,000</span>
            </div>
            <div class="flex justify-between">
                <span>Maintenance Deposit:</span>
                <span class="font-semibold">₹10,000</span>
            </div>
            <hr class="my-2">
            <div class="flex justify-between text-lg font-bold">
                <span>Total Amount:</span>
                <span class="text-green-600">₹15,45,000</span>
            </div>
        </div>
    </div>
</form>

<script>
function togglePaymentDetails() {
    const paymentMethod = document.getElementById('paymentMethod').value;
    const emiDetails = document.getElementById('emiDetails');
    const initialAmount = document.getElementById('initialAmount');
    
    if (paymentMethod === 'emi_payment') {
        emiDetails.classList.remove('hidden');
        initialAmount.value = '100000'; // Down payment for EMI
        calculateEMI();
    } else {
        emiDetails.classList.add('hidden');
        
        switch(paymentMethod) {
            case 'full_payment':
                initialAmount.value = '1500000';
                break;
            case 'token_payment':
                initialAmount.value = '50000';
                break;
            case 'installment':
                initialAmount.value = '200000';
                break;
            default:
                initialAmount.value = '';
        }
    }
}

function calculateEMI() {
    const principal = 1400000; // Remaining after down payment
    const rate = parseFloat(document.getElementById('interestRate').value) / 100 / 12;
    const tenure = parseInt(document.getElementById('loanTenure').value);
    
    const emi = (principal * rate * Math.pow(1 + rate, tenure)) / (Math.pow(1 + rate, tenure) - 1);
    document.getElementById('calculatedEMI').value = '₹' + Math.round(emi).toLocaleString();
}

function calculateCosts() {
    const costBreakdown = document.getElementById('costBreakdown');
    costBreakdown.classList.toggle('hidden');
}

function handleBookingSubmit(event) {
    event.preventDefault();
    
    // Collect form data
    const formData = new FormData(event.target);
    const data = Object.fromEntries(formData);
    
    // Show success message
    alert('🎉 Booking request submitted successfully! Our team will contact you within 24 hours.');
    
    // Here you would normally send the data to your backend
    console.log('Booking Data:', data);
    
    // Close modal
    closeEnhancedModal();
}

// Set minimum date to today
document.addEventListener('DOMContentLoaded', function() {
    const today = new Date().toISOString().split('T')[0];
    const paymentDate = document.getElementById('paymentDate');
    if (paymentDate) {
        paymentDate.value = today;
    }
});
</script>
    
    <div class="form-group">
        <label class="form-label">Email Address *</label>
        @if(true) {{-- canEditField('booking', 'customer_email') --}}
        <input type="email" id="enhancedCustomerEmail" 
               class="form-input" 
               placeholder="your.email@example.com">
        <small class="text-gray-500">Required for booking receipt and updates</small>
        @else
        <input type="email" id="enhancedCustomerEmail" 
               class="form-input" 
               placeholder="Field access restricted"
               readonly>
        @endif
    </div>
    
    @if(true) {{-- canEditField('booking', 'customer_address') --}}
    <div class="form-group">
        <label class="form-label">Address (Optional)</label>
        <textarea id="enhancedCustomerAddress" 
                  class="form-input" 
                  rows="2" 
                  placeholder="Current address for documentation"></textarea>
    </div>
    @endif
    
    <div class="form-group">
        <label class="form-label">Additional Requirements</label>
        <textarea id="enhancedCustomerMessage" 
                  class="form-input" 
                  rows="3" 
                  placeholder="Any specific requirements or questions about this plot..."></textarea>
    </div>
    
    <div class="flex gap-3 pt-4">
        <button type="button" onclick="closeEnhancedModal()" 
                class="btn-enhanced btn-secondary-enhanced flex-1">
            Cancel
        </button>
        <button type="button" onclick="submitEnhancedBooking()" 
                class="btn-enhanced btn-primary-enhanced flex-1">
            🔒 Block Plot (24hrs)
        </button>
    </div>
    
    <div class="text-center mt-4 text-sm text-gray-600">
        <p>🔒 <strong>24-hour free blocking</strong> - Pay later to confirm</p>
        <p class="text-xs text-gray-500 mt-1">
            No payment required at this stage.
        </p>
    </div>
</form>
