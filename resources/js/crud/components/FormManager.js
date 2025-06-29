/**
 * FormManager - Manejo de formularios dinámicos
 * Genera y valida formularios del sistema CRUD
 */
export class FormManager {
    constructor(config = {}) {
        this.fields = config.fields || [];
        this.validationRules = config.validationRules || {};
        this.formId = config.formId || 'crudForm';
        this.onFieldChange = config.onFieldChange || (() => {});
        this.onValidation = config.onValidation || (() => {});
    }

    /**
     * Generar HTML del formulario
     */
    generateFormHtml(data = {}, isEditing = false) {
        const formFields = this.fields.map(field => {
            return this.generateFieldHtml(field, data[field.name] || '', isEditing);
        }).join('');

        return `
            <form id="${this.formId}" class="crud-form">
                <div class="row">
                    ${formFields}
                </div>
            </form>
        `;
    }

    /**
     * Generar HTML de un campo
     */
    generateFieldHtml(field, value = '', isEditing = false) {
        const colClass = field.colClass || 'col-md-6';
        const fieldId = `${field.name}_field`;
        const isRequired = field.required ? 'required' : '';
        const isDisabled = (field.disabled || (field.disabledOnEdit && isEditing)) ? 'disabled' : '';
        
        let fieldHtml = '';
        
        switch (field.type) {
            case 'text':
            case 'email':
            case 'password':
            case 'url':
            case 'tel':
                fieldHtml = this.generateInputField(field, value, isRequired, isDisabled);
                break;
            case 'number':
                fieldHtml = this.generateNumberField(field, value, isRequired, isDisabled);
                break;
            case 'textarea':
                fieldHtml = this.generateTextareaField(field, value, isRequired, isDisabled);
                break;
            case 'select':
                fieldHtml = this.generateSelectField(field, value, isRequired, isDisabled);
                break;
            case 'checkbox':
                fieldHtml = this.generateCheckboxField(field, value, isDisabled);
                break;
            case 'radio':
                fieldHtml = this.generateRadioField(field, value, isRequired, isDisabled);
                break;
            case 'date':
            case 'datetime-local':
            case 'time':
                fieldHtml = this.generateDateField(field, value, isRequired, isDisabled);
                break;
            case 'file':
                fieldHtml = this.generateFileField(field, value, isRequired, isDisabled);
                break;
            case 'hidden':
                return this.generateHiddenField(field, value);
            case 'custom':
                fieldHtml = this.generateCustomField(field, value, isEditing);
                break;
            default:
                fieldHtml = this.generateInputField(field, value, isRequired, isDisabled);
        }

        if (field.type === 'hidden') {
            return fieldHtml;
        }

        return `
            <div class="${colClass} mb-3" id="${fieldId}_container">
                <label for="${fieldId}" class="form-label">
                    ${field.label}
                    ${field.required ? '<span class="text-danger">*</span>' : ''}
                </label>
                ${fieldHtml}
                ${field.help ? `<div class="form-text">${field.help}</div>` : ''}
                <div class="invalid-feedback" id="${fieldId}_error"></div>
            </div>
        `;
    }

    /**
     * Generar campo input
     */
    generateInputField(field, value, isRequired, isDisabled) {
        const fieldId = `${field.name}_field`;
        const placeholder = field.placeholder || '';
        const maxlength = field.maxlength ? `maxlength="${field.maxlength}"` : '';
        const pattern = field.pattern ? `pattern="${field.pattern}"` : '';
        
        return `
            <input type="${field.type}" 
                   class="form-control" 
                   id="${fieldId}" 
                   name="${field.name}" 
                   value="${this.escapeHtml(value)}" 
                   placeholder="${placeholder}"
                   ${maxlength}
                   ${pattern}
                   ${isRequired} 
                   ${isDisabled}>
        `;
    }

    /**
     * Generar campo numérico
     */
    generateNumberField(field, value, isRequired, isDisabled) {
        const fieldId = `${field.name}_field`;
        const min = field.min !== undefined ? `min="${field.min}"` : '';
        const max = field.max !== undefined ? `max="${field.max}"` : '';
        const step = field.step !== undefined ? `step="${field.step}"` : '';
        
        return `
            <input type="number" 
                   class="form-control" 
                   id="${fieldId}" 
                   name="${field.name}" 
                   value="${value}" 
                   ${min}
                   ${max}
                   ${step}
                   ${isRequired} 
                   ${isDisabled}>
        `;
    }

    /**
     * Generar campo textarea
     */
    generateTextareaField(field, value, isRequired, isDisabled) {
        const fieldId = `${field.name}_field`;
        const rows = field.rows || 3;
        const maxlength = field.maxlength ? `maxlength="${field.maxlength}"` : '';
        
        return `
            <textarea class="form-control" 
                      id="${fieldId}" 
                      name="${field.name}" 
                      rows="${rows}"
                      ${maxlength}
                      ${isRequired} 
                      ${isDisabled}>${this.escapeHtml(value)}</textarea>
        `;
    }

