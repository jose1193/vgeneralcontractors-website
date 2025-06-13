@props([
    'columns' => [],
    'items' => [],
    'actionColumn' => true,
    'tableClass' => 'table table-striped table-hover',
    'theadClass' => 'bg-gray-50 dark:bg-gray-800',
    'id' => 'crud-table',
    'responsive' => true,
    'searchable' => false,
    'pagination' => null,
    'noDataText' => 'No data available',
    'showDeleteModal' => false,
])

<div class="{{ $responsive ? 'overflow-x-auto  dark:bg-gray-800 shadow-md rounded-lg' : '' }}">
    <table id="{{ $id }}" class="{{ $tableClass }} min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="{{ $theadClass }}">
            <tr>
                @foreach ($columns as $column)
                    <th scope="col"
                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        {{ $column['label'] ?? ucfirst($column['key']) }}</th>
                @endforeach

                @if ($actionColumn)
                    <th scope="col"
                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Actions</th>
                @endif
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:bg-gray-900 dark:divide-gray-700">
            @forelse($items as $item)
                <tr>
                    @foreach ($columns as $column)
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="text-sm text-gray-900 dark:text-gray-100">
                                @if (isset($column['format']) && is_callable($column['format']))
                                    {!! $column['format']($item, $column['key']) !!}
                                @elseif(isset($column['raw']) && $column['raw'])
                                    {!! data_get($item, $column['key']) !!}
                                @else
                                    {{ data_get($item, $column['key']) }}
                                @endif
                            </div>
                        </td>
                    @endforeach

                    @if ($actionColumn)
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="inline-flex items-center justify-center space-x-4">
                                {{ $slot }}
                            </div>
                        </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($columns) + ($actionColumn ? 1 : 0) }}"
                        class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                        {{ $noDataText }}
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if ($searchable)
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize DataTables if available
                if (typeof $.fn.DataTable !== 'undefined') {
                    $('#{{ $id }}').DataTable({
                        responsive: true,
                        language: {
                            search: "Search:",
                            lengthMenu: "Show _MENU_ entries per page",
                            info: "Showing _START_ to _END_ of _TOTAL_ entries",
                            infoEmpty: "Showing 0 to 0 of 0 entries",
                            infoFiltered: "(filtered from _MAX_ total entries)"
                        }
                    });
                }
            });
        </script>
    @endpush
@endif

@if ($pagination && !$searchable)
    <div class="mt-4">
        {{ $pagination->links() }}
    </div>
@endif
