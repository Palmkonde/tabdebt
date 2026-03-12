<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function websites()
    {
        return $this->hasMany(Website::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function scopeSearch($query, $term)
    {
        if ($term === '' || $term === null) {
            return;
        }

        $query->where(function ($q) use ($term) {
            $q->where('name', 'like', '%'.$term.'%')
                ->orWhere('description', 'like', '%'.$term.'%');
        });
    }

    public function scopeFilterByTag($query, $tagId)
    {
        if ($tagId === '' || $tagId === null) {
            return;
        }

        $query->whereHas('tags', fn ($q) => $q->where('tags.id', $tagId));
    }

    public function scopeSorted($query, $sortBy)
    {
        match ($sortBy) {
            'oldest' => $query->oldest(),
            'name_asc' => $query->orderBy('name'),
            'name_desc' => $query->orderBy('name', 'desc'),
            'most_sites' => $query->withCount('websites')->orderByDesc('websites_count'),
            'fewest_sites' => $query->withCount('websites')->orderBy('websites_count'),
            default => $query->latest(),
        };
    }
}
