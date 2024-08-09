<?php

namespace App\Http\Controllers\V1;

use App\Exceptions\AppException;
use App\Exceptions\UserNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\FindUsersRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\UpdateAvatarRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\VerifyUserRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\UsersWithPaginationResource;
use App\Models\User;
use App\UseCase\DeleteUserUseCase;
use App\UseCase\FindUsersUseCase;
use App\UseCase\FindUserUseCase;
use App\UseCase\RegisterUserUseCase;
use App\UseCase\UpdateAvatarUseCase;
use App\UseCase\UpdateProfileUseCase;
use App\UseCase\VerifyUserUseCase;
use App\Utils\Constants;
use App\Utils\Util;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

use function Ramsey\Uuid\v7;

use Symfony\Component\HttpFoundation\Response as HttpCode;

class UserController extends Controller
{
    private RegisterUserUseCase $registerUserService;

    private VerifyUserUseCase $verifyUserService;

    private FindUsersUseCase $findUsersUseCase;

    private FindUserUseCase $findUserUseCase;

    private DeleteUserUseCase $deleteUserUseCase;

    private UpdateProfileUseCase $updateProfileUseCase;

    private UpdateAvatarUseCase $updateAvatarUseCase;

    /**
     * Constructor.
     *
     * @param  RegisterUserUseCase  $registerUserUseCase  Register user use case
     * @param  VerifyUserUseCase  $verifyUserUseCase  Verify user use case
     * @param  FindUsersUseCase  $getUsersUseCase  Find users use case
     * @param  FindUserUseCase  $getUserUseCase  Find user use case
     * @param  DeleteUserUseCase  $deleteUserUseCase  Delete user use case
     * @param  UpdateProfileUseCase  $updateProfileUseCase  Update profile use case
     * @param  UpdateAvatarUseCase  $updateAvatarUseCase  Update avatar use case
     */
    public function __construct(
        RegisterUserUseCase $registerUserUseCase,
        VerifyUserUseCase $verifyUserUseCase,
        FindUsersUseCase $getUsersUseCase,
        FindUserUseCase $getUserUseCase,
        DeleteUserUseCase $deleteUserUseCase,
        UpdateProfileUseCase $updateProfileUseCase,
        UpdateAvatarUseCase $updateAvatarUseCase,
    ) {
        $this->registerUserService = $registerUserUseCase;
        $this->verifyUserService = $verifyUserUseCase;
        $this->findUsersUseCase = $getUsersUseCase;
        $this->findUserUseCase = $getUserUseCase;
        $this->deleteUserUseCase = $deleteUserUseCase;
        $this->updateProfileUseCase = $updateProfileUseCase;
        $this->updateAvatarUseCase = $updateAvatarUseCase;
    }

    /**
     * Register user.
     *
     * @param  RegisterUserRequest  $request  Data register new a user
     *
     * @throws AppException Register user failed
     * @throws AppException Failed to create verify user token
     *
     * @return JsonResponse User info
     */
    public function register(RegisterUserRequest $request): JsonResponse
    {
        $birthday = $request['birthday'];
        $userId = v7();
        $verifyUserToken = Util::opensslEncrypt($userId);
        if ($verifyUserToken === null) {
            return $this->error('Failed to create verify user token');
        }
        $payload = [
            'id' => $userId,
            'first_name' => $request['first_name'],
            'last_name' => $request['last_name'],
            'email' => $request['email'],
            'gender' => $request['gender'],
            'username' => $request['username'],
            'verify_user_token' => $verifyUserToken,
            'user_verify_token_expiration' => Carbon::now()->addDay(),
            'birthday_day' => $birthday ? Carbon::parse($birthday)->day : null,
            'birthday_month' => $birthday ? Carbon::parse($birthday)->month : null,
            'birthday_year' => $birthday ? Carbon::parse($birthday)->year : null,
            'phone' => $request['phone'],
            'province' => $request['province'],
            'district' => $request['district'],
            'ward' => $request['ward'],
            'address' => $request['address'],
            'created_by' => User::SYSTEM_USER_ID,
            'updated_by' => User::SYSTEM_USER_ID,
            'creator_name' => User::SYSTEM_USER_NAME,
            'updater_name' => User::SYSTEM_USER_NAME,
        ];

        $user = $this->registerUserService->run($payload);

        return $this->ok(new UserResource($user), null, HttpCode::HTTP_CREATED);
    }

