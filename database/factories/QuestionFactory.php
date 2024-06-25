<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'question' => $this->faker->sentence . '?',
            'status'   => 'draft',
            'user_id'  => User::factory(),
        ];
    }

    public function published(): self
    {
        return $this->state(fn (array $attribute) => [
            'status' => 'published',
        ]);
    }

    public function draft(): self
    {
        return $this->state(fn (array $attribute) => [
            'status' => 'draft',
        ]);
    }

    public function archived(): self
    {
        return $this->state(fn (array $attribute) => [
            'deleted_at' => $this->faker->dateTime(),
        ]);
    }

}
