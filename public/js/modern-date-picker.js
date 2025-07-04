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

        console.log('🔍 DEBUG: Checking for Litepicker availability...');
        console.log('🔍 DEBUG: typeof Litepicker:', typeof Litepicker);
        console.log('🔍 DEBUG: window.Litepicker:', window.Litepicker);
        
        // Check if Litepicker is available
        if (typeof Litepicker === 'undefined' && typeof window.Litepicker === 'undefined') {
            console.warn('⚠️ ModernDateRangePicker: Litepicker library not found, loading from CDN...');
            this.loadLitepicker().then(() => {
                console.log('✅ Litepicker loaded successfully from CDN');
                this.createPicker();
            }).catch(error => {
                console.error('❌ Failed to load Litepicker from CDN:', error);
                console.log('🔄 Falling back to native date picker...');
                this.fallbackToNativePicker();
            });
        } else {
            console.log('✅ Litepicker is available, creating picker...');
            this.createPicker();
        }
    }

    /**
     * Dynamically load Litepicker if not available
     */
    async loadLitepicker() {
        return new Promise((resolve, reject) => {
            console.log('📦 Loading Litepicker from CDN...');
            
            // Check if already loaded
            if (document.querySelector('link[href*="litepicker"]') && 
                document.querySelector('script[src*="litepicker"]')) {
                console.log('📦 Litepicker already loaded, checking availability...');
                // Wait a bit for the script to execute
                setTimeout(() => {
                    if (typeof Litepicker !== 'undefined' || typeof window.Litepicker !== 'undefined') {
                        resolve();
                    } else {
                        reject(new Error('Litepicker loaded but not available'));
                    }
                }, 100);
                return;
            }
            
            // Load CSS
            const cssLink = document.createElement('link');
            cssLink.rel = 'stylesheet';
            cssLink.href = 'https://cdn.jsdelivr.net/npm/litepicker@2.0.12/dist/css/litepicker.css';
            cssLink.onload = () => console.log('✅ Litepicker CSS loaded');
            cssLink.onerror = () => console.error('❌ Failed to load Litepicker CSS');
            document.head.appendChild(cssLink);

            // Load JS
            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/litepicker@2.0.12/dist/litepicker.js';
            script.onload = () => {
                console.log('✅ Litepicker JS loaded');
                // Wait a bit for the script to execute and make Litepicker available
                setTimeout(() => {
                    if (typeof Litepicker !== 'undefined' || typeof window.Litepicker !== 'undefined') {
                        console.log('✅ Litepicker is now available');
                        resolve();
                    } else {
                        console.error('❌ Litepicker loaded but not available in global scope');
                        reject(new Error('Litepicker loaded but not available'));
                    }
                }, 100);
            };
            script.onerror = (error) => {
                console.error('❌ Failed to load Litepicker JS:', error);
                reject(error);
            };
            document.head.appendChild(script);
        });
    }

    /**
     * Create the Litepicker instance
     */
    createPicker() {
        const element = typeof this.options.element === 'string' 
            ? document.querySelector(this.options.element)
            : this.options.element;

        if (!element) {
            console.error('ModernDateRangePicker: Element not found');
            return;
        }

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
                // 🐛 DEBUG: Log raw Litepicker values
                console.group('🗓️ DEBUG: ModernDateRangePicker.onSelect() called');
                console.log('📅 Raw start object:', start);
                console.log('📅 Raw end object:', end);
                
                const startDate = start ? start.format(this.options.format) : null;
                const endDate = end ? end.format(this.options.format) : null;
                
                console.log('📅 Formatted startDate:', startDate, '(type:', typeof startDate, ')');
                console.log('📅 Formatted endDate:', endDate, '(type:', typeof endDate, ')');
                console.log('⚙️ Format used:', this.options.format);
                
                if (this.callbacks.onSelect) {
                    console.log('🔄 Calling registered onSelect callback with:', { startDate, endDate });
                    this.callbacks.onSelect(startDate, endDate, { start, end });
                } else {
                    console.log('⚠️ No onSelect callback registered');
                }
                
                // Trigger custom event
                console.log('📡 Dispatching dateRangeSelected event');
                element.dispatchEvent(new CustomEvent('dateRangeSelected', {
                    detail: { startDate, endDate, start, end }
                }));
                console.groupEnd();
            },
            
            onError: (error) => {
                console.error('Litepicker error:', error);
                if (this.callbacks.onError) {
                    this.callbacks.onError(error);
                }
            }
        };

        try {
            // Use global Litepicker or window.Litepicker
            const LitepickerClass = typeof Litepicker !== 'undefined' ? Litepicker : window.Litepicker;
            
            if (!LitepickerClass) {
                throw new Error('Litepicker class not found');
            }
            
            console.log('🔧 Creating Litepicker instance with options:', pickerOptions);
            this.picker = new LitepickerClass(pickerOptions);
            console.log('✅ ModernDateRangePicker initialized successfully with Litepicker');
            
            // Add click event to element to ensure picker opens
            element.addEventListener('click', () => {
                console.log('🖱️ Element clicked, showing picker...');
                if (this.picker) {
                    this.picker.show();
                }
            });
            
        } catch (error) {
            console.error('❌ Failed to initialize Litepicker:', error);
            console.log('🔄 Falling back to native date picker...');
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
        console.warn('Using native date picker fallback');
        
        const element = typeof this.options.element === 'string' 
            ? document.querySelector(this.options.element)
            : this.options.element;
            
        if (!element) return;
        
        // Create native date range inputs
        const container = document.createElement('div');
        container.className = 'native-date-range-container flex gap-2';
        
        const startInput = document.createElement('input');
        startInput.type = 'date';
        startInput.className = 'native-date-input flex-1 px-3 py-2 bg-white/10 border border-white/30 rounded-lg text-white';
        startInput.placeholder = 'Start date';
        
        const endInput = document.createElement('input');
        endInput.type = 'date';
        endInput.className = 'native-date-input flex-1 px-3 py-2 bg-white/10 border border-white/30 rounded-lg text-white';
        endInput.placeholder = 'End date';
        
        container.appendChild(startInput);
        container.appendChild(endInput);
        
        element.parentNode.replaceChild(container, element);
        
        // Add event listeners
        const handleDateChange = () => {
            const startDate = startInput.value;
            const endDate = endInput.value;
            
            if (this.callbacks.onSelect && startDate && endDate) {
                this.callbacks.onSelect(startDate, endDate);
            }
        };
        
        startInput.addEventListener('change', handleDateChange);
        endInput.addEventListener('change', handleDateChange);
        
        this.nativeInputs = { start: startInput, end: endInput };
    }

    /**
     * Set callback functions
     */
    on(event, callback) {
        const callbackName = `on${event.charAt(0).toUpperCase() + event.slice(1)}`;
        
        // 🐛 DEBUG: Log callback registration
        console.group('🔗 DEBUG: ModernDateRangePicker.on() called');
        console.log('📝 Event:', event);
        console.log('📝 Callback name:', callbackName);
        console.log('📝 Callback function:', callback);
        console.log('📝 Available callbacks:', Object.keys(this.callbacks));
        
        if (this.callbacks.hasOwnProperty(callbackName)) {
            this.callbacks[callbackName] = callback;
            console.log('✅ Callback registered successfully');
        } else {
            console.warn('⚠️ Unknown callback:', callbackName);
        }
        
        console.log('📝 Current callbacks state:', this.callbacks);
        console.groupEnd();
        
        return this;
    }

    /**
     * Set date range programmatically
     */
    setDateRange(startDate, endDate) {
        // 🐛 DEBUG: Log date range values being set
        console.group('🗓️ DEBUG: ModernDateRangePicker.setDateRange() called');
        console.log('📅 startDate:', startDate, '(type:', typeof startDate, ')');
        console.log('📅 endDate:', endDate, '(type:', typeof endDate, ')');
        console.log('⚙️ Current picker instance:', this.picker ? 'Litepicker' : (this.nativeInputs ? 'Native Inputs' : 'None'));
        
        if (this.picker) {
            console.log('🔄 Setting date range on Litepicker instance');
            this.picker.setDateRange(startDate, endDate);
        } else if (this.nativeInputs) {
            console.log('🔄 Setting date range on native inputs');
            this.nativeInputs.start.value = startDate || '';
            this.nativeInputs.end.value = endDate || '';
        } else {
            console.warn('⚠️ No picker instance available to set date range');
        }
        
        console.log('✅ Date range set operation completed');
        console.groupEnd();
        return this;
    }

    /**
     * Clear the date range
     */
    clear() {
        if (this.picker) {
            this.picker.clearSelection();
        } else if (this.nativeInputs) {
            this.nativeInputs.start.value = '';
            this.nativeInputs.end.value = '';
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