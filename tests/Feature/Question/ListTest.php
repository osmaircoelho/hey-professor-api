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
