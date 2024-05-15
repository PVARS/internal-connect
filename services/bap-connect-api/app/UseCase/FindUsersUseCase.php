<?php

namespace App\UseCase;

use App\Repositories\UserRepositoryInterface;
use App\Utils\Util;
use Carbon\Carbon;

/** @deprecated */
class FindUsersUseCase
{
    private UserRepositoryInterface $userRepository;

    /**
     * Constructor.
     *
     * @param  UserRepositoryInterface  $userRepository  UserRepositoryInterface
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Find users.
     *
     * @param  array  $payload  An associative array of filters to apply to the query
     * @return array List users
     */
    public function run(array $payload): array
    {
        if ($payload['birthday_from']) {
            $payload['birthday_from'] = Carbon::parse($payload['birthday_from'])->toDateString().' 00:00:00';
        }
        if ($payload['birthday_to']) {
            $payload['birthday_to'] = Carbon::parse($payload['birthday_to'])->toDateString().' 23:59:59';
        }

        $builder = $this->userRepository->findUsers($payload);
        $cursor = $builder->cursorPaginate($payload['per_page']);

        if (!$cursor->items()) {
            return $this->respondWithPagination([], null, null);
        }

        return $this->respondWithPagination($cursor->items(), Util::extractCursorFromUrl($cursor->nextPageUrl()),
            Util::extractCursorFromUrl($cursor->previousPageUrl()));
    }

    /**
     * Build response list users.
     *
     * @param  array  $users  List users
     * @param  string|null  $nextPageToken  Next page token
     * @param  string|null  $previousPageUrl  Previous page token
     * @return array List users and pagination
     */
    private function respondWithPagination(array $users, ?string $nextPageToken, ?string $previousPageUrl): array
    {
        return [
            'data' => $users,
            'meta' => [
                'next_page_token' => $nextPageToken,
                'previous_page_token' => $previousPageUrl,
            ],
        ];
    }
}
