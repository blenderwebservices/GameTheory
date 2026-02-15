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
                'password' => 'password1.',
                'role' => 'admin',
            ]
        );

        // Ensure role is admin if user already existed
        if ($admin->role !== 'admin') {
            $admin->role = 'admin';
            $admin->save();
        }

        // Ensure prompt requested specific password 'password1.'
        // Note: 'password' => 'password1.' might be hashed if 'password' => 'hashed' cast is on User model BUT firstOrCreate second arg is attributes.
        // The User model has 'password' => 'hashed' cast. So simple assignment works in Laravel 10+.

        // Create Game Scenarios from Templates
        $templates = \App\Models\GameScenario::getTemplates();

        foreach ($templates as $key => $template) {
            // Use updateOrCreate to avoid duplicates
            // We match based on the English name, assuming it's unique enough for templates
            // Alternatively, we could add a 'slug' to the templates if available, but name->en is safer given the structure

            $nameEn = $template['name']['en'];

            // We need to find if a scenario with this name exists for this user
            // Since 'name' is a translatable field (JSON), we need to query it carefully if we were doing raw SQL
            // But here we can iterate or just use a simple check.
            // A better approach for the seeder is to check if one exists with this specific English name for this user.

            $existing = \App\Models\GameScenario::where('user_id', $admin->id)
                ->where('name->en', $nameEn)
                ->first();

            if ($existing) {
                $existing->update(array_merge($template, [
                    'user_id' => $admin->id
                ]));
            } else {
                \App\Models\GameScenario::create(array_merge($template, [
                    'user_id' => $admin->id,
                ]));
            }
        }
    }
}
