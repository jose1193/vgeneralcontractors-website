/**
 * DomUtils - Utilidades para manipulación del DOM
 * Funciones auxiliares para trabajar con elementos DOM
 */
export class DomUtils {
    /**
     * Seleccionar elemento por selector
     */
    static select(selector, context = document) {
        return context.querySelector(selector);
    }

    /**
     * Seleccionar múltiples elementos
     */
    static selectAll(selector, context = document) {
        return Array.from(context.querySelectorAll(selector));
    }

    /**
     * Crear elemento con atributos y contenido
     */
    static createElement(tag, attributes = {}, content = '') {
        const element = document.createElement(tag);
        
        // Establecer atributos
        Object.keys(attributes).forEach(key => {
            if (key === 'className') {
                element.className = attributes[key];
            } else if (key === 'innerHTML') {
                element.innerHTML = attributes[key];
            } else if (key === 'textContent') {
                element.textContent = attributes[key];
            } else {
                element.setAttribute(key, attributes[key]);
            }
        });
        
        // Establecer contenido
        if (content) {
            if (typeof content === 'string') {
                element.innerHTML = content;
            } else if (content instanceof Node) {
                element.appendChild(content);
            }
        }
        
        return element;
    }

    /**
     * Agregar clase a elemento
     */
    static addClass(element, className) {
        if (element && className) {
            element.classList.add(className);
        }
    }

    /**
     * Remover clase de elemento
     */
    static removeClass(element, className) {
        if (element && className) {
            element.classList.remove(className);
        }
    }

    /**
     * Alternar clase en elemento
     */
    static toggleClass(element, className) {
        if (element && className) {
            element.classList.toggle(className);
        }
    }

    /**
     * Verificar si elemento tiene clase
     */
    static hasClass(element, className) {
        return element && className && element.classList.contains(className);
    }

    /**
     * Mostrar elemento
     */
    static show(element, display = 'block') {
        if (element) {
            element.style.display = display;
        }
    }

    /**
     * Ocultar elemento
     */
    static hide(element) {
        if (element) {
            element.style.display = 'none';
        }
    }

    /**
     * Alternar visibilidad de elemento
     */
    static toggle(element, display = 'block') {
        if (element) {
            if (element.style.display === 'none') {
                this.show(element, display);
            } else {
                this.hide(element);
            }
        }
    }

    /**
     * Verificar si elemento es visible
     */
    static isVisible(element) {
        if (!element) return false;
        return element.offsetWidth > 0 && element.offsetHeight > 0;
    }

    /**
     * Obtener datos de atributo data-*
     */
    static getData(element, key) {
        if (!element) return null;
        return element.dataset[key] || element.getAttribute(`data-${key}`);
    }

    /**
     * Establecer datos en atributo data-*
     */
    static setData(element, key, value) {
        if (element) {
            element.dataset[key] = value;
        }
    }

    /**
     * Remover elemento del DOM
     */
    static remove(element) {
        if (element && element.parentNode) {
            element.parentNode.removeChild(element);
        }
    }

    /**
     * Vaciar contenido de elemento
     */
    static empty(element) {
        if (element) {
            element.innerHTML = '';
        }
    }

    /**
     * Insertar HTML en elemento
     */
    static insertHTML(element, html, position = 'beforeend') {
        if (element && html) {
            element.insertAdjacentHTML(position, html);
        }
    }

    /**
     * Obtener posición de elemento
     */
    static getPosition(element) {
        if (!element) return { top: 0, left: 0 };
        const rect = element.getBoundingClientRect();
        return {
            top: rect.top + window.pageYOffset,
            left: rect.left + window.pageXOffset,
            width: rect.width,
            height: rect.height
        };
    }

    /**
     * Hacer scroll a elemento
     */
    static scrollTo(element, options = {}) {
        if (element) {
            element.scrollIntoView({
                behavior: options.behavior || 'smooth',
                block: options.block || 'start',
                inline: options.inline || 'nearest'
            });
        }
    }

