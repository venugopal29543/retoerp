// Modal Management JavaScript Module
class ModalManager {
    constructor() {
        this.init();
    }

    init() {
        // Close modals when clicking outside
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('enhanced-modal')) {
                this.closeAllModals();
            }
        });
    }

    // Enhanced Modal Functions
    closeEnhancedModal() {
        const modal = document.getElementById('enhancedModal');
        if (modal) {
            modal.style.display = 'none';
        }
        window.plotManager.currentSelectedPlot = null;
    }

    closeAllModals() {
        const modals = document.querySelectorAll('.enhanced-modal');
        modals.forEach(modal => {
            modal.style.display = 'none';
        });
    }

    // Gallery functions
    changeGalleryImage(thumbnail, index) {
        // Remove active class from all thumbnails
        document.querySelectorAll('.gallery-thumbnail').forEach(thumb => {
            thumb.classList.remove('active');
        });
        
        // Add active class to clicked thumbnail
        thumbnail.classList.add('active');
        
        // Update main image
        const mainImage = document.getElementById('mainGalleryImage');
        if (mainImage && thumbnail.src) {
            // Convert thumbnail URL to larger image URL
            let largeImageUrl = thumbnail.src;
            
            // If it's an Unsplash URL, update the size parameters
            if (largeImageUrl.includes('unsplash.com')) {
                largeImageUrl = largeImageUrl.replace('w=200', 'w=1000');
                largeImageUrl = largeImageUrl.replace('q=80', 'q=90');
            }
            
            mainImage.src = largeImageUrl;
            mainImage.alt = `Plot Image ${index + 1}`;
        }
    }

    // EMI Calculator functions
    openEMICalculator() {
        const modal = document.getElementById('emiModal');
        if (modal) {
            modal.style.display = 'block';
        }
        document.getElementById('optionsMenu').classList.add('hidden');
    }

    closeEMIModal() {
        const modal = document.getElementById('emiModal');
        if (modal) {
            modal.style.display = 'none';
        }
    }

    calculateEMI() {
        const plotPrice = parseFloat(document.getElementById('emiPlotPrice').value) || 0;
        const downPayment = parseFloat(document.getElementById('emiDownPayment').value) || 0;
        const tenure = parseInt(document.getElementById('emiTenure').value) || 10;
        const interestRate = parseFloat(document.getElementById('emiInterestRate').value) || 8.5;
        
        if (plotPrice <= 0) {
            alert('Please enter a valid plot price');
            return;
        }
        
        const loanAmount = plotPrice - downPayment;
        const monthlyRate = interestRate / (12 * 100);
        const numberOfPayments = tenure * 12;
        
        let emi = 0;
        if (monthlyRate > 0) {
            emi = loanAmount * monthlyRate * Math.pow(1 + monthlyRate, numberOfPayments) / 
                  (Math.pow(1 + monthlyRate, numberOfPayments) - 1);
        } else {
            emi = loanAmount / numberOfPayments;
        }
        
        const totalAmount = emi * numberOfPayments;
        const totalInterest = totalAmount - loanAmount;
        
        const resultDiv = document.getElementById('emiResult');
        if (resultDiv) {
            resultDiv.innerHTML = `
                <h4 class="font-bold text-green-800 mb-3">💰 EMI Calculation Result</h4>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span>Monthly EMI:</span>
                        <span class="font-bold">₹${emi.toLocaleString('en-IN', {maximumFractionDigits: 0})}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Total Interest:</span>
                        <span>₹${totalInterest.toLocaleString('en-IN', {maximumFractionDigits: 0})}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Total Amount:</span>
                        <span class="font-bold">₹${totalAmount.toLocaleString('en-IN', {maximumFractionDigits: 0})}</span>
                    </div>
                </div>
            `;
            resultDiv.classList.remove('hidden');
        }
    }

    // Booking form submission
    async submitEnhancedBooking() {
        const customerName = document.getElementById('enhancedCustomerName').value.trim();
        const customerPhone = document.getElementById('enhancedCustomerPhone').value.trim();
        const customerEmail = document.getElementById('enhancedCustomerEmail').value.trim();
        
        if (!customerName || !customerPhone || !customerEmail) {
            alert('❌ Please fill in all required fields (Name, Phone, Email)');
            return;
        }
        
        // Phone validation
        const phoneRegex = /^[0-9]{10}$/;
        if (!phoneRegex.test(customerPhone)) {
            alert('❌ Please enter a valid 10-digit mobile number');
            return;
        }
        
        // Email validation
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(customerEmail)) {
            alert('❌ Please enter a valid email address');
            return;
        }
        
        const bookingData = {
            plot_id: window.plotManager.currentSelectedPlot,
            customer_name: customerName,
            customer_phone: customerPhone,
            customer_email: customerEmail,
            customer_address: document.getElementById('enhancedCustomerAddress').value.trim(),
            customer_message: document.getElementById('enhancedCustomerMessage').value.trim(),
            booking_type: 'block',
            project_id: window.roleManager.currentProjectId
        };
        
        try {
            // Simulate API call
            console.log('📝 Submitting booking:', bookingData);
            
            // Show success message
            alert(`✅ Plot ${bookingData.plot_id} blocked successfully for 24 hours!\n\n` +
                  `📧 Confirmation will be sent to ${customerEmail}\n` +
                  `📱 SMS will be sent to ${customerPhone}\n\n` +
                  `Please complete payment within 24 hours to confirm booking.`);
            
            // Close modal
            this.closeEnhancedModal();
            
            // Update plot status (visual feedback)
            if (window.plotManager.updatePlotStatus) {
                window.plotManager.updatePlotStatus(bookingData.plot_id, 'blocked');
            }
            
        } catch (error) {
            console.error('❌ Booking submission failed:', error);
            alert('❌ Booking submission failed. Please try again.');
        }
    }

    // Feature access functions
    openLocationMap() {
        alert('📍 Location Map feature - Opening Google Maps integration...');
        document.getElementById('optionsMenu').classList.add('hidden');
    }

    openComparison() {
        alert('⚖️ Plot Comparison feature - Compare multiple plots side by side...');
        document.getElementById('optionsMenu').classList.add('hidden');
    }

    openSupport() {
        alert('📞 WhatsApp Support - Redirecting to WhatsApp chat...');
        document.getElementById('optionsMenu').classList.add('hidden');
    }

    openMyBookings() {
        alert('📋 Customer Portal - View your booking history and payments...');
        document.getElementById('optionsMenu').classList.add('hidden');
    }

    openHelp() {
        alert('❓ Help & FAQ - Opening help documentation...');
        document.getElementById('optionsMenu').classList.add('hidden');
    }

    // Tab switching function for modals
    switchTab(tabName) {
        console.log(`🔄 Switching to tab: ${tabName}`);
        
        // Hide all tab contents
        const tabContents = document.querySelectorAll('.tab-content');
        tabContents.forEach(tab => tab.classList.remove('active'));
        
        // Remove active class from all tabs
        const tabs = document.querySelectorAll('.modal-tab');
        tabs.forEach(tab => tab.classList.remove('active'));
        
        // Show selected tab content
        const selectedTab = document.getElementById(tabName + '-tab');
        if (selectedTab) {
            selectedTab.classList.add('active');
        }
        
        // Find and activate the clicked tab button by onclick content
        tabs.forEach(tab => {
            if (tab.onclick && tab.onclick.toString().includes(`'${tabName}'`)) {
                tab.classList.add('active');
            }
        });
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.modalManager = new ModalManager();
    
    // Auto-capitalize name input
    const nameInput = document.getElementById('enhancedCustomerName');
    if (nameInput) {
        nameInput.addEventListener('input', function() {
            this.value = this.value.replace(/\b\w/g, l => l.toUpperCase());
        });
    }
    
    // Phone number validation
    const phoneInput = document.getElementById('enhancedCustomerPhone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '').substring(0, 10);
        });
    }
});

// Export functions for global access
window.closeEnhancedModal = () => window.modalManager.closeEnhancedModal();
window.changeGalleryImage = (thumb, index) => window.modalManager.changeGalleryImage(thumb, index);
window.openEMICalculator = () => window.modalManager.openEMICalculator();
window.closeEMIModal = () => window.modalManager.closeEMIModal();
window.calculateEMI = () => window.modalManager.calculateEMI();
window.submitEnhancedBooking = () => window.modalManager.submitEnhancedBooking();
window.openLocationMap = () => window.modalManager.openLocationMap();
window.openComparison = () => window.modalManager.openComparison();
window.openSupport = () => window.modalManager.openSupport();
window.openMyBookings = () => window.modalManager.openMyBookings();
window.openHelp = () => window.modalManager.openHelp();
window.switchTab = (tabName) => window.modalManager.switchTab(tabName);
