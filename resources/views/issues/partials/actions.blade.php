<div class="flex shrink-0 gap-2">
    <x-secondary-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', '{{ $issue->editModalName() }}')"
    >{{ __('Edit') }}</x-secondary-button>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', '{{ $issue->deleteModalName() }}')"
    >{{ __('Delete') }}</x-danger-button>
</div>
