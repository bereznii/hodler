<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\Currency
 *
 * @property int $id
 * @property int $cmc_id
 * @property int $cmc_rank
 * @property string $name
 * @property string $symbol
 * @property string $slug
 * @property string $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Currency newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Currency newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Currency query()
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereCmcId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereCmcRank($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereSymbol($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Currency extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cmc_id',
        'cmc_rank',
        'name',
        'symbol',
        'slug',
        'price',
        'updated_at'
    ];

    /**
     * @return mixed
     */
    public static function getForSelect(): mixed
    {
        return self::select([
            'slug',
            DB::raw("CONCAT(cmc_rank,'. ',symbol,' ',name) as symbol")
        ])
            ->get()
            ->pluck('symbol', 'slug')
            ->toArray();
    }
}
