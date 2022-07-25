<?php

namespace App\Http\Controllers;

use App\Http\Requests\LinkStoreRequest;
use App\Models\Link;
use App\Models\LinkCurto;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LinkController extends Controller
{
    protected $links;

    public function __construct(Link $links)
    {
        $this->links = $links;
    }

    public function index(Request $request)
    {
        try {
            $search = $request->get('search', '');
            $links = $this->links->links($search);

            return view('index', compact('links', 'search'));
        } catch (\Exception $exception) {
            throw ValidationException::withMessages(['success' => false, 'message' => $exception->getMessage()]);
        }
    }

    public function store(LinkStoreRequest $request)
    {
        try {
            $this->links->salvar($request);

            return redirect()
                ->route('links.index')
                ->withSuccess(__('crud.common.created'));
        } catch (\Exception $exception) {
            throw ValidationException::withMessages(['success' => false, 'message' => $exception->getMessage()]);
        }
    }

    public function destroy(Link $link)
    {
        try {
            $this->links->apagar($link);

            return redirect()
                ->route('links.index')
                ->withSuccess(__('crud.common.removed'));
        } catch (\Exception $exception) {
            throw ValidationException::withMessages(['success' => false, 'message' => $exception->getMessage()]);
        }
    }
}