    /**
     * Agregar event listener con delegación
     */
    static delegate(parent, selector, event, handler) {
        if (!parent) return;
        
        parent.addEventListener(event, function(e) {
            const target = e.target.closest(selector);
            if (target) {
                handler.call(target, e);
            }
        });
    }

    /**
     * Remover todos los event listeners de un elemento
     */
    static removeAllListeners(element) {
        if (element) {
            const newElement = element.cloneNode(true);
            element.parentNode.replaceChild(newElement, element);
            return newElement;
        }
        return null;
    }

    /**
     * Verificar si elemento está en viewport
     */
    static isInViewport(element) {
        if (!element) return false;
        
        const rect = element.getBoundingClientRect();
        return (
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
            rect.right <= (window.innerWidth || document.documentElement.clientWidth)
        );
    }

    /**
     * Obtener elemento padre más cercano que coincida con selector
     */
    static closest(element, selector) {
        if (!element) return null;
        return element.closest(selector);
    }

    /**
     * Obtener hermanos de un elemento
     */
    static siblings(element) {
        if (!element || !element.parentNode) return [];
        return Array.from(element.parentNode.children).filter(child => child !== element);
    }

    /**
     * Obtener siguiente hermano
     */
    static next(element) {
        return element ? element.nextElementSibling : null;
    }

    /**
     * Obtener hermano anterior
     */
    static prev(element) {
        return element ? element.previousElementSibling : null;
    }

    /**
     * Clonar elemento
     */
    static clone(element, deep = true) {
        return element ? element.cloneNode(deep) : null;
    }

    /**
     * Obtener valor de elemento de formulario
     */
    static getValue(element) {
        if (!element) return null;
        
        switch (element.type) {
            case 'checkbox':
                return element.checked;
            case 'radio':
                return element.checked ? element.value : null;
            case 'select-multiple':
                return Array.from(element.selectedOptions).map(option => option.value);
            default:
                return element.value;
        }
    }

    /**
     * Establecer valor en elemento de formulario
     */
    static setValue(element, value) {
        if (!element) return;
        
        switch (element.type) {
            case 'checkbox':
                element.checked = !!value;
                break;
            case 'radio':
                element.checked = element.value === value;
                break;
            case 'select-multiple':
                const values = Array.isArray(value) ? value : [value];
                Array.from(element.options).forEach(option => {
                    option.selected = values.includes(option.value);
                });
                break;
            default:
                element.value = value || '';
        }
    }

    /**
     * Serializar formulario a objeto
     */
    static serializeForm(form) {
        if (!form) return {};
        
        const formData = new FormData(form);
        const data = {};
        
        for (let [key, value] of formData.entries()) {
            if (data[key]) {
                if (Array.isArray(data[key])) {
                    data[key].push(value);
                } else {
                    data[key] = [data[key], value];
                }
            } else {
                data[key] = value;
            }
        }
        
        return data;
    }

    /**
     * Escapar HTML
     */
    static escapeHtml(text) {
        if (typeof text !== 'string') return text;
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    /**
     * Parsear HTML string a elementos
     */
    static parseHTML(htmlString) {
        const template = document.createElement('template');
        template.innerHTML = htmlString.trim();
        return template.content.children;
    }

    /**
     * Verificar si elemento tiene foco
     */
    static hasFocus(element) {
        return element && document.activeElement === element;
    }

    /**
     * Establecer foco en elemento
     */
    static focus(element, options = {}) {
        if (element) {
            element.focus(options);
        }
    }

    /**
     * Quitar foco de elemento
     */
    static blur(element) {
        if (element) {
            element.blur();
        }
    }

    /**
     * Esperar a que el DOM esté listo
     */
    static ready(callback) {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', callback);
        } else {
            callback();
        }
    }

    /**
     * Crear observer para cambios en el DOM
     */
    static observe(element, callback, options = {}) {
        if (!element || typeof callback !== 'function') return null;
        
        const observer = new MutationObserver(callback);
        observer.observe(element, {
            childList: true,
            subtree: true,
            attributes: true,
            ...options
        });
        
        return observer;
    }
}