<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PriceRequest extends FormRequest
{
    private const DEFAULT_RULES = 'required|numeric';

    /**
     * @var array
     */
    private array $rules = [];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * {inheritdoc}
     */
    protected function prepareForValidation()
    {
        if ($this->has('price')) {
            $this->merge(['price' => str_replace(',', '.', $this->price)]);
            $this->rules['price'] = self::DEFAULT_RULES;
        }
        if ($this->has('quantity')) {
            $this->merge(['quantity' => str_replace(',', '.', $this->quantity)]);
            $this->rules['quantity'] = self::DEFAULT_RULES;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return $this->rules;
    }
}
