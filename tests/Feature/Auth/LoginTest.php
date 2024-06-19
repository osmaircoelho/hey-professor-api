<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

use function Pest\Laravel\{assertAuthenticatedAs, postJson};

it('should be able to login', function () {
    $user = User::factory()->create([
        'email'    => 'joe@doe.com',
        'password' => hash::make('password'),
    ]);

    postJson(route('login'), [
        'email'    => 'joe@doe.com',
        'password' => 'password',
    ])->assertNoContent();

    assertAuthenticatedAs($user);
});

it('should check if the email and password is valid', function ($email, $password) {
    $user = User::factory()->create(['email' => 'joe@doe.com', 'password' => hash::make('password'), ]);

    postJson('login', [
        'email'    => $email,
        'password' => $password,
    ])->assertJsonValidationErrors([
        'email' => __('auth.failed'),
    ]);
})->with([
    'wrong email'    => ['wrong@email.com', 'password'],
    'wrong password' => ['wrong@email.com', 'password123'],
    'invalid email'  => ['invalid-email', 'password123'],
]);

test('required fields ', function () {
    $user = User::factory()->create(['email' => 'joe@doe.com', 'password' => hash::make('password'), ]);

    postJson('login', [
        'email'    => '',
        'password' => '',
    ])->assertJsonValidationErrors([
        'email'    => __('validation.required', ['attribute' => 'email']),
        'password' => __('validation.required', ['attribute' => 'password']),
    ]);
});
