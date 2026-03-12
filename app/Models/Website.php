<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function scopeForUser($query, $user)
    {
        $query->whereIn('group_id', $user->groups()->pluck('id'));
    }

    public function scopeSearch($query, $term)
    {
        if ($term === '' || $term === null) {
            return;
        }

        $query->where(function ($q) use ($term) {
            $q->where('name', 'like', '%'.$term.'%')
                ->orWhere('url', 'like', '%'.$term.'%');
        });
    }

    public function scopeFilterByRating($query, $rating)
    {
        if ($rating === '' || $rating === null) {
            return;
        }

        $query->where('rating', $rating);
    }

    public function scopeFilterByGroup($query, $groupId)
    {
        if ($groupId === '' || $groupId === null) {
            return;
        }

        $query->where('group_id', $groupId);
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
            'rating_best' => $query->orderByRaw("CASE rating WHEN 'good' THEN 1 WHEN 'average' THEN 2 WHEN 'bad' THEN 3 END"),
            'rating_worst' => $query->orderByRaw("CASE rating WHEN 'bad' THEN 1 WHEN 'average' THEN 2 WHEN 'good' THEN 3 END"),
            default => $query->latest(),
        };
    }
}
