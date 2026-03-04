<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    public function group() {
        return $this->belongsTo(Group::class);
    }

    public function tags() {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}
