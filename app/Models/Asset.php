<?php

namespace App\Models;

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
     * @param string $type
     * @return float
     */
    public function getPriceDifference(string $type): float
    {
        return match($type) {
            'percent' => $this->getPercentPriceDifference($this->getAveragePrice()),
            'money' => self::formatFloat($this->getAssetPrice() - $this->getBuyPrice()),
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
     * @return float
     */
    public function getAssetPrice(): float
    {
        $price = $this->currency->price * $this->getAssetQuantity();
        return self::formatFloat($price);
    }

    /**
     * @return float
     */
    public function getBuyPrice(): float
    {
        $price = $this->getAveragePrice() * $this->getAssetQuantity();
        return self::formatPrice($price);
    }

    /**
     * @return Builder[]|Collection
     */
    public static function getForTable()
    {
        return self::with('currency')
            ->where('user_id', Auth::id())
            ->get()
            ->sortBy(function($item) {
                return $item->currency->cmc_rank;
            });
    }

    /**
     * @return mixed
     */
    public static function getUserCurrencies(): mixed
    {
        return Asset::select('currency_id')
            ->where('user_id', Auth::id())
            ->get()
            ->pluck('currency_id')
            ->toArray();
    }

    /**
     * @return float
     */
    public function getAssetQuantity(): float
    {
        $res = array_reduce($this->transactions->toArray(), function ($carry, $item) {
            $quantity = (float) $item['quantity'];
            $carry = (float) $carry;

            if ($item['result'] === Transaction::RESULT_BUY) {
                $carry = $carry + $quantity;
            } else {
                $carry = round($carry - $quantity, 10);
            }

            return $carry;
        }, 0.0);

        return $res == 0
            ? 0
            : $res;
    }

    /**
     * @return float
     */
    public function getAveragePrice(): float
    {
        $result = array_reduce($this->transactions->toArray(), function ($carry, $item) {
            if ($item['result'] === Transaction::RESULT_BUY) {
                $carry['quantity'] += $item['quantity'];
                $carry['price'] += $item['quantity'] * $item['price'];
            }
            return $carry;
        }, ['quantity' => 0, 'price' => 0]);

        $price = $result['price'] / ($result['quantity'] == 0 ? 1 : $result['quantity']);
        return self::formatPrice($price);
    }

    /**
     * @param Collection $assets
     * @return float
     */
    public static function getOverallPrice(Collection $assets): float
    {
        $price = $assets->count() === 0
            ? 0
            : $assets->reduce(function ($carry, $item) {
                return $carry + $item->getAssetPrice();
            });

        return self::formatFloat($price);
    }

    /**
     * @param Collection $assets
     * @return float
     */
    public static function getInvestedPrice(Collection $assets): float
    {
        $price = $assets->count() === 0
            ? 0
            : $assets->reduce(function ($carry, $item) {
                return $carry + $item->getBuyPrice();
            });

        return self::formatPrice($price);
    }

    /**
     * @param float|int $value
     * @return string
     */
    private static function formatFloat(float|int $value): string
    {
        return number_format((float)$value, 2, '.', '');
    }

    /**
     * @param float|int $value
     * @return string
     */
    private static function formatPrice(float|int $value): string
    {
        return number_format((float)$value, 4, '.', '');
    }

    /**
     * @param float $overallPrice
     * @param float $fiatInvested
     * @return array
     */
    public static function getTotalPnl(float $overallPrice, float $fiatInvested): array
    {
        if ($overallPrice == 0) {
            return [
                'percentDifference' => 0,
                'moneyDifference' => 0,
            ];
        }

        $increase = $overallPrice - $fiatInvested;
        $difference = ($increase / $fiatInvested) * 100;

        $pnl['percentDifference'] = self::formatFloat($difference);
        $pnl['moneyDifference'] = self::formatFloat($increase);

        return $pnl;
    }
}
