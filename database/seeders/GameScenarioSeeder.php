<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GameScenarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Prisoner's Dilemma
        \App\Models\GameScenario::create([
            'name' => [
                'en' => 'Prisoner\'s Dilemma',
                'es' => 'Dilema del Prisionero',
            ],
            'description' => [
                'en' => 'Two members of a criminal gang are arrested and imprisoned. Each prisoner is in solitary confinement with no means of communicating with the other. The prosecutors lack sufficient evidence to convict the pair on the principal charge, but they have enough to convict both on a lesser charge. Simultaneously, the prosecutors offer each prisoner a bargain.',
                'es' => 'Dos miembros de una banda criminal son arrestados y encarcelados. Cada prisionero está en confinamiento solitario sin medios para comunicarse con el otro. Los fiscales carecen de pruebas suficientes para condenar a la pareja por el cargo principal, pero tienen suficientes para condenar a ambos por un cargo menor. Simultáneamente, los fiscales ofrecen a cada prisionero un trato.',
            ],
            'player_a_name' => [
                'en' => 'Prisoner A',
                'es' => 'Prisionero A',
            ],
            'player_a_strategy_1' => [
                'en' => 'Confess',
                'es' => 'Confesar',
            ],
            'player_a_strategy_2' => [
                'en' => 'Silent',
                'es' => 'Callar',
            ],
            'player_b_name' => [
                'en' => 'Prisoner B',
                'es' => 'Prisionero B',
            ],
            'player_b_strategy_1' => [
                'en' => 'Confess',
                'es' => 'Confesar',
            ],
            'player_b_strategy_2' => [
                'en' => 'Silent',
                'es' => 'Callar',
            ],
            'payoff_matrix' => [
                'AA' => [-5, -5], // Confess / Confess
                'AB' => [0, -10], // Confess / Silent
                'BA' => [-10, 0], // Silent / Confess
                'BB' => [-1, -1], // Silent / Silent
            ],
        ]);

        // 2. Battle of the Sexes
        \App\Models\GameScenario::create([
            'name' => [
                'en' => 'Battle of the Sexes',
                'es' => 'La Guerra de los Sexos',
            ],
            'description' => [
                'en' => 'A couple wants to go out together, but they have different preferences. One prefers the Opera, the other prefers Football. They would rather go to the same place than different places.',
                'es' => 'Una pareja quiere salir junta, pero tienen preferencias diferentes. Uno prefiere la Ópera, el otro prefiere el Fútbol. Preferirían ir al mismo lugar que a lugares diferentes.',
            ],
            'player_a_name' => [
                'en' => 'Husband',
                'es' => 'Esposo',
            ],
            'player_a_strategy_1' => [
                'en' => 'Opera',
                'es' => 'Ópera',
            ],
            'player_a_strategy_2' => [
                'en' => 'Football',
                'es' => 'Fútbol',
            ],
            'player_b_name' => [
                'en' => 'Wife',
                'es' => 'Esposa',
            ],
            'player_b_strategy_1' => [
                'en' => 'Opera',
                'es' => 'Ópera',
            ],
            'player_b_strategy_2' => [
                'en' => 'Football',
                'es' => 'Fútbol',
            ],
            'payoff_matrix' => [
                'AA' => [3, 2], // Opera / Opera
                'AB' => [0, 0], // Opera / Football
                'BA' => [0, 0], // Football / Opera
                'BB' => [2, 3], // Football / Football
            ],
        ]);

        // 3. Game of Chicken
        \App\Models\GameScenario::create([
            'name' => [
                'en' => 'Game of Chicken',
                'es' => 'Juego de la Gallina',
            ],
            'description' => [
                'en' => 'Two drivers drive towards each other on a collision course: one must swerve, or both may die in the crash, but if one driver swerves and the other does not, the one who swerved will be called a "chicken".',
                'es' => 'Dos conductores conducen uno hacia el otro en curso de colisión: uno debe desviarse, o ambos pueden morir en el choque, pero si un conductor se desvía y el otro no, el que se desvió será llamado "gallina".',
            ],
            'player_a_name' => [
                'en' => 'Driver A',
                'es' => 'Conductor A',
            ],
            'player_a_strategy_1' => [
                'en' => 'Swerve',
                'es' => 'Desviarse',
            ],
            'player_a_strategy_2' => [
                'en' => 'Straight',
                'es' => 'Seguir',
            ],
            'player_b_name' => [
                'en' => 'Driver B',
                'es' => 'Conductor B',
            ],
            'player_b_strategy_1' => [
                'en' => 'Swerve',
                'es' => 'Desviarse',
            ],
            'player_b_strategy_2' => [
                'en' => 'Straight',
                'es' => 'Seguir',
            ],
            'payoff_matrix' => [
                'AA' => [0, 0],   // Swerve / Swerve
                'AB' => [-1, 1],  // Swerve / Straight
                'BA' => [1, -1],  // Straight / Swerve
                'BB' => [-10, -10], // Straight / Straight
            ],
        ]);

        // 4. Stag Hunt
        \App\Models\GameScenario::create([
            'name' => [
                'en' => 'Stag Hunt',
                'es' => 'Caza del Ciervo',
            ],
            'description' => [
                'en' => 'Two individuals go out on a hunt. Each can individually choose to hunt a stag or hunt a hare. Each individual must choose an action without knowing the choice of the other. If an individual hunts a stag, they must have the cooperation of their partner in order to succeed. An individual can get a hare by himself, but a hare is worth less than a stag.',
                'es' => 'Dos individuos salen a cazar. Cada uno puede elegir individualmente cazar un ciervo o cazar una liebre. Cada individuo debe elegir una acción sin conocer la elección del otro. Si un individuo caza un ciervo, debe tener la cooperación de su compañero para tener éxito. Un individuo puede conseguir una liebre por sí mismo, pero una liebre vale menos que un ciervo.',
            ],
            'player_a_name' => [
                'en' => 'Hunter A',
                'es' => 'Cazador A',
            ],
            'player_a_strategy_1' => [
                'en' => 'Stag',
                'es' => 'Ciervo',
            ],
            'player_a_strategy_2' => [
                'en' => 'Hare',
                'es' => 'Liebre',
            ],
            'player_b_name' => [
                'en' => 'Hunter B',
                'es' => 'Cazador B',
            ],
            'player_b_strategy_1' => [
                'en' => 'Stag',
                'es' => 'Ciervo',
            ],
            'player_b_strategy_2' => [
                'en' => 'Hare',
                'es' => 'Liebre',
            ],
            'payoff_matrix' => [
                'AA' => [5, 5], // Stag / Stag
                'AB' => [0, 3], // Stag / Hare
                'BA' => [3, 0], // Hare / Stag
                'BB' => [1, 1], // Hare / Hare
            ],
        ]);
    }
}
