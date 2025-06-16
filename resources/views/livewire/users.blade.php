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
            
            // IMPROVED: Simplified initialization
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
            
            // IMPROVED: Single event listener for modal data loading
            $wire.on('modal-data-loaded', (event) => {
                const data = event.detail;
                console.log('Modal data loaded:', data);
            
                // Update modalAction
                modalAction = data.action || 'store';
            
                // Update form with data
                Object.keys(data).forEach(key => {
                    if (key in form && key !== 'action') {
                        form[key] = data[key] || '';
                    }
                });
            
                // Clear any previous errors
                clearErrors();
            });
            
            // IMPROVED: Single event listener for modal state cleaning
            $wire.on('modal-state-cleaned', (event) => {
                const data = event.detail;
                console.log('Modal state cleaned, shouldClose:', data.shouldClose);
            
                // Reset form to empty state
                Object.keys(form).forEach(key => {
                    if (key !== 'password' && key !== 'password_confirmation' && key !== 'send_password_reset') {
                        form[key] = '';
                    } else {
                        form[key] = key === 'send_password_reset' ? false : '';
                    }
                });
            
                // Clear errors
                clearErrors();
            
                // Reset modal action
                modalAction = 'store';
            });
            
            // IMPROVED: Clean success event handling
            $wire.on('user-created-success', () => {
                console.log('User created successfully');
                // State is already cleaned by modal-state-cleaned event
            });">
                <x-livewire.users.form-fields :modalAction="$modalAction" :usernameAvailable="$usernameAvailable ?? null" :roles="$roles" />
            </div>
        </x-modals.form-modal>
    @endif

    <!-- Usar los componentes genéricos para modales de confirmación -->
    <x-modals.delete-confirmation itemType="user" />
    <x-modals.restore-confirmation itemType="user" />
</div>
