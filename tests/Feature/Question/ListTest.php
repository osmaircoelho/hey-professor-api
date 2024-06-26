<?php

use App\Models\{Question, User};
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\getJson;

it('should be able to list only published question', function () {
    # arrange
    Sanctum::actingAs(User::factory()->create());
    $published = Question::factory()->published()->create();
    $draft     = Question::factory()->draft()->create();

    # act
    $request = getJson(route('questions.index'))->assertOk();

    # assert
    $request->assertJsonFragment([
        'id'         => $published->id,
        'question'   => $published->question,
        'status'     => $published->status,
        'created_by' => [
            'id'   => $published->user->id,
            'name' => $published->user->name,
        ],
        'created_at' => $published->created_at->format('Y-m-d h:i:s'),
        'updated_at' => $published->updated_at->format('Y-m-d h:i:s'),
        //TODO: add like and unlike count
    ])->assertJsonMissing([
        'question' => $draft->question,
    ]);
});

it('should be able to search for a question', function () {
    Sanctum::actingAs(User::factory()->create());

    Question::factory()->published()->create(['question' => 'First Question?']);
    Question::factory()->published()->create(['question' => 'Second Question?']);

    getJson(route('questions.index', ['q' => 'first']))
        ->assertOk()
        ->assertJsonMissing(['question' => 'Second Question?'])
        ->assertJsonFragment(['question' => 'First Question?']);

    getJson(route('questions.index', ['q' => 'secon']))
        ->assertOk()
        ->assertJsonMissing(['question' => 'First Question?'])
        ->assertJsonFragment(['question' => 'Second Question?']);
});
