<?php

namespace App\Http\Controllers;

use App\Http\Requests\PriceRequest;
use App\Models\Asset;
use App\Models\Transaction;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
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
     * @param $id
     * @return Application|Factory|View
     */
    public function create($id)
    {
        $transactions = Transaction::with(['asset','asset.currency'])
            ->whereHas('asset', function ($query) use ($id) {
                return $query->where([
                    ['assets.user_id', Auth::id()],
                    ['assets.id', $id],
                ]);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $asset = Asset::where([
            'id' => $id,
            'user_id' => Auth::id(),
        ])->firstOrFail();

        $fiatInvested = Transaction::getFiatInvestedInAsset($transactions);
        $assetPrice = $asset->getAssetPrice();
        $pnl = Asset::getTotalPnl($assetPrice, $fiatInvested);

        return view('_transaction_form', compact('asset', 'transactions', 'fiatInvested', 'assetPrice', 'pnl'));
    }

    /**
     * @param PriceRequest $request
     * @param $id
     * @return RedirectResponse
     */
    public function store(PriceRequest $request, $id): RedirectResponse
    {
        $validated = $request->validate([
            'result' => 'required|string|max:10',
        ]);
        $asset = Asset::where([
            'id' => $id,
            'user_id' => Auth::id(),
        ])->firstOrFail();

        $transaction = new Transaction();
        $transaction->asset_id = $asset->id;
        $transaction->quantity = $request->get('quantity');
        $transaction->price = $request->get('price');
        $transaction->result = $validated['result'];

        if ($transaction->save()) {
            $request->session()->flash('notification', 'Транзакция успешно добавлена!');
        } else {
            $request->session()->flash('notification', 'Произошла ошибка.');
        }

        return redirect()->route('transaction.create.form', ['id' => $asset->id]);
    }

    /**
     * @param $assetId
     * @param $transactionId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($assetId, $transactionId)
    {
        $asset = Asset::where([
            ['user_id', Auth::id()],
            ['id', $assetId],
        ])->firstOrFail();

        Transaction::where([
            ['id', $transactionId],
            ['asset_id', $asset->id],
        ])->delete();

        return redirect()->route('transaction.create.form', ['id' => $asset->id]);
    }
}
