<?php

namespace App\Services;

use App\Models\Asset;
use App\Models\Transaction;

class AssetMetricsService
{
    /** @var Asset $assetModel */
    private Asset $assetModel;

    /** @var float|int */
    public float|int $averageBuyPrice = 0;

    /** @var float|int */
    public float|int $actualPrice = 0;

    /** @var float|int */
    public float|int $coinsQuantity = 0;

    /** @var float|int */
    public float|int $investedMoney = 0;

    /** @var float|int */
    public float|int $currentAssetPrice = 0;

    /** @var array */
    public array $pnl = [];

    /** @var array */
    public array $coinPricePnl = [];

    /**
     * @param Asset $assetModel
     */
    public function __construct(Asset $assetModel)
    {
        $this->assetModel = $assetModel;
        $this->calculateAverageBuyPrice();
        $this->calculateActualPrice();
        $this->calculateCoinsQuantity();
        $this->calculateInvestedMoney();
        $this->calculateCurrentAssetPrice();
        $this->calculatePnl();
        $this->calculateCoinPricePnl();
    }

    /**
     * Средняя цена покупки
     * @return void
     */
    public function calculateAverageBuyPrice()
    {
        $result = array_reduce($this->assetModel->transactions->toArray(), function ($carry, $item) {
            if ($item['result'] === Transaction::RESULT_BUY) {
                $carry['quantity'] += $item['quantity'];
                $carry['price'] += $item['quantity'] * $item['price'];
            }
            return $carry;
        }, ['quantity' => 0, 'price' => 0]);

        $price = $result['price'] / ($result['quantity'] == 0 ? 1 : $result['quantity']);

        $this->averageBuyPrice = self::formatPrice($price);
    }

    /**
     * Текущая цена на Coin Market Cap
     * @return void
     */
    public function calculateActualPrice()
    {
        $this->actualPrice = $this->assetModel->currency->getCurrentPrice();
    }

    /**
     * Количество монет
     * @return void
     */
    public function calculateCoinsQuantity()
    {
        $res = array_reduce($this->assetModel->transactions->toArray(), function ($carry, $item) {
            $quantity = (float) $item['quantity'];
            $carry = (float) $carry;

            if ($item['result'] === Transaction::RESULT_BUY) {
                $carry = $carry + $quantity;
            } else {
                $carry = round($carry - $quantity, 10);
            }

            return $carry;
        }, 0.0);

        $this->coinsQuantity = $res == 0
            ? 0
            : $res;
    }

    /**
     * Фиатные вложения
     * @return void
     */
    public function calculateInvestedMoney()
    {
        $this->investedMoney = self::formatPrice($this->coinsQuantity * $this->averageBuyPrice);
    }

    /**
     * Текущая общая стоимость актива
     * @return void
     */
    public function calculateCurrentAssetPrice()
    {
        $this->currentAssetPrice = self::formatPrice($this->coinsQuantity * $this->actualPrice);
    }

    /**
     * PNL по активу = "Фиатные вложения" - "Текущая общая стоимость актива" (% / $)
     * @return void
     */
    public function calculatePnl()
    {
        if ($this->currentAssetPrice == 0) {
            $this->pnl = [
                'percentDifference' => 0,
                'moneyDifference' => 0,
            ];
        }

        $increase = $this->currentAssetPrice - $this->investedMoney;
        $difference = ($increase / $this->investedMoney) * 100;

        $pnl['percentDifference'] = self::formatFloat($difference);
        $pnl['moneyDifference'] = self::formatFloat($increase);

        $this->pnl = $pnl;
    }

    /**
     * PNL по цене монеты = Разница в процентах между средней ценой покупки и текущей ценой
     * @return void
     */
    public function calculateCoinPricePnl()
    {
        if ($this->actualPrice == 0) {
            $this->coinPricePnl = [
                'percentDifference' => 0,
                'moneyDifference' => 0,
            ];
        }

        $increase = $this->actualPrice - $this->averageBuyPrice;
        $difference = ($increase / $this->averageBuyPrice) * 100;

        $pnl['percentDifference'] = self::formatFloat($difference);
        $pnl['moneyDifference'] = self::formatFloat($increase);

        $this->coinPricePnl = $pnl;
    }

    // FORMATS

    /**
     * @param float|int $value
     * @return string
     */
    private static function formatPrice(float|int $value): string
    {
        return number_format((float)$value, 4, '.', '');
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
