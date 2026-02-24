<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\GameScenario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Parallax\FilamentComments\Livewire\CommentsComponent;
use Tests\TestCase;

class CommentsComponentTest extends TestCase
{
    use RefreshDatabase;

    public function test_component_can_render(): void
    {
        $user = User::factory()->create();
        $scenario = GameScenario::factory()->create(['user_id' => $user->id]);

        Livewire::actingAs($user)
            ->test(CommentsComponent::class, ['record' => $scenario])
            ->assertStatus(200);
    }

    public function test_user_can_create_comment(): void
    {
        $user = User::factory()->create();
        $scenario = GameScenario::factory()->create(['user_id' => $user->id]);

        Livewire::actingAs($user)
            ->test(CommentsComponent::class, ['record' => $scenario])
            ->set('data.comment', 'This is a test comment')
            ->call('create')
            ->assertHasNoErrors()
            ->assertStatus(200);

        $this->assertDatabaseHas('filament_comments', [
            'comment' => 'This is a test comment',
            'user_id' => $user->id,
            'subject_id' => $scenario->id,
            'subject_type' => $scenario->getMorphClass(),
        ]);
    }
}
