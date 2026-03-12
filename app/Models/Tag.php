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

    public function scopeVisibleToUser($query, $user, $includeWebsiteTags = true)
    {
        $groupIds = $user->groups()->pluck('id');

        $query->where(function ($q) use ($groupIds, $includeWebsiteTags) {
            if ($includeWebsiteTags) {
                $q->whereHas('websites', fn ($w) => $w->whereIn('group_id', $groupIds))
                    ->orWhereHas('groups', fn ($g) => $g->whereIn('groups.id', $groupIds));
            } else {
                $q->whereHas('groups', fn ($g) => $g->whereIn('groups.id', $groupIds));
            }
        });
    }

    public function scopeWithUserScopedCounts($query, $user)
    {
        $groupIds = $user->groups()->pluck('id');

        $query->withCount([
            'websites' => fn ($q) => $q->whereIn('group_id', $groupIds),
            'groups' => fn ($q) => $q->whereIn('groups.id', $groupIds),
        ]);
    }

    public function scopeSearch($query, $term)
    {
        if ($term === '' || $term === null) {
            return;
        }

        $query->where('name', 'like', '%'.$term.'%');
    }

    public function scopeSorted($query, $sortBy)
    {
        match ($sortBy) {
            'oldest' => $query->oldest(),
            'name_asc' => $query->orderBy('name'),
            'name_desc' => $query->orderBy('name', 'desc'),
            'most_websites' => $query->orderByDesc('websites_count'),
            'fewest_websites' => $query->orderBy('websites_count'),
            'most_groups' => $query->orderByDesc('groups_count'),
            'fewest_groups' => $query->orderBy('groups_count'),
            default => $query->latest(),
        };
    }
}
