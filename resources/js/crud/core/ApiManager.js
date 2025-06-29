/**
 * ApiManager - Manejo de comunicación HTTP con Laravel
 * Centraliza todas las llamadas AJAX del sistema CRUD
 */
export class ApiManager {
    constructor(routes) {
        this.routes = routes || {};
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    }

    /**
     * Realizar petición HTTP genérica
     */
    async request(url, options = {}) {
        const defaultOptions = {
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': this.csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            }
        };

        const config = {
            ...defaultOptions,
            ...options,
            headers: {
                ...defaultOptions.headers,
                ...options.headers
            }
        };

        try {
            const response = await fetch(url, config);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            return await response.json();
        } catch (error) {
            console.error('API Request failed:', error);
            throw error;
        }
    }

    /**
     * Obtener lista de entidades con paginación
     */
    async getEntities(params = {}) {
        const url = new URL(this.routes.index);
        Object.keys(params).forEach(key => {
            if (params[key] !== null && params[key] !== undefined) {
                url.searchParams.append(key, params[key]);
            }
        });

        return await this.request(url.toString(), {
            method: 'GET'
        });
    }

    /**
     * Crear nueva entidad
     */
    async createEntity(data) {
        return await this.request(this.routes.store, {
            method: 'POST',
            body: JSON.stringify(data)
        });
    }

    /**
     * Actualizar entidad existente
     */
    async updateEntity(id, data) {
        const url = this.routes.update.replace(':id', id);
        return await this.request(url, {
            method: 'PUT',
            body: JSON.stringify(data)
        });
    }

    /**
     * Eliminar entidad
     */
    async deleteEntity(id) {
        const url = this.routes.destroy.replace(':id', id);
        return await this.request(url, {
            method: 'DELETE'
        });
    }

    /**
     * Validación en tiempo real con servidor
     */
    async validateField(field, value, entityId = null) {
        if (!this.routes.validate) return { valid: true };

        const data = {
            field: field,
            value: value
        };

        if (entityId) {
            data.entity_id = entityId;
        }

        return await this.request(this.routes.validate, {
            method: 'POST',
            body: JSON.stringify(data)
        });
    }

    /**
     * Verificar unicidad de campo
     */
    async checkUnique(field, value, entityId = null) {
        if (!this.routes.checkUnique) return { unique: true };

        const data = {
            field: field,
            value: value
        };

        if (entityId) {
            data.entity_id = entityId;
        }

        return await this.request(this.routes.checkUnique, {
            method: 'POST',
            body: JSON.stringify(data)
        });
    }

    /**
     * Obtener opciones para select dinámicos
     */
    async getSelectOptions(endpoint, params = {}) {
        const url = new URL(endpoint);
        Object.keys(params).forEach(key => {
            url.searchParams.append(key, params[key]);
        });

        return await this.request(url.toString(), {
            method: 'GET'
        });
    }
}