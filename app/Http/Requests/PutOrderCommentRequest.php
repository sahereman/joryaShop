<?php

namespace App\Http\Requests;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductComment;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PutOrderCommentRequest extends Request
{
    protected $order_item_ids = [];

    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        return [
            'parent_id' => [
                'bail',
                'required',
                function ($attribute, $value, $fail) {
                    if ($value != 0) {
                        if (!ProductComment::where([
                            'id' => $value,
                            'order_id' => $this->input('order_id'),
                        ])->exists()
                        ) {
                            $fail('您没有权限在此追加评论。');
                        }
                    }
                },
            ],
            'order_id' => [
                'bail',
                'required',
                'exists:orders,id',
            ],
            'order_item_id' => [
                'bail',
                'required',
                'exists:order_items,id',
                function ($attribute, $value, $fail) {
                    if (!OrderItem::where([
                        'id' => $value,
                        'order_id' => $this->input('order_id'),
                    ])->exists()
                    ) {
                        $fail('您没有权限在此追加评论。');
                    }
                },
            ],
            'content' => 'bail|required|string|min:3',
            'photos' => 'sometimes|nullable|string',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     * @return array
     */
    public function attributes()
    {
        return [
            'parent_id' => '父级评价ID',
            'order_id' => '订单ID',
            'order_item_id' => '子订单ID',
            'content' => '评价内容',
            'photos' => '评价图片集',
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
