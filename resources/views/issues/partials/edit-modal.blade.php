<x-modal name="edit-issue-{{ $issue->id }}" :show="session('open_modal') === 'edit-issue-'.$issue->id" focusable>
    <form method="post" action="{{ route('issues.update', $issue) }}" class="p-6">
        @csrf
        @method('patch')
        <h2 class="text-lg font-semibold tracking-tight text-stone-900">{{ __('Edit issue') }}</h2>
        <div class="mt-6">
            @include('issues.partials.form-fields', ['issue' => $issue, 'fieldPrefix' => (string) $issue->id])
        </div>
        <div class="mt-6 flex justify-end gap-3">
            <x-secondary-button x-on:click="$dispatch('close')">{{ __('Cancel') }}</x-secondary-button>
            <x-primary-button>{{ __('Save') }}</x-primary-button>
        </div>
    </form>
</x-modal>
