// Role Management JavaScript Module
class RoleManager {
    constructor() {
        this.currentUserRole = 'agent';
        this.currentProjectId = 1;
        this.appConfig = {
            features: {
                emi_calculator: { enabled_at_project: true, settings: { default_interest_rate: 8.5 } },
                location_map: { enabled_at_project: true, settings: {} },
                plot_comparison: { enabled_at_project: true, settings: {} },
                whatsapp_support: { enabled_at_project: true, settings: {} },
                customer_portal: { enabled_at_project: true, settings: {} }
            },
            tenant: {
                name: 'Sattenapalli Developers',
                branding: {
                    primary_color: '#3b82f6',
                    logo_url: null,
                    title: 'SATTENAPALLI Layout Plan'
                }
            },
            project: {
                id: 1,
                name: 'Sattenapalli Project',
                settings: {
                    plot_booking_enabled: true,
                    emi_calculator_enabled: true
                }
            },
            user: {
                id: 1,
                name: 'Demo User',
                roles: [
                    { role: 'agent', project_id: 1, project_name: 'Sattenapalli Project' },
                    { role: 'sales_manager', project_id: 2, project_name: 'Green Valley Apartments' },
                    { role: 'marketing_executive', project_id: 3, project_name: 'Sunrise Farmlands' },
                    { role: 'project_manager', project_id: 4, project_name: 'Elite Villas' }
                ]
            }
        };
        
        this.init();
    }

    init() {
        this.initializeTenantFeatures();
    }

    // Role Switching Functions
    toggleRoleSwitcher() {
        const switcher = document.getElementById('roleSwitcher');
        if (switcher) {
            switcher.classList.toggle('hidden');
        }
    }

    switchRole(roleName, projectName, projectId) {
        this.currentUserRole = roleName;
        this.currentProjectId = projectId;
        
        // Update role display
        const currentRoleEl = document.getElementById('currentRole');
        const currentProjectEl = document.getElementById('currentProject');
        
        if (currentRoleEl) currentRoleEl.textContent = this.getRoleDisplayName(roleName);
        if (currentProjectEl) currentProjectEl.textContent = projectName;
        
        // Update active role indicator
        document.querySelectorAll('.role-item').forEach(item => {
            item.classList.remove('bg-blue-50', 'border-blue-300');
            const indicator = item.querySelector('.text-xs.text-green-600');
            if (indicator) {
                indicator.textContent = '• Available';
                indicator.className = 'text-xs text-gray-500';
            }
        });
        
        // Mark selected role as active
        const activeItem = event.currentTarget;
        activeItem.classList.add('bg-blue-50', 'border-blue-300');
        const newActiveIndicator = activeItem.querySelector('.text-xs.text-gray-500');
        if (newActiveIndicator) {
            newActiveIndicator.textContent = '• Currently Active';
            newActiveIndicator.className = 'text-xs text-green-600';
        }
        
        // Close the switcher
        document.getElementById('roleSwitcher').classList.add('hidden');
        
        // Update permissions and features based on new role
        this.updateUIBasedOnRole(roleName, projectId);
        
        // Show notification
        this.showRoleSwitchNotification(roleName, projectName);
    }

    getRoleDisplayName(roleName) {
        const roleNames = {
            'super_admin': 'Super Admin',
            'tenant_admin': 'Tenant Admin',
            'project_manager': 'Project Manager',
            'sales_manager': 'Sales Manager',
            'agent': 'Sales Agent',
            'marketing_executive': 'Marketing Executive',
            'finance_manager': 'Finance Manager',
            'customer_support': 'Customer Support',
            'site_supervisor': 'Site Supervisor',
            'viewer': 'Viewer'
        };
        return roleNames[roleName] || roleName;
    }

    updateUIBasedOnRole(roleName, projectId) {
        console.log(`🔄 Switching to ${roleName} role in project ${projectId}`);
        
        // Update feature availability based on role
        const rolePermissions = this.getRolePermissions(roleName);
        
        // Update form field permissions
        this.updateFormFieldPermissions(roleName);
        
        // Update available actions
        this.updateAvailableActions(roleName);
        
        // Update menu options
        this.updateMenuOptions(roleName);
    }

