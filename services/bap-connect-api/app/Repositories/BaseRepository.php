<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Override;

class BaseRepository implements RepositoryInterface
{
    protected Model $model;

    /**
     * BaseRepository constructor.
     *
     * @param  Model  $model  Model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Get all models.
     *
     * @param  array  $columns  Columns
     * @param  array  $relations  Relations
     * @return Collection Collection
     */
    #[Override]
    public function all(array $columns = ['*'], array $relations = []): Collection
    {
        return $this->model->with($relations)->get($columns);
    }

    /**
     * Get all trashed models.
     *
     * @return Collection Collection
     */
    #[Override]
    public function allTrashed(): Collection
    {
        return $this->model->onlyTrashed()->get();
    }

    /**
     * Find model by id.
     *
     * @param  string  $modelId  Model ID
     * @param  array  $columns  Columns
     * @param  array  $relations  Relations
     * @param  array  $appends  Appends
     *
     * @throws ModelNotFoundException If the model with the provided ID is not found
     *
     * @return Model Model
     */
    #[Override]
    public function findById(
        string $modelId,
        array $columns = ['*'],
        array $relations = [],
        array $appends = []
    ): Model {
        return $this->model->select($columns)->with($relations)->findOrFail($modelId)->append($appends);
    }

    /**
     * Find trashed model by id.
     *
     * @param  int  $modelId  Model ID
     *
     * @throws ModelNotFoundException If the model with the provided ID is not found
     *
     * @return Model Model
     */
    #[Override]
    public function findTrashedById(string $modelId): Model
    {
        return $this->model->withTrashed()->findOrFail($modelId);
    }

    /**
     * Find only trashed model by id.
     *
     * @param  string  $modelId  Model ID
     *
     * @throws ModelNotFoundException If the model with the provided ID is not found
     *
     * @return Model Model
     */
    #[Override]
    public function findOnlyTrashedById(string $modelId): Model
    {
        return $this->model->onlyTrashed()->findOrFail($modelId);
    }

    /**
     * Create a model.
     *
     * @param  array  $payload  An associative array containing the data for the new model
     * @return Model New a model
     */
    #[Override]
    public function create(array $payload): Model
    {
        $model = $this->model->create($payload);

        return $model->fresh();
    }

    /**
     * Update existing model.
     *
     * @param  string  $modelId  Model ID
     * @param  array  $payload  An associative array containing the new data for the model
     *
     * @throws ModelNotFoundException If the model with the provided ID is not found
     *
     * @return bool bool True if the update was successful, False otherwise.
     */
    #[Override]
    public function update(string $modelId, array $payload): bool
    {
        $model = $this->findById($modelId);

        return $model->update($payload);
    }

    /**
     * Delete model by id.
     *
     * @param  string  $modelId  Model ID
     *
     * @throws ModelNotFoundException If the model with the provided ID is not found
     *
     * @return bool bool True if delete was successful, False otherwise.
     */
    #[Override]
    public function deleteById(string $modelId): bool
    {
        return $this->findById($modelId)->delete();
    }

    /**
     * Restore model by id.
     *
     * @param  string  $modelId  Model ID
     *
     * @throws ModelNotFoundException If the model with the provided ID is not found
     *
     * @return bool bool True if the restore was successful, False otherwise.
     */
    #[Override]
    public function restoreById(string $modelId): bool
    {
        return $this->findOnlyTrashedById($modelId)->restore();
    }

    /**
     * Permanently delete model by id.
     *
     * @param  string  $modelId  Model ID
     *
     * @throws ModelNotFoundException If the model with the provided ID is not found
     *
     * @return bool bool True if the force delete was successful, False otherwise.
     */
    #[Override]
    public function permanentlyDeleteById(string $modelId): bool
    {
        return $this->findTrashedById($modelId)->forceDelete();
    }

    /**
     * Find all record by condition.
     *
     * @param  array  $filters  Where by filters
     * @param  array  $columns  Columns
     * @param  bool  $toArray  Convert to array?
     * @return Collection|array
     */
    #[Override]
    public function findBy(array $filters, array $columns = ['*'], bool $toArray = true): Collection|array
    {
        $builder = $this->model->newQuery();
        foreach ($filters as $key => $val) {
            $builder->where($key, $val);
        }
        $results = $builder->select($columns)->get();

        return $toArray ? $results->toArray() : $results;
    }

    /**
     * Find a record by condition.
     *
     * @param  array  $filters  Where by filters
     * @param  array  $columns  Columns
     * @param  bool  $toArray  Convert to array?
     * @return array|Model|null
     */
    #[Override]
    public function findOneBy(array $filters, array $columns = ['*'], bool $toArray = true): array|Model|null
    {
        $builder = $this->model->newQuery();
        foreach ($filters as $key => $val) {
            $builder->where($key, $val);
        }
        $result = $builder->select($columns)->first();
        if (!$result) {
            return null;
        }

        return $toArray ? $result->toArray() : $result;
    }
}
