<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    /**
     * @param $transactions
     * @return float
     */
    public static function getFiatInvestedInAsset($transactions): float
    {
        $transactionsArray = $transactions->toArray();
        return array_reduce($transactionsArray, function ($carry, $item) {
            if ($item['result'] === self::RESULT_BUY) {
                $carry += $item['quantity'] * $item['price'];
            } elseif ($item['result'] === self::RESULT_SELL) {
                $carry -= $item['quantity'] * $item['price'];
            }
            return $carry;
        }, 0);
    }
}
