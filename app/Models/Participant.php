<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Participant extends User
{
    protected $table = 'users';

    public function topics(): BelongsToMany
    {
        return $this->belongsToMany(Topic::class);
    }

    public function opinions(): HasMany
    {
        return $this->hasMany(Opinion::class);
    }
}
