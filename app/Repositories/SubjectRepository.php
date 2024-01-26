<?php
namespace App\Repositories;

interface SubjectRepository
{
    public function create(array $data);

    public function update(array $data);

    public function get(int $id);

    public function getList(array $filters = [], int $perPage = null, $page = null);

    public function delete(int $id);
}
