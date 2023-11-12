<?php

namespace Modules\Purchase\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'supplier_id' => 'required|integer',
            'date' => 'required|date',
            'product_id' => 'required|array',
            'product_id.*' => 'required|integer',
            'quantity' => 'required|array',
            'quantity.*' => 'required|numeric',
            'unit_price' => 'required|array',
            'unit_price.*' => 'required|numeric',
            'description' => 'required|array',
            'description.*' => 'required|string',
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
            'supplier_id.required' => localize('The supplier field is required.'),
            'supplier_id.integer' => localize('The supplier field is invalid.'),
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
            'unit_price.required' => localize('The unit price field is required.'),
            'unit_price.array' => localize('The unit price field is invalid.'),
            'unit_price.*.required' => localize('The unit price field is required.'),
            'unit_price.*.numeric' => localize('The unit price field is invalid.'),
            'description.required' => localize('The description field is required.'),
            'description.array' => localize('The description field is invalid.'),
            'description.*.required' => localize('The description field is required.'),
            'description.*.string' => localize('The description field is invalid.'),
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
