<?php

namespace App\Policies;

use http\QueryString;
use App\Models\{Question, User};

class QuestionPolicy
{
    public function update(User $user, Question $question): bool
    {
        return $user->is($question->user);
    }

    public function forceDelete(User $user, Question $question): bool
    {
        return $user->is($question->user);
    }

    public function archive(User $user, Question $question):bool
    {
        return $user->is($question->user);
    }

}
