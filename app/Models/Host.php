<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Host extends User
{
    protected $table = 'users';

    public function topics(): HasMany
    {
        return $this->hasMany(Topic::class);
    }
}
