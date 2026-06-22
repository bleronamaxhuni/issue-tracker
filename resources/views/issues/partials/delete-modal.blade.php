<x-modal :name="$issue->deleteModalName()" focusable>
    <form method="post" action="{{ route('issues.destroy', $issue) }}" class="p-6">
        @csrf
        @method('delete')

        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Delete Issue') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Are you sure you want to delete ":title"? All comments on this issue will also be deleted.', ['title' => $issue->title]) }}
        </p>

        <div class="mt-6 flex justify-end gap-3">
            <x-secondary-button x-on:click="$dispatch('close')">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-danger-button>
                {{ __('Delete Issue') }}
            </x-danger-button>
        </div>
    </form>
</x-modal>
