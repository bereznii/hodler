<?php

namespace App\Repositories;

use App\Models\Asset;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class AssetRepository
{
    /**
     * @return Builder[]|Collection
     */
    public static function getForTable()
    {
        return Asset::with('currency')
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
}
