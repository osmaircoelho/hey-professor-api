<?php

use App\Models\{Question, User};
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\{assertDatabaseHas, putJson};

it('should be able to update a question', function () {

    $user = User::factory()->create();

    $question = Question::factory()->create(['user_id' => $user->id]);

    Sanctum::actingAs($user);

    putJson(route('questions.update', $question), [
        'question' => 'Updating a question?',
    ])->assertOk();

    assertDatabaseHas('questions', [
        'id'       => $question->id,
        'user_id'  => $user->id,
        'question' => 'Updating a question?',
    ]);

});

describe('validation rules', function () {
    test('question::required', function () {
        $user     = User::factory()->create();
        $question = Question::factory()->create(['user_id' => $user->id]);

        Sanctum::actingAs($user);

        putJson(route('questions.update', $question), [
            'question' => '',
        ])->assertJsonValidationErrors([
            'question' => 'required',
        ]);
    });

    test('question::ending with question mark', function () {
        $user     = User::factory()->create();
        $question = Question::factory()->create(['user_id' => $user->id]);

        Sanctum::actingAs($user);

        putJson(route('questions.update', $question), [
            'question' => 'Question should have a mark',
        ])->assertJsonValidationErrors([
            'question' => 'The question should end with question mark (?).',
        ]);
    });

    test('question::min characters should be 10', function () {
        $user     = User::factory()->create();
        $question = Question::factory()->create(['user_id' => $user->id]);

        Sanctum::actingAs($user);

        putJson(route('questions.update', $question), [
            'question' => 'Question?',
        ])->assertJsonValidationErrors([
            'question' => 'least 10 characters',
        ]);
    });

    test('question::should be unique only if id is different', function () {

        $user = User::factory()->create();

        $question = Question::factory()->create([
            'question' => 'Lorem ipsum jeremias?',
            'user_id'  => $user->id,
        ]);

        Sanctum::actingAs($user);

        putJson(route('questions.update', $question), [
            'question' => 'Lorem ipsum jeremias?',
        ])->assertOk();
    });
});

describe('security', function () {
    test('only the person who create the question can update the same question', function () {

        #cria dois usuarios
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        # usuario 1 criamos a pergunta, somente ele tem acesso a editar
        $question = Question::factory()->create(['user_id' => $user1->id]);

        # logamos na plataforma com usuario2
        Sanctum::actingAs($user2);

        # tentamos fazer a nossa atualizacao
        # apresenta erro pois o usuario 2 esta logado
        putJson(route('questions.update', $question), [
            'question' => 'updating the question?',
        ])->assertForbidden();

        # id e question tem que ser iguais
        assertDatabaseHas('questions', [
            'id'       => $question->id,
            'question' => $question->question,
        ]);
    });
});

test('after creating we should return a status 200 with the created question', function () {
    $user     = User::factory()->create();
    $question = Question::factory()->for($user, 'user')->create();

    Sanctum::actingAs($user);

    $request = putJson(
        route('questions.update', $question),
        ['question' => 'Lorem ipsum jeremias?']
    )->assertOk();

    $question = Question::latest()->first();

    $request->assertJson([
        'data' => [
            'id'         => $question->id,
            'question'   => $question->question,
            'status'     => $question->status,
            'created_by' => [
                'id'   => $user->id,
                'name' => $user->name,
            ],
            'created_at' => $question->created_at->format('Y-m-d h:i:s'),
            'updated_at' => $question->updated_at->format('Y-m-d h:i:s'),
        ],
    ]);
});
