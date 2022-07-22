<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search', '');

        $links = Link::search($search)
            ->latest()
            ->paginate(15);

        return view('index', compact('links', 'search'));
    }

    public function create(Request $request)
    {
    }

    public function store()
    {
        return redirect()
            ->route('links.index')
            ->withSuccess(__('crud.common.created'));
    }

    public function show(Request $request)
    {
    }

    public function edit(Request $request)
    {
    }

    public function update()
    {
        return redirect()
            ->route('links.index')
            ->withSuccess(__('crud.common.saved'));
    }

    public function destroy(Request $request)
    {
        return redirect()
            ->route('links.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
