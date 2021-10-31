<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * App\Models\Transaction
 *
 * @property int $id
 * @property int $asset_id
 * @property string $quantity
 * @property string $price
 * @property string $result
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Asset|null $asset
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction query()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereAssetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Transaction extends Model
{
    use HasFactory;

    public const RESULT_BUY = 'buy';
    public const RESULT_SELL = 'sell';

    public const RESULT_BUY_NAME = 'Покупка';
    public const RESULT_SELL_NAME = 'Продажа';

    /**
     * @return HasOne
     */
    public function asset()
    {
        return $this->hasOne(Asset::class, 'id', 'asset_id');
    }

    /**
     * @return string
     */
    public function getResultName(): string
    {
        return match($this->result) {
            self::RESULT_BUY => self::RESULT_BUY_NAME,
            self::RESULT_SELL => self::RESULT_SELL_NAME,
        };
    }
}
