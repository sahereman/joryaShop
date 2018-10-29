<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class RefundOrderRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        return [
            // 'amount' => 'bail|required|numeric',
            'remark_by_user' => 'bail|required|string|min:3|max:255',
            'photos_for_refund' => 'bail|sometimes|nullable|string',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     * @return array
     */
    public function attributes()
    {
        return [
            // 'amount' => '退款金额',
            'remark_by_user' => '退款理由',
            'photos_for_refund' => '退款申请图片凭证',
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
