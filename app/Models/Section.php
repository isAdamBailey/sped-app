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
    ];

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
