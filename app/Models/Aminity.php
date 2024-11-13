<?php

namespace Modules\Aminity\app\Models;

use App\Models\Listing;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Aminity extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'status',
    ];

    protected $with = ['translation'];

    public function getTitleAttribute(): ?string
    {
        return $this->relationLoaded('translation') ? $this->translation->title : '';
    }

    public function translation(): ?HasOne
    {
        return $this->hasOne(AminityTranslation::class)->where('lang_code', getSessionLanguage());
    }

    public function getTranslation($code): ?AminityTranslation
    {
        return $this->hasOne(AminityTranslation::class)->where('lang_code', $code)->first();
    }

    public function translations(): ?HasMany
    {
        return $this->hasMany(AminityTranslation::class, 'aminity_id');
    }

    public function listings()
    {
        return $this->belongsToMany(Listing::class, 'listings_aminities')->withPivot('created_at', 'updated_at');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 0);
    }
}
