<div class="flex shrink-0 gap-2">
    <x-secondary-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', '{{ $project->editModalName() }}')"
    >{{ __('Edit') }}</x-secondary-button>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', '{{ $project->deleteModalName() }}')"
    >{{ __('Delete') }}</x-danger-button>
</div>
