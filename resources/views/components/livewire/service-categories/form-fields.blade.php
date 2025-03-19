<div class="mb-4">
    <x-label for="category" value="Category Name" />
    <x-input id="category" type="text" class="mt-1 block w-full" wire:model.blur="category" x-model="form.category"
        @input="validateField('category')" placeholder="Enter category name" />
    <x-input-error for="category" class="mt-2" />
    <div x-show="errors.category" x-text="errors.category" class="text-red-500 mt-2 text-sm"></div>
</div>
