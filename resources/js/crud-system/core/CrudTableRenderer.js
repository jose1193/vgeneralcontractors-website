// Renderizado de tablas y paginación
export class CrudTableRenderer {
    constructor(tableHeaders) {
        this.tableHeaders = tableHeaders;
        this.tableSelector = "#crud-table-body"; // Puedes parametrizar esto
        this.paginationSelector = "#pagination"; // Puedes parametrizar esto
    }

    renderTable(data) {
        const tbody = document.querySelector(this.tableSelector);
        if (!tbody) return;
        tbody.innerHTML = "";
        if (!data || !data.data || data.data.length === 0) {
            tbody.innerHTML = `<tr><td colspan="${this.tableHeaders.length}" class="text-center">No records found</td></tr>`;
            return;
        }
        data.data.forEach((entity) => {
            const row = document.createElement("tr");
            this.tableHeaders.forEach((header) => {
                const td = document.createElement("td");
                td.innerHTML =
                    entity[header.field] !== undefined
                        ? entity[header.field]
                        : "";
                row.appendChild(td);
            });
            tbody.appendChild(row);
        });
    }

    renderPagination(data) {
        const container = document.querySelector(this.paginationSelector);
        if (!container) return;
        if (!data || data.last_page <= 1) {
            container.innerHTML = "";
            return;
        }
        let html = `<div class="flex items-center justify-between">`;
        html += `<div class="text-sm text-gray-700">Mostrando ${data.from} a ${data.to} de ${data.total} resultados</div>`;
        html += `<div class="flex space-x-1">`;
        if (data.current_page > 1) {
            html += `<button class="pagination-btn" data-page="${
                data.current_page - 1
            }">Anterior</button>`;
        }
        for (let i = 1; i <= data.last_page; i++) {
            html += `<button class="pagination-btn${
                i === data.current_page ? " font-bold" : ""
            }" data-page="${i}">${i}</button>`;
        }
        if (data.current_page < data.last_page) {
            html += `<button class="pagination-btn" data-page="${
                data.current_page + 1
            }">Siguiente</button>`;
        }
        html += `</div></div>`;
        container.innerHTML = html;
    }

    renderTableHeaders() {
        // Si necesitas renderizar los headers dinámicamente
    }
}
