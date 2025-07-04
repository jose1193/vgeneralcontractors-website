# Formateo Decimal con Separadores de Miles

## Descripción

Se han implementado nuevas funciones de formateo decimal que permiten mostrar números con separadores de miles en formato `2,500.00` mientras el usuario escribe en los campos de entrada.

## Funciones Implementadas

### 1. `formatDecimalInput(event, fieldName)`

**Uso:** Para campos generales de moneda como subtotal y tax_amount.

**Características:**
- Formatea números con separadores de miles (comas)
- Limita a 2 decimales
- Mantiene la posición del cursor correctamente
- Guarda el valor numérico sin formato en el modelo para cálculos
- Ejemplo: `2500.50` se muestra como `2,500.50`

**Implementación en Blade:**
```html
<input type="text" x-model="form.subtotal"
    @input="formatDecimalInput($event, 'subtotal')"
    placeholder="2,500.00">
```

### 2. `formatDecimalItemInput(event, itemIndex)`

**Uso:** Para campos de rate en los items de factura.

**Características:**
- Similar a `formatDecimalInput` pero para arrays de items
- Formatea números con separadores de miles
- Limita a 2 decimales
- Actualiza `this.form.items[itemIndex].rate`
- Ejemplo: `1250.75` se muestra como `1,250.75`

**Implementación en Blade:**
```html
<input type="text" x-model="item.rate"
    @input="formatDecimalItemInput($event, index)"
    placeholder="2,500.00">
```

## Campos Actualizados

### En el Modal de Factura (`invoice-modal.blade.php`):

1. **Subtotal** - Usa `formatDecimalInput($event, 'subtotal')`
2. **Tax Amount** - Usa `formatDecimalInput($event, 'tax_amount')`
3. **Rate (Items)** - Usa `formatDecimalItemInput($event, index)`

## Características Técnicas

### Manejo del Cursor
- Las funciones calculan automáticamente la nueva posición del cursor cuando se agregan comas
- Usa `setTimeout()` para evitar conflictos con Alpine.js
- Mantiene una experiencia de usuario fluida durante la escritura

### Almacenamiento de Datos
- **En pantalla:** Formato con comas (ej: `2,500.00`)
- **En modelo:** Valor numérico sin formato (ej: `2500.00`)
- Esto asegura que los cálculos matemáticos funcionen correctamente

### Integración con Cálculos
- Las funciones llaman automáticamente a `calculateTotals()` después de formatear
- Compatible con el sistema existente de cálculo de totales

## Ejemplo de Uso

```javascript
// El usuario escribe: 2500.50
// Se muestra en pantalla: 2,500.50
// Se guarda en el modelo: "2500.50"
// Los cálculos usan el valor numérico: 2500.50
```

## Beneficios

1. **Mejor UX:** Los usuarios ven números formateados de manera profesional
2. **Claridad:** Los separadores de miles hacen que las cantidades grandes sean más legibles
3. **Compatibilidad:** Mantiene la funcionalidad existente de cálculos
4. **Consistencia:** Formato uniforme en toda la aplicación

## Notas de Implementación

- Las funciones están integradas en `invoice-demos.js`
- Compatible con Alpine.js y el sistema existente
- No requiere librerías externas adicionales
- Funciona en tiempo real mientras el usuario escribe