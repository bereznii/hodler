<?php

namespace App\Http\Controllers;

use App\Http\Requests\PriceRequest;
use App\Models\Asset;
use App\Models\Currency;
use App\Models\Fiat;
use App\Models\Transaction;
use App\Rules\Decimal;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
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
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $currencies = Currency::getForSelect();
        $assets = Asset::getForTable();
        $overallPrice = Asset::getOverallPrice($assets);
        $currencyUpdate = Currency::getLastUpdateTime();
        $fiatInvested = Fiat::getInvestmentsSize();
        $totalPnl = Asset::getTotalPnl($overallPrice, $fiatInvested);
        $isPositive = $totalPnl['moneyDifference'] > 0;

        return view(
            'main-dashboard',
            compact('currencies', 'assets', 'overallPrice', 'currencyUpdate', 'fiatInvested', 'totalPnl', 'isPositive')
        );
    }

    /**
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function advanced()
    {
        $currencies = Currency::getForSelect();
        $assets = Asset::getForTable();
        $investedPrice = Asset::getInvestedPrice($assets);
        $overallPrice = Asset::getOverallPrice($assets);
        $currencyUpdate = Currency::getLastUpdateTime();
        $fiatInvested = Fiat::getInvestmentsSize();
        $totalPnl = Asset::getTotalPnl($overallPrice, $fiatInvested);
        $isPositive = $totalPnl['moneyDifference'] > 0;

        return view(
            'advanced-dashboard',
            compact('currencies', 'assets', 'investedPrice', 'overallPrice', 'currencyUpdate', 'fiatInvested', 'totalPnl', 'isPositive')
        );
    }

    /**
     * @param PriceRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(PriceRequest $request)
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
            $transaction->price = $request->get('quantity');
            $transaction->result = Transaction::RESULT_BUY;
            $transaction->save();

            $request->session()->flash('notification', 'Актив успешно добавлен!');
        } else {
            $request->session()->flash('notification', 'Произошла ошибка.');
        }

        return redirect()->route('advanced');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id)
    {
        Asset::find($id)->delete();
        Transaction::where('asset_id', $id)->delete();

        return redirect()->route('advanced');
    }
}