    /**
     * Generar campo select
     */
    generateSelectField(field, value, isRequired, isDisabled) {
        const fieldId = `${field.name}_field`;
        const multiple = field.multiple ? 'multiple' : '';
        const options = this.generateSelectOptions(field.options || [], value, field.multiple);
        
        return `
            <select class="form-select" 
                    id="${fieldId}" 
                    name="${field.name}${field.multiple ? '[]' : ''}" 
                    ${multiple}
                    ${isRequired} 
                    ${isDisabled}>
                ${!field.multiple && !field.required ? '<option value="">Seleccionar...</option>' : ''}
                ${options}
            </select>
        `;
    }

    /**
     * Generar opciones de select
     */
    generateSelectOptions(options, selectedValue, isMultiple = false) {
        const selectedValues = isMultiple ? 
            (Array.isArray(selectedValue) ? selectedValue : [selectedValue]) : 
            [selectedValue];

        return options.map(option => {
            const optionValue = typeof option === 'object' ? option.value : option;
            const optionLabel = typeof option === 'object' ? option.label : option;
            const isSelected = selectedValues.includes(optionValue) ? 'selected' : '';
            
            return `<option value="${this.escapeHtml(optionValue)}" ${isSelected}>${this.escapeHtml(optionLabel)}</option>`;
        }).join('');
    }

    /**
     * Generar campo checkbox
     */
    generateCheckboxField(field, value, isDisabled) {
        const fieldId = `${field.name}_field`;
        const isChecked = value ? 'checked' : '';
        
        return `
            <div class="form-check">
                <input class="form-check-input" 
                       type="checkbox" 
                       id="${fieldId}" 
                       name="${field.name}" 
                       value="1"
                       ${isChecked} 
                       ${isDisabled}>
                <label class="form-check-label" for="${fieldId}">
                    ${field.checkboxLabel || field.label}
                </label>
            </div>
        `;
    }

    /**
     * Generar campo radio
     */
    generateRadioField(field, value, isRequired, isDisabled) {
        const options = field.options || [];
        
        return options.map((option, index) => {
            const optionValue = typeof option === 'object' ? option.value : option;
            const optionLabel = typeof option === 'object' ? option.label : option;
            const fieldId = `${field.name}_${index}`;
            const isChecked = value === optionValue ? 'checked' : '';
            
            return `
                <div class="form-check">
                    <input class="form-check-input" 
                           type="radio" 
                           id="${fieldId}" 
                           name="${field.name}" 
                           value="${this.escapeHtml(optionValue)}"
                           ${isChecked} 
                           ${isRequired}
                           ${isDisabled}>
                    <label class="form-check-label" for="${fieldId}">
                        ${this.escapeHtml(optionLabel)}
                    </label>
                </div>
            `;
        }).join('');
    }

    /**
     * Generar campo de fecha
     */
    generateDateField(field, value, isRequired, isDisabled) {
        const fieldId = `${field.name}_field`;
        const min = field.min ? `min="${field.min}"` : '';
        const max = field.max ? `max="${field.max}"` : '';
        
        return `
            <input type="${field.type}" 
                   class="form-control" 
                   id="${fieldId}" 
                   name="${field.name}" 
                   value="${value}" 
                   ${min}
                   ${max}
                   ${isRequired} 
                   ${isDisabled}>
        `;
    }

    /**
     * Generar campo de archivo
     */
    generateFileField(field, value, isRequired, isDisabled) {
        const fieldId = `${field.name}_field`;
        const accept = field.accept ? `accept="${field.accept}"` : '';
        const multiple = field.multiple ? 'multiple' : '';
        
        let currentFileHtml = '';
        if (value && !field.multiple) {
            currentFileHtml = `
                <div class="current-file mt-2">
                    <small class="text-muted">Archivo actual: ${value}</small>
                </div>
            `;
        }
        
        return `
            <input type="file" 
                   class="form-control" 
                   id="${fieldId}" 
                   name="${field.name}${field.multiple ? '[]' : ''}" 
                   ${accept}
                   ${multiple}
                   ${isRequired} 
                   ${isDisabled}>
            ${currentFileHtml}
        `;
    }

    /**
     * Generar campo oculto
     */
    generateHiddenField(field, value) {
        return `<input type="hidden" name="${field.name}" value="${this.escapeHtml(value)}">`;
    }

    /**
     * Generar campo personalizado
     */
    generateCustomField(field, value, isEditing) {
        if (field.render && typeof field.render === 'function') {
            return field.render(value, isEditing, field);
        }
        return `<div class="alert alert-warning">Campo personalizado no implementado: ${field.name}</div>`;
    }

    /**
     * Vincular eventos del formulario
     */
    bindFormEvents() {
        const form = document.getElementById(this.formId);
        if (!form) return;

        // Eventos de cambio en campos
        this.fields.forEach(field => {
            const fieldElement = form.querySelector(`[name="${field.name}"]`);
            if (fieldElement) {
                fieldElement.addEventListener('change', (e) => {
                    this.onFieldChange(field.name, e.target.value, field);
                    this.validateField(field.name);
                });

                fieldElement.addEventListener('blur', (e) => {
                    this.validateField(field.name);
                });
            }
        });
    }

