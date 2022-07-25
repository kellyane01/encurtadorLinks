<?php

namespace App\Http\Controllers;

use App\Http\Requests\LinkStoreRequest;
use App\Models\Link;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search', '');

        $links = Link::search($search)
            ->where('status', 'Ativo')
            ->latest()
            ->paginate(15);

        return view('index', compact('links', 'search'));
    }

    public function store(LinkStoreRequest $request)
    {
        $validated = $request->validated();

        if (!filter_var($request['link'], FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED)) {
            return redirect()->back()->withErrors('Link inválido');
        }

        $validated['created_at'] = now();
        Link::insert($validated);

        return redirect()
            ->route('links.index')
            ->withSuccess(__('crud.common.created'));
    }

    public function destroy(Request $request, Link $link)
    {
        $link->update(['status' => 'Inativo', 'updated_at' => now()]);

        return redirect()
            ->route('links.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
