<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function groups()
    {
        return $this->morphedByMany(Group::class, 'taggable');
    }

    public function websites()
    {
        return $this->morphedByMany(Website::class, 'taggable');
    }
}
