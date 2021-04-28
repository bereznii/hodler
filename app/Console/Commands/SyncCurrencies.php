<?php

namespace App\Console\Commands;

use App\Models\Currency;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncCurrencies extends Command
{
    private const MARKET_CAP_MIN = 5000000000;

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
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle()
    {
        $client = new Client(['base_uri' => 'https://pro-api.coinmarketcap.com']);

        $response = $client->get('/v1/cryptocurrency/listings/latest', [
            'headers' => [
                'X-CMC_PRO_API_KEY' => env('CMC_TOKEN'),
            ],
            'query' => [
                'market_cap_min' => self::MARKET_CAP_MIN
            ]
        ]);

        $contents = $response->getBody()->getContents();

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
        }

        Log::info('Currencies sync finished');
        echo "Currencies sync finished\n";

        return 1;
    }
}
