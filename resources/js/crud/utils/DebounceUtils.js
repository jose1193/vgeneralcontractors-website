/**
 * DebounceUtils - Utilidades para debounce y throttle
 * Funciones para controlar la frecuencia de ejecución de funciones
 */
export class DebounceUtils {
    /**
     * Debounce - Retrasa la ejecución hasta que no se llame por un tiempo determinado
     */
    static debounce(func, delay = 300) {
        let timeoutId;
        
        return function debounced(...args) {
            const context = this;
            
            clearTimeout(timeoutId);
            
            timeoutId = setTimeout(() => {
                func.apply(context, args);
            }, delay);
        };
    }

    /**
     * Throttle - Limita la ejecución a una vez por período de tiempo
     */
    static throttle(func, delay = 300) {
        let lastExecTime = 0;
        let timeoutId;
        
        return function throttled(...args) {
            const context = this;
            const currentTime = Date.now();
            
            if (currentTime - lastExecTime > delay) {
                func.apply(context, args);
                lastExecTime = currentTime;
            } else {
                clearTimeout(timeoutId);
                timeoutId = setTimeout(() => {
                    func.apply(context, args);
                    lastExecTime = Date.now();
                }, delay - (currentTime - lastExecTime));
            }
        };
    }

    /**
     * Debounce con cancelación
     */
    static debounceCancelable(func, delay = 300) {
        let timeoutId;
        
        const debounced = function(...args) {
            const context = this;
            
            clearTimeout(timeoutId);
            
            timeoutId = setTimeout(() => {
                func.apply(context, args);
            }, delay);
        };
        
        debounced.cancel = function() {
            clearTimeout(timeoutId);
        };
        
        debounced.flush = function(...args) {
            clearTimeout(timeoutId);
            func.apply(this, args);
        };
        
        return debounced;
    }

    /**
     * Throttle con cancelación
     */
    static throttleCancelable(func, delay = 300) {
        let lastExecTime = 0;
        let timeoutId;
        
        const throttled = function(...args) {
            const context = this;
            const currentTime = Date.now();
            
            if (currentTime - lastExecTime > delay) {
                func.apply(context, args);
                lastExecTime = currentTime;
            } else {
                clearTimeout(timeoutId);
                timeoutId = setTimeout(() => {
                    func.apply(context, args);
                    lastExecTime = Date.now();
                }, delay - (currentTime - lastExecTime));
            }
        };
        
        throttled.cancel = function() {
            clearTimeout(timeoutId);
        };
        
        throttled.flush = function(...args) {
            clearTimeout(timeoutId);
            func.apply(this, args);
            lastExecTime = Date.now();
        };
        
        return throttled;
    }

    /**
     * Debounce para promesas
     */
    static debouncePromise(func, delay = 300) {
        let timeoutId;
        let pendingPromise;
        
        return function(...args) {
            const context = this;
            
            return new Promise((resolve, reject) => {
                clearTimeout(timeoutId);
                
                if (pendingPromise) {
                    pendingPromise.reject(new Error('Debounced'));
                }
                
                pendingPromise = { resolve, reject };
                
                timeoutId = setTimeout(async () => {
                    try {
                        const result = await func.apply(context, args);
                        pendingPromise.resolve(result);
                    } catch (error) {
                        pendingPromise.reject(error);
                    } finally {
                        pendingPromise = null;
                    }
                }, delay);
            });
        };
    }

