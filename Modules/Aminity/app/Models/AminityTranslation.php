<?php

namespace Modules\Aminity\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AminityTranslation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'lang_code',
        'aminity_id',
    ];

    public function aminity(): ?BelongsTo
    {
        return $this->belongsTo(Aminity::class);
    }
}
