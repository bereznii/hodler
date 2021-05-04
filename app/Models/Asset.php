<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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
     * @return float
     */
    public function getPriceDifference(): float
    {
        $buyPrice = $this->getAveragePrice();
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
        return self::formatFloat($price);
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
        return array_reduce($this->transactions->toArray(), function ($carry, $item) {
            if ($item['result'] === Transaction::RESULT_BUY) {
                $carry += $item['quantity'];
            } else {
                $carry -= $item['quantity'];
            }
            return $carry;
        }, 0);
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
            } else {
                $carry['quantity'] -= $item['quantity'];
                $carry['price'] -= $item['quantity'] * $item['price'];
            }
            return $carry;
        }, ['quantity' => 0, 'price' => 0]);

        $price = $result['price'] / ($result['quantity'] == 0 ? 1 : $result['quantity']);
        return self::formatFloat($price);
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

        return self::formatFloat($price);
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
