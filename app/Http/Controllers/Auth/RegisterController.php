<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;

class RegisterController extends Controller
{
    public function __invoke()
    {
        User::create(request()->all());
    }
}
