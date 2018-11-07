<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class RefundOrderWithShipmentRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        return [
            // 'amount' => 'bail|sometimes|required|numeric',
            'order_id' =>'bail|required|exists:orders,id',
            'remark_by_user' => 'bail|sometimes|required|string|min:3|max:255',
            'remark_by_seller' => 'bail|sometimes|nullable|string|min:3|max:255',
            'remark_by_shipment' => 'bail|sometimes|nullable|string|min:3|max:255',
            'shipment_sn' => 'bail|sometimes|required_with:shipment_company|string|min:3',
            'shipment_company' => 'bail|sometimes|required_with:shipment_sn|string|min:3',
            'photos_for_refund' => 'bail|sometimes|nullable|string',
            'photos_for_shipment' => 'bail|sometimes|nullable|string',
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
            'order_id' => '订单ID',
            'remark_by_user' => '退款理由',
            'remark_by_seller' => '卖家回复',
            'remark_by_shipment' => '退货物流备注信息',
            'shipment_sn' => '退货物流流水单号',
            'shipment_company' => '退货物流公司名称',
            'photos_for_refund' => '退款申请图片凭证',
            'photos_for_shipment' => '退货物流图片凭证',
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
