<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model EditInfo
 *
 * @property int $id
 * @property int $prev_info_id
 * @property \Illuminate\Support\Carbon|null $edited_at
 * @property \Illuminate\Support\Carbon|null $accepted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read PrevInfo $prevInfo
 */
final class EditInfo extends Model
{
    /**
     * Określa niestandardową nazwę tabeli.
     *
     * @var string
     */
    protected $table = 'edit_info';

    /**
     * Określa, które pola mogą być masowo przypisane.
     *
     * @var list<string>
     */
    protected $fillable = [
        'prev_info_id',
        'edited_at',
        'accepted_at',

    ];

    /**
 * Relacja do poprzednich informacji.
 *
 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\PrevInfo, \App\Models\EditInfo>
 */
    public function prevInfo(): BelongsTo
    {
        /** @var BelongsTo<\App\Models\PrevInfo, \App\Models\EditInfo> $relation */
        $relation = $this->belongsTo(PrevInfo::class, 'prev_info_id');
        return $relation;
    }
}
