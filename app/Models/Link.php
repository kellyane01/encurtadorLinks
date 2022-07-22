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
}
