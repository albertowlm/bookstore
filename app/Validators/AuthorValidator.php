<?php

namespace App\Validators;

class AuthorValidator extends AbstractCrudValidator
{
    protected function getAttributes(): array
    {
        return [
            'name' => 'Nome',
        ];
    }
    protected function getCreateRules(): array
    {
        return [
            'name' => 'required|string|max:40',
        ];
    }
    protected function getUpdateRules(): array
    {
        $rules = $this->getCreateRules();
        $rules['id'] = 'required|exists:authors,id';
        return $rules;
    }
    protected function getDeleteRules(): array
    {
        return [
            'id' => 'required|exists:authors,id'
        ];
    }
}
