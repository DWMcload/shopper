<?php

namespace App\Http\Requests;

use App\Http\Models\ShopType;
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
            'latitude' => ['required', 'decimal'],
            'longitude' => ['required', 'decimal'],
            'status' => ['required', Rule::enum(ShopType::class)
                ->only([ShopType::OPEN, ShopType::CLOSED])
            ],
            'store_type_id' => ['required', 'exists:stores,id', 'integer'],
            'max_delivery_distance' => ['required', 'decimal'],
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
