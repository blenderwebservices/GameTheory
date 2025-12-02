<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Game Theory Scenarios') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Figtree', sans-serif;
        }
    </style>
</head>
<body class="antialiased bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200 min-h-screen">

    <!-- Navigation -->
    <nav class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ route('home') }}" class="text-xl font-bold text-blue-600 dark:text-blue-400">
                            Game Theory
                        </a>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white focus:outline-none transition duration-150 ease-in-out">
                            <span>{{ __('Language') }} ({{ strtoupper(app()->getLocale()) }})</span>
                            <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-700 ring-1 ring-black ring-opacity-5">
                            <div class="py-1">
                                <a href="/lang/en" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">{{ __('English') }}</a>
                                <a href="/lang/es" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">{{ __('Spanish') }}</a>
                            </div>
                        </div>
                    </div>
                    @if (Route::has('filament.admin.auth.login'))
                        @auth
                            <a href="{{ url('/admin') }}" class="text-sm text-gray-700 dark:text-gray-300 underline">{{ __('Dashboard') }}</a>
                        @else
                            <a href="{{ route('filament.admin.auth.login') }}" class="text-sm text-gray-700 dark:text-gray-300 underline">{{ __('Login') }}</a>
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto p-6 lg:p-8">
        <div class="flex justify-center mb-12">
            <h1 class="text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600">
                {{ __('Game Theory Scenarios') }}
            </h1>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($scenarios as $scenario)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-2xl transition-shadow duration-300 overflow-hidden border border-gray-200 dark:border-gray-700 flex flex-col">
                    <div class="p-6 flex-grow">
                        <h2 class="text-2xl font-bold mb-3 text-gray-900 dark:text-white">{{ $scenario->name }}</h2>
                        <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed mb-6">
                            {{ Str::limit($scenario->description, 150) }}
                        </p>

                        <div class="space-y-4">
                            <!-- Player A -->
                            <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg border border-blue-100 dark:border-blue-800">
                                <h3 class="font-semibold text-blue-700 dark:text-blue-300 mb-2 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    {{ $scenario->player_a_name }}
                                </h3>
                                <div class="flex gap-2 text-xs">
                                    <span class="px-2 py-1 bg-white dark:bg-gray-800 rounded border border-blue-200 dark:border-blue-700 text-gray-600 dark:text-gray-300">
                                        {{ $scenario->player_a_strategy_1 }}
                                    </span>
                                    <span class="px-2 py-1 bg-white dark:bg-gray-800 rounded border border-blue-200 dark:border-blue-700 text-gray-600 dark:text-gray-300">
                                        {{ $scenario->player_a_strategy_2 }}
                                    </span>
                                </div>
                            </div>

                            <!-- Player B -->
                            <div class="bg-red-50 dark:bg-red-900/20 p-4 rounded-lg border border-red-100 dark:border-red-800">
                                <h3 class="font-semibold text-red-700 dark:text-red-300 mb-2 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    {{ $scenario->player_b_name }}
                                </h3>
                                <div class="flex gap-2 text-xs">
                                    <span class="px-2 py-1 bg-white dark:bg-gray-800 rounded border border-red-200 dark:border-red-700 text-gray-600 dark:text-gray-300">
                                        {{ $scenario->player_b_strategy_1 }}
                                    </span>
                                    <span class="px-2 py-1 bg-white dark:bg-gray-800 rounded border border-red-200 dark:border-red-700 text-gray-600 dark:text-gray-300">
                                        {{ $scenario->player_b_strategy_2 }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 dark:bg-gray-700/50 p-4 border-t border-gray-200 dark:border-gray-700 flex justify-end">
                         <a href="/simulation/{{ $scenario->id }}" class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 flex items-center transition-colors">
                            {{ __('View Simulation') }}
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="mt-12 text-center text-gray-500 text-sm">
            &copy; {{ date('Y') }} Game Theory App. Built with Laravel & Filament.
        </div>
    </div>
</body>
</html>
