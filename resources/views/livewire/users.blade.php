<div>
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <!-- Success and error messages -->
        @if (session()->has('message'))
            <x-alerts.success :message="session('message')" />
        @endif
        @if (session()->has('error'))
            <x-alerts.error :message="session('error')" />
        @endif

        <!-- Main container -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
                <!-- Add user button -->
                <div
                    class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 space-y-4 md:space-y-0">
                    <x-input-search />

                    <div
                        class="flex flex-col sm:flex-row items-center w-full md:w-auto space-y-3 sm:space-y-0 sm:space-x-4">
                        <!-- Toggle to show inactive users -->
                        <x-toggle label="Show Inactive Users" :isActive="$showDeleted" wireClick="toggleShowDeleted" />

                        <!-- Per page dropdown with better spacing -->
                        <x-select-input-per-pages name="perPage" wireModel="perPage" class="sm:w-32">
                            <option value="10">10 per page</option>
                            <option value="25">25 per page</option>
                            <option value="50">50 per page</option>
                            <option value="100">100 per page</option>
                        </x-select-input-per-pages>

                        <div class="w-full sm:w-auto">
                            <x-add-button :wireClick="'openModal'">
                                Add User
                            </x-add-button>
                        </div>
                    </div>
                </div>

                <!-- Users table -->
                @include('components.livewire.users.data-table', [
                    'users' => $users,
                    'sortField' => $sortField,
                    'sortDirection' => $sortDirection,
                ])

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
                    password: '',
                    password_confirmation: '',
                    send_password_reset: false
                },
                modalAction: '{{ $modalAction }}'
            })" x-init="modalAction = '{{ $modalAction }}';
            // Inicializar valores del formulario
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
                password: '',
                password_confirmation: '',
                send_password_reset: false
            };
            
            // Escuchar eventos de actualización
            $wire.on('user-edit', (event) => {
                const data = event.detail;
                console.log('Received user data:', data);
            
                // Actualizar el formulario con los nuevos datos
                form.name = data.name;
                form.last_name = data.last_name;
                form.email = data.email;
                form.phone = data.phone;
                form.address = data.address;
                form.city = data.city;
                form.zip_code = data.zip_code;
                form.country = data.country;
                form.gender = data.gender;
                form.username = data.username;
            
                // Sincronizar con Livewire
                $wire.set('name', data.name);
                $wire.set('last_name', data.last_name);
                $wire.set('email', data.email);
                $wire.set('phone', data.phone);
                $wire.set('address', data.address);
                $wire.set('city', data.city);
                $wire.set('zip_code', data.zip_code);
                $wire.set('country', data.country);
                $wire.set('gender', data.gender);
                $wire.set('username', data.username);
            
                clearErrors();
            });">
                <x-livewire.users.form-fields :modalAction="$modalAction" :usernameAvailable="$usernameAvailable ?? null" />
            </div>
        </x-modals.form-modal>
    @endif

    <!-- Usar los componentes genéricos para modales de confirmación -->
    <x-modals.delete-confirmation itemType="user" />
    <x-modals.restore-confirmation itemType="user" />
</div>
