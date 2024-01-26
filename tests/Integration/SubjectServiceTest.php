<?php

namespace Tests\Feature;

use App\Models\Subject;
use App\Repositories\SubjectRepositoryEloquent;
use App\Validators\SubjectValidator;
use Database\Factories\SubjectFactory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Mockery;
use PDOException;
use Tests\TestCase;
use App\Services\SubjectService;

class SubjectServiceTest extends TestCase
{
    use DatabaseMigrations;


    public function test_create_returns_a_successful(): void
    {
        $subjectService = app()->make(SubjectService::class);
        $inputCreate   = [
            'description' => 'Ficção'
        ];
        $subjectService->create($inputCreate);
        $this->assertDatabaseHas('subjects', $inputCreate);
    }

    public function test_create_returns_a_error_validator(): void
    {
        $subjectService = app()->make(SubjectService::class);
        $outputCreate  = $subjectService->create([]);
        $expected      = [
            "error"    => true,
            "messages" => [
                "O campo Descrição é obrigatório."
            ]
        ];
        $this->assertEquals($expected, $outputCreate);
    }

    public function test_create_returns_a_error_database(): void
    {
        $mockRepository = Mockery::mock(SubjectRepositoryEloquent::class);
        $mockRepository->shouldReceive('create')->andThrow(new PDOException('Mensagem de erro'));

        $subjectService = new SubjectService($mockRepository, new SubjectValidator());
        $inputCreate   = [
            'description' => 'Ficção'
        ];
        $outputCreate  = $subjectService->create($inputCreate);
        $expected      = [
            "error"   => true,
            "messages" => ["Erro no banco de dados"],
        ];
        $this->assertEquals($expected, $outputCreate);
    }

    public function test_update_returns_a_successful(): void
    {
        $subject = SubjectFactory::new()->create()->toArray();

        $subjectService = app()->make(SubjectService::class);
        $inputUpdate   = [
            'id'   => $subject['id'],
            'description' => 'Ficção'
        ];
        $subjectService->update($inputUpdate);
        $this->assertDatabaseHas('subjects', $inputUpdate);
    }

    public function test_update_returns_a_error_validator(): void
    {
        $subjectService = app()->make(SubjectService::class);
        $outputUpdate  = $subjectService->update([]);
        $expected      = [
            "error"    => true,
            "messages" => [
                "O campo Descrição é obrigatório.",
                'O campo id é obrigatório.'

            ]
        ];
        $this->assertEquals($expected, $outputUpdate);
    }

    public function test_update_returns_a_error_database(): void
    {
        $subject = SubjectFactory::new()->create()->toArray();

        $mockRepository = Mockery::mock(SubjectRepositoryEloquent::class);
        $mockRepository->shouldReceive('update')->andThrow(new PDOException('Mensagem de erro'));

        $subjectService = new SubjectService($mockRepository, new SubjectValidator());
        $inputUpdate   = [
            'id'   => $subject['id'],
            'description' => 'Ficção'
        ];
        $outputUpdate  = $subjectService->update($inputUpdate);
        $expected      = [
            "error"   => true,
            "messages" => ["Erro no banco de dados"],
        ];
        $this->assertEquals($expected, $outputUpdate);
    }

    public function test_getlist_returns_a_successful(): void
    {
        Subject::factory(5)->create();
        $subjectService = app()->make(SubjectService::class);

        $outputGetListData = $subjectService->getList()['data'];
        $this->assertEquals(count($outputGetListData), 5);
    }

    public function test_getlist_returns_a_error_database(): void
    {
        SubjectFactory::new()->create()->toArray();

        $mockRepository = Mockery::mock(SubjectRepositoryEloquent::class);
        $mockRepository->shouldReceive('getList')->andThrow(new PDOException('Mensagem de erro'));

        $subjectService = new SubjectService($mockRepository, new SubjectValidator());
        $outputGetlist = $subjectService->getList();

        $expected = [
            "error"   => true,
            "messages" => ["Erro no banco de dados"],
        ];
        $this->assertEquals($expected, $outputGetlist);
    }

    public function test_getlist_returns_a_successful_with_paginate(): void
    {
        Subject::factory(30)->create();
        $subjectService = app()->make(SubjectService::class);
        $perPage       = 10;

        $outputGetlist = $subjectService->getList([], $perPage);
        $this->assertEquals(count($outputGetlist['data']), $perPage);
        $this->assertEquals($outputGetlist['total'], 30);
        $this->assertEquals($outputGetlist['current_page'], 1);

        $outputGetlistSecoundPage = $subjectService->getList([], $perPage, 2);
        $this->assertEquals(count($outputGetlistSecoundPage['data']), $perPage);
        $this->assertEquals($outputGetlistSecoundPage['total'], 30);
        $this->assertEquals($outputGetlistSecoundPage['current_page'], 2);
    }

    public function test_delete_returns_a_successful(): void
    {
        SubjectFactory::new()->create()->toArray();
        $this->assertDatabaseCount('subjects', 1);
        $subjectService = app()->make(SubjectService::class);
        $outputDelete  = $subjectService->delete(1);
        $this->assertEquals(
            [
                'error'    => false,
                'messages' => [
                    'Assunto excluído com sucesso'
                ]
            ], $outputDelete
        );
        $this->assertDatabaseCount('subjects', 0);
    }

    public function test_delete_returns_a_error_validator(): void
    {
        $subjectService = app()->make(SubjectService::class);
        $outputUpdate  = $subjectService->delete(2);
        $expected      = [
            "error"    => true,
            "messages" => [
                "O campo id é inválido."
            ]
        ];
        $this->assertEquals($expected, $outputUpdate);
    }

    public function test_delete_returns_a_error_database(): void
    {
        SubjectFactory::new()->create()->toArray();

        $mockRepository = Mockery::mock(SubjectRepositoryEloquent::class);
        $mockRepository->shouldReceive('delete')->andThrow(new PDOException('Mensagem de erro'));

        $subjectService = new SubjectService($mockRepository, new SubjectValidator());
        $outputDelete  = $subjectService->delete(1);
        $expected      = [
            "error"   => true,
            "messages" => ["Erro no banco de dados"],
        ];
        $this->assertEquals($expected, $outputDelete);
    }

}
