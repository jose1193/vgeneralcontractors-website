@props(['emailDatas', 'sortField', 'sortDirection'])

<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="bg-gray-50 dark:bg-gray-800">
            <tr>
                <th scope="col"
                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    {{ __('NRO') }}
                </th>
                @include('components.sort-position', [
                    'field' => 'description',
                    'label' => __('description'),
                    'sortField' => $sortField,
                    'sortDirection' => $sortDirection,
                    'model' => 'emailData',
                ])
                @include('components.sort-position', [
                    'field' => 'email',
                    'label' => __('email'),
                    'sortField' => $sortField,
                    'sortDirection' => $sortDirection,
                ])
                @include('components.sort-position', [
                    'field' => 'phone',
                    'label' => __('phone'),
                    'sortField' => $sortField,
                    'sortDirection' => $sortDirection,
                ])
                @include('components.sort-position', [
                    'field' => 'type',
                    'label' => __('type'),
                    'sortField' => $sortField,
                    'sortDirection' => $sortDirection,
                ])
                @include('components.sort-position', [
                    'field' => 'created_at',
                    'label' => __('created_at'),
                    'sortField' => $sortField,
                    'sortDirection' => $sortDirection,
                ])
                <th scope="col"
                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    {{ __('actions') }}
                </th>
            </tr>
        </thead>
        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
            @forelse ($emailDatas as $index => $emailData)
                <tr class="{{ $emailData->trashed() ? 'bg-red-50 dark:bg-red-900/20' : '' }}">
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <div class="text-sm text-gray-900 dark:text-gray-100">
                            {{ $index + 1 }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                            {{ $emailData->description }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <div class="text-sm text-gray-900 dark:text-gray-100">
                            {{ $emailData->email }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <div class="text-sm text-gray-900 dark:text-gray-100">
                            @if ($emailData->phone)
                                {{ \App\Helpers\PhoneHelper::format($emailData->phone) }}
                            @else
                                <span class="text-gray-400 italic">N/A</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <div class="text-sm text-gray-900 dark:text-gray-100">
                            @switch($emailData->type)
                                @case('Appointment')
                                    <span
                                        class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100">
                                        {{ __('appointment') }}
                                    </span>
                                @break

                                @case('Collections')
                                    <span
                                        class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                        {{ __('collections') }}
                                    </span>
                                @break

                                @case('Info')
                                    <span
                                        class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100">
                                        {{ __('info') }}
                                    </span>
                                @break

                                @case('Personal')
                                    <span
                                        class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-amber-100 text-amber-800 dark:bg-amber-800 dark:text-amber-100">
                                        {{ __('personal') }}
                                    </span>
                                @break

                                @case('Work')
                                    <span
                                        class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-100">
                                        {{ __('work') }}
                                    </span>
                                @break

                                @case('Business')
                                    <span
                                        class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-cyan-100 text-cyan-800 dark:bg-cyan-800 dark:text-cyan-100">
                                        {{ __('business') }}
                                    </span>
                                @break

                                @default
                                    <span
                                        class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800 dark:bg-orange-800 dark:text-orange-100">
                                        {{ __('other') }}
                                    </span>
                            @endswitch
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                        {{ $emailData->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                        <div class="flex items-center justify-center gap-2">
                            @if ($emailData->trashed())
                                <button type="button" x-data
                                    @click="window.dispatchEvent(new CustomEvent('restore-confirmation', {
                                        detail: { uuid: '{{ $emailData->uuid }}', name: 'email data' }
                                    }))"
                                    class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-200 bg-green-100 dark:bg-green-900/30 p-1 rounded transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            @else
                                <button wire:click="edit('{{ $emailData->uuid }}')"
                                    class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200 bg-indigo-100 dark:bg-indigo-900/30 p-1 rounded transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path
                                            d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                    </svg>
                                </button>
                                <button type="button" x-data
                                    @click="window.dispatchEvent(new CustomEvent('delete-confirmation', {
                                        detail: { uuid: '{{ $emailData->uuid }}', name: 'email data' }
                                    }))"
                                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-200 bg-red-100 dark:bg-red-900/30 p-1 rounded transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                    <tr>
                        <td colspan="7"
                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                            {{ __('no_email_data_found') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
