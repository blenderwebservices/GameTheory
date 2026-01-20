<x-layouts.app>
    <x-slot name="title">{{ __('View Simulation') }} - {{ $gameScenario->name }}</x-slot>

    <div x-data="{
        matrix: {{ json_encode($gameScenario->payoff_matrix) }},
        playerA: '{{ $gameScenario->player_a_name }}',
        playerB: '{{ $gameScenario->player_b_name }}',
        p: 0,
        q: 0,
        playerAStrategy1: '{{ $gameScenario->player_a_strategy_1 }}',
        playerAStrategy2: '{{ $gameScenario->player_a_strategy_2 }}',
        playerBStrategy1: '{{ $gameScenario->player_b_strategy_1 }}',
        playerBStrategy2: '{{ $gameScenario->player_b_strategy_2 }}',
        bestResponsesA: [],
        bestResponsesB: [],
        nashEquilibria: [],
        dominantInfo: { A: null, B: null },
        showMixed: false,
        showDominant: false,
        init() {
            this.calculateNash();
            this.calculateMixed();
            this.calculateDominant();
            this.$watch('matrix', (val) => {
                this.calculateNash();
                this.calculateMixed();
                this.calculateDominant();
            }, { deep: true });
        },
        swapStrategies() {
            // Swap text for A
            let tempA = this.playerAStrategy1;
            this.playerAStrategy1 = this.playerAStrategy2;
            this.playerAStrategy2 = tempA;

            // Swap text for B
            let tempB = this.playerBStrategy1;
            this.playerBStrategy1 = this.playerBStrategy2;
            this.playerBStrategy2 = tempB;

            // Swap Matrix Logic
            // Swap Rows (A1 <-> A2)
            let tempRow1 = {...this.matrix['AA']};
            let tempRow2 = {...this.matrix['AB']};
            
            this.matrix['AA'] = this.matrix['BA'];
            this.matrix['AB'] = this.matrix['BB'];
            this.matrix['BA'] = tempRow1;
            this.matrix['BB'] = tempRow2;

            // Swap Columns (B1 <-> B2)
            // Now that rows are swapped, we swap columns within each row
            // Col 1 is now (BA, BB original positions), Col 2 is (AA, AB original positions) if just rows swapped
            // But we operate on keys: AA->AB, BA->BB
            
            // New AA (was BA) <-> New AB (was BB)
            let tempAA = {...this.matrix['AA']};
            this.matrix['AA'] = this.matrix['AB'];
            this.matrix['AB'] = tempAA;

            // New BA (was AA) <-> New BB (was AB)
            let tempBA = {...this.matrix['BA']};
            this.matrix['BA'] = this.matrix['BB'];
            this.matrix['BB'] = tempBA;
            
            window.dispatchEvent(new CustomEvent('notify', { 
                detail: { message: '{{ __('Strategies order switched') }}', type: 'info' } 
            }));
        },
        swapPlayers() {
            // Swap Names
            let tempName = this.playerA;
            this.playerA = this.playerB;
            this.playerB = tempName;

            // Swap Strategies (Set A <-> Set B)
            let tempS1 = this.playerAStrategy1;
            let tempS2 = this.playerAStrategy2;
            this.playerAStrategy1 = this.playerBStrategy1;
            this.playerAStrategy2 = this.playerBStrategy2;
            this.playerBStrategy1 = tempS1;
            this.playerBStrategy2 = tempS2;

            // Transpose Matrix and Swap Payoffs [0]<->[1]
            // Original:
            // AA(0,1) AB(0,1)
            // BA(0,1) BB(0,1)
            
            // New (Transposed):
            // AA = Old AA (swapped payoffs)
            // AB = Old BA (swapped payoffs)
            // BA = Old AB (swapped payoffs)
            // BB = Old BB (swapped payoffs)

            let oldAA = [...this.matrix['AA']];
            let oldAB = [...this.matrix['AB']];
            let oldBA = [...this.matrix['BA']];
            let oldBB = [...this.matrix['BB']];

            this.matrix['AA'] = [oldAA[1], oldAA[0]];
            this.matrix['AB'] = [oldBA[1], oldBA[0]];
            this.matrix['BA'] = [oldAB[1], oldAB[0]];
            this.matrix['BB'] = [oldBB[1], oldBB[0]];
            
            window.dispatchEvent(new CustomEvent('notify', { 
                detail: { message: '{{ __('Players switched') }}', type: 'info' } 
            }));
        },
        calculateMixed() {
            // ... (existing logic)
            // Payoffs for Player A
            let A11 = Number(this.matrix['AA'][0]); // Top, Left
            let A12 = Number(this.matrix['AB'][0]); // Top, Right
            let A21 = Number(this.matrix['BA'][0]); // Bottom, Left
            let A22 = Number(this.matrix['BB'][0]); // Bottom, Right

            // Payoffs for Player B
            let B11 = Number(this.matrix['AA'][1]); // Top, Left
            let B12 = Number(this.matrix['AB'][1]); // Top, Right
            let B21 = Number(this.matrix['BA'][1]); // Bottom, Left
            let B22 = Number(this.matrix['BB'][1]); // Bottom, Right

            // Calculate q (Probability B plays Left) to make A indifferent
            // q*A11 + (1-q)*A12 = q*A21 + (1-q)*A22
            // q(A11 - A12 - A21 + A22) = A22 - A12
            let denomA = (A11 - A12 - A21 + A22);
            this.q = denomA !== 0 ? (A22 - A12) / denomA : 0;

            // Calculate p (Probability A plays Top) to make B indifferent
            // p*B11 + (1-p)*B21 = p*B12 + (1-p)*B22
            // p(B11 - B21 - B12 + B22) = B22 - B21
            let denomB = (B11 - B21 - B12 + B22);
            this.p = denomB !== 0 ? (B22 - B21) / denomB : 0;
        },
        formatProb(val) {
            if (val < 0 || val > 1) return val.toFixed(2) + ' (Inválido)';
            return (val * 100).toFixed(1) + '%';
        },
        calculateNash() {
            this.bestResponsesA = [];
            this.bestResponsesB = [];

            // 1. Best responses for Player A (Rows) given each Column of B
            // Column 1 (Left): Compare A11 (AA) vs A21 (BA)
            let A11 = Number(this.matrix['AA'][0]);
            let A21 = Number(this.matrix['BA'][0]);
            if (A11 > A21) this.bestResponsesA.push('AA');
            else if (A21 > A11) this.bestResponsesA.push('BA');
            else { this.bestResponsesA.push('AA'); this.bestResponsesA.push('BA'); } // Indifferent

            // Column 2 (Right): Compare A12 (AB) vs A22 (BB)
            let A12 = Number(this.matrix['AB'][0]);
            let A22 = Number(this.matrix['BB'][0]);
            if (A12 > A22) this.bestResponsesA.push('AB');
            else if (A22 > A12) this.bestResponsesA.push('BB');
            else { this.bestResponsesA.push('AB'); this.bestResponsesA.push('BB'); } // Indifferent

            // 2. Best responses for Player B (Columns) given each Row of A
            // Row 1 (Top): Compare B11 (AA) vs B12 (AB)
            let B11 = Number(this.matrix['AA'][1]);
            let B12 = Number(this.matrix['AB'][1]);
            if (B11 > B12) this.bestResponsesB.push('AA');
            else if (B12 > B11) this.bestResponsesB.push('AB');
            else { this.bestResponsesB.push('AA'); this.bestResponsesB.push('AB'); } // Indifferent

            // Row 2 (Bottom): Compare B21 (BA) vs B22 (BB)
            let B21 = Number(this.matrix['BA'][1]);
            let B22 = Number(this.matrix['BB'][1]);
            if (B21 > B22) this.bestResponsesB.push('BA');
            else if (B22 > B21) this.bestResponsesB.push('BB');
            else { this.bestResponsesB.push('BA'); this.bestResponsesB.push('BB'); } // Indifferent

            // Intersection
            this.nashEquilibria = this.bestResponsesA.filter(val => this.bestResponsesB.includes(val));
        },
        calculateDominant() {
            this.dominantInfo = { A: null, B: null };

            // Player A (Rows) - Compare Strategy 1 vs Strategy 2
            let A11 = Number(this.matrix['AA'][0]);
            let A12 = Number(this.matrix['AB'][0]);
            let A21 = Number(this.matrix['BA'][0]);
            let A22 = Number(this.matrix['BB'][0]);

            if (A11 > A21 && A12 > A22) {
                this.dominantInfo.A = {
                    strategy: this.playerAStrategy1,
                    reason: `{{ __('Strictly dominant because') }} ${A11} > ${A21} {{ __('and') }} ${A12} > ${A22}`
                };
            } else if (A21 > A11 && A22 > A12) {
                this.dominantInfo.A = {
                    strategy: this.playerAStrategy2,
                    reason: `{{ __('Strictly dominant because') }} ${A21} > ${A11} {{ __('and') }} ${A22} > ${A12}`
                };
            }

            // Player B (Columns) - Compare Strategy 1 vs Strategy 2
            let B11 = Number(this.matrix['AA'][1]);
            let B12 = Number(this.matrix['AB'][1]);
            let B21 = Number(this.matrix['BA'][1]);
            let B22 = Number(this.matrix['BB'][1]);

            if (B11 > B12 && B21 > B22) {
                this.dominantInfo.B = {
                    strategy: this.playerBStrategy1,
                    reason: `{{ __('Strictly dominant because') }} ${B11} > ${B12} {{ __('and') }} ${B21} > ${B22}`
                };
            } else if (B12 > B11 && B22 > B21) {
                this.dominantInfo.B = {
                    strategy: this.playerBStrategy2,
                    reason: `{{ __('Strictly dominant because') }} ${B12} > ${B11} {{ __('and') }} ${B22} > ${B21}`
                };
            }
        },
        async saveMatrix() {
            try {
                let payload = {
                    payoff_matrix: this.matrix,
                    player_a_name: this.playerA,
                    player_b_name: this.playerB,
                    player_a_strategy_1: this.playerAStrategy1,
                    player_a_strategy_2: this.playerAStrategy2,
                    player_b_strategy_1: this.playerBStrategy1,
                    player_b_strategy_2: this.playerBStrategy2
                };
                
                let response = await fetch('{{ route('simulation.update', $gameScenario) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(payload)
                });
                
                if (response.ok) {
                    window.dispatchEvent(new CustomEvent('notify', { 
                        detail: { message: '{{ __('Changes saved successfully') }}', type: 'success' } 
                    }));
                } else {
                    window.dispatchEvent(new CustomEvent('notify', { 
                        detail: { message: '{{ __('Error saving changes') }}', type: 'error' } 
                    }));
                }
            } catch (error) {
                console.error('Error:', error);
                window.dispatchEvent(new CustomEvent('notify', { 
                    detail: { message: '{{ __('An error occurred') }}', type: 'error' } 
                }));
            }
        },
        async resetMatrix() {
            if (!confirm('{{ __('Are you sure you want to reset the simulation to default values?') }}')) return;

            try {
                let response = await fetch('{{ route('simulation.reset', $gameScenario) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({})
                });

                if (response.ok) {
                    let data = await response.json();
                    this.matrix = data.payoff_matrix;
                    this.playerA = data.player_a_name;
                    this.playerB = data.player_b_name;
                    this.playerAStrategy1 = data.player_a_strategy_1;
                    this.playerAStrategy2 = data.player_a_strategy_2;
                    this.playerBStrategy1 = data.player_b_strategy_1;
                    this.playerBStrategy2 = data.player_b_strategy_2;
                    
                    window.dispatchEvent(new CustomEvent('notify', { 
                        detail: { message: '{{ __('Simulation reset to defaults') }}', type: 'success' } 
                    }));
                } else {
                    window.dispatchEvent(new CustomEvent('notify', { 
                        detail: { message: '{{ __('Error resetting simulation') }}', type: 'error' } 
                    }));
                }
            } catch (error) {
                console.error('Error:', error);
                window.dispatchEvent(new CustomEvent('notify', { 
                    detail: { message: '{{ __('An error occurred') }}', type: 'error' } 
                }));
            }
        },
        isNash(key) {
            return this.nashEquilibria.includes(key);
        },
        formatNumber(value) {
            if (value === '' || value === null || isNaN(value)) return '';
            return new Intl.NumberFormat('en-US').format(value);
        },
        updatePayoff(rowKey, index, value) {
            // Remove commas and non-numeric chars (allow minus at start)
            let clean = value.replace(/[^0-9-]/g, '');
            // Prevent multiple minuses
            if ((clean.match(/-/g) || []).length > 1) {
                 clean = clean.replace(/-/g, '');
                 // Simple fallback: if user typed double minus, just reset or assume positive. 
                 // Better: keep first minus.
                 if (value.startsWith('-')) clean = '-' + clean; 
            }
            
            let num = parseInt(clean);
            
            // Handle empty or invalid input
            if (isNaN(num)) {
                // If it's just a minus sign, don't update matrix yet, letting user type
                if (clean === '-') return; 
                num = 0; 
            }

            // Clamping
            const min = -10000000;
            const max = 10000000;
            if (num < min) num = min;
            if (num > max) num = max;

            this.matrix[rowKey][index] = num;
        }
    }">
        <div class="mb-8">
            <a href="{{ route('simulation.index') }}"
                class="text-blue-600 dark:text-blue-400 hover:underline flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                {{ __('Back to Simulations') }}
            </a>
        </div>

        <div class="relative overflow-hidden bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6"
            @click.window="if(!($el.contains($event.target))) { showMixed = false; showDominant = false; }">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">{{ $gameScenario->name }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mb-8">{{ $gameScenario->description }}</p>

            <div class="space-y-6">

                <div class="p-6 bg-gray-50 dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">
                            {{ __('Payoff Matrix') }}: <span x-text="playerA"></span> vs <span x-text="playerB"></span>
                        </h2>
                        <div class="flex gap-2">
                             <!-- Mixed Strategies Button -->
                            <button @click.stop="showMixed = !showMixed; showDominant = false"
                                :disabled="nashEquilibria.length === 1"
                                :class="nashEquilibria.length === 1 ? 'opacity-50 cursor-not-allowed grayscale' : 'hover:bg-yellow-600'"
                                class="px-3 py-1 bg-yellow-500 text-white text-sm font-semibold rounded shadow transition-all duration-300">
                                {{ __('Mixed Strategies') }}
                            </button>
                            
                            <!-- Dominant Strategies Button -->
                            <button @click.stop="showDominant = !showDominant; showMixed = false"
                                :disabled="!dominantInfo.A && !dominantInfo.B"
                                :class="(!dominantInfo.A && !dominantInfo.B) ? 'opacity-50 cursor-not-allowed grayscale' : 'hover:bg-purple-600'"
                                class="px-3 py-1 bg-purple-500 text-white text-sm font-semibold rounded shadow transition-all duration-300">
                                {{ __('Dominant Strategies') }}
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-center border-collapse">
                            <thead>
                                <tr>
                                    <th
                                        class="p-4 border-b-2 border-gray-200 dark:border-gray-700 text-left align-bottom">
                                        <div class="flex flex-col">
                                            <span
                                                class="text-sm font-normal text-gray-500 dark:text-gray-400">{{ __('Rows') }}:</span>
                                            <span class="font-bold text-blue-600 dark:text-blue-400"
                                                x-text="playerA"></span>
                                        </div>
                                    </th>
                                    <th colspan="2" class="p-4 border-b-2 border-gray-200 dark:border-gray-700">
                                        <div class="flex flex-col items-center">
                                            <span
                                                class="text-sm font-normal text-gray-500 dark:text-gray-400">{{ __('Columns') }}:</span>
                                            <span class="font-bold text-red-600 dark:text-red-400"
                                                x-text="playerB"></span>
                                        </div>
                                    </th>
                                </tr>
                                <tr>
                                    <th class="p-4 border-b-2 border-gray-200 dark:border-gray-700"></th>
                                    <th
                                        class="p-4 border-b-2 border-gray-200 dark:border-gray-700 text-red-600 dark:text-red-400 font-bold text-lg">
                                        <span x-text="playerBStrategy1"></span>
                                    </th>
                                    <th
                                        class="p-4 border-b-2 border-gray-200 dark:border-gray-700 text-red-600 dark:text-red-400 font-bold text-lg">
                                        <span x-text="playerBStrategy2"></span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Row 1: Top -->
                                <tr>
                                    <th
                                        class="p-4 border-r-2 border-gray-200 dark:border-gray-700 text-blue-600 dark:text-blue-400 font-bold text-lg vertical-text">
                                        <span x-text="playerAStrategy1"></span>
                                    </th>
                                    <!-- Cell AA -->
                                    <td class="p-6 border border-gray-200 dark:border-gray-700 transition-colors duration-300"
                                        :class="isNash('AA') ? 'bg-green-100 dark:bg-green-900/30 ring-2 ring-inset ring-green-500' : 'hover:bg-gray-100 dark:hover:bg-gray-800'">
                                        <div class="flex justify-center items-center gap-2 text-lg">
                                            <input type="text" inputmode="text" 
                                                :value="formatNumber(matrix['AA'][0])"
                                                @input="updatePayoff('AA', 0, $event.target.value)"
                                                @blur="$el.value = formatNumber(matrix['AA'][0])"
                                                class="w-24 text-center font-bold text-blue-600 dark:text-blue-400 bg-transparent border-b border-blue-200 focus:border-blue-500 focus:outline-none"
                                                :class="{ 'underline': bestResponsesA.includes('AA') }">
                                            <span class="text-gray-400">,</span>
                                            <input type="text" inputmode="text" 
                                                :value="formatNumber(matrix['AA'][1])"
                                                @input="updatePayoff('AA', 1, $event.target.value)"
                                                @blur="$el.value = formatNumber(matrix['AA'][1])"
                                                class="w-24 text-center font-bold text-red-600 dark:text-red-400 bg-transparent border-b border-red-200 focus:border-red-500 focus:outline-none"
                                                :class="{ 'underline': bestResponsesB.includes('AA') }">
                                        </div>
                                        <div x-show="isNash('AA')"
                                            class="mt-2 text-xs font-bold text-green-600 dark:text-green-400 uppercase tracking-wider">
                                            {{ __('Nash Equilibrium') }}
                                        </div>
                                    </td>
                                    <!-- Cell AB -->
                                    <td class="p-6 border border-gray-200 dark:border-gray-700 transition-colors duration-300"
                                        :class="isNash('AB') ? 'bg-green-100 dark:bg-green-900/30 ring-2 ring-inset ring-green-500' : 'hover:bg-gray-100 dark:hover:bg-gray-800'">
                                        <div class="flex justify-center items-center gap-2 text-lg">
                                            <input type="text" inputmode="text" 
                                                :value="formatNumber(matrix['AB'][0])"
                                                @input="updatePayoff('AB', 0, $event.target.value)"
                                                @blur="$el.value = formatNumber(matrix['AB'][0])"
                                                class="w-24 text-center font-bold text-blue-600 dark:text-blue-400 bg-transparent border-b border-blue-200 focus:border-blue-500 focus:outline-none"
                                                :class="{ 'underline': bestResponsesA.includes('AB') }">
                                            <span class="text-gray-400">,</span>
                                            <input type="text" inputmode="text" 
                                                :value="formatNumber(matrix['AB'][1])"
                                                @input="updatePayoff('AB', 1, $event.target.value)"
                                                @blur="$el.value = formatNumber(matrix['AB'][1])"
                                                class="w-24 text-center font-bold text-red-600 dark:text-red-400 bg-transparent border-b border-red-200 focus:border-red-500 focus:outline-none"
                                                :class="{ 'underline': bestResponsesB.includes('AB') }">
                                        </div>
                                        <div x-show="isNash('AB')"
                                            class="mt-2 text-xs font-bold text-green-600 dark:text-green-400 uppercase tracking-wider">
                                            {{ __('Nash Equilibrium') }}
                                        </div>
                                    </td>
                                </tr>
                                <!-- Row 2: Bottom -->
                                <tr>
                                    <th
                                        class="p-4 border-r-2 border-gray-200 dark:border-gray-700 text-blue-600 dark:text-blue-400 font-bold text-lg vertical-text">
                                        <span x-text="playerAStrategy2"></span>
                                    </th>
                                    <!-- Cell BA -->
                                    <td class="p-6 border border-gray-200 dark:border-gray-700 transition-colors duration-300"
                                        :class="isNash('BA') ? 'bg-green-100 dark:bg-green-900/30 ring-2 ring-inset ring-green-500' : 'hover:bg-gray-100 dark:hover:bg-gray-800'">
                                        <div class="flex justify-center items-center gap-2 text-lg">
                                            <input type="text" inputmode="text" 
                                                :value="formatNumber(matrix['BA'][0])"
                                                @input="updatePayoff('BA', 0, $event.target.value)"
                                                @blur="$el.value = formatNumber(matrix['BA'][0])"
                                                class="w-24 text-center font-bold text-blue-600 dark:text-blue-400 bg-transparent border-b border-blue-200 focus:border-blue-500 focus:outline-none"
                                                :class="{ 'underline': bestResponsesA.includes('BA') }">
                                            <span class="text-gray-400">,</span>
                                            <input type="text" inputmode="text" 
                                                :value="formatNumber(matrix['BA'][1])"
                                                @input="updatePayoff('BA', 1, $event.target.value)"
                                                @blur="$el.value = formatNumber(matrix['BA'][1])"
                                                class="w-24 text-center font-bold text-red-600 dark:text-red-400 bg-transparent border-b border-red-200 focus:border-red-500 focus:outline-none"
                                                :class="{ 'underline': bestResponsesB.includes('BA') }">
                                        </div>
                                        <div x-show="isNash('BA')"
                                            class="mt-2 text-xs font-bold text-green-600 dark:text-green-400 uppercase tracking-wider">
                                            {{ __('Nash Equilibrium') }}
                                        </div>
                                    </td>
                                    <!-- Cell BB -->
                                    <td class="p-6 border border-gray-200 dark:border-gray-700 transition-colors duration-300"
                                        :class="isNash('BB') ? 'bg-green-100 dark:bg-green-900/30 ring-2 ring-inset ring-green-500' : 'hover:bg-gray-100 dark:hover:bg-gray-800'">
                                        <div class="flex justify-center items-center gap-2 text-lg">
                                            <input type="text" inputmode="text" 
                                                :value="formatNumber(matrix['BB'][0])"
                                                @input="updatePayoff('BB', 0, $event.target.value)"
                                                @blur="$el.value = formatNumber(matrix['BB'][0])"
                                                class="w-24 text-center font-bold text-blue-600 dark:text-blue-400 bg-transparent border-b border-blue-200 focus:border-blue-500 focus:outline-none"
                                                :class="{ 'underline': bestResponsesA.includes('BB') }">
                                            <span class="text-gray-400">,</span>
                                            <input type="text" inputmode="text" 
                                                :value="formatNumber(matrix['BB'][1])"
                                                @input="updatePayoff('BB', 1, $event.target.value)"
                                                @blur="$el.value = formatNumber(matrix['BB'][1])"
                                                class="w-24 text-center font-bold text-red-600 dark:text-red-400 bg-transparent border-b border-red-200 focus:border-red-500 focus:outline-none"
                                                :class="{ 'underline': bestResponsesB.includes('BB') }">
                                        </div>
                                        <div x-show="isNash('BB')"
                                            class="mt-2 text-xs font-bold text-green-600 dark:text-green-400 uppercase tracking-wider">
                                            {{ __('Nash Equilibrium') }}
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                        <p>
                            <span class="font-bold text-blue-600 dark:text-blue-400">{{ __('Blue') }}</span>:
                            {{ __('Player A Payoff') }},
                            <span class="font-bold text-red-600 dark:text-red-400">{{ __('Red') }}</span>:
                            {{ __('Player B Payoff') }}.
                        </p>
                        <p class="mt-1">
                            {{ __('Cells highlighted in') }} <span
                                class="text-green-600 font-bold">{{ __('Green') }}</span>
                            {{ __('represent a Pure Nash Equilibrium') }}.
                        </p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div
                    class="flex justify-between items-center bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg border border-gray-100 dark:border-gray-600 mt-6">
                    <div class="flex gap-4">
                        <button @click="swapStrategies()"
                            class="px-4 py-2 bg-indigo-100 hover:bg-indigo-200 text-indigo-700 rounded-lg transition-colors text-sm font-semibold">
                            {{ __('Switch Strategies Order') }}
                        </button>
                        <button @click="swapPlayers()"
                            class="px-4 py-2 bg-purple-100 hover:bg-purple-200 text-purple-700 rounded-lg transition-colors text-sm font-semibold">
                            {{ __('Switch Players') }}
                        </button>
                    </div>

                    <div class="flex gap-4">
                        <button @click="resetMatrix()"
                            class="px-6 py-2 bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-500 transition-colors font-semibold">
                            {{ __('Reset Default') }}
                        </button>
                        <button @click="saveMatrix()"
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow transition-colors font-semibold">
                            {{ __('Save Changes') }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Overlays -->
            
            <!-- Dominant Strategies Overlay -->
            <div x-show="showDominant"
                x-transition:enter="transform transition ease-out duration-300"
                x-transition:enter-start="translate-x-full"
                x-transition:enter-end="translate-x-0"
                x-transition:leave="transform transition ease-in duration-300"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="translate-x-full"
                class="absolute inset-0 z-20 bg-white dark:bg-gray-800 p-6 overflow-y-auto"
                style="display: none;">
                
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('Dominant Strategies') }}</h2>
                    <button @click="showDominant = false" class="text-blue-600 dark:text-blue-400 hover:underline text-sm font-semibold">
                        {{ __('Back to Payoff Matrix ->') }}
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Player A Dominant Strategy -->
                    <div x-show="dominantInfo.A"
                        class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg border border-blue-100 dark:border-blue-800">
                        <h3 class="font-semibold text-blue-800 dark:text-blue-300 mb-2 flex items-center">
                            <span class="mr-2">{{ __('Player A') }} (<span x-text="playerA"></span>):</span>
                        </h3>
                        <p class="text-lg font-bold text-blue-600 dark:text-blue-400 mb-2"
                            x-text="dominantInfo.A?.strategy"></p>
                        <p class="text-sm text-gray-700 dark:text-gray-300" x-text="dominantInfo.A?.reason"></p>
                    </div>

                    <!-- Player B Dominant Strategy -->
                    <div x-show="dominantInfo.B"
                        class="bg-red-50 dark:bg-red-900/20 p-4 rounded-lg border border-red-100 dark:border-red-800">
                        <h3 class="font-semibold text-red-800 dark:text-red-300 mb-2 flex items-center">
                            <span class="mr-2">{{ __('Player B') }} (<span x-text="playerB"></span>):</span>
                        </h3>
                        <p class="text-lg font-bold text-red-600 dark:text-red-400 mb-2" x-text="dominantInfo.B?.strategy">
                        </p>
                        <p class="text-sm text-gray-700 dark:text-gray-300" x-text="dominantInfo.B?.reason"></p>
                    </div>
                </div>
                
                <div x-show="!dominantInfo.A && !dominantInfo.B" class="text-center text-gray-500 mt-10">
                    {{ __('No dominant strategies found.') }}
                </div>
            </div>

            <!-- Probability Calculation Overlay -->
            <div x-show="showMixed"
                x-transition:enter="transform transition ease-out duration-300"
                x-transition:enter-start="translate-x-full"
                x-transition:enter-end="translate-x-0"
                x-transition:leave="transform transition ease-in duration-300"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="translate-x-full"
                class="absolute inset-0 z-20 bg-white dark:bg-gray-800 p-6 overflow-y-auto"
                style="display: none;">
                
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ __('Probability Calculation (Mixed Strategies)') }}
                    </h2>
                    <button @click="showMixed = false" class="text-blue-600 dark:text-blue-400 hover:underline text-sm font-semibold">
                        {{ __('Back to Payoff Matrix ->') }}
                    </button>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Theory & Origin -->
                    <div class="space-y-4">
                        <div
                            class="bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded-lg border border-yellow-100 dark:border-yellow-800">
                            <h3 class="font-semibold text-yellow-800 dark:text-yellow-300 mb-2">
                                {{ __('Calculation Origin') }}
                            </h3>
                            <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed mb-3">
                                {{ __('When no pure Nash equilibrium exists, players randomize their strategies to make the opponent indifferent. We calculate p and q by equating expected utilities.') }}
                            </p>

                            <div
                                class="bg-white dark:bg-gray-800 p-3 rounded border border-yellow-200 dark:border-yellow-700 text-xs">
                                <strong
                                    class="block text-gray-800 dark:text-gray-200 mb-1">{{ __('Result Validity:') }}</strong>
                                <ul class="list-disc ml-4 space-y-1 text-gray-600 dark:text-gray-400">
                                    <li>
                                        <span
                                            class="text-green-600 dark:text-green-400 font-semibold">{{ __('Valid (0 to 1):') }}</span>
                                        {{ __('If the result is between 0 and 1 (0% to 100%), a Mixed Strategy Nash Equilibrium exists.') }}
                                    </li>
                                    <li>
                                        <span
                                            class="text-red-600 dark:text-red-400 font-semibold">{{ __('Invalid (< 0 or > 1):') }}</span>
                                        {{ __('If the result is negative or greater than 1, mathematically it is not a real probability. This indicates that no mixed equilibrium exists in this game (likely a pure dominant strategy exists.') }}
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Matrix Multiplication Visualization -->
                        <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg border border-blue-100 dark:border-blue-800 overflow-x-auto">
                            <h3 class="font-semibold text-blue-800 dark:text-blue-300 mb-4 text-sm">
                                {{ __('Matrix Verification') }}
                            </h3>
                            
                            <div class="flex flex-col gap-6">
                                <!-- Player A Indifference (finding y/q) -->
                                <div>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mb-2 font-medium">{{ __('To find B\'s strategy (y, 1-y):') }}</p>
                                    <div class="flex items-center gap-2 font-mono text-sm">
                                        <!-- Matrix A -->
                                        <div class="flex flex-col justify-center border-l-2 border-r-2 border-gray-800 dark:border-gray-300 px-2 py-1">
                                            <div class="flex gap-4 justify-between">
                                                <span x-text="matrix['AA'][0]" class="w-6 text-right"></span>
                                                <span x-text="matrix['AB'][0]" class="w-6 text-right"></span>
                                            </div>
                                            <div class="flex gap-4 justify-between">
                                                <span x-text="matrix['BA'][0]" class="w-6 text-right"></span>
                                                <span x-text="matrix['BB'][0]" class="w-6 text-right"></span>
                                            </div>
                                        </div>
                                        <!-- Times -->
                                        <span class="text-gray-500">×</span>
                                        <!-- Vector y -->
                                        <div class="flex flex-col justify-center border-l-2 border-r-2 border-gray-800 dark:border-gray-300 px-2 py-1">
                                            <div class="text-center">y</div>
                                            <div class="text-gray-500 border-t border-gray-400 my-0.5"></div>
                                            <div class="text-center">1-y</div>
                                        </div>
                                        <span class="text-gray-500">=</span>
                                        <div class="text-xs text-gray-500 italic">E(A)</div>
                                    </div>
                                </div>

                                <!-- Player B Indifference (finding x/p) -->
                                <div>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mb-2 font-medium">{{ __('To find A\'s strategy (x, 1-x):') }}</p>
                                    <div class="flex items-center gap-2 font-mono text-sm">
                                        <!-- Matrix B (Transposed for mult) -->
                                        <div class="flex flex-col justify-center border-l-2 border-r-2 border-gray-800 dark:border-gray-300 px-2 py-1">
                                            <div class="flex gap-4 justify-between">
                                                <span x-text="matrix['AA'][1]" class="w-6 text-right"></span>
                                                <span x-text="matrix['BA'][1]" class="w-6 text-right"></span>
                                            </div>
                                            <div class="flex gap-4 justify-between">
                                                <span x-text="matrix['AB'][1]" class="w-6 text-right"></span>
                                                <span x-text="matrix['BB'][1]" class="w-6 text-right"></span>
                                            </div>
                                        </div>
                                        <!-- Times -->
                                        <span class="text-gray-500">×</span>
                                        <!-- Vector x -->
                                        <div class="flex flex-col justify-center border-l-2 border-r-2 border-gray-800 dark:border-gray-300 px-2 py-1">
                                            <div class="text-center">x</div>
                                            <div class="text-gray-500 border-t border-gray-400 my-0.5"></div>
                                            <div class="text-center">1-x</div>
                                        </div>
                                        <span class="text-gray-500">=</span>
                                        <div class="text-xs text-gray-500 italic">E(B)</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div
                            class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-lg border border-gray-200 dark:border-gray-700 font-mono text-xs overflow-x-auto">
                            <p class="mb-3 text-gray-500 dark:text-gray-400 font-sans font-bold">
                                {{ __('Algebraic Formulas and Substitution:') }}
                            </p>

                            <!-- Formula q -->
                            <div class="mb-4">
                                <p class="text-blue-600 dark:text-blue-400 font-bold mb-1">
                                    {{ __('q (Probability B Left):') }} <span class="text-xs font-normal text-gray-500">(y)</span>
                                </p>
                                <p class="mb-1 text-gray-500">
                                    q = (A22 - A12) / (A11 - A12 - A21 + A22)
                                </p>
                                <p class="text-gray-700 dark:text-gray-300">
                                    q = (<span x-text="matrix['BB'][0]"></span> - <span x-text="matrix['AB'][0]"></span>) /
                                    (<span x-text="matrix['AA'][0]"></span> - <span x-text="matrix['AB'][0]"></span> - <span
                                        x-text="matrix['BA'][0]"></span> + <span x-text="matrix['BB'][0]"></span>)
                                </p>
                            </div>

                            <!-- Formula p -->
                            <div>
                                <p class="text-red-600 dark:text-red-400 font-bold mb-1">{{ __('p (Probability A Top):') }} <span class="text-xs font-normal text-gray-500">(x)</span>
                                </p>
                                <p class="mb-1 text-gray-500">
                                    p = (B22 - B21) / (B11 - B21 - B12 + B22)
                                </p>
                                <p class="text-gray-700 dark:text-gray-300">
                                    p = (<span x-text="matrix['BB'][1]"></span> - <span x-text="matrix['BA'][1]"></span>) /
                                    (<span x-text="matrix['AA'][1]"></span> - <span x-text="matrix['BA'][1]"></span> - <span
                                        x-text="matrix['AB'][1]"></span> + <span x-text="matrix['BB'][1]"></span>)
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Results -->
                    <div class="space-y-4">
                        <div
                            class="bg-indigo-50 dark:bg-indigo-900/20 p-4 rounded-lg border border-indigo-100 dark:border-indigo-800">
                            <h3 class="font-semibold text-indigo-800 dark:text-indigo-300 mb-4">
                                {{ __('Calculated Results') }}
                            </h3>

                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div class="bg-white dark:bg-gray-800 p-3 rounded shadow-sm text-center">
                                    <span class="block text-xs text-gray-500 uppercase">{{ __('Probability p (A)') }}</span>
                                    <span class="text-xl font-bold text-blue-600 dark:text-blue-400"
                                        x-text="formatProb(p)"></span>
                                </div>
                                <div class="bg-white dark:bg-gray-800 p-3 rounded shadow-sm text-center">
                                    <span class="block text-xs text-gray-500 uppercase">{{ __('Probability q (B)') }}</span>
                                    <span class="text-xl font-bold text-red-600 dark:text-red-400"
                                        x-text="formatProb(q)"></span>
                                </div>
                            </div>

                            <div class="border-t border-indigo-200 dark:border-indigo-700 pt-4">
                                <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    {{ __('Probability of each Cell') }}
                                </h4>
                                <div class="grid grid-cols-2 gap-2 text-xs">
                                    <div class="p-2 bg-white dark:bg-gray-800 rounded flex justify-between">
                                        <span>Top-Left (p*q):</span>
                                        <span class="font-bold" x-text="formatProb(p * q)"></span>
                                    </div>
                                    <div class="p-2 bg-white dark:bg-gray-800 rounded flex justify-between">
                                        <span>Top-Right (p*(1-q)):</span>
                                        <span class="font-bold" x-text="formatProb(p * (1-q))"></span>
                                    </div>
                                    <div class="p-2 bg-white dark:bg-gray-800 rounded flex justify-between">
                                        <span>Bottom-Left ((1-p)*q):</span>
                                        <span class="font-bold" x-text="formatProb((1-p) * q)"></span>
                                    </div>
                                    <div class="p-2 bg-white dark:bg-gray-800 rounded flex justify-between">
                                        <span>Bottom-Right ((1-p)*(1-q)):</span>
                                        <span class="font-bold" x-text="formatProb((1-p) * (1-q))"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Interpretation Card -->
        <div
            class="mt-8 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">{{ __('Payoff Matrix Interpretation') }}
            </h2>

            <div class="space-y-6 text-gray-700 dark:text-gray-300">
                <!-- Point 1 -->
                <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg border border-blue-100 dark:border-blue-800">
                    <h3 class="font-semibold text-blue-800 dark:text-blue-300 mb-2 flex items-center">
                        <span
                            class="bg-blue-200 dark:bg-blue-800 text-blue-800 dark:text-blue-200 w-6 h-6 rounded-full flex items-center justify-center text-sm mr-2">1</span>
                        {{ __('Positive and Negative Values Selection') }}
                    </h3>
                    <div class="ml-8 text-sm leading-relaxed space-y-2">
                        <p>{{ __('Values in the matrix represent the utility or benefit each player receives. A scale of -10 to 10 has been selected to represent preference intensity:') }}
                        </p>
                        <ul class="list-disc ml-5 space-y-1">
                            <li>
                                    <span
                                    class="font-semibold text-gray-800 dark:text-gray-200">{{ __('Range -10,000,000 to 10,000,000:') }}</span>
                                {{ __('Allows modeling a full spectrum of results, from large losses to large gains.') }}
                            </li>
                            <li>
                                <span
                                    class="font-semibold text-green-600 dark:text-green-400">{{ __('Values 1 to 10 (Positive):') }}</span>
                                {{ __('Represent gains, benefits, or satisfaction. The higher the number, the greater the reward.') }}
                            </li>
                            <li>
                                <span class="font-semibold text-gray-600 dark:text-gray-400">{{ __('Value 0:') }}</span>
                                {{ __('Represents a neutral outcome, where there is neither significant gain nor loss (indifference point).') }}
                            </li>
                            <li>
                                <span
                                    class="font-semibold text-red-600 dark:text-red-400">{{ __('Values -10 to -1 (Negative):') }}</span>
                                {{ __('Represent costs, losses, punishments, or undesirable consequences. The lower the number (more negative), the worse the outcome.') }}
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Point 2 -->
                <div
                    class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg border border-green-100 dark:border-green-800">
                    <h3 class="font-semibold text-green-800 dark:text-green-300 mb-2 flex items-center">
                        <span
                            class="bg-green-200 dark:bg-green-800 text-green-800 dark:text-green-200 w-6 h-6 rounded-full flex items-center justify-center text-sm mr-2">2</span>
                        {{ __('Nash Equilibrium (Green Cells)') }}
                    </h3>
                    <p class="ml-8 text-sm leading-relaxed">
                        {{ __('Cells highlighted in green indicate a Nash Equilibrium. This means that, at that point, neither player has incentives to unilaterally change their strategy. If a player changes their decision while the other maintains theirs, their payoff would decrease or not improve. It is the "stable" state of the game where both strategies coincide optimally.') }}
                    </p>
                </div>

                <!-- Point 3 -->
                <div
                    class="bg-purple-50 dark:bg-purple-900/20 p-4 rounded-lg border border-purple-100 dark:border-purple-800">
                    <h3 class="font-semibold text-purple-800 dark:text-purple-300 mb-2 flex items-center">
                        <span
                            class="bg-purple-200 dark:bg-purple-800 text-purple-800 dark:text-purple-200 w-6 h-6 rounded-full flex items-center justify-center text-sm mr-2">3</span>
                        {{ __('Maximization, Optimization, and Rationality') }}
                    </h3>
                    <div class="ml-8 grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div
                            class="bg-white dark:bg-gray-800 p-3 rounded border border-purple-100 dark:border-purple-700">
                            <strong
                                class="block text-purple-700 dark:text-purple-400 mb-1">{{ __('Rationality') }}</strong>
                            {{ __('It is assumed that players are rational: they think logically, understand the rules, and always act to satisfy their own interests.') }}
                        </div>
                        <div
                            class="bg-white dark:bg-gray-800 p-3 rounded border border-purple-100 dark:border-purple-700">
                            <strong
                                class="block text-purple-700 dark:text-purple-400 mb-1">{{ __('Maximization') }}</strong>
                            {{ __('The fundamental goal is to maximize their own utility. Each player seeks to obtain the highest possible value in the matrix for themselves.') }}
                        </div>
                        <div
                            class="bg-white dark:bg-gray-800 p-3 rounded border border-purple-100 dark:border-purple-700">
                            <strong
                                class="block text-purple-700 dark:text-purple-400 mb-1">{{ __('Optimization') }}</strong>
                            {{ __('It is the process of choosing the "best response" to the opponent\'s actions. It is not just seeking the highest number, but the best possible result given the circumstances.') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Comments Section -->
        <div
            class="mt-8 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">{{ __('Comments') }}</h2>
            @livewire('comments', ['record' => $gameScenario])
        </div>

    </div>
</x-layouts.app>