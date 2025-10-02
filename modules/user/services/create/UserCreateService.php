<?php

declare(strict_types=1);

namespace app\modules\user\services\create;

use app\modules\core\services\authManager\AuthManagerServiceInterface;
use app\modules\core\services\authManager\exceptions\UserRoleNotFoundException;
use app\modules\core\services\database\DatabaseTransactionServiceInterface;
use app\modules\user\repositories\UserRepositoryInterface;
use app\modules\user\services\create\hydrate\HydrateCreateUserDto;
use app\modules\user\services\create\input\UserCreateInputInterface;
use app\modules\user\services\dto\UserDto;
use Exception as CoreException;
use yii\base\Exception;

readonly class UserCreateService implements UserCreateServiceInterface
{
    public function __construct(
        private AuthManagerServiceInterface $authManagerService,
        private DatabaseTransactionServiceInterface $databaseTransactionService,
        private HydrateCreateUserDto $hydrateCreateUserDto,
        private UserRepositoryInterface $userRepository
    ) {}

    public function handle(UserCreateInputInterface $userCreateInput): int
    {
        $userDto = $this->fillDto($userCreateInput);

        // @phpstan-ignore return.type
        return $this->databaseTransactionService->handle(function () use ($userCreateInput, $userDto): int {
            $userId = $this->userRepository->save($userDto);

            $this->assignRole($userCreateInput, $userId);

            return $userId;
        });
    }

    /**
     * @throws Exception
     */
    private function fillDto(UserCreateInputInterface $userCreateInput): UserDto
    {
        return $this->hydrateCreateUserDto->hydrate($userCreateInput);
    }

    /**
     * @throws CoreException
     */
    private function assignRole(UserCreateInputInterface $userCreateInput, int $id): void
    {
        if (!$userCreateInput->getRole()) {
            throw new UserRoleNotFoundException($userCreateInput->getRole());
        }

        $role = $this->authManagerService->getRole($userCreateInput->getRole());

        if (!$role) {
            throw new UserRoleNotFoundException($userCreateInput->getRole());
        }

        $this->authManagerService->assignRole($role, $id);
    }
}
