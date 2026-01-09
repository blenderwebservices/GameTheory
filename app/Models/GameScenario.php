<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;

class GameScenario extends Model
{
    use HasFactory, HasTranslations;

    public $translatable = [
        'name',
        'description',
        'player_a_name',
        'player_b_name',
        'player_a_strategy_1',
        'player_a_strategy_2',
        'player_b_strategy_1',
        'player_b_strategy_2',
    ];

    protected $fillable = [
        'name',
        'description',
        'user_id',
        'player_a_name',
        'player_a_strategy_1',
        'player_a_strategy_2',
        'player_b_name',
        'player_b_strategy_1',
        'player_b_strategy_2',
        'payoff_matrix',
        'default_payoff_matrix',
        'default_configuration',
    ];

    protected $casts = [
        'payoff_matrix' => 'array',
        'default_payoff_matrix' => 'array',
        'default_configuration' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    //
}
