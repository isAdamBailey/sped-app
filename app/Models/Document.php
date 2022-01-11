<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'next_action_date',
        'file_path',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
