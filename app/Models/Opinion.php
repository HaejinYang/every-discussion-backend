<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Opinion extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['topic_id', 'user_id', 'title', 'content', 'agree_type'];
    protected $appends = ['refer_to', 'referred'];

    public function getReferToAttribute()
    {
        return $this->referTo()->get()->pluck('refer_to_id');
    }

    public function getReferredAttribute()
    {
        return $this->referred()->get()->pluck('opinion_id');
    }

    public function participant(): BelongsTo
    {
        return $this->belongsTo(Participant::class);
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    public function referred(): HasMany
    {
        return $this->HasMany(OpinionReference::class, 'refer_to_id');
    }

    public function referTo(): HasOne
    {
        return $this->hasOne(OpinionReference::class, 'opinion_id');
    }
}
