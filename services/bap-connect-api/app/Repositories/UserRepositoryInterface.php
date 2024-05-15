<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;

interface UserRepositoryInterface extends RepositoryInterface
{
    /**
     * Find users.
     *
     * @param  array  $filters  An associative array of filters to apply to the query
     * @return Builder Builder
     */
    public function findUsers(array $filters = []): Builder;
}
