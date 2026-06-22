<x-modal
    :name="$project->editModalName()"
    :show="session('open_modal') === $project->editModalName()"
    focusable
>
    <form method="post" action="{{ route('projects.update', $project) }}" class="p-6">
        @csrf
        @method('patch')

        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Edit Project') }}
        </h2>

        <div class="mt-6 space-y-6">
            @include('projects.partials.form-fields', ['project' => $project])
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <x-secondary-button x-on:click="$dispatch('close')">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-primary-button>
                {{ __('Save Changes') }}
            </x-primary-button>
        </div>
    </form>
</x-modal>
