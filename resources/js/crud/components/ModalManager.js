/**
 * ModalManager - Manejo de modales con SweetAlert2
 * Centraliza la lógica de modales del sistema CRUD
 */
export class ModalManager {
    constructor(config = {}) {
        this.config = {
            width: "800px",
            showCloseButton: true,
            showCancelButton: true,
            confirmButtonText: "Guardar",
            cancelButtonText: "Cancelar",
            customClass: {
                container: "swal-modal-container",
                popup: "swal-modal-popup",
                header: "swal-modal-header",
                title: "swal-modal-title",
                content: "swal-modal-content",
                actions: "swal-modal-actions",
                confirmButton: "swal-confirm-button",
                cancelButton: "swal-cancel-button"
            },
            ...config
        };
    }

    /**
     * Mostrar modal de formulario
     */
    async showFormModal(title, htmlContent, options = {}) {
        const modalConfig = {
            title: title,
            html: htmlContent,
            width: this.config.width,
            showCloseButton: this.config.showCloseButton,
            showCancelButton: this.config.showCancelButton,
            confirmButtonText: this.config.confirmButtonText,
            cancelButtonText: this.config.cancelButtonText,
            customClass: this.config.customClass,
            focusConfirm: false,
            preConfirm: options.preConfirm || (() => true),
            didOpen: options.didOpen,
            willClose: options.willClose,
            ...options
        };

        return await Swal.fire(modalConfig);
    }

    /**
     * Mostrar modal de confirmación
     */
    async showConfirmModal(title, text, options = {}) {
        const modalConfig = {
            title: title,
            text: text,
            icon: options.icon || 'warning',
            showCancelButton: true,
            confirmButtonText: options.confirmButtonText || 'Sí, confirmar',
            cancelButtonText: options.cancelButtonText || 'Cancelar',
            confirmButtonColor: options.confirmButtonColor || '#d33',
            cancelButtonColor: options.cancelButtonColor || '#3085d6',
            ...options
        };

        return await Swal.fire(modalConfig);
    }

    /**
     * Mostrar modal de éxito
     */
    async showSuccessModal(title, text, options = {}) {
        const modalConfig = {
            title: title,
            text: text,
            icon: 'success',
            confirmButtonText: 'Aceptar',
            timer: options.timer || 3000,
            timerProgressBar: true,
            ...options
        };

        return await Swal.fire(modalConfig);
    }

    /**
     * Mostrar modal de error
     */
    async showErrorModal(title, text, options = {}) {
        const modalConfig = {
            title: title,
            text: text,
            icon: 'error',
            confirmButtonText: 'Aceptar',
            ...options
        };

        return await Swal.fire(modalConfig);
    }

    /**
     * Mostrar modal de información
     */
    async showInfoModal(title, text, options = {}) {
        const modalConfig = {
            title: title,
            text: text,
            icon: 'info',
            confirmButtonText: 'Aceptar',
            ...options
        };

        return await Swal.fire(modalConfig);
    }

    /**
     * Mostrar modal de carga
     */
    showLoadingModal(title = 'Procesando...', text = 'Por favor espere') {
        Swal.fire({
            title: title,
            text: text,
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    }

    /**
     * Cerrar modal actual
     */
    closeModal() {
        Swal.close();
    }

    /**
     * Verificar si hay un modal abierto
     */
    isModalOpen() {
        return Swal.isVisible();
    }

    /**
     * Mostrar toast notification
     */
    showToast(message, type = 'success', options = {}) {
        const Toast = Swal.mixin({
            toast: true,
            position: options.position || 'top-end',
            showConfirmButton: false,
            timer: options.timer || 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });

        Toast.fire({
            icon: type,
            title: message
        });
    }

    /**
     * Actualizar configuración del modal
     */
    updateConfig(newConfig) {
        this.config = {
            ...this.config,
            ...newConfig
        };
    }

    /**
     * Obtener configuración actual
     */
    getConfig() {
        return { ...this.config };
    }

    /**
     * Validar formulario dentro del modal
     */
    validateModalForm(formSelector = '#modalForm') {
        const form = document.querySelector(formSelector);
        if (!form) return { valid: false, errors: ['Formulario no encontrado'] };

        const errors = [];
        const requiredFields = form.querySelectorAll('[required]');

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                errors.push(`El campo ${field.name || field.id} es requerido`);
                field.classList.add('is-invalid');
            } else {
                field.classList.remove('is-invalid');
            }
        });

        return {
            valid: errors.length === 0,
            errors: errors
        };
    }

    /**
     * Obtener datos del formulario del modal
     */
    getModalFormData(formSelector = '#modalForm') {
        const form = document.querySelector(formSelector);
        if (!form) return null;

        const formData = new FormData(form);
        const data = {};

        for (let [key, value] of formData.entries()) {
            // Manejar checkboxes múltiples
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

        // Manejar checkboxes no marcados
        const checkboxes = form.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            if (!checkbox.checked && !data.hasOwnProperty(checkbox.name)) {
                data[checkbox.name] = false;
            }
        });

        return data;
    }
}