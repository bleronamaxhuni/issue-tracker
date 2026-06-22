<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="page-title">{{ __('Profile') }}</h1>
            <p class="page-subtitle">{{ __('Update your account settings') }}</p>
        </div>
    </x-slot>

    <x-page-container>
        <section class="panel p-5 sm:p-6">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </section>

        <section class="panel p-5 sm:p-6">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </section>

        <section class="panel p-5 sm:p-6">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </section>
    </x-page-container>
</x-app-layout>
