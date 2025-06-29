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
        const response = await fetch(url, { credentials: "same-origin" });
        if (!response.ok) throw new Error("Error fetching entities");
        return await response.json();
    }

    async createEntity(data) {
        const response = await fetch(this.routes.store, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-Requested-With": "XMLHttpRequest",
            },
            body: JSON.stringify(data),
            credentials: "same-origin",
        });
        if (!response.ok) throw new Error("Error creating entity");
        return await response.json();
    }

    async updateEntity(id, data) {
        const url = this.routes.update.replace(":id", id);
        const response = await fetch(url, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json",
                "X-Requested-With": "XMLHttpRequest",
            },
            body: JSON.stringify(data),
            credentials: "same-origin",
        });
        if (!response.ok) throw new Error("Error updating entity");
        return await response.json();
    }

    async deleteEntity(id) {
        const url = this.routes.destroy.replace(":id", id);
        const response = await fetch(url, {
            method: "DELETE",
            headers: { "X-Requested-With": "XMLHttpRequest" },
            credentials: "same-origin",
        });
        if (!response.ok) throw new Error("Error deleting entity");
        return await response.json();
    }

    async restoreEntity(id) {
        const url = this.routes.restore.replace(":id", id);
        const response = await fetch(url, {
            method: "POST",
            headers: { "X-Requested-With": "XMLHttpRequest" },
            credentials: "same-origin",
        });
        if (!response.ok) throw new Error("Error restoring entity");
        return await response.json();
    }
}
