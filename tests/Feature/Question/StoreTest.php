<?php

use App\Models\User;
use Laravel\Sanctum\Sanctum;
use function Pest\Laravel\postJson;
use function Pest\Laravel\assertDatabaseHas;

it('should be able to store a new question', function () {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    postJson(route('questions.store', [
        'question' => 'Loren ipsum Jeremias?',
    ]))->assertSuccessful();

    assertDatabaseHas('questions', [
        'user_id' => $user->id,
        'question' => 'Loren ipsum Jeremias?',
    ]);

});
