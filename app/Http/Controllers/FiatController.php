<?php

namespace App\Http\Controllers;

use App\Http\Requests\PriceRequest;
use App\Models\Asset;
use App\Models\Fiat;
use App\Models\Transaction;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
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
     * @param PriceRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws ValidationException
     */
    public function store(PriceRequest $request)
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
            $request->session()->flash('notification', 'Вложение успешно добавлено!');
        } else {
            $request->session()->flash('notification', 'Произошла ошибка.');
        }

        return redirect()->route('fiat');
    }

    /**
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('_fiat_form');
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
