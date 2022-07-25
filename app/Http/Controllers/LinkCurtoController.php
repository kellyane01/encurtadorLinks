<?php

namespace App\Http\Controllers;

use App\Http\Requests\LinkCurtoStoreRequest;
use App\Models\Link;
use App\Models\LinkCurto;
use Illuminate\Http\Request;

class LinkCurtoController extends Controller
{
    public function index(Request $request)
    {
        $linksCurtos = LinkCurto::where('link_id', $request['link_id'])
            ->latest()
            ->paginate(10);

        foreach ($linksCurtos as $link) {
            $link->link = env('APP_URL') . '/redirect/' . $link->codigo;
            $link->data_expiracao_ = date('d/m/Y H:i', strtotime($link->data_expiracao));
            $link->situacao = $link->status == 'Ativo' ? $link->data_expiracao >= now() ? 'Ativo' : 'Expirado' : $link->status;
        }

        return $linksCurtos;
    }

    public static function validacaoLinkCurto($codigos)
    {
        return LinkCurto::select('links.link')
            ->join('links', 'links.id', '=', 'links_curtos.link_id')
            ->whereIn('links_curtos.codigo', $codigos)
            ->where('links_curtos.status', 'Ativo')
            ->where('links.status', 'Ativo')
            ->where('links_curtos.data_expiracao', '>=', now())
            ->first();
    }

    public function store(LinkCurtoStoreRequest $request)
    {
        $validated = $request->validated();

        $codigos = LinkCurto::where('link_id', $request['link_id'])->pluck('codigo');
        $validacao = self::validacaoLinkCurto($codigos);

        if ($validacao) {
            return false;
        }

        //Código de 8 caracteres embaralhados
        do {
            $validated['codigo'] = substr(md5(uniqid(rand(), true)), 0, 8);
        } while (in_array($validated['codigo'], (array)LinkCurto::pluck('codigo')));

        $validated['created_at'] = now();
        $validated['data_expiracao'] = date('Y-m-d H:m:s', strtotime($validated['created_at'] . '+7days'));
        LinkCurto::insert($validated);

        return true;
    }

    public function destroy(Request $request)
    {
        LinkCurto::where('id', $request['link_curto_id'])->update(['status' => 'Inativo', 'updated_at' => now()]);
    }

    public function redirecionamento($codigo)
    {
        $linkCurto = self::validacaoLinkCurto((array)$codigo);

        if (!$linkCurto) {
            return redirect()
                ->route('links.index')
                ->withErrors(__('crud.common.no_role'));
        }
        return redirect($linkCurto->link);
    }
}
