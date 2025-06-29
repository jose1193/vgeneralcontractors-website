/**
 * ValidationManager - Sistema de validación centralizado
 * Maneja validaciones del lado cliente y servidor
 */
export class ValidationManager {
    constructor(config = {}) {
        this.rules = config.rules || {};
        this.messages = config.messages || {};
        this.apiManager = config.apiManager || null;
        this.debounceTime = config.debounceTime || 500;
        this.validationTimeouts = new Map();
    }

    /**
     * Validar un campo específico
     */
    async validateField(fieldName, value, context = {}) {
        const fieldRules = this.rules[fieldName] || [];
        const errors = [];

        // Validaciones síncronas
        for (const rule of fieldRules) {
            if (typeof rule === 'string') {
                const error = this.validateRule(rule, value, fieldName);
                if (error) errors.push(error);
            } else if (typeof rule === 'object') {
                const error = this.validateComplexRule(rule, value, fieldName, context);
                if (error) errors.push(error);
            }
        }

        // Validaciones asíncronas (servidor)
        if (this.apiManager && errors.length === 0) {
            try {
                const serverValidation = await this.validateOnServer(fieldName, value, context);
                if (!serverValidation.valid) {
                    errors.push(...serverValidation.errors);
                }
            } catch (error) {
                console.warn('Server validation failed:', error);
            }
        }

        return {
            valid: errors.length === 0,
            errors: errors
        };
    }

    /**
     * Validar regla simple
     */
    validateRule(rule, value, fieldName) {
        switch (rule) {
            case 'required':
                return this.validateRequired(value, fieldName);
            case 'email':
                return this.validateEmail(value, fieldName);
            case 'url':
                return this.validateUrl(value, fieldName);
            case 'numeric':
                return this.validateNumeric(value, fieldName);
            case 'integer':
                return this.validateInteger(value, fieldName);
            case 'alpha':
                return this.validateAlpha(value, fieldName);
            case 'alphanumeric':
                return this.validateAlphanumeric(value, fieldName);
            default:
                return null;
        }
    }

    /**
     * Validar regla compleja
     */
    validateComplexRule(rule, value, fieldName, context) {
        switch (rule.type) {
            case 'min':
                return this.validateMin(value, rule.value, fieldName);
            case 'max':
                return this.validateMax(value, rule.value, fieldName);
            case 'minLength':
                return this.validateMinLength(value, rule.value, fieldName);
            case 'maxLength':
                return this.validateMaxLength(value, rule.value, fieldName);
            case 'pattern':
                return this.validatePattern(value, rule.pattern, fieldName, rule.message);
            case 'custom':
                return this.validateCustom(value, rule.validator, fieldName, context);
            case 'unique':
                // Esta validación se maneja en el servidor
                return null;
            case 'exists':
                // Esta validación se maneja en el servidor
                return null;
            default:
                return null;
        }
    }

    /**
     * Validaciones específicas
     */
    validateRequired(value, fieldName) {
        if (!value || (typeof value === 'string' && value.trim() === '')) {
            return this.getMessage('required', fieldName) || `${fieldName} es requerido`;
        }
        return null;
    }

