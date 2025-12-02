<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('View Simulation') }} - {{ $gameScenario->name }}</title>
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
        <div class="mb-8">
            <a href="{{ route('home') }}" class="text-blue-600 dark:text-blue-400 hover:underline flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                {{ __('Home') }}
            </a>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">{{ $gameScenario->name }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mb-8">{{ $gameScenario->description }}</p>

            <div x-data="{
                matrix: {{ json_encode($gameScenario->payoff_matrix) }},
                playerA: '{{ $gameScenario->player_a_name }}',
                playerB: '{{ $gameScenario->player_b_name }}',
                isNash(r, c) {
                    let rowChar = r === 0 ? 'A' : 'B';
                    let colChar = c === 0 ? 'A' : 'B';
                    let key = rowChar + colChar;
                    
                    if (!this.matrix[key]) return false;

                    let payA = Number(this.matrix[key][0]);
                    let payB = Number(this.matrix[key][1]);

                    let otherRowChar = r === 0 ? 'B' : 'A';
                    let otherKeyA = otherRowChar + colChar;
                    let otherPayA = Number(this.matrix[otherKeyA][0]);
                    let bestForA = payA >= otherPayA;

                    let otherColChar = c === 0 ? 'B' : 'A';
                    let otherKeyB = rowChar + otherColChar;
                    let otherPayB = Number(this.matrix[otherKeyB][1]);
                    let bestForB = payB >= otherPayB;

                    return bestForA && bestForB;
                }
            }" class="space-y-6">

                <div class="p-6 bg-gray-50 dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700">
                    <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-200">
                        {{ __('Payoff Matrix') }}: <span x-text="playerA"></span> vs <span x-text="playerB"></span>
                    </h2>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-center border-collapse">
                            <thead>
                                <tr>
                                    <th class="p-4"></th>
                                    <th class="p-4 border-b border-gray-200 dark:border-gray-700 font-semibold text-gray-600 dark:text-gray-400">
                                        <span x-text="playerB"></span> ({{ __('Left') }})<br>
                                        <span class="text-xs font-normal text-gray-500">{{ $gameScenario->player_b_strategy_1 }}</span>
                                    </th>
                                    <th class="p-4 border-b border-gray-200 dark:border-gray-700 font-semibold text-gray-600 dark:text-gray-400">
                                        <span x-text="playerB"></span> ({{ __('Right') }})<br>
                                        <span class="text-xs font-normal text-gray-500">{{ $gameScenario->player_b_strategy_2 }}</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Row 1: Top -->
                                <tr>
                                    <th class="p-4 border-r border-gray-200 dark:border-gray-700 font-semibold text-gray-600 dark:text-gray-400">
                                        <span x-text="playerA"></span> ({{ __('Top') }})<br>
                                        <span class="text-xs font-normal text-gray-500">{{ $gameScenario->player_a_strategy_1 }}</span>
                                    </th>
                                    <!-- Cell AA -->
                                    <td class="p-6 border border-gray-200 dark:border-gray-700 transition-colors duration-300"
                                        :class="isNash(0, 0) ? 'bg-green-100 dark:bg-green-900/30 ring-2 ring-inset ring-green-500' : 'hover:bg-gray-100 dark:hover:bg-gray-800'">
                                        <div class="flex justify-center items-center gap-4 text-lg">
                                            <span class="font-bold text-blue-600 dark:text-blue-400" x-text="matrix['AA'][0]"></span>
                                            <span class="text-gray-400">,</span>
                                            <span class="font-bold text-red-600 dark:text-red-400" x-text="matrix['AA'][1]"></span>
                                        </div>
                                        <div x-show="isNash(0, 0)" class="mt-2 text-xs font-bold text-green-600 dark:text-green-400 uppercase tracking-wider">
                                            {{ __('Nash Equilibrium') }}
                                        </div>
                                    </td>
                                    <!-- Cell AB -->
                                    <td class="p-6 border border-gray-200 dark:border-gray-700 transition-colors duration-300"
                                        :class="isNash(0, 1) ? 'bg-green-100 dark:bg-green-900/30 ring-2 ring-inset ring-green-500' : 'hover:bg-gray-100 dark:hover:bg-gray-800'">
                                        <div class="flex justify-center items-center gap-4 text-lg">
                                            <span class="font-bold text-blue-600 dark:text-blue-400" x-text="matrix['AB'][0]"></span>
                                            <span class="text-gray-400">,</span>
                                            <span class="font-bold text-red-600 dark:text-red-400" x-text="matrix['AB'][1]"></span>
                                        </div>
                                        <div x-show="isNash(0, 1)" class="mt-2 text-xs font-bold text-green-600 dark:text-green-400 uppercase tracking-wider">
                                            {{ __('Nash Equilibrium') }}
                                        </div>
                                    </td>
                                </tr>
                                <!-- Row 2: Bottom -->
                                <tr>
                                    <th class="p-4 border-r border-gray-200 dark:border-gray-700 font-semibold text-gray-600 dark:text-gray-400">
                                        <span x-text="playerA"></span> ({{ __('Bottom') }})<br>
                                        <span class="text-xs font-normal text-gray-500">{{ $gameScenario->player_a_strategy_2 }}</span>
                                    </th>
                                    <!-- Cell BA -->
                                    <td class="p-6 border border-gray-200 dark:border-gray-700 transition-colors duration-300"
                                        :class="isNash(1, 0) ? 'bg-green-100 dark:bg-green-900/30 ring-2 ring-inset ring-green-500' : 'hover:bg-gray-100 dark:hover:bg-gray-800'">
                                        <div class="flex justify-center items-center gap-4 text-lg">
                                            <span class="font-bold text-blue-600 dark:text-blue-400" x-text="matrix['BA'][0]"></span>
                                            <span class="text-gray-400">,</span>
                                            <span class="font-bold text-red-600 dark:text-red-400" x-text="matrix['BA'][1]"></span>
                                        </div>
                                        <div x-show="isNash(1, 0)" class="mt-2 text-xs font-bold text-green-600 dark:text-green-400 uppercase tracking-wider">
                                            {{ __('Nash Equilibrium') }}
                                        </div>
                                    </td>
                                    <!-- Cell BB -->
                                    <td class="p-6 border border-gray-200 dark:border-gray-700 transition-colors duration-300"
                                        :class="isNash(1, 1) ? 'bg-green-100 dark:bg-green-900/30 ring-2 ring-inset ring-green-500' : 'hover:bg-gray-100 dark:hover:bg-gray-800'">
                                        <div class="flex justify-center items-center gap-4 text-lg">
                                            <span class="font-bold text-blue-600 dark:text-blue-400" x-text="matrix['BB'][0]"></span>
                                            <span class="text-gray-400">,</span>
                                            <span class="font-bold text-red-600 dark:text-red-400" x-text="matrix['BB'][1]"></span>
                                        </div>
                                        <div x-show="isNash(1, 1)" class="mt-2 text-xs font-bold text-green-600 dark:text-green-400 uppercase tracking-wider">
                                            {{ __('Nash Equilibrium') }}
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                        <p>
                            <span class="font-bold text-blue-600 dark:text-blue-400">{{ __('Blue') }}</span>: {{ __('Player A Payoff') }}, 
                            <span class="font-bold text-red-600 dark:text-red-400">{{ __('Red') }}</span>: {{ __('Player B Payoff') }}.
                        </p>
                        <p class="mt-1">
                            {{ __('Cells highlighted in') }} <span class="text-green-600 font-bold">{{ __('Green') }}</span> {{ __('represent a Pure Nash Equilibrium') }}.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
