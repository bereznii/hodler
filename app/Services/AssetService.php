<?php

namespace App\Services;

use App\Models\Asset;
use Illuminate\Database\Eloquent\Collection;

class AssetService
{
    /**
     * @param Collection $assets
     * @return float
     */
    public static function getOverallPrice(Collection $assets): float
    {
        $price = $assets->count() === 0
            ? 0
            : $assets->reduce(function ($carry, $item) {
                /** @var Asset $item */
                return $carry + $item->getAssetMetrics()->currentAssetPrice;
            });

        return self::formatFloat($price);
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

    /**
     * @param Collection $assets
     * @return float
     */
    public static function getInvestedPrice(Collection $assets): float
    {
        $price = $assets->count() === 0
            ? 0
            : $assets->reduce(function ($carry, $item) {
                /** @var Asset $item */
                return $carry + $item->getAssetMetrics()->investedMoney;
            });

        return self::formatPrice($price);
    }

    // FORMATS

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
}
