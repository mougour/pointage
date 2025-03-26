/**
 * PointageX Dashboard JavaScript
 * This file contains all the functionality for the dashboard view
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize dashboard components
    initDatePickers();
    initPeriodSelector();
    initRefreshControls();
    initExportButton();
    
    // Set up charts if they exist on the page
    setupCharts();
});

/**
 * Initialize date pickers with consistent styling and behavior
 */
function initDatePickers() {
    const datePickers = document.querySelectorAll('.date-picker');
    
    if (datePickers.length === 0) return;
    
    datePickers.forEach(picker => {
        // This is a placeholder - in a real app, you would use a date picker library
        picker.addEventListener('click', function() {
            console.log('Date picker clicked - would initialize date picker library here');
        });
    });
}

/**
 * Initialize the time period selector dropdown
 */
function initPeriodSelector() {
    const periodSelector = document.querySelector('#period-selector');
    
    if (!periodSelector) return;
    
    periodSelector.addEventListener('change', function() {
        const selectedPeriod = this.value;
        console.log(`Period changed to: ${selectedPeriod}`);
        
        // In a real app, this would update the dashboard data based on the selected period
        refreshDashboardData(selectedPeriod);
    });
}

/**
 * Set up the refresh and reset buttons
 */
function initRefreshControls() {
    const refreshBtn = document.querySelector('#refresh-btn');
    const resetBtn = document.querySelector('#reset-btn');
    
    if (refreshBtn) {
        refreshBtn.addEventListener('click', function() {
            console.log('Refreshing dashboard data...');
            refreshDashboardData();
        });
    }
    
    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            console.log('Resetting filters...');
            resetFilters();
        });
    }
}

/**
 * Set up export functionality
 */
function initExportButton() {
    const exportBtn = document.querySelector('#export-btn');
    
    if (!exportBtn) return;
    
    exportBtn.addEventListener('click', function() {
        console.log('Exporting dashboard data...');
        exportDashboardData();
    });
}

/**
 * Refresh the dashboard data
 * @param {string} period - Optional time period to filter by
 */
function refreshDashboardData(period = null) {
    // Show loading state
    toggleLoadingState(true);
    
    // In a real app, this would make an AJAX request to get updated data
    console.log(`Refreshing dashboard data${period ? ' for period: ' + period : ''}`);
    
    // Simulate API call delay
    setTimeout(() => {
        refreshAttendanceSummary();
        refreshDepartmentData();
        refreshPerformers();
        
        // Hide loading state
        toggleLoadingState(false);
        
        // Show success message
        showNotification('Dashboard data refreshed successfully', 'success');
    }, 1000);
}

/**
 * Reset all dashboard filters to default state
 */
function resetFilters() {
    // Reset period selector
    const periodSelector = document.querySelector('#period-selector');
    if (periodSelector) {
        periodSelector.value = 'today';
    }
    
    // Reset any date pickers
    const datePickers = document.querySelectorAll('.date-picker');
    datePickers.forEach(picker => {
        picker.value = '';
    });
    
    // Refresh the dashboard with default values
    refreshDashboardData();
    
    // Show notification
    showNotification('Filters have been reset', 'info');
}

/**
 * Export dashboard data in various formats
 */
function exportDashboardData() {
    // In a real app, this would trigger a file download
    const formats = ['PDF', 'Excel', 'CSV'];
    
    // Create a simple dropdown menu for export options
    const exportMenu = document.createElement('div');
    exportMenu.className = 'export-menu';
    exportMenu.style.position = 'absolute';
    exportMenu.style.backgroundColor = 'white';
    exportMenu.style.border = '1px solid #ccc';
    exportMenu.style.borderRadius = '4px';
    exportMenu.style.padding = '8px 0';
    exportMenu.style.boxShadow = '0 2px 10px rgba(0,0,0,0.1)';
    exportMenu.style.zIndex = '1000';
    
    formats.forEach(format => {
        const option = document.createElement('div');
        option.innerText = `Export as ${format}`;
        option.style.padding = '8px 16px';
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
            showNotification(`Dashboard exported as ${format}`, 'success');
            document.body.removeChild(exportMenu);
        });
        
        exportMenu.appendChild(option);
    });
    
    // Position the menu near the export button
    const exportBtn = document.querySelector('#export-btn');
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
 * Set up any charts on the dashboard
 */
function setupCharts() {
    // This would use a chart library like Chart.js
    console.log('Setting up dashboard charts');
    
    // Check if we have chart containers
    const chartContainers = document.querySelectorAll('.chart-container');
    if (chartContainers.length === 0) return;
    
    chartContainers.forEach(container => {
        const chartId = container.id;
        console.log(`Initializing chart: ${chartId}`);
        
        // Here you would initialize your charts with the appropriate library
    });
}

/**
 * Refresh the attendance summary section
 */
function refreshAttendanceSummary() {
    console.log('Refreshing attendance summary');
    // In a real app, this would update the attendance stats with new data
}

/**
 * Refresh department data and charts
 */
function refreshDepartmentData() {
    console.log('Refreshing department data');
    // In a real app, this would update the department stats with new data
}

/**
 * Refresh top and bottom performers
 */
function refreshPerformers() {
    console.log('Refreshing performers data');
    // In a real app, this would update the performers list with new data
}

/**
 * Toggle loading state of the dashboard
 * @param {boolean} isLoading - Whether the dashboard is in a loading state
 */
function toggleLoadingState(isLoading) {
    // In a real app, this would show/hide a loading spinner
    console.log(`Dashboard loading state: ${isLoading ? 'loading' : 'loaded'}`);
    
    // Add or remove loading class from dashboard container
    const dashboard = document.querySelector('.dashboard-container');
    if (dashboard) {
        if (isLoading) {
            dashboard.classList.add('is-loading');
        } else {
            dashboard.classList.remove('is-loading');
        }
    }
}

/**
 * Show a notification message
 * @param {string} message - The message to display
 * @param {string} type - The type of notification (success, error, info, warning)
 */
function showNotification(message, type = 'info') {
    console.log(`Notification (${type}): ${message}`);
    
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerText = message;
    
    // Style the notification
    notification.style.position = 'fixed';
    notification.style.bottom = '20px';
    notification.style.right = '20px';
    notification.style.padding = '12px 16px';
    notification.style.borderRadius = '4px';
    notification.style.backgroundColor = type === 'success' ? '#047857' : 
                                         type === 'error' ? '#B91C1C' : 
                                         type === 'warning' ? '#B45309' : 'teal';
    notification.style.color = 'white';
    notification.style.boxShadow = '0 2px 10px rgba(0,0,0,0.1)';
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