<?php

namespace App\Repositories;

interface BookRepository
{
    public function create(array $data);

    public function update(array $data);

    public function get(int $id);

    public function getList(array $filters = [], int $perPage = 10, $page = null);

    public function delete(int $id);
}
