/**
 * Modern Date Range Picker Implementation
 * Using Litepicker - A lightweight, modern date picker
 * Laravel 12 & PHP 8.4 Compatible
 * 
 * Features:
 * - Zero dependencies
 * - Modern ES6+ syntax
 * - Responsive design
 * - Touch-friendly
 * - Customizable themes
 * - Better performance than Flatpickr
 */

class ModernDateRangePicker {
    constructor(options = {}) {
        this.options = {
            element: null,
            startDate: null,
            endDate: null,
            format: 'YYYY-MM-DD',
            displayFormat: 'MMM DD, YYYY',
            placeholder: 'Select date range...',
            theme: 'dark',
            numberOfMonths: window.innerWidth > 768 ? 2 : 1,
            numberOfColumns: window.innerWidth > 768 ? 2 : 1,
            singleMode: false,
            allowRepick: true,
            autoRefresh: true,
            showTooltip: true,
            showWeekNumbers: false,
            dropdowns: {
                minYear: 2020,
                maxYear: new Date().getFullYear() + 5,
                months: true,
                years: true
            },
            buttonText: {
                apply: 'Apply',
                cancel: 'Cancel',
                previousMonth: '<',
                nextMonth: '>',
                reset: 'Reset'
            },
            tooltipText: {
                one: 'day',
                other: 'days'
            },
            ...options
        };
        
        this.picker = null;
        this.callbacks = {
            onSelect: null,
            onShow: null,
            onHide: null,
            onClear: null,
            onError: null
        };
        
        this.init();
    }

    /**
     * Initialize the date picker
     */
    init() {
        if (!this.options.element) {
            console.error('ModernDateRangePicker: Element is required');
            return;
        }

        // Verify element exists
        const element = typeof this.options.element === 'string' 
            ? document.querySelector(this.options.element)
            : this.options.element;
            
        if (!element) {
            console.error('ModernDateRangePicker: Element not found:', this.options.element);
            return;
        }

        console.log('📅 ModernDateRangePicker initializing for element:', this.options.element);

        // Check if Litepicker is available
        if (typeof Litepicker === 'undefined') {
            console.log('📅 Litepicker not found, loading from CDN...');
            this.loadLitepicker().then(() => {
                if (typeof Litepicker !== 'undefined') {
                    this.createPicker();
                } else {
                    console.error('Litepicker still not available after loading');
                    this.fallbackToNativePicker();
                }
            }).catch(error => {
                console.error('Failed to load Litepicker:', error);
                this.fallbackToNativePicker();
            });
        } else {
            console.log('📅 Litepicker already available, creating picker...');
            this.createPicker();
        }
    }

    /**
     * Dynamically load Litepicker if not available
     */
    async loadLitepicker() {
        return new Promise((resolve, reject) => {
            console.log('📅 Loading Litepicker from CDN...');
            
            // Load CSS first
            const cssLink = document.createElement('link');
            cssLink.rel = 'stylesheet';
            cssLink.href = 'https://cdn.jsdelivr.net/npm/litepicker@2.0.12/dist/css/litepicker.css';
            cssLink.onerror = () => console.warn('Failed to load Litepicker CSS');
            document.head.appendChild(cssLink);

            // Load JS with timeout
            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/litepicker@2.0.12/dist/litepicker.js';
            
            let timeoutId = setTimeout(() => {
                console.error('Litepicker loading timeout');
                reject(new Error('Litepicker loading timeout'));
            }, 10000); // 10 second timeout
            
            script.onload = () => {
                clearTimeout(timeoutId);
                console.log('✅ Litepicker loaded successfully');
                // Wait a bit for the library to be fully available
                setTimeout(resolve, 100);
            };
            
            script.onerror = (error) => {
                clearTimeout(timeoutId);
                console.error('Failed to load Litepicker script:', error);
                reject(error);
            };
            
            document.head.appendChild(script);
        });
    }