    getRolePermissions(roleName) {
        const permissions = {
            'super_admin': {
                actions: ['create', 'read', 'update', 'delete', 'manage_users', 'manage_settings'],
                fields: ['customer_name', 'customer_phone', 'customer_email', 'customer_address', 'booking_amount', 'payment_status'],
                features: ['all']
            },
            'tenant_admin': {
                actions: ['create', 'read', 'update', 'manage_project_users'],
                fields: ['customer_name', 'customer_phone', 'customer_email', 'customer_address', 'booking_amount'],
                features: ['emi_calculator', 'location_map', 'plot_comparison', 'whatsapp_support', 'customer_portal']
            },
            'project_manager': {
                actions: ['create', 'read', 'update', 'assign_agents'],
                fields: ['customer_name', 'customer_phone', 'customer_email', 'customer_address'],
                features: ['emi_calculator', 'location_map', 'plot_comparison']
            },
            'sales_manager': {
                actions: ['create', 'read', 'update'],
                fields: ['customer_name', 'customer_phone', 'customer_email'],
                features: ['emi_calculator', 'plot_comparison']
            },
            'agent': {
                actions: ['create', 'read'],
                fields: ['customer_name', 'customer_phone', 'customer_email'],
                features: ['emi_calculator']
            },
            'marketing_executive': {
                actions: ['read'],
                fields: ['customer_name', 'customer_phone'],
                features: ['plot_comparison']
            },
            'viewer': {
                actions: ['read'],
                fields: [],
                features: []
            }
        };
        
        return permissions[roleName] || permissions['agent'];
    }

    updateFormFieldPermissions(roleName) {
        const permissions = this.getRolePermissions(roleName);
        
        // Update booking form fields based on role
        const fields = {
            'customer_name': document.getElementById('enhancedCustomerName'),
            'customer_phone': document.getElementById('enhancedCustomerPhone'),
            'customer_email': document.getElementById('enhancedCustomerEmail'),
            'customer_address': document.getElementById('enhancedCustomerAddress')
        };
        
        Object.entries(fields).forEach(([fieldName, fieldElement]) => {
            if (fieldElement) {
                if (permissions.fields.includes(fieldName)) {
                    fieldElement.removeAttribute('readonly');
                    fieldElement.classList.remove('bg-gray-100');
                } else {
                    fieldElement.setAttribute('readonly', 'readonly');
                    fieldElement.classList.add('bg-gray-100');
                    fieldElement.placeholder = 'Access restricted for your role';
                }
            }
        });
    }

    updateAvailableActions(roleName) {
        console.log(`📋 Available actions for ${roleName}:`, this.getRolePermissions(roleName).actions);
    }

    updateMenuOptions(roleName) {
        const permissions = this.getRolePermissions(roleName);
        console.log(`🎛️ Menu options updated for ${roleName}`);
    }

    showRoleSwitchNotification(roleName, projectName) {
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transition-all duration-300';
        notification.innerHTML = `
            <div class="flex items-center space-x-2">
                <span>✅</span>
                <div>
                    <div class="font-semibold">Role Switched!</div>
                    <div class="text-sm">Now acting as ${this.getRoleDisplayName(roleName)} in ${projectName}</div>
                </div>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.opacity = '0';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    // Initialize tenant-specific features
    initializeTenantFeatures() {
        console.log('🏢 Tenant Features:', this.appConfig.features);
        
        // Apply tenant branding if available
        if (this.appConfig.tenant && this.appConfig.tenant.branding) {
            this.applyTenantBranding(this.appConfig.tenant.branding);
        }
        
        // Initialize feature-specific settings
        Object.keys(this.appConfig.features).forEach(featureName => {
            if (this.hasFeature(featureName)) {
                this.initializeFeature(featureName, this.appConfig.features[featureName].settings);
            }
        });
    }

    initializeFeature(featureName, settings) {
        switch (featureName) {
            case 'emi_calculator':
                console.log('🧮 EMI Calculator initialized with settings:', settings);
                break;
            case 'location_map':
                console.log('📍 Location Map initialized');
                break;
            default:
                console.log(`📋 Feature ${featureName} initialized`);
        }
    }

    applyTenantBranding(branding) {
        if (branding.primary_color) {
            document.documentElement.style.setProperty('--primary-color', branding.primary_color);
        }
        
        if (branding.logo_url) {
            const logoElements = document.querySelectorAll('.tenant-logo');
            logoElements.forEach(el => el.src = branding.logo_url);
        }
        
        if (branding.title) {
            document.title = branding.title;
        }
    }

    // Feature-aware functions
    hasFeature(featureName) {
        return this.appConfig.features[featureName] && this.appConfig.features[featureName].enabled_at_project;
    }

    getFeatureSettings(featureName) {
        return this.hasFeature(featureName) ? this.appConfig.features[featureName].settings : {};
    }

    // Options menu functions
    toggleOptionsMenu() {
        const menu = document.getElementById('optionsMenu');
        if (menu) {
            menu.classList.toggle('hidden');
        }
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.roleManager = new RoleManager();
});

// Export functions for global access
window.toggleRoleSwitcher = () => window.roleManager.toggleRoleSwitcher();
window.switchRole = (role, project, id) => window.roleManager.switchRole(role, project, id);
window.toggleOptionsMenu = () => window.roleManager.toggleOptionsMenu();
