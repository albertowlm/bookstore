<?php

namespace App\Services;

use App\Repositories\AuthorRepository;
use App\Validators\AuthorValidator;


class AuthorService extends Service
{
    protected $name = 'autor';

    public function __construct(
        protected AuthorRepository $authorRepository,
        protected AuthorValidator  $authorValidator
    ) {
    }

    public function create(array $data)
    {
        return $this->parserError(
            function () use ($data) {
                $validator = $this->authorValidator->create($data);
                if ($validator['error']) {
                    return $validator;
                }
                $data = $this->authorRepository->create($data)->toArray();
                return [
                    "error"    => false,
                    "data"     => $data,
                    "messages" => ["Autor criado com sucesso!"],
                ];
            }
            , 'create'
        );


    }

    public function update(array $data)
    {
        return $this->parserError(
            function () use ($data) {
                $validator = $this->authorValidator->update($data);
                if ($validator['error']) {
                    return $validator;
                }
                $data = $this->authorRepository->update($data)->toArray();
                return [
                    "error"    => false,
                    "data"     => $data,
                    "messages" => ["Autor criado com sucesso!"],
                ];
            }
            , 'update'
        );
    }

    public function delete($id)
    {
        return $this->parserError(
            function () use ($id) {
                $validator = $this->authorValidator->delete(['id' => $id]);
                if ($validator['error']) {
                    return $validator;
                }
                $this->authorRepository->delete($id);
                return [
                    'error'    => false,
                    'messages' => [
                        'Autor excluído com sucesso'
                    ]
                ];
            }
            , 'delete'
        );
    }

    public function getList(array $filters = [], int $perPage = 10, $page = null)
    {
        return $this->parserError(
            function () use (
                $filters, $perPage, $page
            ) {
                $result          = $this->authorRepository->getList($filters, $perPage, $page)->toArray();
                $result['error'] = false;
                return $result;
            }
            , 'view'
        );
    }
}
