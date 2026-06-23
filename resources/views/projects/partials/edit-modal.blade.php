<x-modal name="edit-project-{{ $project->id }}" :show="session('open_modal') === 'edit-project-'.$project->id" focusable>
    <form method="post" action="{{ route('projects.update', $project) }}" class="p-6">
        @csrf
        @method('patch')
        <h2 class="text-lg font-semibold tracking-tight text-stone-900">{{ __('Edit project') }}</h2>
        <div class="mt-6">
            @include('projects.partials.form-fields', ['project' => $project, 'fieldPrefix' => 'project-'.$project->id.'-'])
        </div>
        <div class="mt-6 flex justify-end gap-3">
            <x-secondary-button x-on:click="$dispatch('close')">{{ __('Cancel') }}</x-secondary-button>
            <x-primary-button>{{ __('Save') }}</x-primary-button>
        </div>
    </form>
</x-modal>
