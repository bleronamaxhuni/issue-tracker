<x-modal name="create-issue" :show="session('open_modal') === 'create-issue'" focusable>
    <form method="post" action="{{ route('projects.issues.store', $project) }}" class="p-6">
        @csrf

        <h2 class="text-lg font-medium text-gray-900">
            {{ __('New Issue') }}
        </h2>

        <div class="mt-6 space-y-6">
            @include('issues.partials.form-fields', ['issue' => null])
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <x-secondary-button x-on:click="$dispatch('close')">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-primary-button>
                {{ __('Create Issue') }}
            </x-primary-button>
        </div>
    </form>
</x-modal>
