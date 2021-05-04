<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Currency;
use App\Models\Transaction;
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
        $investedPrice = Asset::getInvestedPrice($assets);
        $overallPrice = Asset::getOverallPrice($assets);
        $currencyUpdate = Currency::getLastUpdateTime();

        return view('main-dashboard', compact('currencies', 'assets', 'investedPrice', 'overallPrice', 'currencyUpdate'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(Request $request)
    {
        try {
            $validated = $request->validate([
                'currency' => 'required|integer',
                'quantity' => 'required|numeric',
                'price' => 'required|numeric',
            ]);
        } catch (ValidationException $e) {
            $request->session()->flash('asset.create.error');
            throw $e;
        }

        $asset = new Asset();
        $asset->user_id = Auth::id();
        $asset->currency_id = $validated['currency'];
        $asset->quantity = $validated['quantity'];
        $asset->avg_price = $validated['price'];

        if ($asset->save()) {

            $transaction = new Transaction();
            $transaction->asset_id = $asset->id;
            $transaction->quantity = $validated['quantity'];
            $transaction->price = $validated['price'];
            $transaction->result = Transaction::RESULT_BUY;
            $transaction->save();

            $request->session()->flash('notification', 'Актив успешно добавлен!');
        } else {
            $request->session()->flash('notification', 'Произошла ошибка.');
        }

        return redirect()->route('home');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id)
    {
        Asset::find($id)->delete();
        Transaction::where('asset_id', $id)->delete();

        return redirect()->route('home');
    }
}
