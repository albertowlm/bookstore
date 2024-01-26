<?php

namespace App\Http\Controllers;

use App\Services\SubjectService;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function __construct(
        protected SubjectService $service
    ) {
    }

    public function index()
    {
        $subjects = $this->service->getList();
        return view('subjects.index', compact('subjects'));
    }

    public function create()
    {
        return view('subjects.create');
    }

    public function store(Request $request)
    {
        $result = $this->service->create($request->all());
        if ($result['error']) {
            return redirect()->route('subjects.create')->with('error', implode(', ', $result['messages']));
        }
        return redirect()->route('subjects.index')->with('success', implode(', ', $result['messages']));
    }

    public function edit($id)
    {
        $subject = $this->service->getList(['id' => $id])['data'][0] ?? [];
        if ($subject === []) {
            return redirect()->route('subjects.index')->with('error', 'Assunto não encontrado!');
        }
        return view('subjects.edit', compact('subject'));
    }

    public function update(Request $request, $id)
    {
        $requestAll       = $request->all();
        $requestAll['id'] = $id;
        $result           = $this->service->update($requestAll);
        if ($result['error']) {
            return redirect()->route('subjects.edit', ['id' => $id])->with('error', implode(', ', $result['messages']));
        }
        return redirect()->route('subjects.index')->with('success', implode(', ', $result['messages']));
    }

    public function destroy($id)
    {
        $result = $this->service->delete($id);
        $status = !$result['error'] ? 'success' : 'error';
        return redirect()->route('subjects.index')->with($status, implode(', ', $result['messages']));
    }
}