    validateEmail(value, fieldName) {
        if (!value) return null;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(value)) {
            return this.getMessage('email', fieldName) || `${fieldName} debe ser un email válido`;
        }
        return null;
    }

    validateUrl(value, fieldName) {
        if (!value) return null;
        try {
            new URL(value);
            return null;
        } catch {
            return this.getMessage('url', fieldName) || `${fieldName} debe ser una URL válida`;
        }
    }

    validateNumeric(value, fieldName) {
        if (!value) return null;
        if (isNaN(value) || isNaN(parseFloat(value))) {
            return this.getMessage('numeric', fieldName) || `${fieldName} debe ser un número`;
        }
        return null;
    }

    validateInteger(value, fieldName) {
        if (!value) return null;
        if (!Number.isInteger(Number(value))) {
            return this.getMessage('integer', fieldName) || `${fieldName} debe ser un número entero`;
        }
        return null;
    }

    validateAlpha(value, fieldName) {
        if (!value) return null;
        const alphaRegex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/;
        if (!alphaRegex.test(value)) {
            return this.getMessage('alpha', fieldName) || `${fieldName} solo puede contener letras`;
        }
        return null;
    }

    validateAlphanumeric(value, fieldName) {
        if (!value) return null;
        const alphanumericRegex = /^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s]+$/;
        if (!alphanumericRegex.test(value)) {
            return this.getMessage('alphanumeric', fieldName) || `${fieldName} solo puede contener letras y números`;
        }
        return null;
    }

    validateMin(value, minValue, fieldName) {
        if (!value) return null;
        const numValue = parseFloat(value);
        if (isNaN(numValue) || numValue < minValue) {
            return this.getMessage('min', fieldName, { min: minValue }) || 
                   `${fieldName} debe ser mayor o igual a ${minValue}`;
        }
        return null;
    }

    validateMax(value, maxValue, fieldName) {
        if (!value) return null;
        const numValue = parseFloat(value);
        if (isNaN(numValue) || numValue > maxValue) {
            return this.getMessage('max', fieldName, { max: maxValue }) || 
                   `${fieldName} debe ser menor o igual a ${maxValue}`;
        }
        return null;
    }

    validateMinLength(value, minLength, fieldName) {
        if (!value) return null;
        if (value.length < minLength) {
            return this.getMessage('minLength', fieldName, { min: minLength }) || 
                   `${fieldName} debe tener al menos ${minLength} caracteres`;
        }
        return null;
    }

    validateMaxLength(value, maxLength, fieldName) {
        if (!value) return null;
        if (value.length > maxLength) {
            return this.getMessage('maxLength', fieldName, { max: maxLength }) || 
                   `${fieldName} no puede tener más de ${maxLength} caracteres`;
        }
        return null;
    }

    validatePattern(value, pattern, fieldName, customMessage) {
        if (!value) return null;
        const regex = new RegExp(pattern);
        if (!regex.test(value)) {
            return customMessage || 
                   this.getMessage('pattern', fieldName) || 
                   `${fieldName} no tiene el formato correcto`;
        }
        return null;
    }

    validateCustom(value, validator, fieldName, context) {
        if (typeof validator === 'function') {
            try {
                const result = validator(value, context);
                if (result !== true) {
                    return typeof result === 'string' ? result : `${fieldName} no es válido`;
                }
            } catch (error) {
                console.error('Custom validation error:', error);
                return `Error en validación de ${fieldName}`;
            }
        }
        return null;
    }

    /**
     * Validar en el servidor
     */
    async validateOnServer(fieldName, value, context = {}) {
        if (!this.apiManager) {
            return { valid: true, errors: [] };
        }

        try {
            const response = await this.apiManager.validateField(fieldName, value, context.entityId);
            return {
                valid: response.valid || response.success,
                errors: response.errors || (response.valid ? [] : [response.message || 'Error de validación'])
            };
        } catch (error) {
            console.error('Server validation error:', error);
            return { valid: true, errors: [] }; // Fallar silenciosamente en validaciones de servidor
        }
    }

    /**
     * Validar formulario completo
     */
    async validateForm(formData, context = {}) {
        const results = {};
        const allErrors = [];

        // Validar cada campo
        for (const [fieldName, value] of Object.entries(formData)) {
            if (this.rules[fieldName]) {
                const result = await this.validateField(fieldName, value, context);
                results[fieldName] = result;
                if (!result.valid) {
                    allErrors.push(...result.errors);
                }
            }
        }

        return {
            valid: allErrors.length === 0,
            errors: allErrors,
            fieldResults: results
        };
    }

    /**
     * Validación en tiempo real con debounce
     */
    validateFieldDebounced(fieldName, value, context = {}, callback) {
        // Limpiar timeout anterior
        if (this.validationTimeouts.has(fieldName)) {
            clearTimeout(this.validationTimeouts.get(fieldName));
        }

        // Establecer nuevo timeout
        const timeoutId = setTimeout(async () => {
            const result = await this.validateField(fieldName, value, context);
            callback(fieldName, result);
            this.validationTimeouts.delete(fieldName);
        }, this.debounceTime);

        this.validationTimeouts.set(fieldName, timeoutId);
    }

    /**
     * Obtener mensaje de error personalizado
     */
    getMessage(rule, fieldName, params = {}) {
        const fieldMessages = this.messages[fieldName] || {};
        const globalMessages = this.messages.global || {};
        
        let message = fieldMessages[rule] || globalMessages[rule];
        
        if (message && params) {
            // Reemplazar parámetros en el mensaje
            Object.keys(params).forEach(key => {
                message = message.replace(new RegExp(`:${key}`, 'g'), params[key]);
            });
        }
        
        return message;
    }

    /**
     * Agregar regla de validación
     */
    addRule(fieldName, rule) {
        if (!this.rules[fieldName]) {
            this.rules[fieldName] = [];
        }
        this.rules[fieldName].push(rule);
    }

    /**
     * Remover regla de validación
     */
    removeRule(fieldName, rule) {
        if (this.rules[fieldName]) {
            this.rules[fieldName] = this.rules[fieldName].filter(r => r !== rule);
        }
    }

    /**
     * Establecer reglas para un campo
     */
    setRules(fieldName, rules) {
        this.rules[fieldName] = Array.isArray(rules) ? rules : [rules];
    }

    /**
     * Obtener reglas de un campo
     */
    getRules(fieldName) {
        return this.rules[fieldName] || [];
    }

    /**
     * Establecer mensajes personalizados
     */
    setMessages(fieldName, messages) {
        this.messages[fieldName] = messages;
    }

    /**
     * Limpiar timeouts de validación
     */
    clearValidationTimeouts() {
        this.validationTimeouts.forEach(timeoutId => {
            clearTimeout(timeoutId);
        });
        this.validationTimeouts.clear();
    }

    /**
     * Validar unicidad en servidor
     */
    async validateUnique(fieldName, value, entityId = null) {
        if (!this.apiManager) {
            return { unique: true };
        }

        try {
            const response = await this.apiManager.checkUnique(fieldName, value, entityId);
            return {
                unique: response.unique || response.available,
                message: response.message
            };
        } catch (error) {
            console.error('Unique validation error:', error);
            return { unique: true }; // Fallar silenciosamente
        }
    }

    /**
     * Crear validador personalizado
     */
    createCustomValidator(name, validator, message) {
        this[`validate${name.charAt(0).toUpperCase() + name.slice(1)}`] = (value, fieldName) => {
            const result = validator(value);
            if (result !== true) {
                return typeof result === 'string' ? result : (message || `${fieldName} no es válido`);
            }
            return null;
        };
    }

    /**
     * Resetear validaciones
     */
    reset() {
        this.clearValidationTimeouts();
    }
}