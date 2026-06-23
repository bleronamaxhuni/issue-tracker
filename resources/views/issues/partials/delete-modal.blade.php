<x-modal name="delete-issue-{{ $issue->id }}" focusable>
    <form method="post" action="{{ route('issues.destroy', $issue) }}" class="p-6">
        @csrf
        @method('delete')
        <h2 class="text-lg font-semibold tracking-tight text-stone-900">{{ __('Delete issue') }}</h2>
        <p class="mt-2 text-sm text-stone-600">{{ __('Delete ":title" and all its comments?', ['title' => $issue->title]) }}</p>
        <div class="mt-6 flex justify-end gap-3">
            <x-secondary-button x-on:click="$dispatch('close')">{{ __('Cancel') }}</x-secondary-button>
            <x-danger-button>{{ __('Delete') }}</x-danger-button>
        </div>
    </form>
</x-modal>
