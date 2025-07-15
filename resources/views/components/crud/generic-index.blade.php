{{-- Generic CRUD Index Blade Component --}}
<x-crud.index-layout :title="$title" :subtitle="$subtitle" :entity-name="$entityName" :entity-name-plural="$entityNamePlural" :search-placeholder="$searchPlaceholder"
    :show-deleted-label="$showDeletedLabel" :add-new-label="$addNewLabel" manager-name="crudManager" table-id="crudTable" create-button-id="createCrudBtn"
    search-id="searchInput" show-deleted-id="showDeleted" per-page-id="perPage" pagination-id="pagination"
    alert-id="alertContainer" :table-columns="$tableColumns">
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            $(document).ready(function() {
                window.crudManager = new CrudManagerModal({
                    entityName: @json($entityName),
                    entityNamePlural: @json($entityNamePlural),
                    routes: @json($routes),
                    tableSelector: '#crudTable-body',
                    searchSelector: '#searchInput',
                    perPageSelector: '#perPage',
                    showDeletedSelector: '#showDeleted',
                    paginationSelector: '#pagination',
                    alertSelector: '#alertContainer',
                    createButtonSelector: '#createCrudBtn',
                    idField: $idField ?? 'uuid',
                    searchFields: @json($searchFields),
                    showDeleted: $showDeleted ?? false,
                    entityConfig: @json($entityConfig),
                    formFields: @json($formFields),
                    tableHeaders: @json($tableHeaders),
                    translations: @json($translations),
                });
                window.crudManager.loadEntities();
            });
        </script>
    @endpush
</x-crud.index-layout>
