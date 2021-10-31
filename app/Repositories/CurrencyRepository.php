<?php

namespace App\Repositories;

use App\Models\Currency;
use Illuminate\Support\Facades\DB;

class CurrencyRepository
{
    /**
     * @return array
     */
    public static function getForSelect()
    {
        return Currency::select([
                'cmc_id',
                DB::raw("CONCAT(cmc_rank,'. ',symbol,' ',name) as symbol")
            ])
            ->whereNotIn('cmc_id', AssetRepository::getUserCurrencies())
            ->where('cmc_rank', '<=', 400)
            ->orderBy('cmc_rank')
            ->limit(400)
            ->get()
            ->pluck('symbol', 'cmc_id')
            ->toArray();
    }

    /**
     * @return array
     */
    public static function getLastUpdateTime()
    {
        return Currency::select('updated_at')
                ->orderBy('updated_at', 'desc')
                ->first()
                ->updated_at ?? null;
    }

    /**
     * @return string
     */
    public static function getBtcPrice(): string
    {
        $price = Currency::where('symbol', Currency::BTC_SYMBOL)->first()?->price;
        return number_format((float)$price, 4, '.', '');
    }
}
