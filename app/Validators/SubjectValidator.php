<?php

namespace App\Validators;

class SubjectValidator extends AbstractCrudValidator
{
    protected function getAttributes(): array
    {
        return [
            'description' => 'Descrição',
        ];
    }
    protected function getCreateRules(): array
    {
        return [
            'description' => 'required|string|max:20',
        ];
    }
    protected function getUpdateRules(): array
    {
        $rules = $this->getCreateRules();
        $rules['id'] = 'required|exists:subjects,id';
        return $rules;
    }
    protected function getDeleteRules(): array
    {
        return [
            'id' => 'required|exists:subjects,id'
        ];
    }
}
