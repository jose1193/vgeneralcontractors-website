/**
 * GUÍA DE REFACTORIZACIÓN PARA TRADUCCIONES DEL SISTEMA CRUD
 *
 * Este archivo documenta cómo reemplazar los mensajes hardcodeados en el sistema CRUD
 * con llamadas al sistema de traducciones.
 */

import { crudTranslations } from "./utils/CrudTranslations.js";

// ============================================================================
// EJEMPLOS DE REFACTORIZACIÓN POR ARCHIVO
// ============================================================================

// ----------------------------------------------------------------------------
// 1. CrudManager.js - REFACTORIZACIÓN
// ----------------------------------------------------------------------------

/*
ANTES (líneas hardcodeadas):
```javascript
this.translations = options.translations || {
    confirmDelete: "Are you sure?",
    deleteMessage: "Do you want to delete this element?",
    // ... más traducciones hardcodeadas
};
```

DESPUÉS (usando sistema de traducciones):
```javascript
this.translations = options.translations || crudTranslations.getCrudTranslations();
```
*/

/*
ANTES (mensajes de error hardcodeados):
```javascript
this.modalManager.showAlert(
    "error",
    "Invalid ID provided for editing"
);
```

DESPUÉS (usando traducción):
```javascript
this.modalManager.showAlert(
    "error",
    crudTranslations.get('invalid_id_editing')
);
```
*/

/*
ANTES (mensajes dinámicos hardcodeados):
```javascript
customMessage = `¿Deseas eliminar ${entityDisplayName}: <strong>${entityIdentifier}</strong>?`;
```

DESPUÉS (usando función de formato):
```javascript
customMessage = crudTranslations.formatConfirmMessage('delete', entityDisplayName, entityIdentifier);
```
*/

/*
ANTES (información de paginación hardcodeada):
```javascript
return `${showingText} ${from}-${to} ${ofText} ${total} ${recordsText}`;
```

DESPUÉS (usando función de formato):
```javascript
return crudTranslations.formatPaginationMessage(from, to, total);
```
*/

// ----------------------------------------------------------------------------
// 2. CrudValidator.js - REFACTORIZACIÓN
// ----------------------------------------------------------------------------

/*
ANTES (mensajes de validación hardcodeados):
```javascript
this.showFieldError(
    "email",
    this.translations.invalidEmail || "Invalid email format"
);
```

DESPUÉS (usando traducción):
```javascript
this.showFieldError(
    "email",
    crudTranslations.get('invalid_email_format')
);
```
*/

/*
ANTES (mensajes con placeholders hardcodeados):
```javascript
this.showFieldError(
    field.name,
    this.translations.minLength || `Minimum ${minLength} characters required`
);
```

DESPUÉS (usando función de formato):
```javascript
this.showFieldError(
    field.name,
    crudTranslations.formatCharacterMessage('min', minLength)
);
```
*/

/*
ANTES (mensajes de disponibilidad hardcodeados):
```javascript
this.showFieldSuccess(
    "email",
    this.translations.emailAvailable || "Email available"
);
```

DESPUÉS (usando traducción):
```javascript
this.showFieldSuccess(
    "email",
    crudTranslations.get('email_available')
);
```
*/

// ----------------------------------------------------------------------------
// 3. CrudApiClient.js - REFACTORIZACIÓN
// ----------------------------------------------------------------------------

/*
ANTES (mensajes de error de red hardcodeados):
```javascript
console.error("Network error in fetchEntities:", error);
throw new Error(`Network error: ${error.message}`);
```

DESPUÉS (usando traducción):
```javascript
console.error(crudTranslations.get('network_error_fetch'), error);
throw new Error(`${crudTranslations.get('network_error')}: ${error.message}`);
```
*/

/*
ANTES (respuesta inesperada hardcodeada):
```javascript
throw new Error(
    `Respuesta inesperada del servidor: ${response.status} ${response.statusText}`
);
```

DESPUÉS (usando traducción):
```javascript
throw new Error(
    `${crudTranslations.get('unexpected_server_response')}: ${response.status} ${response.statusText}`
);
```
*/

// ----------------------------------------------------------------------------
// 4. CrudModalManager.js - REFACTORIZACIÓN
// ----------------------------------------------------------------------------

/*
ANTES (configuración de modal hardcodeada):
```javascript
this.modalConfig = modalConfig || {
    confirmButtonText: "Save",
    cancelButtonText: "Cancel",
};
```

DESPUÉS (usando traducciones):
```javascript
this.modalConfig = modalConfig || {
    confirmButtonText: crudTranslations.get('save'),
    cancelButtonText: crudTranslations.get('cancel'),
};
```
*/

