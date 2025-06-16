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
    <div x-data="userFormHandler()" x-init="initializeForm()">
        @if ($isOpen)
            <x-modals.form-modal :isOpen="$isOpen" :modalTitle="$modalTitle" :modalAction="$modalAction">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-livewire.users.form-fields :modalAction="$modalAction" :usernameAvailable="$usernameAvailable ?? null" :roles="$roles" />
                </div>
            </x-modals.form-modal>
        @endif
    </div>

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

                initializeForm() {
                    this.modalAction = 'store';
                    this.clearErrors();

                    // Listen for user edit events
                    this.$wire.on('user-edit', (event) => {
                        console.log('Alpine.js received user-edit event:', event.detail);
                        this.updateForm(event.detail);
                    });

                    // Listen for modal closed events
                    this.$wire.on('modal-closed', () => {
                        console.log('Alpine.js received modal-closed event');
                        this.resetForm();
                    });

                    // Listen for success events
                    this.$wire.on('user-created-success', () => {
                        console.log('Alpine.js received user-created-success event');
                        this.resetForm();
                    });
                },

                updateForm(data) {
                    console.log('Alpine.js updating form with data:', data);
                    // Update form with received data
                    Object.keys(this.form).forEach(key => {
                        this.form[key] = data[key] || '';
                    });
                    this.modalAction = data.action || 'store';
                    this.clearErrors();
                    console.log('Alpine.js form updated:', this.form);
                },

                resetForm() {
                    console.log('Alpine.js resetting form');
                    // Reset all form fields
                    Object.keys(this.form).forEach(key => {
                        this.form[key] = '';
                    });
                    this.clearErrors();
                    this.modalAction = 'store';
                    console.log('Alpine.js form reset complete');
                },

                clearErrors() {
                    this.errors = {};
                },

                // Validation functions that components expect
                validateField(fieldName) {
                    // Remove error for this field when user starts typing
                    if (this.errors[fieldName]) {
                        delete this.errors[fieldName];
                    }
                },

                validateEmail(email) {
                    if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                        this.errors.email = 'Please enter a valid email address';
                    } else {
                        delete this.errors.email;
                    }
                },

                checkEmailAvailability(email) {
                    if (email && email.length > 0) {
                        // Let Livewire handle the server-side validation
                        this.$wire.set('email', email);
                    }
                },

                checkUsernameAvailability(username) {
                    if (username && username.length > 0) {
                        // Let Livewire handle the server-side validation
                        this.$wire.set('username', username);
                    }
                },

                checkPhoneAvailability(phone) {
                    if (phone && phone.length > 0) {
                        // Let Livewire handle the server-side validation
                        this.$wire.set('phone', phone);
                    }
                }
            }
        }
    </script>

    <!-- Usar los componentes genéricos para modales de confirmación -->
    <x-modals.delete-confirmation itemType="user" />
    <x-modals.restore-confirmation itemType="user" />
</div>
