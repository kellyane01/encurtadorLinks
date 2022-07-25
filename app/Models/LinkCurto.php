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
}
