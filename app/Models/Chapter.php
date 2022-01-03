<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chapter extends Model
{
    use HasFactory;

    protected $fillable = [
        'state_id',
        'code',
        'description',
        'active',
    ];

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function sections(): HasMany
    {
        return $this->hasMany(Section::class);
    }
}
