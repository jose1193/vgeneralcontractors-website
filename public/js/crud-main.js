// Punto de entrada principal para el sistema CRUD modular
import { CrudManager } from "./crud-system/index.js";

class CrudMain extends CrudManager {
    constructor(options) {
        super(options);
        if (typeof this.init === "function") this.init();
    }
    // Métodos públicos para retrocompatibilidad
    loadEntities(page = 1) {
        return super.loadEntities(page);
    }
    showCreateModal() {
        return super.showCreateModal();
    }
    showEditModal(id) {
        return super.showEditModal(id);
    }
    // ...otros métodos públicos que quieras exponer
}

// Retrocompatibilidad global
window.CrudManagerModal = CrudMain;
export default CrudMain;
