<?php

namespace App\Shared\Infrastructure\Http\Controllers;

use App\Modules\Role\Application\Services\RoleService;
use App\Shared\Domain\Enums\GenericStatus;
use App\Shared\Infrastructure\Http\Resources\RoleResource;
use Symfony\Component\HttpFoundation\Request;

class SettingController
{

    public function __construct(
        protected RoleService $role_service
    ) {}

    public function manageTeams(Request $request)
    {
        $roles = $this->role_service->getAllRoles();

        return response()->json([
            'message' => 'Manage team prerequisites',
            'data' => [
                'roles' => RoleResource::collection($roles),
                'statuses' => GenericStatus::toDropdownArray()
            ]
        ]);
    }
}
