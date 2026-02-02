<?php

namespace App\Modules\User\Infrastructure\Http\Controllers;

use App\Modules\Master\Application\Exceptions\InsuranceProductException;
use App\Modules\User\Application\Services\UserService;
use App\Modules\User\Application\UseCases\CreateUser;
use App\Modules\User\Application\UseCases\PaginatedUsers;
use App\Modules\User\Application\UseCases\ProductAgentAccess;
use App\Modules\User\Application\UseCases\UpdateUser;
use App\Modules\User\Application\UseCases\UpdateUserPassword;
use App\Modules\User\Application\UseCases\UpdateUserStatus;
use App\Modules\User\Application\UseCases\UpsertAgentProductAssignment;
use App\Modules\User\Infrastructure\Http\Requests\CreateTeamRequest;
use App\Modules\User\Infrastructure\Http\Requests\PaginatedUserRequest;
use App\Modules\User\Infrastructure\Http\Requests\UpdateTeamPasswordRequest;
use App\Modules\User\Infrastructure\Http\Requests\UpdateTeamRequest;
use App\Modules\User\Infrastructure\Http\Requests\UpdateTeamStatusRequest;
use App\Modules\User\Infrastructure\Http\Requests\UuidUserRequest;
use App\Modules\User\Infrastructure\Http\Resources\TeamResource;

class TeamController
{
    public function __construct(
        protected UserService $user_service
    ) {}

    public function index(PaginatedUserRequest $request, PaginatedUsers $paginated_users)
    {
        $dto = $request->toDTO();

        $users = $paginated_users->execute($dto);

        $users->through(fn($user) => new TeamResource($user));

        return response()->json([
            'message' => 'Manage Teams',
            'data' => $users
        ]);
    }

    public function store(CreateTeamRequest $request, CreateUser $createUser)
    {
        $dto = $request->toDTO();

        $createUser->execute($dto);

        return response()->json([
            'message' => 'Team successfully added'
        ], 201);
    }

    public function update(UpdateTeamRequest $request, UpdateUser $updateUser)
    {
        $dto = $request->toDTO();

        $updateUser->execute($dto);

        return response()->json([
            'message' => 'Team successfully updated'
        ], 200);
    }

    public function getAssignedProduct(UuidUserRequest $request, ProductAgentAccess $productAgentAccess)
    {
        $agentId = $request->agentId();

        $accessed = $productAgentAccess->execute($agentId);

        return response()->json([
            'message' => 'Team product accessed',
            'data' => [
                'accessed' => $accessed
            ]
        ]);
    }

    public function upsertAssignProduct(UuidUserRequest $request, UpsertAgentProductAssignment $upsertAgentProductAssignment)
    {
        $agentId = $request->agentId();

        if (empty($request->accessed)) {
            throw new InsuranceProductException();
        }

        $upsertAgentProductAssignment->execute($agentId, $request->accessed);

        return response()->json([
            'message' => 'Team product assignment',
        ]);
    }

    public function updatePassword(UpdateTeamPasswordRequest $request, UpdateUserPassword $updateUserPassword)
    {
        $dto = $request->toDTO();

        $updateUserPassword->execute($dto);

        return response()->json([
            'message' => 'Team password successfully updated'
        ], 200);
    }

    public function updateStatus(UpdateTeamStatusRequest $request, UpdateUserStatus $updateUserStatus)
    {
        $dtos = $request->toDto();

        foreach ($dtos as $dto) {
            $updateUserStatus->execute($dto);
        }

        return response()->json([
            'message' => 'Team status updated'
        ]);
    }
}