    /**
     * Verify user.
     *
     * @param  VerifyUserRequest  $request  Data to verify user
     *
     * @throws AppException User already verified
     * @throws AppException Token expired
     * @throws AppException Verify user failed
     * @throws UserNotFoundException User not found
     * @throws AppException Token invalid
     *
     * @return JsonResponse Data format json
     */
    public function verify(VerifyUserRequest $request): JsonResponse
    {
        $payload = [
            'token' => $request['token'],
            'password' => $request['password'],
        ];
        $this->verifyUserService->run($payload);

        return $this->ok([], 'User has been verified');
    }

    /**
     * @deprecated
     * Get list users.
     *
     * @param  FindUsersRequest  $request  Find users request
     * @return JsonResponse List users
     */
    public function index(FindUsersRequest $request): JsonResponse
    {
        $payload = [
            'per_page' => $request->query('per_page', Constants::PER_PAGE_LIST_USERS),
            'first_name' => $request->query('first_name'),
            'last_name' => $request->query('last_name'),
            'username' => $request->query('username'),
            'email' => $request->query('email'),
            'birthday_from' => request()->query('birthday_from'),
            'birthday_to' => request()->query('birthday_to'),
            'gender' => request()->query('gender'),
        ];

        $data = $this->findUsersUseCase->run($payload);

        return $this->ok(new UsersWithPaginationResource($data));
    }

    /**
     * @deprecated
     * Find user by ID.
     *
     * @param  string  $id  User ID
     *
     * @throws UserNotFoundException User not found
     *
     * @return JsonResponse User
     */
    public function findById(string $id): JsonResponse
    {
        return $this->ok(new UserResource($this->findUserUseCase->run($id)));
    }

    /**
     * Delete user.
     *
     * @throws UserNotFoundException User not found
     * @throws AppException Failed to delete
     *
     * @return JsonResponse Delete status
     */
    public function delete(): JsonResponse
    {
        $this->deleteUserUseCase->run(auth()->id(), auth()->user()->username);

        return $this->ok([], 'Delete user was successful')->withCookie(cookie(
            env('AUTH_COOKIE_NAME')));
    }

    /**
     * Update profile.
     *
     * @param  UpdateProfileRequest  $request  UpdateProfileRequest
     *
     * @throws AppException Failed to update
     *
     * @return JsonResponse User updated
     */
    public function update(UpdateProfileRequest $request): JsonResponse
    {
        $userId = auth()->id();
        $payload = [];
        if (array_key_exists('birthday', $request->all())) {
            $payload['birthday_day'] = Carbon::parse($request['birthday'])->day;
            $payload['birthday_month'] = Carbon::parse($request['birthday'])->month;
            $payload['birthday_year'] = Carbon::parse($request['birthday'])->year;
        }
        if (array_key_exists('phone', $request->all())) {
            $payload['phone'] = $request['phone'];
        }
        if (array_key_exists('province', $request->all())) {
            $payload['province'] = $request['province'];
        }
        if (array_key_exists('district', $request->all())) {
            $payload['district'] = $request['district'];
        }
        if (array_key_exists('ward', $request->all())) {
            $payload['ward'] = $request['ward'];
        }
        if (array_key_exists('address', $request->all())) {
            $payload['address'] = $request['address'];
        }
        if ($payload) {
            $payload['updated_by'] = $userId;
            $payload['updater_name'] = auth()->user()->username;
        }
        $user = $this->updateProfileUseCase->run($userId, $payload);

        return $this->ok(new UserResource($user), 'Update user successfully');
    }

    /**
     * Update avatar.
     *
     * @param  UpdateAvatarRequest  $request  UpdateAvatarRequest
     *
     * @throws AppException Failed to upload avatar
     *
     * @return JsonResponse JsonResponse
     */
    public function updateAvatar(UpdateAvatarRequest $request): JsonResponse
    {
        $payload = [
            'avatar' => $request['avatar'],
            'user_id' => auth()->id(),
            'username' => auth()->user()->username,
        ];
        $this->updateAvatarUseCase->run($payload);

        return $this->ok();
    }
}
