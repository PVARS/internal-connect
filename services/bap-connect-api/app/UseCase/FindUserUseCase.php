<?php

namespace App\UseCase;

use App\Exceptions\UserNotFoundException;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/** @deprecated */
class FindUserUseCase
{
    private UserRepository $userRepository;

    /**
     * Constructor.
     *
     * @param  UserRepository  $userRepository  UserRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Find user.
     *
     * @param  string  $id  User ID
     *
     * @throws UserNotFoundException User not found
     *
     * @return Model a User
     */
    public function run(string $id): Model
    {
        try {
            $user = $this->userRepository->findById($id);
        } catch (ModelNotFoundException $e) {
            throw new UserNotFoundException('User not found', $e);
        }

        return $user;
    }
}
