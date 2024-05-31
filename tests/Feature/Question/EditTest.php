<?php

use App\Models\{Question, User};
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\{assertDatabaseHas, postJson, putJson};

it('should be able to update a question', function () {

    $user = User::factory()->create();

    $question = Question::factory()->create(['user_id'=>$user->id]);

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

describe('validation rules', function () {
    test('question::required', function () {
        $user = User::factory()->create();
        $question = Question::factory()->create(['user_id' => $user->id]);

        Sanctum::actingAs($user);

        putJson(route('questions.update', $question), [
            'question' => ''
        ])->assertJsonValidationErrors([
            'question' => 'required'
        ]);
    });

    test('question::ending with question mark', function () {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        postJson(route('questions.store', [
            'question' => 'Question without a question mark',
        ]))

            ->assertJsonValidationErrors([
                'question' => 'The question should end with question mark (?).',
            ]);
    });

    test('question::min characters should be 10', function () {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        postJson(route('questions.store', [
            'question' => 'Question?',
        ]))

            ->assertJsonValidationErrors([
                'question' => 'least 10 characters',
            ]);
    });

    test('question::should be unique', function () {

        $user = User::factory()->create();

        Question::factory()->create([
            'question' => 'Lorem ipsum jeremias?',
            'status'   => 'draft',
            'user_id'  => $user->id,
        ]);

        Sanctum::actingAs($user);

        postJson(route('questions.store', [
            'question' => 'Lorem ipsum jeremias?',
        ]))

            ->assertJsonValidationErrors([
                'question' => 'already been taken',
            ]);
    });
});

