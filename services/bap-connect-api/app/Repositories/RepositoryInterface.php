<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

interface RepositoryInterface
{
    /**
     * Get all models.
     *
     * @param  array  $columns  Columns
     * @param  array  $relations  Relations
     * @return Collection Collection
     */
    public function all(array $columns = ['*'], array $relations = []): Collection;

    /**
     * Get all trashed models.
     *
     * @return Collection Collection
     */
    public function allTrashed(): Collection;

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
    public function findById(
        string $modelId,
        array $columns = ['*'],
        array $relations = [],
        array $appends = []
    ): Model;

    /**
     * Find trashed model by id.
     *
     * @param  string  $modelId  Model ID
     *
     * @throws ModelNotFoundException If the model with the provided ID is not found
     *
     * @return Model Model
     */
    public function findTrashedById(string $modelId): Model;

    /**
     * Find only trashed model by id.
     *
     * @param  string  $modelId  Model ID
     *
     * @throws ModelNotFoundException If the model with the provided ID is not found
     *
     * @return Model Model
     */
    public function findOnlyTrashedById(string $modelId): Model;

    /**
     * Create a model.
     *
     * @param  array  $payload  An associative array containing the data for the new model
     * @return Model New a model
     */
    public function create(array $payload): Model;

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
    public function update(string $modelId, array $payload): bool;

    /**
     * Delete model by id.
     *
     * @param  string  $modelId  Model ID
     *
     * @throws ModelNotFoundException If the model with the provided ID is not found
     *
     * @return bool bool True if delete was successful, False otherwise.
     */
    public function deleteById(string $modelId): bool;

    /**
     * Restore model by id.
     *
     * @param  string  $modelId  Model ID
     *
     * @throws ModelNotFoundException If the model with the provided ID is not found
     *
     * @return bool bool True if the restore was successful, False otherwise.
     */
    public function restoreById(string $modelId): bool;

    /**
     * Permanently delete model by id.
     *
     * @param  string  $modelId  Model ID
     *
     * @throws ModelNotFoundException If the model with the provided ID is not found
     *
     * @return bool bool True if the force delete was successful, False otherwise.
     */
    public function permanentlyDeleteById(string $modelId): bool;

    /**
     * Find all record by condition.
     *
     * @param  array  $filters  Where by filters
     * @param  array  $columns  Columns
     * @param  bool  $toArray  Convert to array?
     * @return Collection|array
     */
    public function findBy(array $filters, array $columns = ['*'], bool $toArray = true): Collection|array;

    /**
     * Find a record by condition.
     *
     * @param  array  $filters  Where by filters
     * @param  array  $columns  Columns
     * @param  bool  $toArray  Convert to array?
     * @return Model|array|null
     */
    public function findOneBy(array $filters, array $columns, bool $toArray = true): array|Model|null;
}
