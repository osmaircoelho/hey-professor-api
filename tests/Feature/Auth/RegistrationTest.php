<?php

use App\Models\User;

use Illuminate\Support\Facades\Hash;

use function Pest\Laravel\{assertDatabaseHas, postJson};
use function PHPUnit\Framework\assertTrue;

it('should be able to register in the application', function () {
    postJson(route('register'), [
        'name'     => 'John Doe',
        'email'    => 'joe@doe.com',
        'password' => 'password',
    ])->assertOk();

    assertDatabaseHas('users', [
        'name'  => 'John Doe',
        'email' => 'joe@doe.com',
    ]);

    $joeDoe = User::whereEmail('joe@doe.com')->first();

    assertTrue(
        Hash::check('password', $joeDoe->password)
    );
});

describe('validations', function () {

    test('name', function ($rule, $value, $meta = []) {
        postJson(route('register'), ['name' => $value])
            ->assertJsonValidationErrors([
                'name' => __(
                    'validation.' . $rule,
                    array_merge(['attribute' => 'name'], $meta)
                ),
            ]);
    })->with([
        'required' => ['required', ''],
        'min:3'    => ['min', 'AB', ['min' => 3]],
        'max:255'  => ['max', str_repeat('*', 256), ['max' => 255]],
    ]);

    /*test('name', function ($rule, $value, $meta = []) {
        postJson(route('register'), ['name' => $value])
            ->assertJsonValidationErrors([
                'name' => __(
                    'validation.' . $rule,
                    array_merge(['attribute' => 'email'], $meta)
                ),
            ]);
    })->with([
        'required' => ['required', ''],
        'min:3'    => ['min', 'AB', ['min' => 3]],
        'max:255'  => ['max', str_repeat('*', 256), ['max' => 255]],
        'email'    => ['email' => ['not-email']],
    ]);*/

});
