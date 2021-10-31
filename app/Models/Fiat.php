<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * App\Models\Fiat
 *
 * @property int $id
 * @property int $user_id
 * @property string $price
 * @property string $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Fiat newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Fiat newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Fiat query()
 * @method static \Illuminate\Database\Eloquent\Builder|Fiat whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fiat whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fiat wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fiat whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fiat whereUserId($value)
 * @mixin \Eloquent
 */
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
}
