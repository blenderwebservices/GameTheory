<x-layouts.app>
    <x-slot name="title">{{ __('Home - Game Theory') }}</x-slot>

    <div class="flex justify-center mb-12">
        <h1 class="text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600">
            {{ __('Nash Equilibrium in Non-Cooperative Games') }}
        </h1>
    </div>

    <div class="prose dark:prose-invert max-w-none mb-12">
        <div class="flex justify-center mb-8">
            <img src="{{ asset('assets/images/nash-equilibrium-v2.jpg') }}" alt="Nash Equilibrium Illustration" class="rounded-xl shadow-2xl w-full md:w-2/3 lg:w-1/2 border border-gray-200 dark:border-gray-700">
        </div>

        <p class="text-lg text-gray-700 dark:text-gray-300 leading-relaxed mb-6">
            {{ __('In game theory, the Nash equilibrium, named after the mathematician John Nash, is the most common way to define the solution of a non-cooperative game involving two or more players. In a Nash equilibrium, each player is assumed to know the equilibrium strategies of the other players, and no player has anything to gain by changing only their own strategy.') }}
        </p>
        
        <p class="text-lg text-gray-700 dark:text-gray-300 leading-relaxed mb-8">
            {{ __('For a basic 2x2 payoff matrix, we look for a pair of strategies where neither player can increase their payoff strictly by deviating, given the other player\'s choice.') }}
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <!-- Card 1: Definition -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden flex flex-col">
            <img src="{{ asset('assets/images/what-is-it.jpg') }}" alt="{{ __('What is it?') }}" class="w-full h-48 object-cover">
            <div class="p-6">
                <h2 class="text-2xl font-bold mb-3 text-gray-900 dark:text-white">{{ __('What is it?') }}</h2>
                <p class="text-gray-600 dark:text-gray-400">
                    {{ __('A stable state of a system involving the interaction of different participants, in which no participant can gain by a unilateral change of strategy if the strategies of the others remain unchanged.') }}
                </p>
            </div>
        </div>

        <!-- Card 2: Calculation -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden flex flex-col">
            <img src="{{ asset('assets/images/how-to-calculate.jpg') }}" alt="{{ __('How to Calculate') }}" class="w-full h-48 object-cover">
            <div class="p-6">
                <h2 class="text-2xl font-bold mb-3 text-gray-900 dark:text-white">{{ __('How to Calculate') }}</h2>
                <p class="text-gray-600 dark:text-gray-400">
                    {{ __('For player A, find the best response strategy for each of player B\'s strategies. Do the same for player B. If a cell in the matrix represents a best response for both players simultaneously, that cell is a Nash Equilibrium.') }}
                </p>
            </div>
        </div>

        <!-- Card 3: Non-Cooperative -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden flex flex-col">
            <img src="{{ asset('assets/images/non-cooperative.jpg') }}" alt="{{ __('Non-Cooperative') }}" class="w-full h-48 object-cover">
            <div class="p-6">
                <h2 class="text-2xl font-bold mb-3 text-gray-900 dark:text-white">{{ __('Non-Cooperative') }}</h2>
                <p class="text-gray-600 dark:text-gray-400">
                    {{ __('This model assumes players cannot form binding agreements. Each acts independently to maximize their own self-interest, often leading to outcomes that are not optimal for the group (like in the Prisoner\'s Dilemma).') }}
                </p>
            </div>
        </div>
    </div>
    
    <div class="mt-12 flex justify-center">
        <a href="{{ route('simulation.index') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-md transition-transform transform hover:scale-105">
            {{ __('Go to Simulations') }}
            <svg class="ml-2 -mr-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
        </a>
    </div>

</x-layouts.app>
