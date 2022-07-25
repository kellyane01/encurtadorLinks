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
}
