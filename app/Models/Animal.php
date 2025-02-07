<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $name
 * @property string $type
 * @property int $breed_id
 * @property int $age
 * @property string $description
 * @property-read \App\Models\Cat $catBreed
 * @property-read \App\Models\Dog $dogBreed
 * @property-read \App\Models\Cat|\App\Models\Dog|null $breed
 */
class Animal extends Model
{
    protected $fillable = ['name',
        'type',
        'breed_id',
        'age',
        'description',
        'country',
        'city',
        'owner_id',
        'c_black',
        'c_white',
        'c_ginger',
        'c_tricolor',
        'c_grey',
        'c_brown',
        'c_golden',
        'c_other',
        'isApproved',
        'photo'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Cat, \App\Models\Animal>
     */
    public function catBreed(): BelongsTo
    {
        /** @var \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Cat, \App\Models\Animal> */
        return $this->belongsTo(Cat::class, 'breed_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Dog, \App\Models\Animal>
     */
    public function dogBreed(): BelongsTo
    {
        /** @var \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Dog, \App\Models\Animal> */
        return $this->belongsTo(Dog::class, 'breed_id');
    }

    /**
     * @return \App\Models\Cat|\App\Models\Dog|null
     */
    public function getBreedAttribute(): Cat|Dog|null
    {
        return $this->type === 'cat' ? $this->catBreed : $this->dogBreed;
    }

    public function getPhotoAttribute(?string $value): ?string
    {
        if ($value === null) {
            $defaultImagePath = public_path('profile_pictures/lapka.jpg');

            if (file_exists($defaultImagePath) && is_readable($defaultImagePath)) {
                $fileContent = file_get_contents($defaultImagePath);
                return $fileContent !== false ? base64_encode($fileContent) : null;
            }

            return null;
        }

        return $value;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Like, \App\Models\Animal>
     */
    public function likes(): HasMany
    {
        /** @var \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Like, \App\Models\Animal> */
        return $this->hasMany(Like::class, 'animal_id', 'id');
    }



    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\PrevInfo, static>
     */
    public function prevInfo(): HasMany
    {
        /** @var \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\PrevInfo, static> */
        return $this->hasMany(PrevInfo::class, 'animal_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\PrevInfo, static>
     */
    public function changeHistory(): HasMany
    {
        /** @var \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\PrevInfo, static> */
        return $this->hasMany(PrevInfo::class, 'animal_id');
    }




}
