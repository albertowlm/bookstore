<?php

namespace App\Services;

use App\Repositories\BookRepository;
use App\Validators\BookValidator;


class BookService extends Service
{
    public function __construct(
        protected BookRepository $bookRepository,
        protected BookValidator  $bookValidator
    ) {
    }

    public function create(array $data)
    {
        return $this->parserError(
            function () use ($data) {
                $validator = $this->bookValidator->create($data);
                if ($validator['error']) {
                    return $validator;
                }
                $data = $this->bookRepository->create($data)->toArray();
                return [
                    "error"    => false,
                    "data"     => $data,
                    "messages" => ["Livro criado com sucesso!"],
                ];
            }
            , 'create'
        );
    }

    public function update(array $data)
    {
        return $this->parserError(
            function () use ($data) {
                $validator = $this->bookValidator->update($data);
                if ($validator['error']) {
                    return $validator;
                }
                $data = $this->bookRepository->update($data)->toArray();
                return [
                    "error"    => false,
                    "data"     => $data,
                    "messages" => ["Livro criado com sucesso!"],
                ];
            }
            , 'update'
        );
    }

    public function delete($id)
    {
        return $this->parserError(
            function () use ($id) {
                $validator = $this->bookValidator->delete(['id' => $id]);
                if ($validator['error']) {
                    return $validator;
                }
                $this->bookRepository->delete($id);
                return [
                    'error'    => false,
                    'messages' => [
                        'Livro excluído com sucesso'
                    ]
                ];
            }
            , 'delete'
        );


    }

    public function getList(array $filters = [], int $perPage = 10, $page = null)
    {
        return $this->parserError(
            function () use ($filters, $perPage, $page) {
                $result = $this->bookRepository->getList($filters, $perPage, $page)->toArray();
                $result = $this->getDetailsString($result, 'authors', 'name');
                $result = $this->getDetailsString($result, 'subjects', 'description');

                $result['error'] = false;
                return $result;
            }
            , 'view'
        );
    }

    protected function getDetailsString($books, $key, $value)
    {
        if ($books['data'] === []) {
            return $books;
        }
        $i = 0;
        foreach ($books['data'] as $book) {
            $arrayId  = [];
            $arrayKey = [];
            foreach ($book[$key] as $item) {
                $arrayId[]  = $item['id'];
                $arrayKey[] = $item[$value];
            }
            $books['data'][$i][$key . '_ids']       = $arrayId;
            $books['data'][$i][$key . '_' . $value] = implode(', ', $arrayKey);
            $i++;
        }
        return $books;
    }
}
