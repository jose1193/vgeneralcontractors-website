// Funciones utilitarias generales para el sistema CRUD
export const CrudUtils = {
    debounce(func, wait) {
        let timeout;
        return function (...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    },
    // Puedes agregar más utilidades aquí (formateo, helpers, etc.)
};
