<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinkCurto extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = [
        'link_id',
        'codigo',
        'data_expiracao',
    ];

    protected $searchableFields = ['*'];

    protected $table = 'links_curtos';

    public function link()
    {
        return $this->belongsTo(Link::class);
    }

    public function linksCurtos($link_id)
    {
        $linksCurtos = LinkCurto::where('link_id', $link_id)
            ->latest()
            ->paginate(10);

        foreach ($linksCurtos as $link) {
            $link->link = env('APP_URL') . '/redirect/' . $link->codigo;
            $link->data_expiracao_ = date('d/m/Y H:i', strtotime($link->data_expiracao));
            $link->situacao = $link->status == 'Ativo' ? $link->data_expiracao >= now() ? 'Ativo' : 'Expirado' : $link->status;
        }

        return $linksCurtos;
    }

    public function validacaoLinkCurto($codigos)
    {
        return LinkCurto::select('links.link')
            ->join('links', 'links.id', '=', 'links_curtos.link_id')
            ->whereIn('links_curtos.codigo', $codigos)
            ->where('links_curtos.status', 'Ativo')
            ->where('links.status', 'Ativo')
            ->where('links_curtos.data_expiracao', '>=', now())
            ->first();
    }

    public function salvar($request)
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

    public function apagar($request)
    {
        LinkCurto::where('id', $request['link_curto_id'])->update(['status' => 'Inativo', 'updated_at' => now()]);
    }
}
