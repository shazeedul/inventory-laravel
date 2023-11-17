<?php

namespace Modules\Invoice\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'customer_id' => 'required|integer',
            'date' => 'required|date',
            'product_id' => 'required|array',
            'product_id.*' => 'required|integer',
            'quantity' => 'required|array',
            'quantity.*' => 'required|numeric|min:1',
            'unit_price' => 'required|array',
            'unit_price.*' => 'required|numeric|min:1',
            'total' => 'required|array',
            'total.*' => 'required|numeric',
            'total_price' => 'required|numeric',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'customer_id.required' => localize('The customer field is required.'),
            'customer_id.integer' => localize('The customer field is invalid.'),
            'date.required' => localize('The date field is required.'),
            'date.date' => localize('The date field is invalid.'),
            'product_id.required' => localize('The product field is required.'),
            'product_id.array' => localize('The product field is invalid.'),
            'product_id.*.required' => localize('The product field is required.'),
            'product_id.*.integer' => localize('The product field is invalid.'),
            'quantity.required' => localize('The quantity field is required.'),
            'quantity.array' => localize('The quantity field is invalid.'),
            'quantity.*.required' => localize('The quantity field is required.'),
            'quantity.*.numeric' => localize('The quantity field is invalid.'),
            'quantity.*.min' => localize('The quantity field value greater then 0.'),
            'unit_price.required' => localize('The unit price field is required.'),
            'unit_price.array' => localize('The unit price field is invalid.'),
            'unit_price.*.required' => localize('The unit price field is required.'),
            'unit_price.*.numeric' => localize('The unit price field is invalid.'),
            'unit_price.*.min' => localize('The unit price field value greater then 0.'),
            'total.required' => localize('The total field is required.'),
            'total.array' => localize('The total field is invalid.'),
            'total.*.required' => localize('The total field is required.'),
            'total.*.numeric' => localize('The total field is invalid.'),
            'total_price.required' => localize('The total price field is required.'),
            'total_price.numeric' => localize('The total price field is invalid.'),
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
