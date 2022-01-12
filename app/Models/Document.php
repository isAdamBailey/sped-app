<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'next_action_date',
        'file_path',
    ];

    public function getNameAttribute($value): string
    {
        return ucfirst($value);
    }

    public function getFileUrlAttribute(): ?string
    {
        if (empty($this->file_path)) {
            return null;
        }

        return Storage::temporaryUrl($this->file_path, now()->addMinutes(30));
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
