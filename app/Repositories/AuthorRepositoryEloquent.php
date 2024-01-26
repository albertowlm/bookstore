<?php

namespace App\Repositories;

use App\Models\Author;

class AuthorRepositoryEloquent implements AuthorRepository
{

    public function __construct(protected Author $model) { }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(array $data)
    {
        $author = $this->model->findOrFail($data['id']);
        $author->update($data);
        return $author;
    }

    public function get(int $id)
    {
        return $this->model->findOrFail($id);
    }

    public function getList(array $filters = [], int $perPage = null, $page = null)
    {
        $query = $this->model->query();

        if (!empty($filters['id'])) {
            $query->where('id', '=', $filters['id']);
        }

        if (!empty($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        return $query->paginate($perPage, ['*'], 'page', $page);
    }

    public function delete(int $id)
    {
        $author = $this->model->findOrFail($id);
        $author->books()->detach();
        $author->delete();
    }
}
