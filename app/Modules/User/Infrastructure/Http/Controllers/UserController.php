<?php

namespace App\Modules\User\Infrastructure\Http\Controllers;

use App\Modules\User\Application\Services\UserService;
use App\Modules\User\Infrastructure\Http\Resources\CurrentUserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController
{
    public function __construct(
        protected UserService $user_service
    ) {}

    public function getCurrentuser(Request $request)
    {
        return response()->json([
            'message' => 'Current user!',
            'user' => new CurrentUserResource(getAuthenticatedUser())
        ]);
    }
}
