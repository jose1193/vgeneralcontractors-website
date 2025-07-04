/**
 * Decimal Formatter Utility
 * Formatea campos de entrada decimal con separadores de miles y decimales
 * Formato: 2,500.00
 * 
 * @author Invoice Demo System
 * @version 1.0.0
 */

class DecimalFormatter {
    constructor(options = {}) {
        this.options = {
            thousandsSeparator: ',',
            decimalSeparator: '.',
            decimalPlaces: 2,
            allowNegative: false,
            maxValue: null,
            minValue: 0,
            ...options
        };
    }

    /**
     * Formatea un input de decimal con separadores de miles
     * @param {Event} event - Evento del input
     * @param {string} fieldName - Nombre del campo en el formulario
     * @param {Object} formObject - Objeto del formulario (this.form)
     * @param {Function} calculateCallback - Función de callback para recalcular totales
     */
    formatDecimalInput(event, fieldName, formObject, calculateCallback = null) {
        const input = event.target;
        const cursorPosition = input.selectionStart;
        let value = input.value;
        
        // Guardar el valor original para comparación
        const originalValue = value;
        
        // Remover caracteres no válidos (mantener solo números, punto decimal y signo negativo si está permitido)
        let cleanValue = this.cleanValue(value);
        
        // Aplicar restricciones de valor
        cleanValue = this.applyValueRestrictions(cleanValue);
        
        // Formatear con separadores de miles
        const formattedValue = this.addThousandsSeparators(cleanValue);
        
        // Solo actualizar si el valor cambió para evitar loops infinitos
        if (formattedValue !== originalValue) {
            input.value = formattedValue;
            
            // Actualizar el modelo del formulario con el valor numérico limpio
            const numericValue = this.getNumericValue(formattedValue);
            formObject[fieldName] = numericValue;
            
            // Ajustar la posición del cursor
            this.adjustCursorPosition(input, cursorPosition, originalValue, formattedValue);
        }
        
        // Ejecutar callback si se proporciona (para recalcular totales)
        if (calculateCallback && typeof calculateCallback === 'function') {
            calculateCallback();
        }
    }

    /**
     * Limpia el valor removiendo caracteres no válidos
     * @param {string} value - Valor a limpiar
     * @returns {string} - Valor limpio
     */
    cleanValue(value) {
        // Remover todos los separadores de miles existentes
        value = value.replace(new RegExp(`\\${this.options.thousandsSeparator}`, 'g'), '');
        
        // Crear patrón regex basado en las opciones
        let pattern = '[^0-9\\.]';
        if (this.options.allowNegative) {
            pattern = '[^0-9\\.\\-]';
        }
        
        // Remover caracteres no válidos
        value = value.replace(new RegExp(pattern, 'g'), '');
        
        // Manejar múltiples puntos decimales (mantener solo el primero)
        const parts = value.split(this.options.decimalSeparator);
        if (parts.length > 2) {
            value = parts[0] + this.options.decimalSeparator + parts.slice(1).join('');
        }
        
        // Limitar decimales
        if (parts.length > 1 && parts[1].length > this.options.decimalPlaces) {
            value = parts[0] + this.options.decimalSeparator + parts[1].substring(0, this.options.decimalPlaces);
        }
        
        // Manejar signo negativo (solo al inicio)
        if (this.options.allowNegative && value.includes('-')) {
            const isNegative = value.charAt(0) === '-';
            value = value.replace(/-/g, '');
            if (isNegative) {
                value = '-' + value;
            }
        }
        
        return value;
    }

    /**
     * Aplica restricciones de valor mínimo y máximo
     * @param {string} value - Valor a validar
     * @returns {string} - Valor con restricciones aplicadas
     */
    applyValueRestrictions(value) {
        const numericValue = parseFloat(value) || 0;
        
        // Aplicar valor mínimo
        if (this.options.minValue !== null && numericValue < this.options.minValue) {
            return this.options.minValue.toString();
        }
        
        // Aplicar valor máximo
        if (this.options.maxValue !== null && numericValue > this.options.maxValue) {
            return this.options.maxValue.toString();
        }
        
        return value;
    }

