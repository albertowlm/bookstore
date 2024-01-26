<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use App\Models\Subject;
use App\Repositories\BookRepositoryEloquent;
use App\Validators\BookValidator;
use Database\Factories\BookFactory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Mockery;
use Tests\TestCase;
use App\Services\BookService;

class BookServiceTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        Author::factory(2)->create();
        Subject::factory(2)->create();
    }

    public function test_create_returns_a_successful(): void
    {
        $bookService = app()->make(BookService::class);
        $inputCreate = [
            'title'                => 'Harry Potter e a Pedra Filosofal',
            'publishing_companies' => 'Bloomsbury Publishing',
            'edition'              => 1,
            'year_publication'     => '1997',
            'price'                => 99.90,
            'author_ids'            => [1],
            'subject_ids'           => [1]
        ];
        $result = $bookService->create($inputCreate);
        unset($inputCreate['author_ids']);
        unset($inputCreate['subject_ids']);
        $this->assertEquals('Livro criado com sucesso!', $result['messages'][0]);
        $this->assertDatabaseHas('books', $inputCreate);
        $this->assertDatabaseHas('book_authors', ['book_id' => 1, 'author_id' => 1]);
        $this->assertDatabaseHas('book_subjects', ['book_id' => 1, 'subject_id' => 1]);
    }

    public function test_create_returns_a_error_validator(): void
    {
        $bookService  = app()->make(BookService::class);
        $outputCreate = $bookService->create([]);
        $expected     = [
            "error"    => true,
            "messages" => [
                "O campo Título é obrigatório.",
                "O campo Editora é obrigatório.",
                "O campo Edição é obrigatório.",
                "O campo Ano de Publicação é obrigatório.",
                "O campo Preço é obrigatório.",
                "O campo Id do Autor é obrigatório.",
                "O campo Id do Assunto é obrigatório."
            ]
        ];
        $this->assertEquals($expected, $outputCreate);
    }

    public function test_create_returns_a_error_database(): void
    {
        $mockRepository = Mockery::mock(BookRepositoryEloquent::class);
        $mockRepository->shouldReceive('create')->andThrow(new \PDOException('Mensagem de erro'));

        $bookService  = new BookService($mockRepository, new BookValidator());
        $inputCreate  = [
            'title'                => 'Harry Potter e a Pedra Filosofal',
            'publishing_companies' => 'Bloomsbury Publishing',
            'edition'              => 1,
            'year_publication'     => '1997',
            'price'                => 99.90,
            'author_ids'            => [1],
            'subject_ids'           => [1]
        ];
        $outputCreate = $bookService->create($inputCreate);
        $expected     = [
            "error"   => true,
            "messages" => ["Erro no banco de dados"],
        ];
        $this->assertEquals($expected, $outputCreate);
    }

    public function test_update_returns_a_successful(): void
    {
        $book = BookFactory::new()->create()->toArray();

        $bookService = app()->make(BookService::class);
        $inputUpdate = [
            'id'                   => $book['id'],
            'title'                => 'Harry Potter e a Pedra Filosofal',
            'publishing_companies' => 'Bloomsbury Publishing',
            'edition'              => 1,
            'year_publication'     => '1997',
            'price'                => 99.90,
            'author_ids'            => [2],
            'subject_ids'           => [2]
        ];
        $bookService->update($inputUpdate);
        unset($inputUpdate['author_ids']);
        unset($inputUpdate['subject_ids']);
        $this->assertDatabaseHas('books', $inputUpdate);
        $this->assertDatabaseHas('book_authors', ['book_id' => 1, 'author_id' => 2]);
        $this->assertDatabaseHas('book_subjects', ['book_id' => 1, 'subject_id' => 2]);
    }

    public function test_update_returns_a_error_validator(): void
    {
        $bookService  = app()->make(BookService::class);
        $outputUpdate = $bookService->update([]);
        $expected     = [
            "error"    => true,
            "messages" => [
                "O campo Título é obrigatório.",
                "O campo Editora é obrigatório.",
                "O campo Edição é obrigatório.",
                "O campo Ano de Publicação é obrigatório.",
                "O campo Preço é obrigatório.",
                "O campo Id do Autor é obrigatório.",
                "O campo Id do Assunto é obrigatório.",
                "O campo id é obrigatório.",
            ]
        ];
        $this->assertEquals($expected, $outputUpdate);
    }

    public function test_update_returns_a_error_database(): void
    {
        $book = BookFactory::new()->create()->toArray();

        $mockRepository = Mockery::mock(BookRepositoryEloquent::class);
        $mockRepository->shouldReceive('update')->andThrow(new \PDOException('Mensagem de erro'));

        $bookService  = new BookService($mockRepository, new BookValidator());
        $inputUpdate  = [
            'id'                   => $book['id'],
            'title'                => 'Harry Potter e a Pedra Filosofal',
            'publishing_companies' => 'Bloomsbury Publishing',
            'edition'              => 1,
            'year_publication'     => '1997',
            'price'                => 99.90,
            'author_ids'            => [1],
            'subject_ids'           => [1]
        ];
        $outputUpdate = $bookService->update($inputUpdate);
        $expected     = [
            "error"   => true,
            "messages" => ["Erro no banco de dados"],
        ];
        $this->assertEquals($expected, $outputUpdate);
    }

    public function test_getlist_returns_a_successful(): void
    {
        Book::factory(5)->create();
        $bookService = app()->make(BookService::class);

        $outputGetListData = $bookService->getList()['data'];
        $this->assertEquals(count($outputGetListData), 5);
    }

    public function test_getlist_returns_a_error_database(): void
    {
        BookFactory::new()->create()->toArray();

        $mockRepository = Mockery::mock(BookRepositoryEloquent::class);
        $mockRepository->shouldReceive('getList')->andThrow(new \PDOException('Mensagem de erro'));

        $bookService   = new BookService($mockRepository, new BookValidator());
        $outputGetlist = $bookService->getList();

        $expected = [
            "error"   => true,
            "messages" => ["Erro no banco de dados"],
        ];
        $this->assertEquals($expected, $outputGetlist);
    }

    public function test_getlist_returns_a_successful_with_paginate(): void
    {
        Book::factory(30)->create();
        $bookService = app()->make(BookService::class);
        $perPage     = 10;

        $outputGetlist = $bookService->getList([], $perPage);
        $this->assertEquals(count($outputGetlist['data']), $perPage);
        $this->assertEquals($outputGetlist['total'], 30);
        $this->assertEquals($outputGetlist['current_page'], 1);

        $outputGetlistSecoundPage = $bookService->getList([], $perPage, 2);
        $this->assertEquals(count($outputGetlistSecoundPage['data']), $perPage);
        $this->assertEquals($outputGetlistSecoundPage['total'], 30);
        $this->assertEquals($outputGetlistSecoundPage['current_page'], 2);
    }

    public function test_delete_returns_a_successful(): void
    {
        $bookService  = app()->make(BookService::class);
        $inputCreate = [
            'title'                => 'Harry Potter e a Pedra Filosofal',
            'publishing_companies' => 'Bloomsbury Publishing',
            'edition'              => 1,
            'year_publication'     => '1997',
            'price'                => 99.90,
            'author_ids'            => [1],
            'subject_ids'           => [1]
        ];
        $bookService->create($inputCreate);
        $this->assertDatabaseCount('books', 1);
        $this->assertDatabaseCount('book_authors', 1);
        $this->assertDatabaseCount('book_subjects', 1);
        $outputDelete = $bookService->delete(1);
        $this->assertEquals(
            [
                'error'    => false,
                'messages' => [
                    'Livro excluído com sucesso'
                ]
            ], $outputDelete
        );
        $this->assertDatabaseCount('books', 0);
        $this->assertDatabaseCount('book_authors', 0);
        $this->assertDatabaseCount('book_subjects', 0);
    }

    public function test_delete_returns_a_error_validator(): void
    {
        $bookService  = app()->make(BookService::class);
        $outputUpdate = $bookService->delete(2);
        $expected     = [
            "error"    => true,
            "messages" => [
                "O campo id é inválido."
            ]
        ];
        $this->assertEquals($expected, $outputUpdate);
    }

    public function test_delete_returns_a_error_database(): void
    {
        BookFactory::new()->create()->toArray();

        $mockRepository = Mockery::mock(BookRepositoryEloquent::class);
        $mockRepository->shouldReceive('delete')->andThrow(new \PDOException('Mensagem de erro'));

        $bookService  = new BookService($mockRepository, new BookValidator());
        $outputDelete = $bookService->delete(1);
        $expected     = [
            "error"   => true,
            "messages" => ["Erro no banco de dados"],
        ];
        $this->assertEquals($expected, $outputDelete);
    }

}
