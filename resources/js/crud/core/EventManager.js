/**
 * EventManager - Sistema de eventos personalizado
 * Permite comunicación entre componentes del sistema CRUD
 */
export class EventManager {
    constructor() {
        this.events = new Map();
    }

    /**
     * Registrar un listener para un evento
     */
    on(eventName, callback) {
        if (!this.events.has(eventName)) {
            this.events.set(eventName, []);
        }
        this.events.get(eventName).push(callback);
    }

    /**
     * Registrar un listener que se ejecuta solo una vez
     */
    once(eventName, callback) {
        const onceCallback = (...args) => {
            callback(...args);
            this.off(eventName, onceCallback);
        };
        this.on(eventName, onceCallback);
    }

    /**
     * Remover un listener específico
     */
    off(eventName, callback) {
        if (!this.events.has(eventName)) return;
        
        const listeners = this.events.get(eventName);
        const index = listeners.indexOf(callback);
        if (index > -1) {
            listeners.splice(index, 1);
        }
    }

    /**
     * Remover todos los listeners de un evento
     */
    removeAllListeners(eventName) {
        if (eventName) {
            this.events.delete(eventName);
        } else {
            this.events.clear();
        }
    }

    /**
     * Emitir un evento
     */
    emit(eventName, ...args) {
        if (!this.events.has(eventName)) return;
        
        const listeners = this.events.get(eventName);
        listeners.forEach(callback => {
            try {
                callback(...args);
            } catch (error) {
                console.error(`Error in event listener for '${eventName}':`, error);
            }
        });
    }

    /**
     * Verificar si hay listeners para un evento
     */
    hasListeners(eventName) {
        return this.events.has(eventName) && this.events.get(eventName).length > 0;
    }

    /**
     * Obtener el número de listeners para un evento
     */
    listenerCount(eventName) {
        return this.events.has(eventName) ? this.events.get(eventName).length : 0;
    }

    /**
     * Obtener todos los nombres de eventos registrados
     */
    eventNames() {
        return Array.from(this.events.keys());
    }
}