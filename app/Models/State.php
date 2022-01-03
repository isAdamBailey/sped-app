<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class State extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code_title',
    ];

    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class);
    }

    public function sections(): HasMany
    {
        return $this->hasMany(Section::class);
    }
}
