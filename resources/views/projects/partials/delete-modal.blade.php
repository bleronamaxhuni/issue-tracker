<x-modal name="delete-project-{{ $project->id }}" focusable>
    <form method="post" action="{{ route('projects.destroy', $project) }}" class="p-6">
        @csrf
        @method('delete')
        <h2 class="text-lg font-semibold tracking-tight text-stone-900">{{ __('Delete project') }}</h2>
        <p class="mt-2 text-sm text-stone-600">{{ __('Delete ":name" and all its issues?', ['name' => $project->name]) }}</p>
        <div class="mt-6 flex justify-end gap-3">
            <x-secondary-button x-on:click="$dispatch('close')">{{ __('Cancel') }}</x-secondary-button>
            <x-danger-button>{{ __('Delete') }}</x-danger-button>
        </div>
    </form>
</x-modal>