    /**
     * Obtener datos del formulario
     */
    getFormData() {
        const form = document.getElementById(this.formId);
        if (!form) return null;

        const formData = new FormData(form);
        const data = {};

        // Procesar campos normales
        for (let [key, value] of formData.entries()) {
            if (data[key]) {
                if (Array.isArray(data[key])) {
                    data[key].push(value);
                } else {
                    data[key] = [data[key], value];
                }
            } else {
                data[key] = value;
            }
        }

        // Procesar checkboxes no marcados
        this.fields.forEach(field => {
            if (field.type === 'checkbox' && !data.hasOwnProperty(field.name)) {
                data[field.name] = false;
            }
        });

        return data;
    }

    /**
     * Establecer datos en el formulario
     */
    setFormData(data) {
        const form = document.getElementById(this.formId);
        if (!form) return;

        Object.keys(data).forEach(key => {
            const field = form.querySelector(`[name="${key}"]`);
            if (field) {
                if (field.type === 'checkbox') {
                    field.checked = !!data[key];
                } else if (field.type === 'radio') {
                    const radioField = form.querySelector(`[name="${key}"][value="${data[key]}"]`);
                    if (radioField) radioField.checked = true;
                } else {
                    field.value = data[key] || '';
                }
            }
        });
    }

    /**
     * Validar campo individual
     */
    validateField(fieldName) {
        const field = this.fields.find(f => f.name === fieldName);
        if (!field) return true;

        const fieldElement = document.querySelector(`[name="${fieldName}"]`);
        const errorElement = document.getElementById(`${fieldName}_field_error`);
        
        if (!fieldElement || !errorElement) return true;

        const value = fieldElement.value;
        const errors = [];

        // Validación requerido
        if (field.required && (!value || value.trim() === '')) {
            errors.push(`${field.label} es requerido`);
        }

        // Validaciones específicas por tipo
        if (value && value.trim() !== '') {
            switch (field.type) {
                case 'email':
                    if (!this.isValidEmail(value)) {
                        errors.push(`${field.label} debe ser un email válido`);
                    }
                    break;
                case 'url':
                    if (!this.isValidUrl(value)) {
                        errors.push(`${field.label} debe ser una URL válida`);
                    }
                    break;
                case 'number':
                    if (field.min !== undefined && parseFloat(value) < field.min) {
                        errors.push(`${field.label} debe ser mayor o igual a ${field.min}`);
                    }
                    if (field.max !== undefined && parseFloat(value) > field.max) {
                        errors.push(`${field.label} debe ser menor o igual a ${field.max}`);
                    }
                    break;
            }

            // Validación de longitud
            if (field.minlength && value.length < field.minlength) {
                errors.push(`${field.label} debe tener al menos ${field.minlength} caracteres`);
            }
            if (field.maxlength && value.length > field.maxlength) {
                errors.push(`${field.label} no puede tener más de ${field.maxlength} caracteres`);
            }

            // Validación de patrón
            if (field.pattern && !new RegExp(field.pattern).test(value)) {
                errors.push(field.patternMessage || `${field.label} no tiene el formato correcto`);
            }
        }

        // Mostrar/ocultar errores
        if (errors.length > 0) {
            fieldElement.classList.add('is-invalid');
            errorElement.textContent = errors[0];
            errorElement.style.display = 'block';
            return false;
        } else {
            fieldElement.classList.remove('is-invalid');
            errorElement.textContent = '';
            errorElement.style.display = 'none';
            return true;
        }
    }

    /**
     * Validar todo el formulario
     */
    validateForm() {
        let isValid = true;
        const errors = [];

        this.fields.forEach(field => {
            if (!this.validateField(field.name)) {
                isValid = false;
                errors.push(field.name);
            }
        });

        this.onValidation(isValid, errors);
        return isValid;
    }

    /**
     * Limpiar formulario
     */
    clearForm() {
        const form = document.getElementById(this.formId);
        if (form) {
            form.reset();
            // Limpiar errores
            form.querySelectorAll('.is-invalid').forEach(field => {
                field.classList.remove('is-invalid');
            });
            form.querySelectorAll('.invalid-feedback').forEach(error => {
                error.textContent = '';
                error.style.display = 'none';
            });
        }
    }

    /**
     * Validaciones auxiliares
     */
    isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    isValidUrl(url) {
        try {
            new URL(url);
            return true;
        } catch {
            return false;
        }
    }

    /**
     * Escapar HTML
     */
    escapeHtml(text) {
        if (typeof text !== 'string') return text;
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    /**
     * Actualizar configuración de campos
     */
    updateFields(newFields) {
        this.fields = newFields;
    }

    /**
     * Obtener configuración de campo
     */
    getField(fieldName) {
        return this.fields.find(field => field.name === fieldName);
    }
}