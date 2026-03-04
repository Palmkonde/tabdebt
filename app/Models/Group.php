<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;
    
    protected $guarded = [];
    
    public function websites() {
        return $this->hasMany(Website::class); 
    }
    
    public function user() {
        return $this->belongsTo(User::class);
    }
    
    public function tags() {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}
