// Validación de datos y validaciones únicas
export class CrudValidator {
    constructor(formFields) {
        this.formFields = formFields;
    }

    validateField(field, value) {
        if (field.required && !value) {
            return {
                valid: false,
                message: `${field.label || field.name} es requerido`,
            };
        }
        if (field.minLength && value.length < field.minLength) {
            return {
                valid: false,
                message: `Mínimo ${field.minLength} caracteres`,
            };
        }
        if (field.maxLength && value.length > field.maxLength) {
            return {
                valid: false,
                message: `Máximo ${field.maxLength} caracteres`,
            };
        }
        if (
            field.type === "email" &&
            value &&
            !/^[^@]+@[^@]+\.[^@]+$/.test(value)
        ) {
            return { valid: false, message: "Email inválido" };
        }
        // Puedes agregar más validaciones según tus necesidades
        return { valid: true, message: "" };
    }

    validateForm(formData) {
        const errors = {};
        let valid = true;
        this.formFields.forEach((field) => {
            const value = formData[field.name];
            const result = this.validateField(field, value);
            if (!result.valid) {
                valid = false;
                errors[field.name] = result.message;
            }
        });
        return { valid, errors };
    }

    setupRealTimeValidation(formElement) {
        this.formFields.forEach((field) => {
            const input = formElement.querySelector(`[name="${field.name}"]`);
            if (input) {
                input.addEventListener("input", () => {
                    const result = this.validateField(field, input.value);
                    if (!result.valid) {
                        input.classList.add("border-red-500");
                        // Puedes mostrar el mensaje de error en el DOM
                    } else {
                        input.classList.remove("border-red-500");
                    }
                });
            }
        });
    }
}
