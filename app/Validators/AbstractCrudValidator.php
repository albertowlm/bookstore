<?php

namespace App\Validators;
abstract class AbstractCrudValidator
{
    protected abstract function getAttributes(): array;
    protected abstract function getCreateRules(): array;

    protected abstract function getUpdateRules(): array;

    protected abstract function getDeleteRules(): array;

    public function create($data)
    {
        return $this->validate($data, $this->getCreateRules());
    }

    public function update($data)
    {
        return $this->validate($data, $this->getUpdateRules());
    }

    public function delete($data)
    {
        return $this->validate($data, $this->getDeleteRules());
    }

    protected function validate($data, $rules)
    {
        $customMessages = [
            'exists' => 'O campo :attribute é inválido.',
            'required' => 'O campo :attribute é obrigatório.',
            'string'   => 'O campo :attribute deve ser uma string.',
            'max'      => [
                'string' => 'O campo :attribute não pode ter mais de :max caracteres.',
            ],
            'integer'  => 'O campo :attribute deve ser um número inteiro.',
            'size'     => [
                'string' => 'O campo :attribute deve ter exatamente :size caracteres.',
            ],
        ];
        $validator = validator($data, $rules,$customMessages,$this->getAttributes());
        $messages = $validator->errors()->all();
        return [
            "error" => count($messages) > 0,
            "messages" => $messages
        ];
    }
}
