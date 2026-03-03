<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tag extends Model
{
    /** @use HasFactory<\Database\Factories\TagFactory> */
    use HasFactory;
    
    public function groups() {
        return $this->morphedByMany(Group::class, 'taggable');
    }
    
    public function websites() {
        return $this->morphedByMany(Website::class, 'taggable');
    }
}
