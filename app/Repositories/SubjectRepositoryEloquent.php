<?php

namespace App\Repositories;

use App\Models\Subject;

class SubjectRepositoryEloquent implements SubjectRepository
{
    public function __construct(protected Subject $model) { }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(array $data)
    {
        $subject = $this->model->findOrFail($data['id']);
        $subject->update($data);
        return $subject;
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

        if (!empty($filters['description'])) {
            $query->where('description', 'like', '%' . $filters['description'] . '%');
        }

        return $query->paginate($perPage, ['*'], 'page', $page);
    }

    public function delete(int $id)
    {
        $subject = $this->model->findOrFail($id);
        $subject->books()->detach();
        $subject->delete();
    }
}
