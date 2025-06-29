import { CrudApiClient } from "./CrudApiClient.js";
import { CrudFormBuilder } from "./CrudFormBuilder.js";
import { CrudValidator } from "./CrudValidator.js";
import { CrudTableRenderer } from "./CrudTableRenderer.js";
import { CrudModalManager } from "./CrudModalManager.js";
import { CrudEventHandler } from "./CrudEventHandler.js";

export class CrudManager {
    constructor(options) {
        this.apiClient = new CrudApiClient(options.routes);
        this.formBuilder = new CrudFormBuilder(options.formFields);
        this.validator = new CrudValidator(options.formFields);
        this.tableRenderer = new CrudTableRenderer(options.tableHeaders);
        this.modalManager = new CrudModalManager();
        this.eventHandler = new CrudEventHandler(this);
        this.options = options;

        // Inicializar listeners
        this.eventHandler.attachEventListeners();
    }

    async loadEntities(page = 1) {
        try {
            const data = await this.apiClient.fetchEntities({ page });
            this.tableRenderer.renderTable(data);
            this.tableRenderer.renderPagination(data);
        } catch (e) {
            this.modalManager.showAlert("error", e.message);
        }
    }

    showCreateModal() {
        const formHtml = this.formBuilder.generateFormHtml();
        // Asume que tienes un modal con id "createModal" y un contenedor para el form
        const modal = document.getElementById("createModal");
        if (modal) {
            const formContainer = modal.querySelector(".form-container");
            if (formContainer) formContainer.innerHTML = formHtml;
        }
        this.modalManager.showModal("createModal");
    }

    showEditModal(id) {
        // Debes obtener la entidad por id, luego generar el form y mostrar el modal
        // Ejemplo:
        // const entity = ...;
        // const formHtml = this.formBuilder.generateFormHtml(entity);
        // ...
    }

    // ...otros métodos públicos...
}
