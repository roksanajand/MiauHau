<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model PrevInfo
 *
 * @property int $id
 * @property int $animal_id
 * @property array<string, mixed> $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Animal $animal
 */
final class PrevInfo extends Model
{
    /**
     * Określa niestandardową nazwę tabeli.
     *
     * @var string
     */
    protected $table = 'prev_info';

    /**
     * Określa, które pola mogą być masowo przypisane.
     *
     * @var list<string>
     */
    protected $fillable = [
        'animal_id',
        'data',
    ];

    /**
     * Relacja do zwierzęcia.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Animal, \App\Models\PrevInfo>
     */
    public function animal(): BelongsTo
    {
        /** @var BelongsTo<\App\Models\Animal, \App\Models\PrevInfo> $relation */
        $relation = $this->belongsTo(Animal::class, 'animal_id');
        return $relation;
    }
}
