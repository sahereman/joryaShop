<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class SkuGeneratorRequest extends FormRequest
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
            'delta_price' => ['required', 'numeric'],
            // 'stock' => ['required', 'integer', 'min:0'],
            'attrs' => ['required', 'json', function ($key, $json, $fail) {
                $val = json_decode($json, true);
                if (empty($val)) {
                    $fail('SKU 参数组合 不能为空');
                }
                Validator::validate($val, [
                    '*.*.data' => ['required'],
                    '*.*.photo' => ['sometimes'],
                ], [], [
                    '*.*.data' => 'SKU 参数值',
                    '*.*.photo' => 'SKU-Photo',
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
            'delta_price' => 'SKU 差价',
            // 'stock' => 'SKU 库存',
            'attrs' => 'SKU 参数组合',
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
