/**
 * IMPLEMENTACIÓN INMEDIATA DEL SISTEMA DE TRADUCCIONES CRUD
 *
 * Este archivo muestra cómo empezar a usar el sistema de traducciones
 * en lugar de los mensajes hardcodeados actuales.
 */

import { crudTranslations } from "./utils/CrudTranslations.js";

// ============================================================================
// 1. CONFIGURACIÓN EN EL LAYOUT PRINCIPAL
// ============================================================================

/*
En tu archivo app.blade.php o el layout que uses, agregar ANTES de cargar el JavaScript:

```php
@push('scripts')
<script>
    // Hacer las traducciones disponibles globalmente para JavaScript
    window.translations = @json(trans('*'));
</script>
@endpush
```

O si prefieres cargar solo las traducciones necesarias:

```php
@push('scripts')
<script>
    window.translations = {
        // Traducciones básicas
        'are_you_sure': @json(__('are_you_sure')),
        'yes_delete': @json(__('yes_delete')),
        'yes_restore': @json(__('yes_restore')),
        'cancel': @json(__('cancel')),
        'deleted_successfully': @json(__('deleted_successfully')),
        'restored_successfully': @json(__('restored_successfully')),
        'error_deleting': @json(__('error_deleting')),
        'error_restoring': @json(__('error_restoring')),
        
        // Validaciones
        'invalid_email_format': @json(__('invalid_email_format')),
        'email_already_in_use': @json(__('email_already_in_use')),
        'email_available': @json(__('email_available')),
        'phone_already_in_use': @json(__('phone_already_in_use')),
        'phone_available': @json(__('phone_available')),
        'minimum_characters': @json(__('minimum_characters')),
        'must_contain_numbers': @json(__('must_contain_numbers')),
        
        // Sistema general
        'save': @json(__('save')),
        'update': @json(__('update')),
        'success': @json(__('success')),
        'error': @json(__('error')),
        'loading': @json(__('loading')),
        'previous': @json(__('previous')),
        'next': @json(__('next')),
        'showing': @json(__('showing')),
        'to': @json(__('to')),
        'of': @json(__('of')),
        'total_records': @json(__('total_records')),
        'no_records_found': @json(__('no_records_found')),
        
        // Nuevas traducciones CRUD
        'invalid_id_editing': @json(__('invalid_id_editing')),
        'no_edit_info_found': @json(__('no_edit_info_found')),
        'error_loading_edit_data': @json(__('error_loading_edit_data')),
        'network_error_fetch': @json(__('network_error_fetch')),
        'unexpected_server_response': @json(__('unexpected_server_response')),
        'api_error': @json(__('api_error')),
        'network_error': @json(__('network_error')),
        'restore_confirmation': @json(__('restore_confirmation')),
        'delete_confirmation': @json(__('delete_confirmation'))
    };
</script>
@endpush
```
*/

// ============================================================================
// 2. EJEMPLO DE USO INMEDIATO EN CRUDS EXISTENTES
// ============================================================================

/*
En lugar de modificar todo el sistema de una vez, puedes empezar
reemplazando los mensajes hardcodeados uno por uno:
*/

// EJEMPLO: Refactorizar un CrudManager existente
/*
// ANTES:
const userCrudManager = new CrudManager({
    entityName: "Usuario",
    routes: userRoutes,
    translations: {
        confirmDelete: "¿Estás seguro?",
        deleteMessage: "¿Deseas eliminar este elemento?",
        // ... más mensajes hardcodeados
    }
});

// DESPUÉS (refactorización gradual):
const userCrudManager = new CrudManager({
    entityName: "Usuario", 
    routes: userRoutes,
    translations: crudTranslations.getCrudTranslations() // ¡Listo!
});
*/

// ============================================================================
// 3. UTILIDADES PARA MIGRACIÓN GRADUAL
// ============================================================================

/**
 * Función helper para migrar mensajes existentes gradualmente
 */
export function migrateTranslations(existingTranslations = {}) {
    const defaultTranslations = crudTranslations.getCrudTranslations();

    // Combinar traducciones existentes con las nuevas, dando prioridad a las nuevas
    return {
        ...existingTranslations,
        ...defaultTranslations,
    };
}

/**
 * Función helper para validar que todas las traducciones necesarias están disponibles
 */
export function validateTranslations() {
    const requiredKeys = [
        "confirmDelete",
        "deleteMessage",
        "confirmRestore",
        "restoreMessage",
        "yesDelete",
        "yesRestore",
        "cancel",
        "deletedSuccessfully",
        "restoredSuccessfully",
        "errorDeleting",
        "errorRestoring",
        "showing",
        "to",
        "of",
        "results",
        "total_records",
        "noRecordsFound",
    ];

    const translations = crudTranslations.getCrudTranslations();
    const missing = [];

    requiredKeys.forEach((key) => {
        if (!translations[key] || translations[key] === key) {
            missing.push(key);
        }
    });

    if (missing.length > 0) {
        console.warn("Traducciones CRUD faltantes:", missing);
        console.warn(
            "Agregar estas claves al archivo es.json:",
            missing.map((key) => `"${key}": "Traducción para ${key}"`)
        );
    }

    return missing.length === 0;
}

