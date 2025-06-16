@props(['model' => false])

<div class="mb-4 col-span-2">
    <label for="send_password_reset" class="flex items-center cursor-pointer">
        <div class="relative">
            <input type="checkbox" x-model="{{ $model }}"
                @change="$wire.set('send_password_reset', $event.target.checked)" id="send_password_reset" class="sr-only">
            <div class="block bg-gray-600 dark:bg-gray-700 w-14 h-8 rounded-full"></div>
            <div class="dot absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition transform"
                :class="{ 'translate-x-6': {{ $model }} }"></div>
        </div>
        <span class="ml-3 text-gray-700 dark:text-gray-300">Send password reset email to user</span>
    </label>
</div>
