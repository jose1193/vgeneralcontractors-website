// Gestión de eventos y listeners
export class CrudEventHandler {
    constructor(manager) {
        this.manager = manager; // Referencia a CrudManager para llamar métodos
    }

    attachEventListeners() {
        // Ejemplo: paginación
        document.addEventListener("click", (e) => {
            if (e.target.classList.contains("pagination-btn")) {
                const page = parseInt(e.target.getAttribute("data-page"));
                if (!isNaN(page)) {
                    this.manager.loadEntities(page);
                }
            }
        });

        // Puedes agregar más listeners para crear, editar, eliminar, etc.
    }

    handleFormSubmit(event) {
        event.preventDefault();
        // Lógica para manejar el submit del formulario
        // Puedes usar this.manager.apiClient.createEntity o updateEntity
    }

    handleTableActions(event) {
        // Lógica para manejar clicks en la tabla (editar, eliminar, restaurar)
    }
}
