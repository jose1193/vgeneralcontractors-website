/**
 * Ejemplos de uso del DecimalFormatter
 * 
 * Este archivo contiene ejemplos de cómo usar el sistema de formateo decimal
 * implementado en decimal-formatter.js
 */

// Ejemplo 1: Formateo básico de currency con separadores de miles
function exampleCurrencyFormatting() {
    // Para usar en un input field:
    // <input type="text" @input="formatGeneralCurrencyInput($event, 'subtotal')" />
    
    // El resultado será:
    // Input: "2500.50" -> Display: "2,500.50"
    // Input: "1000000" -> Display: "1,000,000.00"
}

// Ejemplo 2: Formateo de porcentajes
function examplePercentageFormatting() {
    // Para usar en un input field:
    // <input type="text" @input="formatPercentageInput($event, 'tax_rate')" />
    
    // El resultado será:
    // Input: "15.5" -> Display: "15.50" (limitado a 100%)
    // Input: "150" -> Display: "100.00" (máximo 100%)
}

// Ejemplo 3: Formateo solo para display (sin input)
function exampleDisplayFormatting() {
    // Para mostrar valores formateados:
    // <span x-text="formatDecimalDisplay(invoice.balance_due)"></span>
    
    // El resultado será:
    // Value: 2500.5 -> Display: "2,500.50"
    // Value: 1000000 -> Display: "1,000,000.00"
}

// Ejemplo 4: Uso directo del formateador
function exampleDirectUsage() {
    // Acceso directo a los formateadores:
    if (window.CurrencyFormatter) {
        // Formatear un valor para display
        const formatted = window.CurrencyFormatter.formatForDisplay(2500.50);
        console.log(formatted); // "2,500.50"
        
        // Obtener valor numérico limpio
        const numeric = window.CurrencyFormatter.getNumericValue("2,500.50");
        console.log(numeric); // 2500.50
    }
    
    if (window.PercentageFormatter) {
        // Formatear porcentaje
        const formatted = window.PercentageFormatter.formatForDisplay(15.5);
        console.log(formatted); // "15.50"
    }
    
    if (window.GeneralDecimalFormatter) {
        // Formatear decimal general (sin límites específicos)
        const formatted = window.GeneralDecimalFormatter.formatForDisplay(1234.567);
        console.log(formatted); // "1,234.57" (redondeado a 2 decimales)
    }
}

// Ejemplo 5: Configuración personalizada
function exampleCustomFormatter() {
    // Crear un formateador personalizado
    const customFormatter = new DecimalFormatter({
        maxDecimals: 3,           // Hasta 3 decimales
        allowNegative: true,      // Permitir números negativos
        maxValue: 999999.999,     // Valor máximo
        minValue: -999999.999     // Valor mínimo
    });
    
    // Usar el formateador personalizado
    const formatted = customFormatter.formatForDisplay(-1234.567);
    console.log(formatted); // "-1,234.567"
}

// Ejemplo 6: Integración con Alpine.js
function exampleAlpineIntegration() {
    /*
    En tu componente Alpine.js:
    
    <div x-data="{
        form: {
            amount: '',
            percentage: '',
            custom_field: ''
        },
        
        // Método para formatear currency
        formatCurrency(event, fieldName) {
            if (window.CurrencyFormatter) {
                window.CurrencyFormatter.formatDecimalInput(
                    event, 
                    fieldName, 
                    this.form, 
                    () => this.calculateTotals()
                );
            }
        },
        
        // Método para formatear porcentaje
        formatPercentage(event, fieldName) {
            if (window.PercentageFormatter) {
                window.PercentageFormatter.formatDecimalInput(
                    event, 
                    fieldName, 
                    this.form, 
                    () => this.updateCalculations()
                );
            }
        },
        
        calculateTotals() {
            // Tu lógica de cálculo aquí
        },
        
        updateCalculations() {
            // Tu lógica de actualización aquí
        }
    }">
        <!-- Currency Input -->
        <input type="text" 
               x-model="form.amount" 
               @input="formatCurrency($event, 'amount')"
               placeholder="0.00" />
        
        <!-- Percentage Input -->
        <input type="text" 
               x-model="form.percentage" 
               @input="formatPercentage($event, 'percentage')"
               placeholder="0.00" />
        
        <!-- Display formatted value -->
        <span x-text="formatDecimalDisplay(form.amount)"></span>
    </div>
    */
}

// Ejemplo 7: Validación y manejo de errores
function exampleErrorHandling() {
    // El sistema incluye fallbacks automáticos
    // Si decimal-formatter.js no está cargado, las funciones usarán métodos básicos
    
    // Verificar si el formateador está disponible
    if (window.CurrencyFormatter) {
        console.log('✅ CurrencyFormatter está disponible');
    } else {
        console.warn('⚠️ CurrencyFormatter no está disponible, usando fallback');
    }
    
    // Los métodos en invoice-demos.js incluyen fallbacks automáticos
    // para garantizar que la funcionalidad básica siempre funcione
}

console.log('📄 Decimal Formatter Examples loaded - Check the functions above for usage examples');