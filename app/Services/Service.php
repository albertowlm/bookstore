<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use PDOException;

class Service
{
    protected $messages = [
        'error_create'   => 'Erro ao criar',
        'error_update'   => 'Erro ao alterar',
        'error_delete'   => 'Erro ao deletar',
        'error_view'     => 'Erro ao listar',
        'error_database' => 'Erro no banco de dados'
    ];

    protected $name;

    public function parserError(
        $callable,
        $action,
        $useTransaction = true
    ) {
        try {
            if ($useTransaction) {
                DB::beginTransaction();
            }
            $result = $callable();
            if ($useTransaction) {
                DB::commit();
            }
            return $result;
        }
        catch (PDOException $e) {
            if ($useTransaction) {
                DB::rollBack();
            }
            return [
                "error"    => true,
                "messages" => [$this->messages['error_database']],
            ];
        }
        catch (\Exception $e) {
            if ($useTransaction) {
                DB::rollBack();
            }
            return [
                "error"    => true,
                "messages" => [$this->messages['error_' . $action] . " " . $this->name],
            ];
        }
    }
}

