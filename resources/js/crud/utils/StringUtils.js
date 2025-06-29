/**
 * StringUtils - Utilidades para manipulación de strings
 * Funciones auxiliares para trabajar con cadenas de texto
 */
export class StringUtils {
    /**
     * Capitalizar primera letra
     */
    static capitalize(str) {
        if (!str || typeof str !== 'string') return str;
        return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();
    }

    /**
     * Capitalizar cada palabra
     */
    static capitalizeWords(str) {
        if (!str || typeof str !== 'string') return str;
        return str.replace(/\b\w/g, char => char.toUpperCase());
    }

    /**
     * Convertir a camelCase
     */
    static toCamelCase(str) {
        if (!str || typeof str !== 'string') return str;
        return str
            .replace(/(?:^\w|[A-Z]|\b\w)/g, (word, index) => {
                return index === 0 ? word.toLowerCase() : word.toUpperCase();
            })
            .replace(/\s+/g, '');
    }

    /**
     * Convertir a PascalCase
     */
    static toPascalCase(str) {
        if (!str || typeof str !== 'string') return str;
        return str
            .replace(/(?:^\w|[A-Z]|\b\w)/g, word => word.toUpperCase())
            .replace(/\s+/g, '');
    }

    /**
     * Convertir a snake_case
     */
    static toSnakeCase(str) {
        if (!str || typeof str !== 'string') return str;
        return str
            .replace(/\W+/g, ' ')
            .split(/ |\s/)
            .map(word => word.toLowerCase())
            .join('_');
    }

    /**
     * Convertir a kebab-case
     */
    static toKebabCase(str) {
        if (!str || typeof str !== 'string') return str;
        return str
            .replace(/\W+/g, ' ')
            .split(/ |\s/)
            .map(word => word.toLowerCase())
            .join('-');
    }

    /**
     * Truncar string con ellipsis
     */
    static truncate(str, length = 100, suffix = '...') {
        if (!str || typeof str !== 'string') return str;
        if (str.length <= length) return str;
        return str.substring(0, length - suffix.length) + suffix;
    }

    /**
     * Truncar por palabras
     */
    static truncateWords(str, wordCount = 10, suffix = '...') {
        if (!str || typeof str !== 'string') return str;
        const words = str.split(/\s+/);
        if (words.length <= wordCount) return str;
        return words.slice(0, wordCount).join(' ') + suffix;
    }

    /**
     * Limpiar espacios extra
     */
    static cleanSpaces(str) {
        if (!str || typeof str !== 'string') return str;
        return str.replace(/\s+/g, ' ').trim();
    }

    /**
     * Remover acentos
     */
    static removeAccents(str) {
        if (!str || typeof str !== 'string') return str;
        return str.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
    }

    /**
     * Generar slug
     */
    static slug(str) {
        if (!str || typeof str !== 'string') return str;
        return this.removeAccents(str)
            .toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .replace(/^-|-$/g, '');
    }

    /**
     * Verificar si string está vacío
     */
    static isEmpty(str) {
        return !str || typeof str !== 'string' || str.trim().length === 0;
    }

    /**
     * Verificar si string no está vacío
     */
    static isNotEmpty(str) {
        return !this.isEmpty(str);
    }

    /**
     * Contar palabras
     */
    static wordCount(str) {
        if (!str || typeof str !== 'string') return 0;
        return str.trim().split(/\s+/).filter(word => word.length > 0).length;
    }

    /**
     * Contar caracteres sin espacios
     */
    static charCount(str, includeSpaces = true) {
        if (!str || typeof str !== 'string') return 0;
        return includeSpaces ? str.length : str.replace(/\s/g, '').length;
    }

    /**
     * Repetir string
     */
    static repeat(str, count) {
        if (!str || typeof str !== 'string' || count < 1) return '';
        return str.repeat(count);
    }

    /**
     * Padding izquierdo
     */
    static padLeft(str, length, char = ' ') {
        if (!str) str = '';
        str = String(str);
        return str.padStart(length, char);
    }

    /**
     * Padding derecho
     */
    static padRight(str, length, char = ' ') {
        if (!str) str = '';
        str = String(str);
        return str.padEnd(length, char);
    }

    /**
     * Revertir string
     */
    static reverse(str) {
        if (!str || typeof str !== 'string') return str;
        return str.split('').reverse().join('');
    }

    /**
     * Verificar si contiene substring
     */
    static contains(str, substring, caseSensitive = true) {
        if (!str || !substring) return false;
        if (!caseSensitive) {
            str = str.toLowerCase();
            substring = substring.toLowerCase();
        }
        return str.includes(substring);
    }

    /**
     * Verificar si empieza con substring
     */
    static startsWith(str, substring, caseSensitive = true) {
        if (!str || !substring) return false;
        if (!caseSensitive) {
            str = str.toLowerCase();
            substring = substring.toLowerCase();
        }
        return str.startsWith(substring);
    }

    /**
     * Verificar si termina con substring
     */
    static endsWith(str, substring, caseSensitive = true) {
        if (!str || !substring) return false;
        if (!caseSensitive) {
            str = str.toLowerCase();
            substring = substring.toLowerCase();
        }
        return str.endsWith(substring);
    }