    /**
     * Create the Litepicker instance
     */
    createPicker() {
        // Double-check Litepicker availability
        if (typeof Litepicker === 'undefined') {
            console.error('Litepicker is not available when trying to create picker');
            this.fallbackToNativePicker();
            return;
        }
        
        const element = typeof this.options.element === 'string' 
            ? document.querySelector(this.options.element)
            : this.options.element;

        if (!element) {
            console.error('ModernDateRangePicker: Element not found when creating picker');
            return;
        }
        
        console.log('📅 Creating Litepicker instance for element:', element);

        // Configure Litepicker options
        const pickerOptions = {
            element: element,
            elementEnd: this.options.elementEnd || null,
            startDate: this.options.startDate,
            endDate: this.options.endDate,
            format: this.options.displayFormat,
            delimiter: ' - ',
            numberOfMonths: this.options.numberOfMonths,
            numberOfColumns: this.options.numberOfColumns,
            singleMode: this.options.singleMode,
            allowRepick: this.options.allowRepick,
            autoRefresh: this.options.autoRefresh,
            showTooltip: this.options.showTooltip,
            showWeekNumbers: this.options.showWeekNumbers,
            dropdowns: this.options.dropdowns,
            buttonText: this.options.buttonText,
            tooltipText: this.options.tooltipText,
            
            // Enhanced positioning
            position: 'auto',
            zIndex: 99999,
            parentEl: document.body,
            
            // Custom CSS classes for theming
            css: {
                main: 'litepicker-modern',
                month: 'litepicker-month-modern',
                day: 'litepicker-day-modern'
            },

            // Event callbacks
            setup: (picker) => {
                this.picker = picker;
                this.applyCustomStyles();
                
                if (this.callbacks.onShow) {
                    picker.on('show', this.callbacks.onShow);
                }
                
                if (this.callbacks.onHide) {
                    picker.on('hide', this.callbacks.onHide);
                }
            },
            
            onSelect: (start, end) => {
                const startDate = start ? start.format(this.options.format) : null;
                const endDate = end ? end.format(this.options.format) : null;
                
                if (this.callbacks.onSelect) {
                    this.callbacks.onSelect(startDate, endDate, { start, end });
                }
                
                // Trigger custom event
                element.dispatchEvent(new CustomEvent('dateRangeSelected', {
                    detail: { startDate, endDate, start, end }
                }));
            },
            
            onError: (error) => {
                console.error('Litepicker error:', error);
                if (this.callbacks.onError) {
                    this.callbacks.onError(error);
                }
            }
        };

        try {
            this.picker = new Litepicker(pickerOptions);
            console.log('✅ ModernDateRangePicker initialized successfully with Litepicker');
        } catch (error) {
            console.error('Failed to initialize Litepicker:', error);
            this.fallbackToNativePicker();
        }
    }

