/**
 * PointageX Payroll Analysis JavaScript
 * This file contains all the functionality for the payroll analysis view
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize filter controls
    initFilterControls();
    
    // Initialize export button
    initExportButton();
});

/**
 * Initialize the filter controls
 */
function initFilterControls() {
    const filterSelects = document.querySelectorAll('.filter-select');
    
    filterSelects.forEach(select => {
        select.addEventListener('change', function() {
            const filterType = this.previousElementSibling.textContent.trim().replace(':', '');
            const filterValue = this.value;
            
            console.log(`Filter changed: ${filterType} - ${filterValue}`);
            
            // Show loading state
            toggleLoadingState(true);
            
            // In a real app, this would trigger an AJAX request to update the data
            setTimeout(() => {
                // Simulate data refresh
                refreshPayrollData(filterType, filterValue);
                
                // Hide loading state
                toggleLoadingState(false);
                
                // Show success message
                showFilterChangeNotification(filterType, filterValue);
            }, 1000);
        });
    });
}

/**
 * Initialize the export button
 */
function initExportButton() {
    const exportBtn = document.querySelector('.action-btn');
    
    if (!exportBtn) return;
    
    exportBtn.addEventListener('click', function() {
        console.log('Exporting payroll report...');
        
        // In a real app, this would trigger an AJAX request to generate a report
        showExportOptions();
    });
}

/**
 * Show export options in a dropdown
 */
function showExportOptions() {
    const formats = ['PDF', 'Excel', 'CSV'];
    
    // Create a simple dropdown menu for export options
    const exportMenu = document.createElement('div');
    exportMenu.className = 'export-menu';
    exportMenu.style.position = 'absolute';
    exportMenu.style.backgroundColor = 'white';
    exportMenu.style.border = '1px solid rgba(0, 128, 128, 0.2)';
    exportMenu.style.borderRadius = '8px';
    exportMenu.style.padding = '8px 0';
    exportMenu.style.boxShadow = '0 4px 16px rgba(0, 0, 0, 0.1)';
    exportMenu.style.zIndex = '1000';
    
    formats.forEach(format => {
        const option = document.createElement('div');
        option.innerText = `Export as ${format}`;
        option.style.padding = '10px 16px';
        option.style.cursor = 'pointer';
        option.style.transition = 'background-color 0.2s';
        
        option.addEventListener('mouseover', () => {
            option.style.backgroundColor = 'rgba(0, 128, 128, 0.1)';
        });
        
        option.addEventListener('mouseout', () => {
            option.style.backgroundColor = 'transparent';
        });
        
        option.addEventListener('click', () => {
            console.log(`Exporting as ${format}...`);
            showNotification(`Payroll report exported as ${format}`, 'success');
            document.body.removeChild(exportMenu);
        });
        
        exportMenu.appendChild(option);
    });
    
    // Position the menu near the export button
    const exportBtn = document.querySelector('.action-btn');
    const rect = exportBtn.getBoundingClientRect();
    exportMenu.style.top = `${rect.bottom + window.scrollY}px`;
    exportMenu.style.left = `${rect.left + window.scrollX}px`;
    
    // Add to document
    document.body.appendChild(exportMenu);
    
    // Close when clicking outside
    document.addEventListener('click', function closeMenu(e) {
        if (!exportMenu.contains(e.target) && e.target !== exportBtn) {
            if (document.body.contains(exportMenu)) {
                document.body.removeChild(exportMenu);
            }
            document.removeEventListener('click', closeMenu);
        }
    });
}

/**
 * Toggle loading state on the page
 * @param {boolean} isLoading - Whether the page is in a loading state
 */
function toggleLoadingState(isLoading) {
    const container = document.querySelector('.analysis-container');
    
    if (!container) return;
    
    if (isLoading) {
        container.classList.add('is-loading');
    } else {
        container.classList.remove('is-loading');
    }
}

/**
 * Refresh payroll data based on filter changes
 * @param {string} filterType - The type of filter that changed
 * @param {string} filterValue - The new value of the filter
 */
function refreshPayrollData(filterType, filterValue) {
    console.log(`Refreshing payroll data for ${filterType}: ${filterValue}`);
    
    // In a real app, this would update the charts and tables with new data from an AJAX call
    // For this demo, we'll just log the action
}

/**
 * Show a notification about filter changes
 * @param {string} filterType - The type of filter that changed
 * @param {string} filterValue - The new value of the filter
 */
function showFilterChangeNotification(filterType, filterValue) {
    const readableValue = filterValue.replace('_', ' ');
    const message = `Payroll data updated for ${filterType}: ${readableValue}`;
    
    showNotification(message, 'info');
}

/**
 * Show a notification message
 * @param {string} message - The message to display
 * @param {string} type - The type of notification (success, error, info, warning)
 */
function showNotification(message, type = 'info') {
    // Use the notification system from notifications.js if it exists
    if (typeof window.showNotification === 'function') {
        window.showNotification(message, type);
        return;
    }
    
    // Fallback implementation if the global function doesn't exist
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerText = message;
    
    // Style the notification if the CSS isn't loaded
    notification.style.position = 'fixed';
    notification.style.bottom = '20px';
    notification.style.right = '20px';
    notification.style.padding = '12px 16px';
    notification.style.borderRadius = '4px';
    notification.style.backgroundColor = type === 'success' ? '#047857' : 
                                         type === 'error' ? '#B91C1C' : 
                                         type === 'warning' ? '#B45309' : 'teal';
    notification.style.color = 'white';
    notification.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.1)';
    notification.style.zIndex = '9999';
    notification.style.transition = 'opacity 0.3s, transform 0.3s';
    notification.style.opacity = '0';
    notification.style.transform = 'translateY(20px)';
    
    // Add to document
    document.body.appendChild(notification);
    
    // Show with animation
    setTimeout(() => {
        notification.style.opacity = '1';
        notification.style.transform = 'translateY(0)';
    }, 10);
    
    // Auto-remove after 3 seconds
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateY(20px)';
        
        // Remove from DOM after animation
        setTimeout(() => {
            if (document.body.contains(notification)) {
                document.body.removeChild(notification);
            }
        }, 300);
    }, 3000);
} 