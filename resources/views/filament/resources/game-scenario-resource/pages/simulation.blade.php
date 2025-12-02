<x-filament-panels::page>
    <div x-data="{
        matrix: @js($this->record->payoff_matrix),
        playerA: @js($this->record->player_a_name),
        playerB: @js($this->record->player_b_name),
        isNash(r, c) {
            // Map indices to keys: 0->A, 1->B
            let rowChar = r === 0 ? 'A' : 'B';
            let colChar = c === 0 ? 'A' : 'B';
            let key = rowChar + colChar;
            
            if (!this.matrix[key]) return false;

            let payA = Number(this.matrix[key][0]);
            let payB = Number(this.matrix[key][1]);

            // Check Best Response for A (compare with other row, same col)
            let otherRowChar = r === 0 ? 'B' : 'A';
            let otherKeyA = otherRowChar + colChar;
            let otherPayA = Number(this.matrix[otherKeyA][0]);
            let bestForA = payA >= otherPayA;

            // Check Best Response for B (compare with other col, same row)
            let otherColChar = c === 0 ? 'B' : 'A';
            let otherKeyB = rowChar + otherColChar;
            let otherPayB = Number(this.matrix[otherKeyB][1]);
            let bestForB = payB >= otherPayB;

            return bestForA && bestForB;
        }
    }" class="space-y-6">

        <div class="p-6 bg-white rounded-xl shadow dark:bg-gray-900 border border-gray-200 dark:border-gray-700">
            <h2 class="text-xl font-bold mb-4" x-text="'Payoff Matrix: ' + playerA + ' vs ' + playerB"></h2>
            
            <div class="overflow-x-auto">
                <table class="w-full text-center border-collapse">
                    <thead>
                        <tr>
                            <th class="p-4"></th>
                            <th class="p-4 border-b border-gray-200 dark:border-gray-700 font-semibold text-gray-600 dark:text-gray-400">
                                <span x-text="playerB"></span> (Left)
                            </th>
                            <th class="p-4 border-b border-gray-200 dark:border-gray-700 font-semibold text-gray-600 dark:text-gray-400">
                                <span x-text="playerB"></span> (Right)
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Row 1: Top -->
                        <tr>
                            <th class="p-4 border-r border-gray-200 dark:border-gray-700 font-semibold text-gray-600 dark:text-gray-400">
                                <span x-text="playerA"></span> (Top)
                            </th>
                            <!-- Cell AA -->
                            <td class="p-6 border border-gray-200 dark:border-gray-700 transition-colors duration-300"
                                :class="isNash(0, 0) ? 'bg-green-100 dark:bg-green-900/30 ring-2 ring-inset ring-green-500' : 'hover:bg-gray-50 dark:hover:bg-gray-800'">
                                <div class="flex justify-center items-center gap-4 text-lg">
                                    <span class="font-bold text-blue-600 dark:text-blue-400" x-text="matrix['AA'][0]"></span>
                                    <span class="text-gray-400">,</span>
                                    <span class="font-bold text-red-600 dark:text-red-400" x-text="matrix['AA'][1]"></span>
                                </div>
                                <div x-show="isNash(0, 0)" class="mt-2 text-xs font-bold text-green-600 dark:text-green-400 uppercase tracking-wider">
                                    Nash Equilibrium
                                </div>
                            </td>
                            <!-- Cell AB -->
                            <td class="p-6 border border-gray-200 dark:border-gray-700 transition-colors duration-300"
                                :class="isNash(0, 1) ? 'bg-green-100 dark:bg-green-900/30 ring-2 ring-inset ring-green-500' : 'hover:bg-gray-50 dark:hover:bg-gray-800'">
                                <div class="flex justify-center items-center gap-4 text-lg">
                                    <span class="font-bold text-blue-600 dark:text-blue-400" x-text="matrix['AB'][0]"></span>
                                    <span class="text-gray-400">,</span>
                                    <span class="font-bold text-red-600 dark:text-red-400" x-text="matrix['AB'][1]"></span>
                                </div>
                                <div x-show="isNash(0, 1)" class="mt-2 text-xs font-bold text-green-600 dark:text-green-400 uppercase tracking-wider">
                                    Nash Equilibrium
                                </div>
                            </td>
                        </tr>
                        <!-- Row 2: Bottom -->
                        <tr>
                            <th class="p-4 border-r border-gray-200 dark:border-gray-700 font-semibold text-gray-600 dark:text-gray-400">
                                <span x-text="playerA"></span> (Bottom)
                            </th>
                            <!-- Cell BA -->
                            <td class="p-6 border border-gray-200 dark:border-gray-700 transition-colors duration-300"
                                :class="isNash(1, 0) ? 'bg-green-100 dark:bg-green-900/30 ring-2 ring-inset ring-green-500' : 'hover:bg-gray-50 dark:hover:bg-gray-800'">
                                <div class="flex justify-center items-center gap-4 text-lg">
                                    <span class="font-bold text-blue-600 dark:text-blue-400" x-text="matrix['BA'][0]"></span>
                                    <span class="text-gray-400">,</span>
                                    <span class="font-bold text-red-600 dark:text-red-400" x-text="matrix['BA'][1]"></span>
                                </div>
                                <div x-show="isNash(1, 0)" class="mt-2 text-xs font-bold text-green-600 dark:text-green-400 uppercase tracking-wider">
                                    Nash Equilibrium
                                </div>
                            </td>
                            <!-- Cell BB -->
                            <td class="p-6 border border-gray-200 dark:border-gray-700 transition-colors duration-300"
                                :class="isNash(1, 1) ? 'bg-green-100 dark:bg-green-900/30 ring-2 ring-inset ring-green-500' : 'hover:bg-gray-50 dark:hover:bg-gray-800'">
                                <div class="flex justify-center items-center gap-4 text-lg">
                                    <span class="font-bold text-blue-600 dark:text-blue-400" x-text="matrix['BB'][0]"></span>
                                    <span class="text-gray-400">,</span>
                                    <span class="font-bold text-red-600 dark:text-red-400" x-text="matrix['BB'][1]"></span>
                                </div>
                                <div x-show="isNash(1, 1)" class="mt-2 text-xs font-bold text-green-600 dark:text-green-400 uppercase tracking-wider">
                                    Nash Equilibrium
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                <p>
                    <span class="font-bold text-blue-600 dark:text-blue-400">Blue</span>: Player A Payoff, 
                    <span class="font-bold text-red-600 dark:text-red-400">Red</span>: Player B Payoff.
                </p>
                <p class="mt-1">
                    Cells highlighted in <span class="text-green-600 font-bold">Green</span> represent a Pure Nash Equilibrium.
                </p>
            </div>
        </div>
    </div>
</x-filament-panels::page>
