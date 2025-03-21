<div>
    <div class="mb-4">
        <label for="blog_category_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Category
            Name</label>
        <input type="text" id="blog_category_name" wire:model="blog_category_name" x-model="form.blog_category_name"
            @input="
                // Only allow letters, spaces and hyphens
                $event.target.value = $event.target.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s-]/g, '');
                // Capitalize first letter of each word
                let words = $event.target.value.toLowerCase().split(' ');
                words = words.map(word => word.charAt(0).toUpperCase() + word.slice(1));
                $event.target.value = words.join(' ');
                form.blog_category_name = $event.target.value;
                $wire.set('blog_category_name', $event.target.value);
                validateField('blog_category_name');
            "
            placeholder="Enter category name"
            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
        <div x-show="errors.blog_category_name" x-text="errors.blog_category_name" class="text-red-500 text-xs mt-1">
        </div>
        @error('blog_category_name')
            <span class="text-red-500 text-xs">{{ $message }}</span>
        @enderror
    </div>

    <div class="mb-4">
        <label for="blog_category_description"
            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
        <textarea id="blog_category_description" wire:model="blog_category_description" x-model="form.blog_category_description"
            @input="
                // Capitalize first letter of the description
                $event.target.value = $event.target.value.charAt(0).toUpperCase() + $event.target.value.slice(1);
                form.blog_category_description = $event.target.value;
                $wire.set('blog_category_description', $event.target.value);
                validateField('blog_category_description');
            "
            placeholder="Enter category description" rows="3"
            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md"></textarea>
        <div x-show="errors.blog_category_description" x-text="errors.blog_category_description"
            class="text-red-500 text-xs mt-1"></div>
        @error('blog_category_description')
            <span class="text-red-500 text-xs">{{ $message }}</span>
        @enderror
    </div>
</div>



<script>
    function formValidation(config) {
        return {
            form: config.initialValues,
            errors: {},
            isSubmitting: false,
            modalAction: config.modalAction,

            validateField(field) {
                // Basic validation - this can be expanded
                if (field === 'blog_category_name') {
                    if (!this.form.blog_category_name || this.form.blog_category_name.length < 3) {
                        this.errors.blog_category_name = 'Category name must be at least 3 characters';
                    } else {
                        delete this.errors.blog_category_name;
                    }
                }

                if (field === 'blog_category_description') {
                    if (this.form.blog_category_description && this.form.blog_category_description.length > 500) {
                        this.errors.blog_category_description = 'Description must be 500 characters or less';
                    } else {
                        delete this.errors.blog_category_description;
                    }
                }
            },

            validateForm() {
                this.validateField('blog_category_name');
                this.validateField('blog_category_description');
                return Object.keys(this.errors).length === 0;
            },

            clearErrors() {
                this.errors = {};
            },

            get isFormInvalid() {
                return !this.form.blog_category_name || Object.keys(this.errors).length > 0;
            },

            submitForm(action) {
                if (!this.validateForm()) {
                    return;
                }

                this.isSubmitting = true;

                if (action === 'store') {
                    // Create new category
                    $wire.store();
                } else if (action === 'update') {
                    // Update existing category
                    $wire.update();
                }
            }
        };
    }
</script>