    /**
     * Throttle para promesas
     */
    static throttlePromise(func, delay = 300) {
        let lastExecTime = 0;
        let timeoutId;
        let pendingPromise;
        
        return function(...args) {
            const context = this;
            const currentTime = Date.now();
            
            return new Promise((resolve, reject) => {
                if (currentTime - lastExecTime > delay) {
                    lastExecTime = currentTime;
                    func.apply(context, args)
                        .then(resolve)
                        .catch(reject);
                } else {
                    clearTimeout(timeoutId);
                    
                    if (pendingPromise) {
                        pendingPromise.reject(new Error('Throttled'));
                    }
                    
                    pendingPromise = { resolve, reject };
                    
                    timeoutId = setTimeout(async () => {
                        try {
                            const result = await func.apply(context, args);
                            lastExecTime = Date.now();
                            pendingPromise.resolve(result);
                        } catch (error) {
                            pendingPromise.reject(error);
                        } finally {
                            pendingPromise = null;
                        }
                    }, delay - (currentTime - lastExecTime));
                }
            });
        };
    }

    /**
     * Debounce con leading edge (ejecuta inmediatamente la primera vez)
     */
    static debounceLeading(func, delay = 300) {
        let timeoutId;
        let lastCallTime;
        
        return function(...args) {
            const context = this;
            const currentTime = Date.now();
            
            const shouldCallNow = !lastCallTime || (currentTime - lastCallTime > delay);
            
            lastCallTime = currentTime;
            
            if (shouldCallNow) {
                func.apply(context, args);
            }
            
            clearTimeout(timeoutId);
            timeoutId = setTimeout(() => {
                if (Date.now() - lastCallTime >= delay) {
                    func.apply(context, args);
                }
            }, delay);
        };
    }

    /**
     * Throttle con trailing edge (ejecuta al final del período)
     */
    static throttleTrailing(func, delay = 300) {
        let lastExecTime = 0;
        let timeoutId;
        let lastArgs;
        let lastContext;
        
        return function(...args) {
            const context = this;
            const currentTime = Date.now();
            
            lastArgs = args;
            lastContext = context;
            
            if (currentTime - lastExecTime > delay) {
                func.apply(context, args);
                lastExecTime = currentTime;
            } else {
                clearTimeout(timeoutId);
                timeoutId = setTimeout(() => {
                    func.apply(lastContext, lastArgs);
                    lastExecTime = Date.now();
                }, delay - (currentTime - lastExecTime));
            }
        };
    }

    /**
     * Rate limiter - Limita el número de llamadas por período
     */
    static rateLimit(func, maxCalls = 5, period = 1000) {
        const calls = [];
        
        return function(...args) {
            const context = this;
            const now = Date.now();
            
            // Limpiar llamadas antiguas
            while (calls.length > 0 && calls[0] <= now - period) {
                calls.shift();
            }
            
            if (calls.length < maxCalls) {
                calls.push(now);
                return func.apply(context, args);
            } else {
                throw new Error(`Rate limit exceeded: ${maxCalls} calls per ${period}ms`);
            }
        };
    }

    /**
     * Batch - Agrupa múltiples llamadas en una sola ejecución
     */
    static batch(func, delay = 100, maxBatchSize = 10) {
        let timeoutId;
        let batch = [];
        
        return function(item) {
            const context = this;
            
            batch.push(item);
            
            if (batch.length >= maxBatchSize) {
                clearTimeout(timeoutId);
                const currentBatch = [...batch];
                batch = [];
                func.call(context, currentBatch);
            } else {
                clearTimeout(timeoutId);
                timeoutId = setTimeout(() => {
                    if (batch.length > 0) {
                        const currentBatch = [...batch];
                        batch = [];
                        func.call(context, currentBatch);
                    }
                }, delay);
            }
        };
    }

    /**
     * Once - Ejecuta una función solo una vez
     */
    static once(func) {
        let called = false;
        let result;
        
        return function(...args) {
            if (!called) {
                called = true;
                result = func.apply(this, args);
            }
            return result;
        };
    }

    /**
     * After - Ejecuta una función después de N llamadas
     */
    static after(count, func) {
        let callCount = 0;
        
        return function(...args) {
            callCount++;
            if (callCount >= count) {
                return func.apply(this, args);
            }
        };
    }

