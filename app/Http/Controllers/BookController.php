<?php

namespace App\Http\Controllers;

use App\Services\AuthorService;
use App\Services\BookService;
use App\Services\SubjectService;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function __construct(
        protected BookService $service,
        protected AuthorService $authorService,
        protected SubjectService $subjectService,
    ) {
    }

    public function index()
    {
        $books = $this->service->getList();
        return view('books.index', compact('books'));
    }

    public function create()
    {
        $authors  = $this->authorService->getList(['*'], 99999999)['data'];
        $subjects = $this->subjectService->getList(['*'], 99999999)['data'];
        return view('books.create',compact('authors','subjects'));
    }

    public function store(Request $request)
    {
        $result = $this->service->create($request->all());
        if ($result['error']) {
            return redirect()->route('books.create')->with('error', implode(', ', $result['messages']));
        }
        return redirect()->route('books.index')->with('success', implode(', ', $result['messages']));
    }

    public function edit($id)
    {
        $book = $this->service->getList(['id' => $id])['data'][0] ?? [];
        $authors  = $this->authorService->getList(['*'], 99999999)['data'];
        $subjects = $this->subjectService->getList(['*'], 99999999)['data'];
        if ($book === []) {
            return redirect()->route('books.index')->with('error', 'Livro não encontrado!');
        }
        return view('books.edit', compact('book','authors','subjects'));
    }

    public function update(Request $request, $id)
    {
        $requestAll       = $request->all();
        $requestAll['id'] = $id;
        $result           = $this->service->update($requestAll);
        if ($result['error']) {
            return redirect()->route('books.edit', ['id' => $id])->with('error', implode(', ', $result['messages']));
        }
        return redirect()->route('books.index')->with('success', implode(', ', $result['messages']));
    }

    public function destroy($id)
    {
        $result = $this->service->delete($id);
        $status = !$result['error'] ? 'success' : 'error';
        return redirect()->route('books.index')->with($status, implode(', ', $result['messages']));
    }
}
