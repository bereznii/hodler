<?php

namespace App\Http\Controllers;

use App\Http\Requests\PriceRequest;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

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
     * @param PriceRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(PriceRequest $request)
    {
        try {
            $validated = $request->validate([
                'asset_id' => 'required|integer',
                'result' => 'required|string|max:10',
            ]);
        } catch (ValidationException $e) {
            $request->session()->flash('transaction.create.error', request('asset_id'));
            throw $e;
        }

        $transaction = new Transaction();
        $transaction->asset_id = $validated['asset_id'];
        $transaction->quantity = $request->get('quantity');
        $transaction->price = $request->get('price');
        $transaction->result = $validated['result'];

        if ($transaction->save()) {
            $request->session()->flash('notification', 'Актив успешно обновлён!');
        } else {
            $request->session()->flash('notification', 'Произошла ошибка.');
        }

        return redirect()->route('advanced');
    }
}
