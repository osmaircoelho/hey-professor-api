<?php

namespace App\Policies;

use App\Models\Question;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class QuestionPolicy
{
    public function update(User $user, Question $question): bool
    {
        return $user->is($question->user);
    }

}
