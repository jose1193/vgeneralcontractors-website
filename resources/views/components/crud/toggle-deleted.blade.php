@props([
    'id' => 'showDeleted',
    'label' => 'Show Inactive Items',
    'hasLabel' => true,
])

<div class="flex items-center justify-between md:justify-start space-x-2">
    @if ($hasLabel)
        <span class="text-sm text-gray-700 dark:text-gray-300">{{ $label }}</span>
    @endif

    <label for="{{ $id }}" class="relative inline-flex items-center cursor-pointer">
        <input type="checkbox" id="{{ $id }}" class="sr-only peer" {{ $attributes }}>
        <div
            class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
        </div>
    </label>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleElement = document.getElementById('{{ $id }}');

        if (toggleElement) {
            // Check if there's a value stored in localStorage
            const storedValue = localStorage.getItem('{{ $id }}');
            if (storedValue !== null) {
                toggleElement.checked = storedValue === 'true';

                // If it was checked, trigger a change event to load deleted items immediately
                if (toggleElement.checked && window.appointmentManager) {
                    window.appointmentManager.showDeleted = true;
                    window.appointmentManager.loadEntities();
                }
            }

            // Handle change events
            toggleElement.addEventListener('change', function() {
                // Store the current state in localStorage
                localStorage.setItem('{{ $id }}', this.checked);

                // If we have access to the CrudManager instance, update and refresh
                if (window.appointmentManager) {
                    console.log('Toggle Deleted State Changed:', {
                        checked: this.checked,
                        id: '{{ $id }}',
                        previousState: window.appointmentManager.showDeleted
                    });

                    window.appointmentManager.showDeleted = this.checked;
                    window.appointmentManager.currentPage = 1; // Reset to first page 

                    // Ensure it's passed as a string 'true'/'false' as expected by the backend
                    const originalLoadEntities = window.appointmentManager.loadEntities;
                    window.appointmentManager.loadEntities = function() {
                        console.log('Before AJAX call - show_deleted:', this.showDeleted ? 'true' :
                            'false');
                        originalLoadEntities.call(window.appointmentManager);
                    };

                    window.appointmentManager.loadEntities();

                    // Restore original method
                    setTimeout(() => {
                        window.appointmentManager.loadEntities = originalLoadEntities;
                    }, 1000);
                }
            });
        }
    });
</script>
