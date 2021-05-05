<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Fiat extends Model
{
    use HasFactory;

    /**
     * @param $value
     * @return string
     * @throws \Exception
     */
    public function getCreatedAtAttribute($value): string
    {
        return (new \DateTime($value))->format('Y-m-d');
    }

    /**
     * @return float|int
     */
    public static function getInvestmentsSize(): float|int
    {
        $records = self::where('user_id', Auth::id())->pluck('price')->toArray();

        return array_reduce($records, function ($carry, $item) {
            $carry += $item;
            return $carry;
        }, 0);
    }
}
