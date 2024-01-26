<?php

namespace App\Http\Controllers;

use App\Services\AuthorService;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function __construct(
        protected AuthorService $service
    ) {
    }

    public function index()
    {
        $authors = $this->service->getList();
        return view('authors.index', compact('authors'));
    }

    public function create()
    {
        return view('authors.create');
    }

    public function store(Request $request)
    {
        $result = $this->service->create($request->all());
        if ($result['error']) {
            return redirect()->route('authors.create')->with('error', implode(', ', $result['messages']));
        }
        return redirect()->route('authors.index')->with('success', implode(', ', $result['messages']));
    }

    public function edit($id)
    {
        $author = $this->service->getList(['id' => $id])['data'][0] ?? [];
        if ($author === []) {
            return redirect()->route('authors.index')->with('error', 'Autor não encontrado!');
        }
        return view('authors.edit', compact('author'));
    }

    public function update(Request $request, $id)
    {
        $requestAll       = $request->all();
        $requestAll['id'] = $id;
        $result           = $this->service->update($requestAll);
        if ($result['error']) {
            return redirect()->route('authors.edit', ['id' => $id])->with('error', implode(', ', $result['messages']));
        }
        return redirect()->route('authors.index')->with('success', implode(', ', $result['messages']));
    }

    public function destroy($id)
    {
        $result = $this->service->delete($id);
        $status = !$result['error'] ? 'success' : 'error';
        return redirect()->route('authors.index')->with($status, implode(', ', $result['messages']));
    }
}
