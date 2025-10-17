<?php

declare(strict_types=1);

namespace app\modules\user\repositories;

use app\modules\user\models\User;
use app\modules\user\services\dto\UserDto;

/**
 * Interface for user repository.
 * Provides methods for user persistence and retrieval.
 */
interface UserRepositoryInterface
{
    /**
     * Saves a user DTO to the storage.
     *
     * @param UserDto $userDto user data transfer object
     *
     * @return int saved user ID
     */
    public function save(UserDto $userDto): int;

    /**
     * Finds a user by ID.
     *
     * @param int $id user ID
     *
     * @return UserDto user data transfer object
     */
    public function findById(int $id): UserDto;

    /**
     * Checks if a user exists by ID.
     *
     * @param int $id user ID
     *
     * @return bool true if user exists, false otherwise
     */
    public function existById(int $id): bool;

    /**
     * Finds a user by access token.
     *
     * @param string $accessToken access token
     *
     * @return UserDto user data transfer object
     */
    public function findByAccessToken(string $accessToken): UserDto;

    /**
     * Finds a user by email, optionally excluding a user by ID.
     *
     * @param string $email email address
     * @param null|int $notId user ID to exclude
     *
     * @return UserDto user data transfer object
     */
    public function findByEmail(string $email, ?int $notId = null): UserDto;

    /**
     * Checks if a user exists by email, optionally excluding a user by ID.
     *
     * @param string $email email address
     * @param null|int $notId user ID to exclude
     *
     * @return bool true if user exists, false otherwise
     */
    public function existByEmail(string $email, ?int $notId = null): bool;

    /**
     * Fills a User model with data from a UserDto.
     *
     * @param User $user user model
     * @param UserDto $userDto user data transfer object
     *
     * @return User filled user model
     */
    public function fillModel(User $user, UserDto $userDto): User;

    /**
     * Creates a UserDto from a User model.
     *
     * @param User $user user model
     *
     * @return UserDto user data transfer object
     */
    public function fillDto(User $user): UserDto;
}