    /**
     * Añade separadores de miles al valor
     * @param {string} value - Valor numérico limpio
     * @returns {string} - Valor formateado con separadores
     */
    addThousandsSeparators(value) {
        if (!value || value === '' || value === '-') {
            return value;
        }
        
        const parts = value.split(this.options.decimalSeparator);
        const integerPart = parts[0];
        const decimalPart = parts[1];
        
        // Manejar signo negativo
        const isNegative = integerPart.startsWith('-');
        const absoluteInteger = isNegative ? integerPart.substring(1) : integerPart;
        
        // Añadir separadores de miles
        const formattedInteger = absoluteInteger.replace(/\B(?=(\d{3})+(?!\d))/g, this.options.thousandsSeparator);
        
        // Reconstruir el valor
        let result = (isNegative ? '-' : '') + formattedInteger;
        
        if (decimalPart !== undefined) {
            result += this.options.decimalSeparator + decimalPart;
        }
        
        return result;
    }

    /**
     * Obtiene el valor numérico sin formato
     * @param {string} formattedValue - Valor formateado
     * @returns {number} - Valor numérico
     */
    getNumericValue(formattedValue) {
        if (!formattedValue || formattedValue === '' || formattedValue === '-') {
            return 0;
        }
        
        // Remover separadores de miles
        const cleanValue = formattedValue.replace(new RegExp(`\\${this.options.thousandsSeparator}`, 'g'), '');
        
        return parseFloat(cleanValue) || 0;
    }

    /**
     * Ajusta la posición del cursor después del formateo
     * @param {HTMLInputElement} input - Elemento input
     * @param {number} originalCursor - Posición original del cursor
     * @param {string} originalValue - Valor original
     * @param {string} newValue - Nuevo valor formateado
     */
    adjustCursorPosition(input, originalCursor, originalValue, newValue) {
        // Calcular la diferencia en longitud
        const lengthDiff = newValue.length - originalValue.length;
        
        // Ajustar la posición del cursor
        let newCursorPos = originalCursor + lengthDiff;
        
        // Asegurar que la posición esté dentro de los límites
        newCursorPos = Math.max(0, Math.min(newCursorPos, newValue.length));
        
        // Aplicar la nueva posición del cursor
        setTimeout(() => {
            input.setSelectionRange(newCursorPos, newCursorPos);
        }, 0);
    }

    /**
     * Formatea un valor numérico para mostrar
     * @param {number|string} value - Valor a formatear
     * @returns {string} - Valor formateado
     */
    formatForDisplay(value) {
        if (value === null || value === undefined || value === '') {
            return '0.00';
        }
        
        const numericValue = parseFloat(value) || 0;
        const fixedValue = numericValue.toFixed(this.options.decimalPlaces);
        
        return this.addThousandsSeparators(fixedValue);
    }

    /**
     * Valida si un valor es válido
     * @param {string} value - Valor a validar
     * @returns {boolean} - True si es válido
     */
    isValid(value) {
        if (!value || value === '') {
            return true; // Valores vacíos son válidos
        }
        
        const numericValue = this.getNumericValue(value);
        
        // Verificar rango
        if (this.options.minValue !== null && numericValue < this.options.minValue) {
            return false;
        }
        
        if (this.options.maxValue !== null && numericValue > this.options.maxValue) {
            return false;
        }
        
        return true;
    }
}

// Crear instancias predefinidas para diferentes tipos de campos
const CurrencyFormatter = new DecimalFormatter({
    decimalPlaces: 2,
    allowNegative: false,
    minValue: 0
});

const PercentageFormatter = new DecimalFormatter({
    decimalPlaces: 2,
    allowNegative: false,
    minValue: 0,
    maxValue: 100
});

const GeneralDecimalFormatter = new DecimalFormatter({
    decimalPlaces: 2,
    allowNegative: true
});

// Exportar para uso global
if (typeof window !== 'undefined') {
    window.DecimalFormatter = DecimalFormatter;
    window.CurrencyFormatter = CurrencyFormatter;
    window.PercentageFormatter = PercentageFormatter;
    window.GeneralDecimalFormatter = GeneralDecimalFormatter;
}

// Exportar para módulos
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        DecimalFormatter,
        CurrencyFormatter,
        PercentageFormatter,
        GeneralDecimalFormatter
    };
}