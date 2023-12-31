<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OpinionReference extends Model
{
    use HasFactory;

    protected $table = 'opinions_reference';
    public $timestamps = false;

    public function referredOpinion(): BelongsTo
    {
        return $this->belongsTo(Opinion::class, 'opinion_id');
    }

    public function referToOpinion(): BelongsTo
    {
        return $this->belongsTo(Opinion::class, 'refer_to_id');
    }
}
