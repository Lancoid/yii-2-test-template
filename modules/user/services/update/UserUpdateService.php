<?php

declare(strict_types=1);

namespace app\modules\user\services\update;

use app\modules\core\services\authManager\AuthManagerServiceInterface;
use app\modules\core\services\authManager\dto\UserRoleDto;
use app\modules\core\services\authManager\exceptions\UserRoleNotFoundException;
use app\modules\core\services\database\DatabaseTransactionServiceInterface;
use app\modules\user\repositories\UserRepositoryInterface;
use app\modules\user\services\dto\UserDto;
use app\modules\user\services\update\hydrate\HydrateUpdateUserDto;
use app\modules\user\services\update\input\UserUpdateInputInterface;
use Exception as CoreException;

readonly class UserUpdateService implements UserUpdateServiceInterface
{
    public function __construct(
        private AuthManagerServiceInterface $authManagerService,
        private DatabaseTransactionServiceInterface $databaseTransactionService,
        private HydrateUpdateUserDto $hydrateUpdateUserDto,
        private UserRepositoryInterface $userRepository
    ) {}

    public function handle(UserDto $userDto, UserUpdateInputInterface $userUpdateInput): int
    {
        $userDto = $this->fillDto($userDto, $userUpdateInput);

        // @phpstan-ignore return.type
        return $this->databaseTransactionService->handle(function () use ($userUpdateInput, $userDto): int {
            $userId = $this->userRepository->save($userDto);

            $this->assignRole($userUpdateInput, $userId);

            return $userId;
        });
    }

    private function fillDto(UserDto $userDto, UserUpdateInputInterface $userUpdateInput): UserDto
    {
        return $this->hydrateUpdateUserDto->hydrate($userDto, $userUpdateInput);
    }

    /**
     * @throws CoreException
     */
    private function assignRole(UserUpdateInputInterface $userUpdateInput, int $id): void
    {
        if (!$userUpdateInput->getRole()) {
            throw new UserRoleNotFoundException($userUpdateInput->getRole());
        }

        $role = $this->authManagerService->getRole($userUpdateInput->getRole());

        if (!$role instanceof UserRoleDto) {
            throw new UserRoleNotFoundException($userUpdateInput->getRole());
        }

        $this->authManagerService->assignRole($role, $id);
    }
}
