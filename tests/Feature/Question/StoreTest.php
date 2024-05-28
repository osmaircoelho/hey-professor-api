<?php

use App\Models\User;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\{assertDatabaseHas, postJson};

it('should be able to store a new question', function () {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    postJson(route('questions.store', [
        'question' => 'Loren ipsum Jeremias?',
    ]))->assertSuccessful();

    assertDatabaseHas('questions', [
        'user_id'  => $user->id,
        'question' => 'Loren ipsum Jeremias?',
    ]);

});

test('after creating a new question, I need to make sure that it creates on _draft_ status', function () {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    postJson(route('questions.store', [
        'question' => 'Loren ipsum jeremias?',
    ]))->assertSuccessful();

    assertDatabaseHas('questions', [
       'user_id' => $user->id,
       'status' => 'draft',
       'question' => 'Loren ipsum jeremias?'
    ]);
});

describe('validation rules', function (){
    test('question::required', function (){
       $user = User::factory()->create();

       Sanctum::actingAs($user);

       postJson(route('questions.store', []))
           ->assertJsonValidationErrors([
              'question' => 'required'
           ]);
    });
});
