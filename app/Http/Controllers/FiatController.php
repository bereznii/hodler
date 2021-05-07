<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Fiat;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class FiatController extends Controller
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
     *
     */
    public function index()
    {
        $fiats = Fiat::where('user_id', Auth::id())->get();
        $fiatInvested = Fiat::getInvestmentsSize();

        return view('fiat', compact('fiats', 'fiatInvested'));
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
                'price' => 'required|numeric',
            ]);
        } catch (ValidationException $e) {
            $request->session()->flash('fiat.create.error');
            throw $e;
        }

        $asset = new Fiat();
        $asset->user_id = Auth::id();
        $asset->price = $validated['price'];

        if ($asset->save()) {
            $request->session()->flash('notification', 'Актив успешно добавлен!');
        } else {
            $request->session()->flash('notification', 'Произошла ошибка.');
        }

        return redirect()->route('fiat');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id)
    {
        Fiat::find($id)->delete();
        return redirect()->route('fiat');
    }
}