    /**
     * Before - Ejecuta una función solo las primeras N veces
     */
    static before(count, func) {
        let callCount = 0;
        let lastResult;
        
        return function(...args) {
            if (callCount < count) {
                callCount++;
                lastResult = func.apply(this, args);
            }
            return lastResult;
        };
    }

    /**
     * Memoize - Cachea resultados de función
     */
    static memoize(func, keyGenerator) {
        const cache = new Map();
        
        return function(...args) {
            const key = keyGenerator ? keyGenerator(...args) : JSON.stringify(args);
            
            if (cache.has(key)) {
                return cache.get(key);
            }
            
            const result = func.apply(this, args);
            cache.set(key, result);
            
            return result;
        };
    }

    /**
     * Memoize con TTL (Time To Live)
     */
    static memoizeWithTTL(func, ttl = 60000, keyGenerator) {
        const cache = new Map();
        
        return function(...args) {
            const key = keyGenerator ? keyGenerator(...args) : JSON.stringify(args);
            const now = Date.now();
            
            if (cache.has(key)) {
                const { value, timestamp } = cache.get(key);
                if (now - timestamp < ttl) {
                    return value;
                }
                cache.delete(key);
            }
            
            const result = func.apply(this, args);
            cache.set(key, { value: result, timestamp: now });
            
            return result;
        };
    }

    /**
     * Retry - Reintenta una función con backoff exponencial
     */
    static retry(func, maxAttempts = 3, baseDelay = 1000) {
        return async function(...args) {
            let lastError;
            
            for (let attempt = 1; attempt <= maxAttempts; attempt++) {
                try {
                    return await func.apply(this, args);
                } catch (error) {
                    lastError = error;
                    
                    if (attempt === maxAttempts) {
                        throw error;
                    }
                    
                    const delay = baseDelay * Math.pow(2, attempt - 1);
                    await new Promise(resolve => setTimeout(resolve, delay));
                }
            }
            
            throw lastError;
        };
    }

    /**
     * Timeout - Añade timeout a una función
     */
    static timeout(func, timeoutMs = 5000) {
        return async function(...args) {
            const context = this;
            
            return Promise.race([
                func.apply(context, args),
                new Promise((_, reject) => {
                    setTimeout(() => {
                        reject(new Error(`Function timed out after ${timeoutMs}ms`));
                    }, timeoutMs);
                })
            ]);
        };
    }

    /**
     * Queue - Ejecuta funciones en cola secuencial
     */
    static createQueue(concurrency = 1) {
        const queue = [];
        let running = 0;
        
        const processQueue = async () => {
            if (running >= concurrency || queue.length === 0) {
                return;
            }
            
            running++;
            const { func, resolve, reject } = queue.shift();
            
            try {
                const result = await func();
                resolve(result);
            } catch (error) {
                reject(error);
            } finally {
                running--;
                processQueue();
            }
        };
        
        return {
            add: (func) => {
                return new Promise((resolve, reject) => {
                    queue.push({ func, resolve, reject });
                    processQueue();
                });
            },
            size: () => queue.length,
            clear: () => {
                queue.length = 0;
            }
        };
    }

    /**
     * Compose - Compone múltiples funciones
     */
    static compose(...functions) {
        return function(value) {
            return functions.reduceRight((acc, fn) => fn(acc), value);
        };
    }

    /**
     * Pipe - Encadena múltiples funciones
     */
    static pipe(...functions) {
        return function(value) {
            return functions.reduce((acc, fn) => fn(acc), value);
        };
    }

    /**
     * Curry - Convierte función en versión currificada
     */
    static curry(func) {
        return function curried(...args) {
            if (args.length >= func.length) {
                return func.apply(this, args);
            }
            return function(...nextArgs) {
                return curried.apply(this, args.concat(nextArgs));
            };
        };
    }

    /**
     * Partial - Aplicación parcial de argumentos
     */
    static partial(func, ...partialArgs) {
        return function(...remainingArgs) {
            return func.apply(this, partialArgs.concat(remainingArgs));
        };
    }
}