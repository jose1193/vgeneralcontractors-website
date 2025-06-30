// Comunicación con APIs (fetch, create, update, delete, restore)
export class CrudApiClient {
    constructor(routes) {
        this.routes = routes;
    }

    async fetchEntities(params = {}) {
        const url = new URL(this.routes.index, window.location.origin);
        Object.keys(params).forEach((key) =>
            url.searchParams.append(key, params[key])
        );
        const response = await fetch(url, {
            method: "GET",
            headers: {
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
            },
            credentials: "same-origin",
        });
        return this._handleResponse(response);
    }

    async createEntity(data) {
        const response = await fetch(this.routes.store, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
            },
            body: JSON.stringify(data),
            credentials: "same-origin",
        });
        return this._handleResponse(response);
    }

    async updateEntity(id, data) {
        const url = this.routes.update.replace(":id", id);
        const response = await fetch(url, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
            },
            body: JSON.stringify(data),
            credentials: "same-origin",
        });
        return this._handleResponse(response);
    }

    async deleteEntity(id) {
        const url = this.routes.destroy.replace(":id", id);
        const response = await fetch(url, {
            method: "DELETE",
            headers: {
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
            },
            credentials: "same-origin",
        });
        return this._handleResponse(response);
    }

    async restoreEntity(id) {
        const url = this.routes.restore.replace(":id", id);
        const response = await fetch(url, {
            method: "POST",
            headers: {
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
            },
            credentials: "same-origin",
        });
        return this._handleResponse(response);
    }

    async _handleResponse(response) {
        // Si la respuesta no es JSON, lanza error con el texto recibido (probablemente HTML)
        const contentType = response.headers.get("content-type");
        if (!contentType || !contentType.includes("application/json")) {
            const text = await response.text();
            throw new Error(
                "Respuesta inesperada del servidor:\n" + text.substring(0, 200)
            );
        }
        if (!response.ok) {
            const error = await response.json();
            throw new Error(error.message || "Error en la petición");
        }
        return await response.json();
    }
}
