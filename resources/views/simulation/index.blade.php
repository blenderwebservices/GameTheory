<x-layouts.app>
    <x-slot name="title">{{ __('Simulations - Game Theory') }}</x-slot>

    <div class="flex justify-center mb-12">
        <h1 class="text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600">
            {{ __('Available Simulations') }}
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

                    @if(auth()->user()->role === 'admin')
                        <div class="mt-6 pt-6 border-t border-gray-100 dark:border-gray-700 space-y-2">
                            <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                                <svg class="w-3.5 h-3.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                <span class="font-medium text-gray-700 dark:text-gray-300">{{ $scenario->user->name ?? 'N/A' }}</span>
                                <span class="mx-2">&bull;</span>
                                <span>{{ $scenario->user->email ?? 'N/A' }}</span>
                            </div>
                            <div class="flex flex-col gap-1 text-[10px] text-gray-400 uppercase tracking-wider">
                                <div class="flex justify-between">
                                    <span>{{ __('Created') }}:</span>
                                    <span>{{ $scenario->created_at->format('Y-m-d H:i') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>{{ __('Modified') }}:</span>
                                    <span>{{ $scenario->updated_at->format('Y-m-d H:i') }}</span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                
                <div class="bg-gray-50 dark:bg-gray-700/50 p-4 border-t border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <div class="flex items-center text-gray-500 dark:text-gray-400">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <span class="text-xs font-semibold uppercase tracking-wider">
                            {{ $scenario->filamentComments()->count() }} {{ trans_choice(__('comment|comments'), $scenario->filamentComments()->count()) }}
                        </span>
                    </div>
                    <a href="{{ route('simulation.show', $scenario) }}" class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 flex items-center transition-colors">
                        {{ __('View Simulation') }}
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</x-layouts.app>
