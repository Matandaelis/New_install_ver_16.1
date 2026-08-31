/**
 * Smart Dropdown Positioning for Horizontal Navigation
 * Automatically positions dropdowns to prevent them from going off-screen
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize dropdown positioning
    initDropdownPositioning();
    
    // Re-initialize on window resize
    window.addEventListener('resize', function() {
        setTimeout(initDropdownPositioning, 100);
    });
});

function initDropdownPositioning() {
    // Get all dropdown containers
    const dropdowns = document.querySelectorAll('.nav-dropdown');
    
    dropdowns.forEach(function(dropdown) {
        const menu = dropdown.querySelector('.dropdown-menu');
        if (!menu) return;
        
        // Remove existing positioning classes
        dropdown.classList.remove('dropdown-right', 'dropdown-up', 'dropdown-center');
    });
}

function positionDropdown(dropdown, menu) {
    // Get viewport dimensions
    const viewportWidth = window.innerWidth;
    const viewportHeight = window.innerHeight;
    
    // Get dropdown position and dimensions
    const dropdownRect = dropdown.getBoundingClientRect();
    
    // Mobile-specific positioning (Bootstrap 5 breakpoints)
    if (viewportWidth <= 575) {
        // Extra small screens - center dropdown and adjust width
        dropdown.classList.add('dropdown-center');
        menu.style.width = '280px';
        menu.style.maxWidth = '90vw';
        return;
    }
    
    if (viewportWidth <= 767) {
        // Small screens - simple positioning with adjusted width
        menu.style.width = '250px';
        menu.style.maxWidth = '85vw';
        const wouldGoOffRight = dropdownRect.left + 250 > viewportWidth;
        if (wouldGoOffRight) {
            dropdown.classList.add('dropdown-right');
        }
        return;
    }
    
    if (viewportWidth <= 991) {
        // Medium screens - standard positioning with slightly smaller width
        menu.style.width = '280px';
        menu.style.maxWidth = '320px';
    }
    
    // Desktop positioning logic
    // Calculate menu dimensions (approximate if not visible)
    const menuWidth = menu.offsetWidth || 280; // Default width
    const menuHeight = menu.scrollHeight || 200; // Approximate height
    
    // Check if menu would go off right edge
    const wouldGoOffRight = dropdownRect.left + menuWidth > viewportWidth;
    
    // Check if menu would go off bottom edge
    const wouldGoOffBottom = dropdownRect.bottom + menuHeight > viewportHeight;
    
    // Apply positioning classes
    if (wouldGoOffRight) {
        dropdown.classList.add('dropdown-right');
    }
    
    if (wouldGoOffBottom) {
        dropdown.classList.add('dropdown-up');
    }
    
    // If both, prioritize right alignment
    if (wouldGoOffRight && wouldGoOffBottom) {
        dropdown.classList.add('dropdown-right');
        dropdown.classList.add('dropdown-up');
    }

}
// Bootstrap dropdown event handlers - ONLY POSITIONING
document.addEventListener('show.bs.dropdown', function(e) {
    const dropdown = e.target;
    const menu = dropdown.querySelector('.dropdown-menu');
    if (menu && dropdown.classList.contains('nav-dropdown')) {
        setTimeout(() => positionDropdown(dropdown, menu), 10);
    }
});

// Mobile menu functionality
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    const mobileNavMenu = document.querySelector('#mobileNavMenu');
    
    if (mobileMenuToggle && mobileNavMenu) {
        // Close mobile menu when clicking outside
        document.addEventListener('click', function(e) {
            if (!mobileMenuToggle.contains(e.target) && !mobileNavMenu.contains(e.target)) {
                const bsCollapse = bootstrap.Collapse.getInstance(mobileNavMenu);
                if (bsCollapse) {
                    bsCollapse.hide();
                }
            }
        });
        
        // Close mobile menu when clicking on a link (but not on dropdown toggles)
        mobileNavMenu.addEventListener('click', function(e) {
            const link = e.target.closest('a');
            if (link && !link.classList.contains('dropdown-toggle')) {
                const bsCollapse = bootstrap.Collapse.getInstance(mobileNavMenu);
                if (bsCollapse) {
                    bsCollapse.hide();
                }
            }
        });
        
        // Handle window resize
        window.addEventListener('resize', function() {
            const viewportWidth = window.innerWidth;
            if (viewportWidth > 991) {
                const bsCollapse = bootstrap.Collapse.getInstance(mobileNavMenu);
                if (bsCollapse) {
                    bsCollapse.hide();
                }
            }
        });
    }
});
