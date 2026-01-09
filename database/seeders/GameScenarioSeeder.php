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
        // Create Admin User
        $admin = \App\Models\User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin',
                'password' => 'password1.', // Using plain text as requested in prompt, usually should be hashed but Laravel mutator/cast handles it or Hash::make()
            ]
        );

        // Ensure prompt requested specific password 'password1.'
        // Note: 'password' => 'password1.' might be hashed if 'password' => 'hashed' cast is on User model BUT firstOrCreate second arg is attributes.
        // The User model has 'password' => 'hashed' cast. So simple assignment works in Laravel 10+.

        // Create Game Scenarios from Templates
        $templates = \App\Models\GameScenario::getTemplates();

        foreach ($templates as $key => $template) {
            \App\Models\GameScenario::create(array_merge($template, [
                'user_id' => $admin->id,
            ]));
        }
    }
}
