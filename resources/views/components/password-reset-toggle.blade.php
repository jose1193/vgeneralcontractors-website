@props(['model' => null, 'wireModel' => null])

<div class="mb-4 col-span-2">
    <label for="send_password_reset" class="flex items-center cursor-pointer">
        <input type="checkbox"
            @if ($wireModel) wire:model.blur="{{ $wireModel }}" @else wire:model.blur="send_password_reset" @endif
            id="send_password_reset"
            class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
        <span class="ml-3 text-gray-700 dark:text-gray-300">Send password reset email to user</span>
    </label>
</div>
