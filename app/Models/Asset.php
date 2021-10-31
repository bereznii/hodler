<?php

namespace App\Models;

use App\Services\AssetMetricsService;
use http\Exception\RuntimeException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * App\Models\Asset
 *
 * @property int $id
 * @property int $user_id
 * @property int $currency_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Currency $currency
 * @property-read Collection|\App\Models\Transaction[] $transactions
 * @property-read int|null $transactions_count
 * @method static Builder|Asset newModelQuery()
 * @method static Builder|Asset newQuery()
 * @method static Builder|Asset query()
 * @method static Builder|Asset whereCreatedAt($value)
 * @method static Builder|Asset whereCurrencyId($value)
 * @method static Builder|Asset whereId($value)
 * @method static Builder|Asset whereUpdatedAt($value)
 * @method static Builder|Asset whereUserId($value)
 * @mixin \Eloquent
 */
class Asset extends Model
{
    use HasFactory;

    /** @var AssetMetricsService|null $assetMetrics */
    private AssetMetricsService|null $assetMetrics;

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id', 'cmc_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * @return AssetMetricsService
     */
    public function getAssetMetrics(): AssetMetricsService
    {
        if (!isset($this->assetMetrics)) {
            $this->assetMetrics = new AssetMetricsService($this);
        }

        return $this->assetMetrics;
    }

    /**
     * @param string $type
     * @return float
     */
    public function getPriceDifference(string $type): float
    {
        return match($type) {
            'percent' => $this->getPercentPriceDifference($this->getAssetMetrics()->averageBuyPrice),
            'money' => self::formatFloat($this->getAssetMetrics()->currentAssetPrice - $this->getAssetMetrics()->investedMoney),
            'default' => null
        };
    }

    /**
     * @param float $buyPrice
     * @return string
     */
    private function getPercentPriceDifference(float $buyPrice)
    {
        $currentPrice = $this->currency->price;
        $increase = $currentPrice - $buyPrice;
        $difference = ($increase / $buyPrice) * 100;

        return self::formatFloat($difference);
    }

    /**
     * @param float|int $value
     * @return string
     */
    private static function formatFloat(float|int $value): string
    {
        return number_format((float)$value, 2, '.', '');
    }
}