// ============================================================================
// 4. EJEMPLOS DE REEMPLAZOS ESPECÍFICOS
// ============================================================================

/**
 * Reemplazos para CrudManager
 */
export const CrudManagerTranslations = {
    // En lugar de hardcodear en showEditModal:
    getInvalidIdMessage: () => crudTranslations.get("invalid_id_editing"),
    getNoInfoFoundMessage: () => crudTranslations.get("no_edit_info_found"),
    getLoadErrorMessage: () => crudTranslations.get("error_loading_edit_data"),

    // En lugar de hardcodear mensajes de éxito/error:
    getCreatedSuccessMessage: (entityName) =>
        `${entityName} ${crudTranslations.get("created_successfully")}`,
    getUpdatedSuccessMessage: (entityName) =>
        `${entityName} ${crudTranslations.get("updated_successfully")}`,
    getDeletedSuccessMessage: (entityName) =>
        `${entityName} ${crudTranslations.get("deleted_successfully")}`,
    getRestoredSuccessMessage: (entityName) =>
        `${entityName} ${crudTranslations.get("restored_successfully")}`,

    // Para mensajes dinámicos de confirmación:
    formatDeleteMessage: (displayName, identifier) =>
        crudTranslations.formatConfirmMessage(
            "delete",
            displayName,
            identifier
        ),
    formatRestoreMessage: (displayName, identifier) =>
        crudTranslations.formatConfirmMessage(
            "restore",
            displayName,
            identifier
        ),
};

/**
 * Reemplazos para CrudValidator
 */
export const CrudValidatorTranslations = {
    // Mensajes de validación de email:
    getInvalidEmailMessage: () => crudTranslations.get("invalid_email_format"),
    getEmailInUseMessage: () => crudTranslations.get("email_already_in_use"),
    getEmailAvailableMessage: () => crudTranslations.get("email_available"),

    // Mensajes de validación de teléfono:
    getPhoneInUseMessage: () => crudTranslations.get("phone_already_in_use"),
    getPhoneAvailableMessage: () => crudTranslations.get("phone_available"),
    getPhoneFormatMessage: () => "Formato: (xxx) xxx-xxxx",

    // Mensajes de caracteres con count:
    getMinCharactersMessage: (count) =>
        crudTranslations.formatCharacterMessage("min", count),
    getMaxCharactersMessage: (count) =>
        crudTranslations.formatCharacterMessage("max", count),

    // Otros mensajes de validación:
    getMustContainNumbersMessage: () =>
        crudTranslations.get("must_contain_numbers"),
    getLastNameRequiredMessage: () =>
        crudTranslations.get("last_name_required"),
    getCorrectErrorsMessage: () =>
        crudTranslations.get("please_correct_errors"),
};

/**
 * Reemplazos para CrudApiClient
 */
export const CrudApiTranslations = {
    getNetworkErrorMessage: (operation) =>
        `${crudTranslations.get("network_error_fetch")} ${operation}`,
    getUnexpectedResponseMessage: (status, statusText) =>
        `${crudTranslations.get(
            "unexpected_server_response"
        )}: ${status} ${statusText}`,
    getApiErrorMessage: () => crudTranslations.get("api_error"),
};

/**
 * Reemplazos para CrudTableRenderer
 */
export const CrudTableTranslations = {
    getNoRecordsMessage: () => crudTranslations.get("no_records_found"),
    getPreviousButtonText: () => crudTranslations.get("previous"),
    getNextButtonText: () => crudTranslations.get("next"),
    formatPaginationInfo: (from, to, total) =>
        crudTranslations.formatPaginationMessage(from, to, total),
};

// ============================================================================
// 5. FUNCIÓN DE MIGRACIÓN AUTOMÁTICA
// ============================================================================

/**
 * Función que puede ser llamada para migrar automáticamente
 * un objeto de configuración de CRUD existente
 */
export function migrateCrudConfig(config) {
    const migratedConfig = { ...config };

    // Migrar traducciones si existen
    if (migratedConfig.translations) {
        migratedConfig.translations = migrateTranslations(
            migratedConfig.translations
        );
    } else {
        migratedConfig.translations = crudTranslations.getCrudTranslations();
    }

    return migratedConfig;
}

// ============================================================================
// 6. EXPORTACIONES PARA USO INMEDIATO
// ============================================================================

export {
    crudTranslations,
    CrudManagerTranslations,
    CrudValidatorTranslations,
    CrudApiTranslations,
    CrudTableTranslations,
};

// Exportación por defecto para uso rápido
export default {
    translations: crudTranslations,
    manager: CrudManagerTranslations,
    validator: CrudValidatorTranslations,
    api: CrudApiTranslations,
    table: CrudTableTranslations,
    migrate: migrateCrudConfig,
    validate: validateTranslations,
};
