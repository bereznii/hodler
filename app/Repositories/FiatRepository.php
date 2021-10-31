<?php

namespace App\Repositories;

use App\Models\Fiat;
use Illuminate\Support\Facades\Auth;

class FiatRepository
{
    /**
     * @return float|int
     */
    public static function getInvestmentsSize(): float|int
    {
        $records = Fiat::where('user_id', Auth::id())->pluck('price')->toArray();

        return array_reduce($records, function ($carry, $item) {
            $carry += $item;
            return $carry;
        }, 0);
    }
}
