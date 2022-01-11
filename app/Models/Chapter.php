<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Chapter extends Model
{
    use HasFactory;
    use HasSlug;

    protected $fillable = [
        'title_id',
        'state_id',
        'code',
        'description',
        'active',
        'code_title',
    ];

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class)
            ->select(['id', 'name', 'code_title']);
    }

    public function sections(): HasMany
    {
        return $this->hasMany(Section::class)
            ->select(['chapter_id', 'url', 'code', 'description', 'content']);
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
