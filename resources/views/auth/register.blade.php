<x-layouts.app>
    <x-slot name="title">{{ __('Register') }}</x-slot>

    <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-sm">
            <h2 class="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900 dark:text-white">{{ __('Create your account') }}</h2>
        </div>

        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
            <form class="space-y-6" action="{{ route('register.store') }}" method="POST">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-200">{{ __('Name') }}</label>
                    <div class="mt-2">
                        <input id="name" name="name" type="text" autocomplete="name" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-white dark:bg-gray-800 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 pl-2" value="{{ old('name') }}">
                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-200">{{ __('Email address') }}</label>
                    <div class="mt-2">
                        <input id="email" name="email" type="email" autocomplete="email" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-white dark:bg-gray-800 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 pl-2" value="{{ old('email') }}">
                        @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-200">{{ __('Password') }}</label>
                    <div class="mt-2">
                        <input id="password" name="password" type="password" autocomplete="new-password" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-white dark:bg-gray-800 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 pl-2">
                        @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-200">{{ __('Confirm Password') }}</label>
                    <div class="mt-2">
                        <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-white dark:bg-gray-800 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 pl-2">
                    </div>
                </div>

                <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                    <fieldset>
                        <legend class="text-sm font-semibold leading-6 text-gray-900 dark:text-white">{{ __('Select Default Scenarios') }}</legend>
                        <p class="mt-1 text-sm leading-6 text-gray-600 dark:text-gray-400">{{ __('Choose which game theory scenarios you want to start with in your dashboard.') }}</p>
                        <div class="mt-4 space-y-4">
                            @foreach($defaultScenarios as $scenario)
                            <div class="relative flex gap-x-3">
                                <div class="flex h-6 items-center">
                                    <input id="scenario_{{ $scenario->id }}" name="scenarios[]" value="{{ $scenario->id }}" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-600">
                                </div>
                                <div class="text-sm leading-6">
                                    <label for="scenario_{{ $scenario->id }}" class="font-medium text-gray-900 dark:text-gray-200">{{ $scenario->name }}</label>
                                    <p class="text-gray-500 dark:text-gray-400">{{ Str::limit($scenario->description, 50) }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </fieldset>
                </div>

                <div>
                    <button type="submit" class="flex w-full justify-center rounded-md bg-blue-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                        {{ __('Register') }}
                    </button>
                </div>
            </form>

            <p class="mt-10 text-center text-sm text-gray-500 dark:text-gray-400">
                {{ __('Already have an account?') }}
                <a href="{{ route('filament.admin.auth.login') }}" class="font-semibold leading-6 text-blue-600 hover:text-blue-500">{{ __('Sign in') }}</a>
            </p>
        </div>
    </div>
</x-layouts.app>
