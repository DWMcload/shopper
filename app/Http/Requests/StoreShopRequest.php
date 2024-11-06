<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Shop;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class StoreShopRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'latitude' => ['required', 'decimal:0,6'],
            'longitude' => ['required', 'decimal:0,6'],
            'status' => ['required',
                Rule::in([Shop::OPEN, Shop::CLOSED])
            ],
            'store_type_id' => ['required', 'exists:store_types,id', 'integer'],
            'max_delivery_distance' => ['required', 'decimal:0,4'],
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'message'   => 'Validation errors',
            'data'      => $validator->errors()
        ]));
    }
}
