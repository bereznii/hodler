<?php

namespace App\Http\Controllers;

use App\Http\Requests\PriceRequest;
use App\Models\Asset;
use App\Models\Currency;
use App\Models\Fiat;
use App\Models\Transaction;
use App\Repositories\AssetRepository;
use App\Repositories\CurrencyRepository;
use App\Repositories\FiatRepository;
use App\Services\AssetService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AssetController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @return Renderable
     */
    public function index()
    {
        $currencies = CurrencyRepository::getForSelect();
        $assets = AssetRepository::getForTable();
        $overallPrice = AssetService::getOverallPrice($assets);
        $currencyUpdate = CurrencyRepository::getLastUpdateTime();
        $fiatInvested = FiatRepository::getInvestmentsSize();
        $totalPnl = AssetService::getTotalPnl($overallPrice, $fiatInvested);
        $isPositive = $totalPnl['moneyDifference'] > 0;

        return view(
            'main-dashboard',
            compact('currencies', 'assets', 'overallPrice', 'currencyUpdate', 'fiatInvested', 'totalPnl', 'isPositive')
        );
    }

    /**
     * @return Renderable
     */
    public function advanced()
    {
        $currencies = CurrencyRepository::getForSelect();
        $assets = AssetRepository::getForTable();
        $investedPrice = AssetService::getInvestedPrice($assets);
        $overallPrice = AssetService::getOverallPrice($assets);
        $currencyUpdate = CurrencyRepository::getLastUpdateTime();
        $btcPrice = CurrencyRepository::getBtcPrice();
        $fiatInvested = FiatRepository::getInvestmentsSize();
        $totalPnl = AssetService::getTotalPnl($overallPrice, $fiatInvested);
        $isPositive = $totalPnl['moneyDifference'] > 0;

        return view(
            'advanced-dashboard',
            compact('currencies', 'assets', 'investedPrice', 'overallPrice', 'currencyUpdate', 'fiatInvested', 'totalPnl', 'isPositive', 'btcPrice')
        );
    }

    /**
     * @param PriceRequest $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(PriceRequest $request)
    {
        try {
            $validated = $request->validate([
                'currency' => 'required|integer'
            ]);
        } catch (ValidationException $e) {
            $request->session()->flash('asset.create.error');
            throw $e;
        }

        $asset = new Asset();
        $asset->user_id = Auth::id();
        $asset->currency_id = $validated['currency'];

        if ($asset->save()) {
            $transaction = new Transaction();
            $transaction->asset_id = $asset->id;
            $transaction->quantity = $request->get('quantity');
            $transaction->price = $request->get('price');
            $transaction->result = Transaction::RESULT_BUY;
            $transaction->save();

            $request->session()->flash('notification', 'Актив успешно добавлен!');
        } else {
            $request->session()->flash('notification', 'Произошла ошибка.');
        }

        return redirect()->route('advanced');
    }

    /**
     * @return Application|Factory|View
     */
    public function create()
    {
        $currencies = CurrencyRepository::getForSelect();

        return view('_asset_form', compact('currencies'));
    }

    /**
     * @param $id
     * @return RedirectResponse
     */
    public function delete($id)
    {
        Asset::find($id)->delete();
        Transaction::where('asset_id', $id)->delete();

        return redirect()->route('advanced');
    }
}
