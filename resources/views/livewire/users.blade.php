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
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4" x-data="userFormHandler()" x-init="initializeForm('{{ $modalAction }}')">
                <x-livewire.users.form-fields :modalAction="$modalAction" :usernameAvailable="$usernameAvailable ?? null" :roles="$roles" />
            </div>
        </x-modals.form-modal>
    @endif

    <script>
        function userFormHandler() {
            return {
                form: {
                    name: '',
                    last_name: '',
                    email: '',
                    phone: '',
                    address: '',
                    city: '',
                    zip_code: '',
                    state: '',
                    country: '',
                    gender: '',
                    date_of_birth: '',
                    username: '',
                    role: '',
                    password: '',
                    password_confirmation: '',
                    send_password_reset: false
                },
                errors: {},
                modalAction: 'store',

                initializeForm(action) {
                    this.modalAction = action;
                    this.clearErrors();

                    // Listen for user edit events
                    this.$wire.on('user-edit', (event) => {
                        this.updateForm(event.detail);
                    });

                    // Listen for modal closed events
                    this.$wire.on('modal-closed', () => {
                        this.resetForm();
                    });

                    // Listen for success events
                    this.$wire.on('user-created-success', () => {
                        this.resetForm();
                    });
                },

                updateForm(data) {
                    // Update form with received data
                    Object.keys(this.form).forEach(key => {
                        this.form[key] = data[key] || '';
                    });
                    this.modalAction = data.action || 'store';
                    this.clearErrors();
                },

                resetForm() {
                    // Reset all form fields
                    Object.keys(this.form).forEach(key => {
                        this.form[key] = '';
                    });
                    this.clearErrors();
                    this.modalAction = 'store';
                },

                clearErrors() {
                    this.errors = {};
                }
            }
        }
    </script>

    <!-- Usar los componentes genéricos para modales de confirmación -->
    <x-modals.delete-confirmation itemType="user" />
    <x-modals.restore-confirmation itemType="user" />
</div>
