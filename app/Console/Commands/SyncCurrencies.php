<?php

namespace App\Console\Commands;

use App\Models\Currency;
use Illuminate\Support\Facades\Http;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncCurrencies extends Command
{
    private const MARKET_CAP_MIN = 80000000;
//    private const MARKET_CAP_MIN = 1000000000;
    private const DEFAULT_LIMIT = 399;
    private const DEFAULT_CMC_RANK = 401;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:currencies';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync currencies data with CoinMarketCap';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return int
     */
    public function handle()
    {
        $contents = Http::withHeaders([
            'X-CMC_PRO_API_KEY' => env('CMC_TOKEN'),
        ])->get('https://pro-api.coinmarketcap.com/v1/cryptocurrency/listings/latest', [
            'limit' => self::DEFAULT_LIMIT,
//                'market_cap_min' => self::MARKET_CAP_MIN
        ])->body();


        if ($contents = json_decode($contents, true)) {
            foreach ($contents['data'] ?? [] as $currency) {
                Currency::updateOrCreate(
                    [
                        'cmc_id' => $currency['id']
                    ],
                    [
                        'name' => $currency['name'],
                        'symbol' => $currency['symbol'],
                        'cmc_rank' => $currency['cmc_rank'],
                        'slug' => $currency['slug'],
                        'price' => round($currency['quote']['USD']['price'], 7, PHP_ROUND_HALF_DOWN),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]
                );
            }
            Currency::where('updated_at', '<', \DB::raw('DATE_SUB(NOW(), INTERVAL 1 MINUTE)'))
                ->update(['cmc_rank' => (self::DEFAULT_LIMIT + 1)]);
        }

        Log::info('Currencies sync finished');
        echo "Currencies sync finished\n";

        return 1;
    }
}
