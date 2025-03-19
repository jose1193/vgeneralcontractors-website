@props(['paginator'])

<div class="mt-4 flex flex-col sm:flex-row justify-between items-center space-y-3 sm:space-y-0">
    <div class="text-sm text-gray-700 dark:text-gray-300 w-full sm:w-auto text-center sm:text-left">
        Showing {{ $paginator->firstItem() ?? 0 }} to {{ $paginator->lastItem() ?? 0 }} of
        {{ $paginator->total() }} entries
    </div>
    <div class="w-full sm:w-auto flex justify-center sm:justify-end">
        {{ $paginator->links() }}
    </div>
</div>
