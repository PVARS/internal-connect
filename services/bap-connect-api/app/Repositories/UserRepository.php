<?php

namespace App\Repositories;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Override;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    protected Model $model;

    /**
     * Constructor.
     *
     * @param  User  $model  User
     */
    public function __construct(User $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    /**
     * Find users.
     *
     * @param  array  $filters  An associative array of filters to apply to the query
     * @return Builder Builder
     */
    #[Override]
    public function findUsers(array $filters = []): Builder
    {
        $builder = $this->model->select('*');
        if ($filters) {
            if (!empty($filters['first_name'])) {
                $builder->where('first_name', 'like', $filters['first_name'].'%');
            }

            if (!empty($filters['last_name'])) {
                $builder->where('last_name', 'like', $filters['last_name'].'%');
            }

            if (!empty($filters['birthday_from'])) {
                $builder->where('birthday_year', '>=', Carbon::parse($filters['birthday_from'])->year);
                $builder->where('birthday_month', '>=', Carbon::parse($filters['birthday_from'])->month);
                $builder->where('birthday_day', '>=', Carbon::parse($filters['birthday_from'])->day);
            }
            if (!empty($filters['birthday_to'])) {
                $builder->where('birthday_year', '<=', Carbon::parse($filters['birthday_to'])->year);
                $builder->where('birthday_month', '<=', Carbon::parse($filters['birthday_to'])->month);
                $builder->where('birthday_day', '<=', Carbon::parse($filters['birthday_to'])->day);
            }

            if (!empty($filters['gender'])) {
                $builder->where('gender', $filters['gender']);
            }

            if (!empty($filters['username'])) {
                $builder->where('username', 'like', $filters['username'].'%');
            }

            if (!empty($filters['email'])) {
                $builder->where('email', 'like', $filters['email'].'%');
            }
        }

        return $builder
            ->orderBy('status', 'desc')
            ->orderBy('email_verified_at', 'desc')
            ->orderBy('created_at', 'desc');
    }
}
