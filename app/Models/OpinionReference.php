<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class OpinionReference extends Model
{
    use HasFactory;

    protected $table = 'opinions_reference';

    public function opinion(): HasOne
    {
        return $this->hasOne(Opinion::class, 'opinion_id');
    }
}
