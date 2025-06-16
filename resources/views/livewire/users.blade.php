<div>
    <div class="max-w-7xl mx-auto py-10">
        <!-- Success and error messages -->
        @if (session()->has('message'))
            <x-alerts.success :message="session('message')" />
        @endif
        @if (session()->has('error'))
            <x-alerts.error :message="session('error')" />
        @endif

        <!-- Main container -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-lg">
            <div class="p-6">
                <!-- Add user button -->
                <div
                    class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 space-y-4 md:space-y-0">
                    <x-input-search />

                    <div
                        class="flex flex-col sm:flex-row items-center w-full md:w-auto space-y-3 sm:space-y-0 sm:space-x-4">
                        <!-- Toggle to show inactive users -->
                        <x-toggle label="{{ __('show_inactive_users') }}" :isActive="$showDeleted"
                            wireClick="toggleShowDeleted" />

                        <!-- Per page dropdown with better spacing -->
                        <x-select-input-per-pages name="perPage" wireModel="perPage" class="sm:w-32">
                            <option value="10">10 {{ __('per_page') }}</option>
                            <option value="25">25 {{ __('per_page') }}</option>
                            <option value="50">50 {{ __('per_page') }}</option>
                            <option value="100">100 {{ __('per_page') }}</option>
                        </x-select-input-per-pages>

                        <div class="w-full sm:w-auto">
                            <x-add-button :wireClick="'create'">
                                {{ __('add_user') }}
                            </x-add-button>
                        </div>
                    </div>
                </div>

                <!-- Users table -->
                <div class="mt-6 sm:mt-4">
                    @include('components.livewire.users.data-table', [
                        'users' => $users,
                        'sortField' => $sortField,
                        'sortDirection' => $sortDirection,
                    ])
                </div>

                <!-- Pagination -->
                <x-pagination :paginator="$users" />
            </div>
        </div>
    </div>

    <!-- Modal Form -->
    @if ($isOpen)
        <x-modals.form-modal :isOpen="$isOpen" :modalTitle="$modalTitle" :modalAction="$modalAction">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4" x-data="formValidation({
                initialValues: {
                    name: '{{ $name }}',
                    last_name: '{{ $last_name }}',
                    email: '{{ $email }}',
                    phone: '{{ $phone }}',
                    address: '{{ $address }}',
                    city: '{{ $city }}',
                    zip_code: '{{ $zip_code }}',
                    country: '{{ $country }}',
                    gender: '{{ $gender }}',
                    date_of_birth: '{{ $date_of_birth }}',
                    username: '{{ $username }}',
                    role: '{{ $role }}',
                    password: '',
                    password_confirmation: '',
                    send_password_reset: false
                },
                modalAction: '{{ $modalAction }}'
            })" x-init="modalAction = '{{ $modalAction }}';
            
            // Función para inicializar el formulario
            function initializeForm() {
                form = {
                    name: '{{ $name }}',
                    last_name: '{{ $last_name }}',
                    email: '{{ $email }}',
                    phone: '{{ $phone }}',
                    address: '{{ $address }}',
                    city: '{{ $city }}',
                    zip_code: '{{ $zip_code }}',
                    country: '{{ $country }}',
                    gender: '{{ $gender }}',
                    date_of_birth: '{{ $date_of_birth }}',
                    username: '{{ $username }}',
                    role: '{{ $role }}',
                    password: '',
                    password_confirmation: '',
                    send_password_reset: false
                };
            }
            
            // Inicializar el formulario
            initializeForm();
            
            // Escuchar eventos de actualización con mejor manejo
            $wire.on('user-edit', (event) => {
                const data = event.detail || event[0] || {};
                console.log('Received user data:', data);
            
                // Limpiar completamente el formulario primero
                Object.keys(form).forEach(key => {
                    form[key] = '';
                });
            
                // Actualizar el formulario con los nuevos datos solo si están definidos
                Object.keys(data).forEach(key => {
                    if (key in form && data[key] !== undefined) {
                        form[key] = data[key] || '';
                    }
                });
            
                // Sincronizar con Livewire de manera más robusta
                $nextTick(() => {
                    $wire.set('name', data.name || '');
                    $wire.set('last_name', data.last_name || '');
                    $wire.set('email', data.email || '');
                    $wire.set('phone', data.phone || '');
                    $wire.set('address', data.address || '');
                    $wire.set('city', data.city || '');
                    $wire.set('zip_code', data.zip_code || '');
                    $wire.set('country', data.country || '');
                    $wire.set('gender', data.gender || '');
                    $wire.set('date_of_birth', data.date_of_birth || '');
                    $wire.set('username', data.username || '');
                    $wire.set('role', data.role || '');
            
                    clearErrors();
                });
            });
            
            // Manejo de eventos de éxito para limpiar estado
            $wire.on('user-created-success', () => {
                // Forzar reinicialización del formulario después de crear usuario
                setTimeout(() => {
                    initializeForm();
                    clearErrors();
                }, 100);
            });
            
            $wire.on('user-updated-success', () => {
                // Forzar reinicialización del formulario después de actualizar usuario
                setTimeout(() => {
                    initializeForm();
                    clearErrors();
                }, 100);
            });">
                <x-livewire.users.form-fields :modalAction="$modalAction" :usernameAvailable="$usernameAvailable ?? null" :roles="$roles" />
            </div>
        </x-modals.form-modal>
    @endif

    <!-- Usar los componentes genéricos para modales de confirmación -->
    <x-modals.delete-confirmation itemType="user" />
    <x-modals.restore-confirmation itemType="user" />
</div>
