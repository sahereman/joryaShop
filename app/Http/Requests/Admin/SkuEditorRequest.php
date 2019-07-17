<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class SkuEditorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        return [
            'skus' => ['required', 'array', function ($attribute, $value, $fail) {
                if (empty($value)) {
                    $fail('SKU 参数组合 不能为空');
                }
                Validator::validate($value, [
                    '*.photo' => 'sometimes|image',
                    '*.price' => 'sometimes|numeric|min:0.01',
                    '*.stock' => 'sometimes|integer|min:0',
                    '*.sales' => 'sometimes|integer|min:0',
                    '*.stock_increment' => 'sometimes|nullable|integer|min:0',
                    '*.stock_decrement' => 'sometimes|nullable|integer|min:0'
                ], [], [
                    '*.photo' => 'SKU-Photo',
                    '*.price' => 'SKU-Price',
                    '*.stock' => 'SKU-Stock',
                    '*.sales' => 'SKU-Sales',
                    '*.stock_increment' => 'SKU-Stock-Increment',
                    '*.stock_decrement' => 'SKU-Stock-Decrement'
                ]);
            }]
        ];
    }

    /**
     * Get custom attributes for validator errors.
     * @return array
     */
    public function attributes()
    {
        return [
            'skus' => 'SKU 参数组合'
        ];
    }

    /**
     * Get custom messages for validator errors.
     * @return array
     */
    public function messages()
    {
        return [
            //
        ];
    }
}
