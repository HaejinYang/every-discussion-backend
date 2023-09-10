<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Opinion extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['topic_id', 'user_id', 'title', 'content', 'agree_type'];

    public function participant(): BelongsTo
    {
        return $this->belongsTo(Participant::class);
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    public function reference(): HasOne
    {
        return $this->hasOne(OpinionReference::class, 'reference_id');
    }
}
