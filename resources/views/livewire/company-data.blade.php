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
                <!-- Controls: Search, Toggle Deleted, Per Page, Add Company -->
                <div
                    class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 space-y-4 md:space-y-0">
                    <x-input-search />

                    <div
                        class="flex flex-col sm:flex-row items-center w-full md:w-auto space-y-3 sm:space-y-0 sm:space-x-4">
                        <!-- Toggle to show inactive companies -->
                        <x-toggle :label="__('show_inactive_companies')" :isActive="$showDeleted" wireClick="toggleShowDeleted" />

                        <!-- Per page dropdown -->
                        <x-select-input-per-pages name="perPage" wireModel="perPage" class="sm:w-32">
                            <option value="10">10 {{ __('per_page') }}</option>
                            <option value="25">25 {{ __('per_page') }}</option>
                            <option value="50">50 {{ __('per_page') }}</option>
                            <option value="100">100 {{ __('per_page') }}</option>
                        </x-select-input-per-pages>

                        <!-- Add company button -->
                        <div class="w-full sm:w-auto">
                            @if (!$hasExistingCompany)
                                <x-add-button :wireClick="'create'">
                                    {{ __('add_company') }}
                                </x-add-button>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Companies table -->
                @include('components.livewire.companies.data-table', [
                    'companies' => $companies,
                    'sortField' => $sortField,
                    'sortDirection' => $sortDirection,
                ])

                <!-- Pagination -->
                <x-pagination :paginator="$companies" />
            </div>
        </div>
    </div>

    <!-- Modal Form -->
    @if ($isOpen)
        <x-modals.form-modal :isOpen="$isOpen" :modalTitle="$modalTitle" :modalAction="$modalAction">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4" x-data="formValidation({
                initialValues: {
                    company_name: '{{ $company_name }}',
                    name: '{{ $name }}',
                    email: '{{ $email }}',
                    phone: '{{ $phone }}',
                    address: '{{ $address }}',
                    website: '{{ $website }}',
                    latitude: '{{ $latitude }}',
                    longitude: '{{ $longitude }}'
                },
                modalAction: '{{ $modalAction }}'
            })" x-init="modalAction = '{{ $modalAction }}';
            // Initialize form values
            form = {
                company_name: '{{ $company_name }}',
                name: '{{ $name }}',
                email: '{{ $email }}',
                phone: '{{ $phone }}',
                address: '{{ $address }}',
                website: '{{ $website }}',
                latitude: '{{ $latitude }}',
                longitude: '{{ $longitude }}'
            };
            
            // Listen for update events
            $wire.on('company-edit', (event) => {
                const data = event.detail;
            
                // Update form with new data
                form.company_name = data.company_name || '';
                form.name = data.name || '';
                form.email = data.email || '';
                form.phone = data.phone || '';
                form.address = data.address || '';
                form.website = data.website || '';
                form.latitude = data.latitude || '';
                form.longitude = data.longitude || '';
            
                // Sync with Livewire
                $wire.set('company_name', form.company_name);
                $wire.set('name', form.name);
                $wire.set('email', form.email);
                $wire.set('phone', form.phone);
                $wire.set('address', form.address);
                $wire.set('website', form.website);
                $wire.set('latitude', form.latitude);
                $wire.set('longitude', form.longitude);
            
                clearErrors();
            });">
                <x-livewire.companies.form-fields :modalAction="$modalAction" />
            </div>
        </x-modals.form-modal>
    @endif

    <!-- Use the generic confirmation modals but pass debugging info -->
    <div x-data="{
        verifyEvent() {
            window.addEventListener('delete-confirmation', (event) => {
                console.log('Delete confirmation event received:', event.detail);
            });
        }
    }" x-init="verifyEvent()"></div>

    <x-modals.delete-confirmation itemType="company" />
    <x-modals.restore-confirmation itemType="company" />
</div>
