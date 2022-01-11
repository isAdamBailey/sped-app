<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Section extends Model
{
    use HasFactory;
    use HasSlug;

    protected $fillable = [
        'state_id',
        'chapter_id',
        'code',
        'url',
        'description',
        'content',
        'code_title',
    ];

    public function scopeSearch($query, ?string $search)
    {
        if ($search) {
            return $query->where('code', 'LIKE', '%'.$search.'%')
                ->orWhere('description', 'LIKE', '%'.$search.'%')
                ->orWhere('content', 'LIKE', '%'.$search.'%');
        }

        return $query;
    }

    public function scopeFilterState($query, ?string $state)
    {
        if ($state) {
            return $query->whereHas('state', fn ($q) => $q->where('states.name', strtolower($state)));
        }

        return $query;
    }

    public function scopeActive($query)
    {
        return $query->whereHas('chapter', fn ($query) => $query->where('active', 1));
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class)
            ->select(['id', 'name', 'code_title']);
    }

    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class)
            ->select(['id', 'code', 'description', 'active']);
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(['code_title', 'code'])
            ->saveSlugsTo('slug');
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
