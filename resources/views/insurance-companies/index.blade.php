{{-- Ejemplo de uso de la tabla glassmorphic refactorizada --}}
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-900" style="background-color: #141414;">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        
        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-white mb-2">
                Gestión de Usuarios
            </h1>
            <p class="text-gray-400">
                Administra los usuarios del sistema con la nueva tabla glassmorphic
            </p>
        </div>

        {{-- Filter Bar (opcional) --}}
        <div class="mb-6 flex flex-col md:flex-row gap-4 items-center justify-between">
            <div class="flex gap-4">
                <input type="text" 
                       id="searchInput" 
                       placeholder="Buscar usuarios..." 
                       class="px-4 py-2 bg-black/50 border border-white/10 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-purple-500">
                
                <select id="statusFilter" 
                        class="px-4 py-2 bg-black/50 border border-white/10 rounded-lg text-white focus:outline-none focus:border-purple-500">
                    <option value="">Todos los estados</option>
                    <option value="active">Activos</option>
                    <option value="inactive">Inactivos</option>
                </select>
            </div>
            
            <button id="addUserBtn" 
                    class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition-colors">
                Agregar Usuario
            </button>
        </div>

        {{-- Tabla Glassmorphic --}}
        <x-crud.glassmorphic-table 
            id="users-table"
            :columns="[
                ['field' => 'id', 'label' => 'ID', 'sortable' => true],
                ['field' => 'name', 'label' => 'Nombre', 'sortable' => true],
                ['field' => 'email', 'label' => 'Email', 'sortable' => true],
                ['field' => 'phone', 'label' => 'Teléfono', 'sortable' => false],
                ['field' => 'type', 'label' => 'Tipo', 'sortable' => true],
                ['field' => 'created_at', 'label' => 'Creado', 'sortable' => true],
                ['field' => 'actions', 'label' => 'Acciones', 'sortable' => false]
            ]"
            manager-name="userManager"
            loading-text="Cargando usuarios..."
            no-data-text="No hay usuarios registrados"
            :sortable="true"
            :responsive="true"
        />

        {{-- Paginación --}}
        <div id="pagination" class="mt-6 flex justify-between items-center">
            <div class="text-gray-400 text-sm">
                Mostrando <span id="showing-from">1</span> a <span id="showing-to">10</span> de <span id="total-records">50</span> registros
            </div>
            
            <div class="flex gap-2">
                <button id="prevBtn" class="px-3 py-1 bg-black/50 border border-white/10 rounded text-white hover:bg-black/70 disabled:opacity-50">
                    Anterior
                </button>
                <div id="pageNumbers" class="flex gap-1">
                    <!-- Page numbers will be inserted here -->
                </div>
                <button id="nextBtn" class="px-3 py-1 bg-black/50 border border-white/10 rounded text-white hover:bg-black/70 disabled:opacity-50">
                    Siguiente
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Scripts para manejo de datos --}}
@push('scripts')
<script>
// Simulación de datos de usuarios
const sampleUsers = [
    {
        id: 1,
        name: 'Juan Pérez',
        email: 'juan@ejemplo.com',
        phone: '+34 600 123 456',
        type: 'administrador',
        created_at: '2024-01-15',
        actions: `
            <button class="action-btn edit" data-action="edit" data-id="1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Editar
            </button>
            <button class="action-btn delete" data-action="delete" data-id="1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Eliminar
            </button>
        `
    },
    {
        id: 2,
        name: 'María García',
        email: 'maria@ejemplo.com',
        phone: '+34 600 234 567',
        type: 'cobranzas',
        created_at: '2024-01-16',
        actions: `
            <button class="action-btn edit" data-action="edit" data-id="2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Editar
            </button>
            <button class="action-btn delete" data-action="delete" data-id="2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Eliminar
            </button>
        `
    },
    {
        id: 3,
        name: 'Carlos López',
        email: 'carlos@ejemplo.com',
        phone: '+34 600 345 678',
        type: 'informacion',
        created_at: '2024-01-17',
        actions: `
            <button class="action-btn edit" data-action="edit" data-id="3">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Editar
            </button>
            <button class="action-btn delete" data-action="delete" data-id="3">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Eliminar
            </button>
        `
    }
];

// Manager de usuarios personalizado
class UserManager {
    constructor() {
        this.data = [...sampleUsers];
        this.filteredData = [...sampleUsers];
        this.currentPage = 1;
        this.perPage = 10;
        this.currentSort = { field: null, direction: null };
        this.filters = { search: '', status: '' };
        
        this.init();
    }
    
    init() {
        this.setupEventListeners();
        this.loadData();
    }
    
