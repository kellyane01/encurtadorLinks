<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = [
        'nome',
        'link',
        'status',
    ];

    protected $searchableFields = ['*'];

    protected $table = 'links';

    public function linksCurtos()
    {
        return $this->hasMany(LinkCurto::class);
    }

    public function links($search)
    {
        return Link::search($search)
            ->where('status', 'Ativo')
            ->latest()
            ->paginate(15);
    }

    public function salvar($request)
    {
        $validated = $request->validated();

        $validated['created_at'] = now();
        Link::insert($validated);
    }

    public function apagar(Link $link)
    {
        $link->delete();
    }
}