/*
ANTES (títulos de alertas hardcodeados):
```javascript
title: type === "success" ? "Éxito" : "Error",
```

DESPUÉS (usando traducciones):
```javascript
title: type === "success" ? crudTranslations.get('success') : crudTranslations.get('error'),
```
*/

/*
ANTES (mensaje de validación hardcodeado):
```javascript
let errorMessage = "Errores de validación:\n";
```

DESPUÉS (usando traducción):
```javascript
let errorMessage = crudTranslations.get('validation_errors') + ":\n";
```
*/

// ----------------------------------------------------------------------------
// 5. CrudTableRenderer.js - REFACTORIZACIÓN
// ----------------------------------------------------------------------------

/*
ANTES (mensaje de tabla vacía hardcodeado):
```javascript
tbody.innerHTML = `<tr><td colspan="${this.tableHeaders.length}" class="text-center">No records found</td></tr>`;
```

DESPUÉS (usando traducción):
```javascript
tbody.innerHTML = `<tr><td colspan="${this.tableHeaders.length}" class="text-center">${crudTranslations.get('no_records_found')}</td></tr>`;
```
*/

/*
ANTES (botones de paginación hardcodeados):
```javascript
html += `<button class="pagination-btn" data-page="${data.current_page - 1}">Previous</button>`;
html += `<button class="pagination-btn" data-page="${data.current_page + 1}">Next</button>`;
```

DESPUÉS (usando traducciones):
```javascript
html += `<button class="pagination-btn" data-page="${data.current_page - 1}">${crudTranslations.get('previous')}</button>`;
html += `<button class="pagination-btn" data-page="${data.current_page + 1}">${crudTranslations.get('next')}</button>`;
```
*/

// ----------------------------------------------------------------------------
// 6. CrudFormBuilder.js - REFACTORIZACIÓN
// ----------------------------------------------------------------------------

/*
Los comentarios en español se pueden mantener tal como están ya que son para desarrolladores,
pero si se desea internacionalizarlos, se pueden mover a constantes:
```javascript
// ANTES:
// Poblar formulario con datos de entidad

// DESPUÉS:
// ${crudTranslations.get('populate_form_with_entity')}
```
*/

// ============================================================================
// PASOS PARA LA IMPLEMENTACIÓN
// ============================================================================

/*
1. CONFIGURAR TRADUCCIONES EN BLADE:
   En tu layout principal (app.blade.php), agregar:
   ```php
   <script>
       window.translations = @json(__('*'));
   </script>
   ```

2. REFACTORIZAR POR ARCHIVO:
   - Comenzar con CrudManager.js (archivo principal)
   - Continuar con CrudValidator.js (muchos mensajes)
   - Seguir con CrudModalManager.js
   - Completar con CrudApiClient.js y CrudTableRenderer.js

3. TESTEAR CADA REFACTORIZACIÓN:
   - Verificar que los mensajes se muestren correctamente
   - Probar cambio de idioma (si está implementado)
   - Confirmar que no hay regresiones

4. AGREGAR TRADUCCIONES FALTANTES:
   Si se encuentran claves que no existen en es.json, agregarlas:
   ```json
   {
       "invalid_id_editing": "ID inválido proporcionado para edición",
       "no_edit_info_found": "No se encontró información para editar. Contacte al administrador.",
       "error_loading_edit_data": "Error al cargar datos para edición"
   }
   ```

5. DOCUMENTAR CAMBIOS:
   - Actualizar documentación del sistema CRUD
   - Crear ejemplos de uso para desarrolladores
   - Documentar nuevas claves de traducción
*/

// ============================================================================
// EJEMPLO DE USO COMPLETO
// ============================================================================

/*
// Ejemplo de inicialización de CrudManager con traducciones:
const crudManager = new CrudManager({
    entityName: "Usuario",
    entityNamePlural: "Usuarios",
    routes: userRoutes,
    tableHeaders: userTableHeaders,
    validationFields: userValidationFields,
    formFields: userFormFields,
    
    // Usar traducciones del sistema en lugar de objeto hardcodeado
    translations: crudTranslations.getCrudTranslations(),
    
    entityConfig: {
        identifierField: "name",
        displayName: crudTranslations.get('user'),
        fallbackFields: ["email", "username"]
    }
});
*/

export default {
    // Este objeto exporta referencias para uso en la refactorización
    translations: crudTranslations,
    getCrudTranslations: () => crudTranslations.getCrudTranslations(),
    formatConfirmMessage: (type, entityDisplayName, entityIdentifier) =>
        crudTranslations.formatConfirmMessage(
            type,
            entityDisplayName,
            entityIdentifier
        ),
    formatCharacterMessage: (type, count) =>
        crudTranslations.formatCharacterMessage(type, count),
    formatPaginationMessage: (from, to, total) =>
        crudTranslations.formatPaginationMessage(from, to, total),
};