    /**
     * Extraer números de string
     */
    static extractNumbers(str) {
        if (!str || typeof str !== 'string') return [];
        const matches = str.match(/\d+/g);
        return matches ? matches.map(Number) : [];
    }

    /**
     * Extraer emails de string
     */
    static extractEmails(str) {
        if (!str || typeof str !== 'string') return [];
        const emailRegex = /\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Z|a-z]{2,}\b/g;
        return str.match(emailRegex) || [];
    }

    /**
     * Extraer URLs de string
     */
    static extractUrls(str) {
        if (!str || typeof str !== 'string') return [];
        const urlRegex = /https?:\/\/(www\.)?[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%_\+.~#?&//=]*)/g;
        return str.match(urlRegex) || [];
    }

    /**
     * Reemplazar múltiples valores
     */
    static replaceMultiple(str, replacements) {
        if (!str || typeof str !== 'string' || !replacements) return str;
        
        let result = str;
        Object.keys(replacements).forEach(key => {
            result = result.replace(new RegExp(key, 'g'), replacements[key]);
        });
        
        return result;
    }

    /**
     * Formatear template con variables
     */
    static template(str, variables = {}) {
        if (!str || typeof str !== 'string') return str;
        
        return str.replace(/\{\{\s*(\w+)\s*\}\}/g, (match, key) => {
            return variables.hasOwnProperty(key) ? variables[key] : match;
        });
    }

    /**
     * Generar string aleatorio
     */
    static random(length = 10, chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789') {
        let result = '';
        for (let i = 0; i < length; i++) {
            result += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        return result;
    }

    /**
     * Generar UUID simple
     */
    static uuid() {
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
            const r = Math.random() * 16 | 0;
            const v = c === 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        });
    }

    /**
     * Validar email
     */
    static isValidEmail(str) {
        if (!str || typeof str !== 'string') return false;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(str);
    }

    /**
     * Validar URL
     */
    static isValidUrl(str) {
        if (!str || typeof str !== 'string') return false;
        try {
            new URL(str);
            return true;
        } catch {
            return false;
        }
    }

    /**
     * Validar número
     */
    static isNumeric(str) {
        if (!str) return false;
        return !isNaN(str) && !isNaN(parseFloat(str));
    }

    /**
     * Formatear número con separadores
     */
    static formatNumber(num, decimals = 2, thousandSep = ',', decimalSep = '.') {
        if (!this.isNumeric(num)) return num;
        
        const number = parseFloat(num).toFixed(decimals);
        const parts = number.split('.');
        
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousandSep);
        
        return parts.join(decimalSep);
    }

    /**
     * Formatear como moneda
     */
    static formatCurrency(amount, currency = 'USD', locale = 'en-US') {
        if (!this.isNumeric(amount)) return amount;
        
        return new Intl.NumberFormat(locale, {
            style: 'currency',
            currency: currency
        }).format(amount);
    }

    /**
     * Formatear porcentaje
     */
    static formatPercentage(value, decimals = 2) {
        if (!this.isNumeric(value)) return value;
        return (parseFloat(value) * 100).toFixed(decimals) + '%';
    }

    /**
     * Escapar caracteres especiales para regex
     */
    static escapeRegex(str) {
        if (!str || typeof str !== 'string') return str;
        return str.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    }

    /**
     * Comparar strings ignorando case y acentos
     */
    static compare(str1, str2, options = {}) {
        if (!str1 || !str2) return str1 === str2;
        
        let s1 = str1;
        let s2 = str2;
        
        if (!options.caseSensitive) {
            s1 = s1.toLowerCase();
            s2 = s2.toLowerCase();
        }
        
        if (options.ignoreAccents) {
            s1 = this.removeAccents(s1);
            s2 = this.removeAccents(s2);
        }
        
        return s1 === s2;
    }

    /**
     * Calcular similitud entre strings (algoritmo simple)
     */
    static similarity(str1, str2) {
        if (!str1 || !str2) return 0;
        if (str1 === str2) return 1;
        
        const longer = str1.length > str2.length ? str1 : str2;
        const shorter = str1.length > str2.length ? str2 : str1;
        
        if (longer.length === 0) return 1;
        
        const distance = this.levenshteinDistance(longer, shorter);
        return (longer.length - distance) / longer.length;
    }

    /**
     * Calcular distancia de Levenshtein
     */
    static levenshteinDistance(str1, str2) {
        const matrix = [];
        
        for (let i = 0; i <= str2.length; i++) {
            matrix[i] = [i];
        }
        
        for (let j = 0; j <= str1.length; j++) {
            matrix[0][j] = j;
        }
        
        for (let i = 1; i <= str2.length; i++) {
            for (let j = 1; j <= str1.length; j++) {
                if (str2.charAt(i - 1) === str1.charAt(j - 1)) {
                    matrix[i][j] = matrix[i - 1][j - 1];
                } else {
                    matrix[i][j] = Math.min(
                        matrix[i - 1][j - 1] + 1,
                        matrix[i][j - 1] + 1,
                        matrix[i - 1][j] + 1
                    );
                }
            }
        }
        
        return matrix[str2.length][str1.length];
    }
}