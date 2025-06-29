// Gestión de modales y alertas
export class CrudModalManager {
    constructor() {}

    showModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) modal.classList.remove("hidden");
    }

    hideModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) modal.classList.add("hidden");
    }

    showAlert(type, message) {
        // Puedes implementar un sistema de alertas aquí
        alert(`${type.toUpperCase()}: ${message}`);
    }

    updateUI() {
        // Actualizar la UI si es necesario
    }
}
