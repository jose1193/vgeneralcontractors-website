@php
// Ejemplo de definición de columnas para la tabla glassmórfica
$tableColumns = [
    [
        'label' => 'ID',
        'field' => 'id',
        'sortable' => true,
    ],
    [
        'label' => 'Nombre',
        'field' => 'name',
        'sortable' => true,
    ],
    [
        'label' => 'Email',
        'field' => 'email',
        'sortable' => true,
    ],
    [
        'label' => 'Estado',
        'field' => 'status',
        'sortable' => true,
        'class' => 'status-cell', // Clase especial para formatear badges de estado
    ],
    [
        'label' => 'Fecha',
        'field' => 'created_at',
        'sortable' => true,
    ],
    [
        'label' => 'Acciones',
        'field' => 'actions',
        'sortable' => false,
    ],
];
@endphp

<x-crud.index-layout
    title="Ejemplo de Tabla Glassmórfica"
    subtitle="Una demostración del componente de tabla con efectos visuales glassmórficos"
    entityName="Usuario"
    entityNamePlural="Usuarios"
    searchPlaceholder="Buscar usuarios..."
    showDeletedLabel="Mostrar eliminados"
    addNewLabel="Agregar Usuario"
    managerName="userManager"
    :tableColumns="$tableColumns"
    tableId="users-table"
    createButtonId="createUserBtn"
    searchId="searchUsers"
    showDeletedId="showDeletedUsers"
    perPageId="perPageUsers"
    paginationId="usersPagination"
    alertId="usersAlert"
>
    <!-- Modales y contenido adicional aquí -->
</x-crud.index-layout>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar el gestor CRUD para usuarios
        window.userManager = new CrudManagerModal({
            entityName: 'usuario',
            entityNamePlural: 'usuarios',
            apiUrl: '/api/users',
            columns: @json($tableColumns),
            tableId: 'users-table',
            createButtonId: 'createUserBtn',
            searchInputId: 'searchUsers',
            showDeletedId: 'showDeletedUsers',
            perPageId: 'perPageUsers',
            paginationId: 'usersPagination',
            alertContainerId: 'usersAlert',
            // Configuración adicional aquí
        });

        // Cargar datos iniciales
        userManager.loadEntities();

        // Formatear badges de estado después de cargar los datos
        userManager.onDataLoaded = function() {
            formatStatusBadges('users-table');
        };
    });
</script>
@endpush