    /**
     * Apply custom styles for modern appearance
     */
    applyCustomStyles() {
        if (!document.getElementById('modern-datepicker-styles')) {
            const styles = document.createElement('style');
            styles.id = 'modern-datepicker-styles';
            styles.textContent = `
                .litepicker {
                    background: rgba(30, 30, 30, 0.95) !important;
                    backdrop-filter: blur(20px) !important;
                    border: 1px solid rgba(255, 255, 255, 0.1) !important;
                    border-radius: 12px !important;
                    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3) !important;
                    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif !important;
                }
                
                .litepicker .container__months {
                    background: transparent !important;
                }
                
                .litepicker .container__months .month-item {
                    background: rgba(255, 255, 255, 0.05) !important;
                    border-radius: 8px !important;
                    margin: 8px !important;
                }
                
                .litepicker .container__months .month-item-header {
                    background: rgba(147, 51, 234, 0.2) !important;
                    color: white !important;
                    border-radius: 8px 8px 0 0 !important;
                    padding: 12px !important;
                    font-weight: 600 !important;
                }
                
                .litepicker .container__days .day-item {
                    color: rgba(255, 255, 255, 0.8) !important;
                    border-radius: 6px !important;
                    transition: all 0.2s ease !important;
                    margin: 1px !important;
                }
                
                .litepicker .container__days .day-item:hover {
                    background: rgba(147, 51, 234, 0.3) !important;
                    color: white !important;
                    transform: scale(1.05) !important;
                }
                
                .litepicker .container__days .day-item.is-selected {
                    background: rgba(147, 51, 234, 0.8) !important;
                    color: white !important;
                    font-weight: 600 !important;
                }
                
                .litepicker .container__days .day-item.is-in-range {
                    background: rgba(147, 51, 234, 0.2) !important;
                    color: rgba(255, 255, 255, 0.9) !important;
                }
                
                .litepicker .container__days .day-item.is-start-date,
                .litepicker .container__days .day-item.is-end-date {
                    background: rgba(147, 51, 234, 0.9) !important;
                    color: white !important;
                    font-weight: 700 !important;
                }
                
                .litepicker .container__days .day-item.is-today {
                    border: 2px solid rgba(59, 130, 246, 0.6) !important;
                    font-weight: 600 !important;
                }
                
                .litepicker .container__days .day-item.is-locked {
                    color: rgba(255, 255, 255, 0.3) !important;
                    cursor: not-allowed !important;
                }
                
                .litepicker .container__footer {
                    background: rgba(255, 255, 255, 0.05) !important;
                    border-top: 1px solid rgba(255, 255, 255, 0.1) !important;
                    border-radius: 0 0 12px 12px !important;
                    padding: 12px !important;
                }
                
                .litepicker .container__footer .button-apply,
                .litepicker .container__footer .button-cancel {
                    background: rgba(147, 51, 234, 0.8) !important;
                    color: white !important;
                    border: none !important;
                    border-radius: 6px !important;
                    padding: 8px 16px !important;
                    font-weight: 500 !important;
                    transition: all 0.2s ease !important;
                }
                
                .litepicker .container__footer .button-cancel {
                    background: rgba(255, 255, 255, 0.1) !important;
                    margin-right: 8px !important;
                }
                
                .litepicker .container__footer .button-apply:hover,
                .litepicker .container__footer .button-cancel:hover {
                    transform: translateY(-1px) !important;
                    box-shadow: 0 4px 12px rgba(147, 51, 234, 0.3) !important;
                }
                
                /* Responsive adjustments */
                @media (max-width: 768px) {
                    .litepicker {
                        max-width: 90vw !important;
                        margin: 0 auto !important;
                    }
                    
                    .litepicker .container__months .month-item {
                        margin: 4px !important;
                    }
                }
            `;
            document.head.appendChild(styles);
        }
    }

    /**
     * Fallback to native HTML5 date inputs
     */
    fallbackToNativePicker() {
        console.warn('📅 Using native date picker fallback');
        
        const element = typeof this.options.element === 'string' 
            ? document.querySelector(this.options.element)
            : this.options.element;
            
        if (!element) {
            console.error('Element not found for fallback');
            return;
        }
        
        // Hide the original element and create native inputs
        element.style.display = 'none';
        
        // Create native date range inputs
        const container = document.createElement('div');
        container.className = 'native-date-range-container';
        container.style.cssText = `
            display: flex;
            gap: 8px;
            align-items: center;
        `;
        
        const startInput = document.createElement('input');
        startInput.type = 'date';
        startInput.className = 'native-date-input';
        startInput.style.cssText = `
            flex: 1;
            padding: 8px 12px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 6px;
            color: white;
            font-size: 14px;
        `;
        
        const separator = document.createElement('span');
        separator.textContent = 'to';
        separator.style.cssText = `
            color: rgba(255, 255, 255, 0.7);
            font-size: 12px;
            padding: 0 4px;
        `;
        
        const endInput = document.createElement('input');
        endInput.type = 'date';
        endInput.className = 'native-date-input';
        endInput.style.cssText = startInput.style.cssText;
        
        container.appendChild(startInput);
        container.appendChild(separator);
        container.appendChild(endInput);
        
        element.parentNode.insertBefore(container, element.nextSibling);
        
        // Add event listeners
        const handleDateChange = () => {
            const startDate = startInput.value;
            const endDate = endInput.value;
            
            if (startDate && endDate) {
                // Update original element value for compatibility
                element.value = `${startDate} - ${endDate}`;
                
                if (this.callbacks.onSelect) {
                    this.callbacks.onSelect(startDate, endDate, { start: startDate, end: endDate });
                }
            }
        };
        
        startInput.addEventListener('change', handleDateChange);
        endInput.addEventListener('change', handleDateChange);
        
        this.nativeInputs = { start: startInput, end: endInput, container };
        this.isNativeFallback = true;
        
        console.log('📅 Native date picker fallback initialized');
    }

