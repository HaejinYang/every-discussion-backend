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

    protected $fillable = ['title', 'description', 'user_id'];
    protected $appends = ['participants_count', 'opinions_count', 'host'];
    protected $hidden = ['pivot'];

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

    /*
     * $appends 배열의 내용을 key로 하고 아래 함수에서 리턴하는 값을 value로 모델에 프로퍼티가 추가된다.
     */
    public function getParticipantsCountAttribute(): int
    {
        return $this->participants()->count();
    }

    public function getOpinionsCountAttribute(): int
    {
        return $this->opinions()->count();
    }

    public function getHostAttribute(): string
    {
        return $this->host()->get()->value('name');
    }
}
