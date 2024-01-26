<?php

namespace App\Services;

use App\Repositories\SubjectRepository;
use App\Validators\SubjectValidator;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class SubjectService extends Service
{

    public function __construct(
        protected SubjectRepository $subjectRepository,
        protected SubjectValidator  $subjectValidator
    ) {
    }

    public function create(array $data)
    {
        return $this->parserError(
            function () use ($data) {
                $validator = $this->subjectValidator->create($data);
                if ($validator['error']) {
                    return $validator;
                }
                $data = $this->subjectRepository->create($data)->toArray();
                return [
                    "error"    => false,
                    "data"     => $data,
                    "messages" => ["Assunto criado com sucesso!"],
                ];
            }
            , 'create'
        );


    }

    public function update(array $data)
    {
        return $this->parserError(
            function () use ($data) {
                $validator = $this->subjectValidator->update($data);
                if ($validator['error']) {
                    return $validator;
                }
                $data = $this->subjectRepository->update($data)->toArray();
                return [
                    "error"    => false,
                    "data"     => $data,
                    "messages" => ["Assunto criado com sucesso!"],
                ];
            }
            , 'update'
        );


    }

    public function delete($id)
    {
        return $this->parserError(
            function () use ($id) {
                $validator = $this->subjectValidator->delete(['id' => $id]);
                if ($validator['error']) {
                    return $validator;
                }
                $this->subjectRepository->delete($id);
                return [
                    'error'    => false,
                    'messages' => [
                        'Assunto excluído com sucesso'
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
                $result          = $this->subjectRepository->getList($filters, $perPage, $page)->toArray();
                $result['error'] = false;
                return $result;
            }
            , 'view'
        );
    }
}
