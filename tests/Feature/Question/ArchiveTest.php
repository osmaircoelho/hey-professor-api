<?php

use App\Models\{Question, User};
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\{assertDatabaseHas,
    assertDatabaseMissing,
    assertNotSoftDeleted,
    assertSoftDeleted,
    deleteJson};

it('should be able to archive a question', function () {
    $user     = User::factory()->create();
    $question = Question::factory()->for($user)->create();

    Sanctum::actingAs($user);

    deleteJson(route('questions.archive', $question))
    ->assertNoContent();

    assertSoftDeleted('questions', ['id' => $question->id]);

});

it('should allow that only creator can archive', function () {
    $user     = User::factory()->create();
    $user2    = User::factory()->create();
    $question = Question::factory()->for($user)->create();

    Sanctum::actingAs($user2);

    deleteJson(route('questions.archive', $question))
        ->assertForbidden();

    assertNotSoftDeleted('questions', ['id' => $question->id]);
});
