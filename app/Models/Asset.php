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
        return $difference;
    }

    /**
     * @return float
     */
    public function getAssetPrice(): float
    {
        return $this->currency->price * $this->getAssetQuantity();
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

        return $result['price'] / ($result['quantity'] === 0 ? 1 : $result['quantity']);
    }

    /**
     * @param Collection $assets
     * @return float
     */
    public static function getOverallPrice(Collection $assets): float
    {
        return $assets->count() === 0
            ? 0
            : $assets->reduce(function ($carry, $item) {
                return $carry + $item->getAssetPrice();
            });
    }
}
