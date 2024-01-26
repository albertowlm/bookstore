<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Repositories\AuthorRepositoryEloquent;
use App\Validators\AuthorValidator;
use Database\Factories\AuthorFactory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Mockery;
use Tests\TestCase;
use App\Services\AuthorService;

class AuthorServiceTest extends TestCase
{
    use DatabaseMigrations;


    public function test_create_returns_a_successful(): void
    {
        $authorService = app()->make(AuthorService::class);
        $inputCreate   = [
            'name' => 'J. K. Rowling'
        ];
        $authorService->create($inputCreate);
        $this->assertDatabaseHas('authors', $inputCreate);
    }

    public function test_create_returns_a_error_validator(): void
    {
        $authorService = app()->make(AuthorService::class);
        $outputCreate  = $authorService->create([]);
        $expected      = [
            "error"    => true,
            "messages" => [
                "O campo Nome é obrigatório."
            ]
        ];
        $this->assertEquals($expected, $outputCreate);
    }

    public function test_create_returns_a_error_database(): void
    {
        $mockRepository = Mockery::mock(AuthorRepositoryEloquent::class);
        $mockRepository->shouldReceive('create')->andThrow(new \PDOException('Mensagem de erro'));

        $authorService = new AuthorService($mockRepository, new AuthorValidator());
        $inputCreate   = [
            'name' => 'J. K. Rowling'
        ];
        $outputCreate  = $authorService->create($inputCreate);
        $expected      = [
            "error"   => true,
            "messages" => ["Erro no banco de dados"],
        ];
        $this->assertEquals($expected, $outputCreate);
    }

    public function test_update_returns_a_successful(): void
    {
        $author = AuthorFactory::new()->create()->toArray();

        $authorService = app()->make(AuthorService::class);
        $inputUpdate   = [
            'id'   => $author['id'],
            'name' => 'J. K. Rowling'
        ];
        $authorService->update($inputUpdate);
        $this->assertDatabaseHas('authors', $inputUpdate);
    }

    public function test_update_returns_a_error_validator(): void
    {
        $authorService = app()->make(AuthorService::class);
        $outputUpdate  = $authorService->update([]);
        $expected      = [
            "error"    => true,
            "messages" => [
                "O campo Nome é obrigatório.",
                'O campo id é obrigatório.'

            ]
        ];
        $this->assertEquals($expected, $outputUpdate);
    }

    public function test_update_returns_a_error_database(): void
    {
        $author = AuthorFactory::new()->create()->toArray();

        $mockRepository = Mockery::mock(AuthorRepositoryEloquent::class);
        $mockRepository->shouldReceive('update')->andThrow(new \PDOException('Mensagem de erro'));

        $authorService = new AuthorService($mockRepository, new AuthorValidator());
        $inputUpdate   = [
            'id'   => $author['id'],
            'name' => 'J. K. Rowling'
        ];
        $outputUpdate  = $authorService->update($inputUpdate);
        $expected      = [
            "error"   => true,
            "messages" => ["Erro no banco de dados"],
        ];
        $this->assertEquals($expected, $outputUpdate);
    }

    public function test_getlist_returns_a_successful(): void
    {
        Author::factory(5)->create();
        $authorService = app()->make(AuthorService::class);

        $outputGetListData = $authorService->getList()['data'];
        $this->assertEquals(count($outputGetListData), 5);
    }

    public function test_getlist_returns_a_error_database(): void
    {
        AuthorFactory::new()->create()->toArray();

        $mockRepository = Mockery::mock(AuthorRepositoryEloquent::class);
        $mockRepository->shouldReceive('getList')->andThrow(new \PDOException('Mensagem de erro'));

        $authorService = new AuthorService($mockRepository, new AuthorValidator());
        $outputGetlist = $authorService->getList();

        $expected = [
            "error"   => true,
            "messages" => ["Erro no banco de dados"],
        ];
        $this->assertEquals($expected, $outputGetlist);
    }

    public function test_getlist_returns_a_successful_with_paginate(): void
    {
        Author::factory(30)->create();
        $authorService = app()->make(AuthorService::class);
        $perPage       = 10;

        $outputGetlist = $authorService->getList([], $perPage);
        $this->assertEquals(count($outputGetlist['data']), $perPage);
        $this->assertEquals($outputGetlist['total'], 30);
        $this->assertEquals($outputGetlist['current_page'], 1);

        $outputGetlistSecoundPage = $authorService->getList([], $perPage, 2);
        $this->assertEquals(count($outputGetlistSecoundPage['data']), $perPage);
        $this->assertEquals($outputGetlistSecoundPage['total'], 30);
        $this->assertEquals($outputGetlistSecoundPage['current_page'], 2);
    }

    public function test_delete_returns_a_successful(): void
    {
        AuthorFactory::new()->create()->toArray();
        $this->assertDatabaseCount('authors', 1);
        $authorService = app()->make(AuthorService::class);
        $outputDelete  = $authorService->delete(1);
        $this->assertEquals(
            [
                'error'    => false,
                'messages' => [
                    'Autor excluído com sucesso'
                ]
            ], $outputDelete
        );
        $this->assertDatabaseCount('authors', 0);
    }

    public function test_delete_returns_a_error_validator(): void
    {
        $authorService = app()->make(AuthorService::class);
        $outputUpdate  = $authorService->delete(2);
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
        AuthorFactory::new()->create()->toArray();

        $mockRepository = Mockery::mock(AuthorRepositoryEloquent::class);
        $mockRepository->shouldReceive('delete')->andThrow(new \PDOException('Mensagem de erro'));

        $authorService = new AuthorService($mockRepository, new AuthorValidator());
        $outputDelete  = $authorService->delete(1);
        $expected      = [
            "error"   => true,
            "messages" => ["Erro no banco de dados"],
        ];
        $this->assertEquals($expected, $outputDelete);
    }

}
