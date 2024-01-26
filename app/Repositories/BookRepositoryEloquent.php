<?php

namespace App\Repositories;

use App\Models\Book;

class BookRepositoryEloquent implements BookRepository
{
    public function __construct(protected Book $model) { }

    public function create(array $data)
    {
        $book =  $this->model->create($data);
        if (!empty($data['author_ids'])) {
            $book->authors()->attach($data['author_ids']);
        }

        if (!empty($data['subject_ids'])) {
            $book->subjects()->attach($data['subject_ids']);
        }
        return $book;
    }

    public function update(array $data)
    {
        $book = $this->model->findOrFail($data['id']);
        $book->update($data);

        $book->authors()->detach();
        $book->subjects()->detach();

        if (!empty($data['author_ids'])) {
            $book->authors()->sync($data['author_ids']);
        }

        if (!empty($data['subject_ids'])) {
            $book->subjects()->sync($data['subject_ids']);
        }

        return $book;
    }

    public function get(int $id)
    {
        return $this->model->findOrFail($id);
    }

    public function getList(array $filters = [], int $perPage = null, $page = null)
    {
        $query = $this->model->query();

        if (!empty($filters['id'])) {
            $query->where('id','=' ,  $filters['id'] );
        }

        if (!empty($filters['title'])) {
            $query->where('title', 'like', '%' . $filters['title'] . '%');
        }

        if (!empty($filters['publishing_companies'])) {
            $query->where('publishing_companies', 'like', '%' . $filters['publishing_companies'] . '%');
        }

        if (!empty($filters['edition'])) {
            $query->where('edition', $filters['edition']);
        }

        if (!empty($filters['year_publication'])) {
            $query->where('year_publication', $filters['year_publication']);
        }
        $query->with(['authors', 'subjects']);

        return $query->paginate($perPage, ['*'], 'page', $page);
    }

    public function delete(int $id)
    {
        $book = $this->model->findOrFail($id);
        $book->authors()->detach();
        $book->subjects()->detach();
        $book->delete();
    }
}
