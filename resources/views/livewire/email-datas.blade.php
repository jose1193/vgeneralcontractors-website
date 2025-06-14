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
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-lg">
            <div class="p-6">
                <!-- Add button and search -->
                <div
                    class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 space-y-4 md:space-y-0">
                    <x-input-search />

                    <div
                        class="flex flex-col sm:flex-row items-center w-full md:w-auto space-y-3 sm:space-y-0 sm:space-x-4">
                        <!-- Toggle to show inactive email data -->
                        <x-toggle label="Show Inactive Email Data" :isActive="$showDeleted" wireClick="toggleShowDeleted" />

                        <!-- Per page dropdown with better spacing -->
                        <x-select-input-per-pages name="perPage" wireModel="perPage" class="sm:w-32">
                            <option value="10">10 per page</option>
                            <option value="25">25 per page</option>
                            <option value="50">50 per page</option>
                            <option value="100">100 per page</option>
                        </x-select-input-per-pages>

                        <div class="w-full sm:w-auto">
                            <x-add-button :wireClick="'create'">
                                Add Email Data
                            </x-add-button>
                        </div>
                    </div>
                </div>

                <!-- Email data table with extra spacing on mobile -->
                <div class="mt-8 sm:mt-6">
                    @include('components.livewire.email-datas.data-table', [
                        'emailDatas' => $emailDatas,
                        'sortField' => $sortField,
                        'sortDirection' => $sortDirection,
                    ])
                </div>

                <!-- Pagination -->
                <x-pagination :paginator="$emailDatas" />
            </div>
        </div>
    </div>

    <!-- Modal Form -->
    @if ($isOpen)
        <x-modals.form-modal :isOpen="$isOpen" :modalTitle="$modalTitle" :modalAction="$modalAction">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4" x-data="formValidation({
                initialValues: {
                    description: '{{ $description }}',
                    email: '{{ $email }}',
                    phone: '{{ $phone }}',
                    type: '{{ $type }}',
                },
                modalAction: '{{ $modalAction }}'
            })" x-init="modalAction = '{{ $modalAction }}';
            // Inicializar valores del formulario
            form = {
                description: '{{ $description }}',
                email: '{{ $email }}',
                phone: '{{ $phone }}',
                type: '{{ $type }}',
            };
            
            // Escuchar eventos de actualización
            $wire.on('email-data-edit', (event) => {
                const data = event.detail;
                console.log('Received email data:', data);
            
                // Actualizar el formulario con los nuevos datos
                form.description = data.description;
                form.email = data.email;
                form.phone = data.phone;
                form.type = data.type;
            
                // Sincronizar con Livewire
                $wire.set('description', data.description);
                $wire.set('email', data.email);
                $wire.set('phone', data.phone);
                $wire.set('type', data.type);
            
                clearErrors();
            });">
                <x-livewire.email-datas.form-fields :modalAction="$modalAction" />
            </div>
        </x-modals.form-modal>
    @endif

    <!-- Usar los componentes genéricos para modales de confirmación -->
    <x-modals.delete-confirmation itemType="email data" />
    <x-modals.restore-confirmation itemType="email data" />
</div>
