<x-modal :name="$project->deleteModalName()" focusable>
    <form method="post" action="{{ route('projects.destroy', $project) }}" class="p-6">
        @csrf
        @method('delete')

        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Delete Project') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Are you sure you want to delete ":name"? All issues in this project will also be deleted.', ['name' => $project->name]) }}
        </p>

        <div class="mt-6 flex justify-end gap-3">
            <x-secondary-button x-on:click="$dispatch('close')">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-danger-button>
                {{ __('Delete Project') }}
            </x-danger-button>
        </div>
    </form>
</x-modal>
