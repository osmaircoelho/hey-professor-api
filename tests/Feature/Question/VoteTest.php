<?php

use App\Models\{Question, User};
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\{assertDatabaseHas, postJson};

function createUserAndLogin()
{
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    return $user;
}

it('should be able to like a question', function () {
    #arrange
    $user = createUserAndLogin();

    $question = Question::factory()->published()->create();

    postJson(
        route('questions.vote', [
            'question' => $question,
            'vote'     => 'like',
        ])
    )->assertNoContent();

    expect($question->votes)
        ->toHaveCount(1);

    assertDatabaseHas('votes', [
        'question_id' => $question->id,
        'user_id'     => $user->id,
        'like'        => 1,
    ]);
});
