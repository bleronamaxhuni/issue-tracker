<x-modal
    :name="$issue->editModalName()"
    :show="session('open_modal') === $issue->editModalName()"
    focusable
>
    <form method="post" action="{{ route('issues.update', $issue) }}" class="p-6">
        @csrf
        @method('patch')

        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Edit Issue') }}
        </h2>

        <div class="mt-6 space-y-6">
            @include('issues.partials.form-fields', ['issue' => $issue])
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
