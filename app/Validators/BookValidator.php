<?php

namespace App\Validators;

class BookValidator extends AbstractCrudValidator
{
    protected function getAttributes(): array
    {
        return [
            'title' => 'Título',
            'publishing_companies' => 'Editora',
            'edition' => 'Edição',
            'year_publication' => 'Ano de Publicação',
            'price' => 'Preço',
            'author_ids' => 'Id do Autor',
            'subject_ids' => 'Id do Assunto',
        ];
    }
    protected function getCreateRules(): array
    {
        return [
            'title' => 'required|string|max:40',
            'publishing_companies' => 'required|string|max:40',
            'edition' => 'required|integer',
            'year_publication' => 'required|string|size:4',
            'price' => 'required|numeric|between:0,999999999999999999.99',
            'author_ids' => 'required|array',
            'author_ids.*' => 'exists:authors,id',
            'subject_ids' => 'required|array',
            'subject_ids.*' => 'exists:subjects,id',
        ];
    }
    protected function getUpdateRules(): array
    {
        $rules = $this->getCreateRules();
        $rules['id'] = 'required|exists:books,id';
        return $rules;
    }
    protected function getDeleteRules(): array
    {
        return [
            'id' => 'required|exists:books,id'
        ];
    }
}