    setupEventListeners() {
        // Búsqueda
        document.getElementById('searchInput').addEventListener('input', (e) => {
            this.filters.search = e.target.value;
            this.applyFilters();
        });
        
        // Filtro de estado
        document.getElementById('statusFilter').addEventListener('change', (e) => {
            this.filters.status = e.target.value;
            this.applyFilters();
        });
        
        // Botón agregar
        document.getElementById('addUserBtn').addEventListener('click', () => {
            this.addUser();
        });
        
        // Escuchar eventos de la tabla
        document.addEventListener('rowSelected', (e) => {
            console.log('Fila seleccionada:', e.detail);
        });
    }
    
    loadData() {
        const tableManager = window['users-tableManager'];
        if (tableManager) {
            tableManager.showLoading();
            
            // Simular carga asíncrona
            setTimeout(() => {
                this.populateTable();
                tableManager.hideLoading();
            }, 1000);
        }
    }
    
    populateTable() {
        const tbody = document.getElementById('users-table-body');
        
        // Limpiar filas existentes (excepto loading y no-data)
        const existingRows = tbody.querySelectorAll('tr:not(.loading-row):not(.no-data-row)');
        existingRows.forEach(row => row.remove());
        
        if (this.filteredData.length === 0) {
            const tableManager = window['users-tableManager'];
            if (tableManager) {
                tableManager.showNoData();
            }
            return;
        }
        
        // Crear filas
        this.filteredData.forEach(user => {
            const row = this.createUserRow(user);
            tbody.appendChild(row);
        });
        
        // Procesar badges y botones
        this.processBadgesAndButtons();
    }
    
    createUserRow(user) {
        const row = document.createElement('tr');
        row.className = 'glassmorphic-tr';
        
        row.innerHTML = `
            <td class="glassmorphic-td">${user.id}</td>
            <td class="glassmorphic-td">${user.name}</td>
            <td class="glassmorphic-td">${user.email}</td>
            <td class="glassmorphic-td">${user.phone}</td>
            <td class="glassmorphic-td status-cell" data-status="${user.type}">${user.type}</td>
            <td class="glassmorphic-td">${user.created_at}</td>
            <td class="glassmorphic-td actions-cell">${user.actions}</td>
        `;
        
        return row;
    }
    
    processBadgesAndButtons() {
        const tableManager = window['users-tableManager'];
        if (tableManager) {
            const rows = document.querySelectorAll('#users-table-body tr:not(.loading-row):not(.no-data-row)');
            rows.forEach(row => {
                tableManager.processStatusBadges(row);
                tableManager.processActionButtons(row);
            });
        }
    }
    
    applyFilters() {
        this.filteredData = this.data.filter(user => {
            const matchesSearch = !this.filters.search || 
                user.name.toLowerCase().includes(this.filters.search.toLowerCase()) ||
                user.email.toLowerCase().includes(this.filters.search.toLowerCase());
            
            const matchesStatus = !this.filters.status || user.type === this.filters.status;
            
            return matchesSearch && matchesStatus;
        });
        
        this.applySorting();
        this.populateTable();
    }
    
    sortBy(field, direction) {
        this.currentSort = { field, direction };
        this.applySorting();
        this.populateTable();
    }
    
    applySorting() {
        if (!this.currentSort.field || !this.currentSort.direction) {
            return;
        }
        
        this.filteredData.sort((a, b) => {
            let aVal = a[this.currentSort.field];
            let bVal = b[this.currentSort.field];
            
            // Convertir a string para comparación
            aVal = String(aVal).toLowerCase();
            bVal = String(bVal).toLowerCase();
            
            if (this.currentSort.direction === 'asc') {
                return aVal < bVal ? -1 : aVal > bVal ? 1 : 0;
            } else {
                return aVal > bVal ? -1 : aVal < bVal ? 1 : 0;
            }
        });
    }
    
    refresh() {
        this.loadData();
    }
    
    addUser() {
        alert('Función de agregar usuario - implementar modal aquí');
    }
}

// Inicializar el manager cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    // Crear instancia del manager
    window.userManager = new UserManager();
    
    // Esperar a que la tabla esté inicializada
    setTimeout(() => {
        // Configurar eventos de botones de acción
        document.addEventListener('click', function(e) {
            if (e.target.closest('.action-btn')) {
                const btn = e.target.closest('.action-btn');
                const action = btn.dataset.action;
                const id = btn.dataset.id;
                
                if (action === 'edit') {
                    alert(`Editar usuario ID: ${id}`);
                } else if (action === 'delete') {
                    if (confirm('¿Está seguro de eliminar este usuario?')) {
                        alert(`Eliminar usuario ID: ${id}`);
                    }
                }
            }
        });
    }, 100);
});
</script>
@endpush