    /**
     * Set callback functions
     */
    on(event, callback) {
        if (this.callbacks.hasOwnProperty(`on${event.charAt(0).toUpperCase() + event.slice(1)}`)) {
            this.callbacks[`on${event.charAt(0).toUpperCase() + event.slice(1)}`] = callback;
        }
        return this;
    }

    /**
     * Set date range programmatically
     */
    setDateRange(startDate, endDate) {
        console.log('📅 Setting date range:', { startDate, endDate });
        
        if (this.picker) {
            this.picker.setDateRange(startDate, endDate);
        } else if (this.nativeInputs) {
            this.nativeInputs.start.value = startDate || '';
            this.nativeInputs.end.value = endDate || '';
            
            // Update original element for compatibility
            const element = typeof this.options.element === 'string' 
                ? document.querySelector(this.options.element)
                : this.options.element;
            if (element && startDate && endDate) {
                element.value = `${startDate} - ${endDate}`;
            }
        }
        return this;
    }

    /**
     * Clear the date range
     */
    clear() {
        console.log('📅 Clearing date range');
        
        if (this.picker) {
            this.picker.clearSelection();
        } else if (this.nativeInputs) {
            this.nativeInputs.start.value = '';
            this.nativeInputs.end.value = '';
            
            // Clear original element for compatibility
            const element = typeof this.options.element === 'string' 
                ? document.querySelector(this.options.element)
                : this.options.element;
            if (element) {
                element.value = '';
            }
        }
        
        if (this.callbacks.onClear) {
            this.callbacks.onClear();
        }
        
        return this;
    }

    /**
     * Show the picker
     */
    show() {
        if (this.picker) {
            this.picker.show();
        }
        return this;
    }

    /**
     * Hide the picker
     */
    hide() {
        if (this.picker) {
            this.picker.hide();
        }
        return this;
    }

    /**
     * Destroy the picker
     */
    destroy() {
        if (this.picker) {
            this.picker.destroy();
            this.picker = null;
        }
        return this;
    }

    /**
     * Get current date range
     */
    getDateRange() {
        if (this.picker) {
            const start = this.picker.getStartDate();
            const end = this.picker.getEndDate();
            return {
                start: start ? start.format(this.options.format) : null,
                end: end ? end.format(this.options.format) : null
            };
        } else if (this.nativeInputs) {
            return {
                start: this.nativeInputs.start.value || null,
                end: this.nativeInputs.end.value || null
            };
        }
        return { start: null, end: null };
    }

    /**
     * Update options
     */
    updateOptions(newOptions) {
        this.options = { ...this.options, ...newOptions };
        if (this.picker) {
            this.destroy();
            this.createPicker();
        }
        return this;
    }
}

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ModernDateRangePicker;
} else if (typeof window !== 'undefined') {
    window.ModernDateRangePicker = ModernDateRangePicker;
}

// Auto-initialize if data attributes are found
document.addEventListener('DOMContentLoaded', () => {
    const elements = document.querySelectorAll('[data-modern-datepicker]');
    elements.forEach(element => {
        const options = {
            element: element,
            ...JSON.parse(element.dataset.modernDatepicker || '{}')
        };
        new ModernDateRangePicker(options);
    });
});