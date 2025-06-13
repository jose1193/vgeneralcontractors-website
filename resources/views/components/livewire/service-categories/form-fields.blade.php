<div class="mb-4">
    <x-label for="category" value="{{ __('category_name') }}" />
    <x-input id="category" type="text" class="mt-1 block w-full" x-model="form.category"
        @input="
            // Only allow letters, spaces and hyphens
            $event.target.value = $event.target.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s-]/g, '');
            // Capitalize first letter of each word
            let words = $event.target.value.toLowerCase().split(' ');
            words = words.map(word => word.charAt(0).toUpperCase() + word.slice(1));
            $event.target.value = words.join(' ');
            form.category = $event.target.value;
            $wire.set('category', $event.target.value);
        "
        @blur="validateField('category')" placeholder="Enter category name" />
    <x-input-error for="category" class="mt-2" />
    <div x-show="errors.category" x-text="errors.category" class="text-red-500 mt-2 text-sm"></div>
</div>
