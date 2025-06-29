@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Test - Sistema CRUD Modular</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary" id="test-create-btn">
                            <i class="fas fa-plus"></i> Test Create
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="test-table-container">
                        <!-- La tabla se renderizará aquí -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush

@push('scripts')
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    @vite(['resources/js/crud/index.js'])
    
    <script>
        // Asegurar que SweetAlert2 esté disponible globalmente
        window.Swal = Swal;
    </script>
    
    <!-- Test del Sistema CRUD Modular -->
    <script type="module">
        // Importar el sistema CRUD
        import { CrudManagerModal } from '/resources/js/crud/index.js';
        
        // Configuración de prueba
        const testConfig = {
            entityName: 'Insurance Company',
            routes: {
                index: '/insurance-companies',
                store: '/insurance-companies',
                show: '/insurance-companies/{id}',
                update: '/insurance-companies/{id}',
                destroy: '/insurance-companies/{id}',
                restore: '/insurance-companies/{id}/restore'
            },
            selectors: {
                table: '#test-table-container',
                createBtn: '#test-create-btn',
                searchInput: '#search-input',
                perPageSelect: '#per-page-select'
            },
            formFields: [
                {
                    name: 'name',
                    type: 'text',
                    label: 'Company Name',
                    required: true,
                    validation: {
                        required: true,
                        minLength: 2,
                        maxLength: 255
                    }
                },
                {
                    name: 'email',
                    type: 'email',
                    label: 'Email',
                    required: true,
                    validation: {
                        required: true,
                        email: true
                    }
                },
                {
                    name: 'phone',
                    type: 'text',
                    label: 'Phone',
                    required: false,
                    validation: {
                        minLength: 10
                    }
                }
            ],
            tableHeaders: [
                { key: 'id', label: 'ID', sortable: true },
                { key: 'name', label: 'Company Name', sortable: true },
                { key: 'email', label: 'Email', sortable: true },
                { key: 'phone', label: 'Phone', sortable: false },
                { key: 'created_at', label: 'Created', sortable: true },
                { key: 'actions', label: 'Actions', sortable: false }
            ],
            translations: {
                confirmDelete: '¿Estás seguro?',
                deleteMessage: 'Esta acción no se puede deshacer.',
                confirmRestore: '¿Restaurar registro?',
                restoreMessage: '¿Deseas restaurar este registro?',
                createSuccess: 'Compañía de seguros creada exitosamente',
                updateSuccess: 'Compañía de seguros actualizada exitosamente',
                deleteSuccess: 'Compañía de seguros eliminada exitosamente',
                restoreSuccess: 'Compañía de seguros restaurada exitosamente'
            }
        };
        
        // Inicializar el sistema CRUD
        try {
            window.testCrudManager = new CrudManagerModal(testConfig);
            console.log('✅ Sistema CRUD Modular inicializado correctamente');
            console.log('Instancia:', window.testCrudManager);
            
            // Test de componentes individuales
            console.log('🧪 Probando componentes:');
            console.log('- ApiManager:', window.testCrudManager.apiManager);
            console.log('- TableManager:', window.testCrudManager.tableManager);
            console.log('- FormManager:', window.testCrudManager.formManager);
            console.log('- ModalManager:', window.testCrudManager.modalManager);
            console.log('- ValidationManager:', window.testCrudManager.validationManager);
            console.log('- EventManager:', window.testCrudManager.eventManager);
            
        } catch (error) {
            console.error('❌ Error inicializando sistema CRUD:', error);
            
            // Mostrar error en la interfaz
            document.getElementById('test-table-container').innerHTML = `
                <div class="alert alert-danger">
                    <h5>Error del Sistema CRUD</h5>
                    <p><strong>Mensaje:</strong> ${error.message}</p>
                    <p><strong>Stack:</strong></p>
                    <pre>${error.stack}</pre>
                </div>
            `;
        }
    </script>
@endpush