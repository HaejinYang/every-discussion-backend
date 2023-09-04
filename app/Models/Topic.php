<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Topic extends Model
{
    use HasFactory, SoftDeletes;

    protected $appends = ['participants_count', 'opinions_count', 'host'];

    public function host(): BelongsTo
    {
        return $this->belongsTo(Host::class, 'user_id', 'id');
    }

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(Participant::class);
    }

    public function opinions(): HasMany
    {
        return $this->hasMany(Opinion::class);
    }

    public function getParticipantsCountAttribute()
    {
        return $this->participants()->count();
    }

    public function getOpinionsCountAttribute()
    {
        return $this->opinions()->count();
    }

    public function getHostAttribute()
    {
        return $this->host()->get()->value('name');
    }
}
