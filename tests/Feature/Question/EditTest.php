<?php

use App\Models\{Question, User};
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\{assertDatabaseHas, postJson, putJson};

it('should be able to update a question', function () {

    $user = User::factory()->create();

    $question = Question::factory()->create([
        'user_id'=>$user->id
    ]);

    Sanctum::actingAs($user);

    putJson(route('questions.update', $question), [
        'question' => 'Updating a question?'
    ])->assertOk();

    assertDatabaseHas('questions', [
        'id' => $question->id,
        'user_id'  => $user->id,
        'question' => 'Updating a question?',
    ]);

});

