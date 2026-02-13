<?php

namespace App\Modules\Authentication\Infrastructure\Http\Controllers;

use App\Modules\Authentication\Application\Services\AuthenticationService;
use App\Modules\Authentication\Application\UseCases\LoginMobile;
use App\Modules\Authentication\Application\UseCases\LoginSpa;
use App\Modules\Authentication\Application\UseCases\LogoutMobile;
use App\Modules\Authentication\Application\UseCases\LogoutSpa;
use App\Modules\Authentication\Infrastructure\Http\Requests\LoginRequest;
use App\Modules\User\Application\Exceptions\UserNotFoundException;
use App\Modules\User\Infrastructure\Http\Resources\CurrentUserResource;
use Illuminate\Support\Facades\Request;

class AuthenticationController
{
    public function __construct(
        protected AuthenticationService $authenticationService
    ) {}

    public function spaLogin(LoginRequest $request, LoginSpa $login_spa)
    {
        $dto = $request->toDTO();

        $user = $login_spa->execute($dto, $request);

        $request->session()->regenerate();

        return response()->json([
            "message" => "Logged in successfully!",
            "user" => new CurrentUserResource($user)
        ]);
    }

    public function spaLogout(Request $request, LogoutSpa $logout_spa)
    {
        $logout_spa->execute($request);

        return response()->json(["message" => "Logout sucessfully"]);
    }

    public function mobileLogin(LoginRequest $request, LoginMobile $login_mobile)
    {
        $dto = $request->toDTO();
        $user = $login_mobile->execute($dto);

        if (!$user) throw new UserNotFoundException();

        // delete all the previous tokens
        $user->tokens()->delete();

        $token = $user->createToken(
            "mobile",
            ['*'],
            now()->addDays(7) // expiry
        );

        return response()->json([
            'token' => $token->plainTextToken,
            'expires_at' => $token->accessToken->expires_at,
        ]);
    }

    public function mobileLogout(Request $request, LogoutMobile $logout_mobile)
    {
        $logout_mobile->execute($request->revoke_all ?? false);

        return response()->json(["message" => "Logout sucessfully"]);
    }
}
