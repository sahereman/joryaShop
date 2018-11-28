<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\App;
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
            'order_id' => 'bail|required|exists:orders,id',
            'remark_from_user' => 'bail|sometimes|required|string|min:3|max:255',
            'remark_from_seller' => 'bail|sometimes|nullable|string|min:3|max:255',
            'photos_for_refund' => 'bail|sometimes|nullable|string',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     * @return array
     */
    public function attributes()
    {
        if (App::isLocale('en')) {
            return [];
        }
        return [
            // 'amount' => '退款金额',
            'order_id' => '订单ID',
            'remark_from_user' => '退款理由',
            'remark_from_seller' => '卖家回复',
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
