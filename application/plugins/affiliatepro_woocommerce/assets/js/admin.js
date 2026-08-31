// AffiliateProSaaS Admin JavaScript
// Tab memory functionality
let currentTab = '';

function showTab(tabName) {
    // Update current tab
    currentTab = tabName;
    document.getElementById('current_tab').value = tabName;
    
    // Save to localStorage for immediate feedback
    localStorage.setItem('affi_last_tab', tabName);
    
    // Hide all content
    document.querySelectorAll('.affi-content').forEach(content => {
        content.classList.remove('active');
    });
    
    // Remove active from all tabs
    document.querySelectorAll('.affi-tab').forEach(tab => {
        tab.classList.remove('active');
    });
    
    // Show selected content
    document.getElementById(tabName).classList.add('active');
    
    // Add active to clicked tab - find the correct tab button
    document.querySelector(`[data-tab="${tabName}"]`).classList.add('active');
}

function selAll(){
    document.querySelectorAll('[name="affi_enabled_client_fields[]"]').forEach(c=>c.checked=true);
    upd();
}

function selNone(){
    document.querySelectorAll('[name="affi_enabled_client_fields[]"]').forEach(c=>c.checked=false);
    upd();
}

function upd(){
    document.getElementById('cnt').textContent=document.querySelectorAll('[name="affi_enabled_client_fields[]"]:checked').length;
}

function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = 'affi-notification';
    notification.innerHTML = `
        <span class="affi-notification-icon">${type === 'success' ? '✅' : 'ℹ️'}</span>
        ${message}
    `;
    
    document.body.appendChild(notification);
    
    // Show notification
    setTimeout(() => notification.classList.add('show'), 100);
    
    // Hide and remove notification
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => document.body.removeChild(notification), 300);
    }, 3000);
}

function checkWooCartflowsDependency() {
    const wooCheckbox = document.querySelector('[name="affi_woo_option"]');
    const cartflowsCheckbox = document.querySelector('[name="affi_cartflows_option"]');
    
    if (cartflowsCheckbox && cartflowsCheckbox.checked && wooCheckbox && !wooCheckbox.checked) {
        wooCheckbox.checked = true;
        showNotification(window.affiTranslations?.wooRequired || 'WooCommerce has been automatically enabled as it\'s required for CartFlows.', 'success');
    }
}

document.addEventListener('DOMContentLoaded',()=>{
    // Get current tab from PHP
    currentTab = window.affiCurrentTab || 'core';
    
    // Initialize tab memory - fix the active state on page load
    const savedTab = localStorage.getItem('affi_last_tab');
    const serverTab = currentTab;
    
    
    // Determine which tab to show
    const allTabContents = document.querySelectorAll('.affi-content');
    const firstTabId = allTabContents.length > 0 ? allTabContents[0].id : null;
    const targetTab = (savedTab && document.getElementById(savedTab)) ? savedTab : (document.getElementById(serverTab) ? serverTab : firstTabId);
    
    // Always ensure the correct tab is active (fixes the styling issue)
    setTimeout(() => {
        // Remove all active states first
        document.querySelectorAll('.affi-content').forEach(content => {
            content.classList.remove('active');
        });
        document.querySelectorAll('.affi-tab').forEach(tab => {
            tab.classList.remove('active');
        });
        
        // Show correct tab with proper styling
        const targetContent = document.getElementById(targetTab);
        const targetTabButton = document.querySelector(`[data-tab="${targetTab}"]`);
        
        if (targetContent) targetContent.classList.add('active');
        if (targetTabButton) targetTabButton.classList.add('active');
        
        if (document.getElementById('current_tab')) {
            document.getElementById('current_tab').value = targetTab;
        }
        currentTab = targetTab;
    }, 50); // Small delay to ensure DOM is ready
    
    // Update hidden field whenever user switches tabs
    document.querySelectorAll('.affi-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            const tabName = this.getAttribute('data-tab');
            if (document.getElementById('current_tab')) {
                document.getElementById('current_tab').value = tabName;
            }
            localStorage.setItem('affi_last_tab', tabName);
        });
    });
    
    // Show success notification if settings were saved
    if (window.affiSettingsSaved) {
        showNotification(window.affiTranslations?.settingsSaved || 'Settings saved successfully!', 'success');
    }
    
    // Auto-enable WooCommerce when CartFlows is enabled
    const cartflowsCheckbox = document.querySelector('[name="affi_cartflows_option"]');
    if (cartflowsCheckbox) {
        cartflowsCheckbox.addEventListener('change', function() {
            if (this.checked) {
                checkWooCartflowsDependency();
            }
        });
    }
    
    // Prevent disabling WooCommerce when CartFlows is enabled
    const wooCheckbox = document.querySelector('[name="affi_woo_option"]');
    if (wooCheckbox) {
        wooCheckbox.addEventListener('change', function() {
            if (!this.checked) {
                // Check if CartFlows is enabled
                const cartflowsCheckbox = document.querySelector('[name="affi_cartflows_option"]');
                if (cartflowsCheckbox && cartflowsCheckbox.checked) {
                    // Re-enable WooCommerce
                    this.checked = true;
                    showNotification(window.affiTranslations?.wooRequiredDisable || 'WooCommerce cannot be disabled while CartFlows is enabled.', 'info');
                }
            }
        });
    }
    
    // Existing functionality
    document.querySelectorAll('[name="affi_enabled_client_fields[]"]').forEach(c=>c.addEventListener('change',upd));
    
    // Remove old success message if exists
    const m=document.getElementById('affi-success');
    if(m) setTimeout(()=>m.remove(),5000);
});