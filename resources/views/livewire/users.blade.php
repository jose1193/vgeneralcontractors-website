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
        <script>
            console.log('🔴 MODAL DEBUG: Modal is being rendered', {
                isOpen: @json($isOpen),
                modalAction: @json($modalAction),
                modalTitle: @json($modalTitle),
                name: @json($name),
                email: @json($email)
            });
        </script>
        <x-modals.form-modal :isOpen="$isOpen" :modalTitle="$modalTitle" :modalAction="$modalAction">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-livewire.users.form-fields :modalAction="$modalAction" :usernameAvailable="$usernameAvailable ?? null" :roles="$roles" />
            </div>
        </x-modals.form-modal>
    @else
        <script>
            console.log('🔴 MODAL DEBUG: Modal is NOT being rendered', {
                isOpen: @json($isOpen)
            });
        </script>
    @endif

    <!-- Usar los componentes genéricos para modales de confirmación -->
    <x-modals.delete-confirmation itemType="user" />
    <x-modals.restore-confirmation itemType="user" />
</div>
