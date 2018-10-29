<?php

namespace App\Http\Requests;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PostOrderCommentRequest extends Request
{
    protected $order_item_ids = [];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'order_id' => [
                'bail',
                'required',
                'exists:orders,id',
                function ($attribute, $value, $fail) {
                    $this->order_item_ids = Order::find($value)->items->pluck('id')->all();
                    // $this->order_item_ids = OrderItem::where('order_id', $value)->select('id')->get()->pluck('id')->all();
                    sort($this->order_item_ids);
                }
            ],
            'composite_index' => [
                'bail',
                'required',
                'array',
                function ($attribute, $value, $fail) {
                    $order_item_ids = array_keys($value);
                    sort($order_item_ids);
                    if ($this->order_item_ids == []) {
                        $this->order_item_ids = Order::find($value)->items->pluck('id')->all();
                        // $this->order_item_ids = OrderItem::where('order_id', $value)->select('id')->get()->pluck('id')->all();
                        sort($this->order_item_ids);
                    }
                    if ($order_item_ids != $this->order_item_ids) {
                        $fail('请确保评价每个商品的综合评分。');
                    }
                },
            ],
            'description_index' => [
                'bail',
                'required',
                'array',
                function ($attribute, $value, $fail) {
                    $order_item_ids = array_keys($value);
                    sort($order_item_ids);
                    if ($this->order_item_ids == []) {
                        $this->order_item_ids = Order::find($value)->items->pluck('id')->all();
                        // $this->order_item_ids = OrderItem::where('order_id', $value)->select('id')->get()->pluck('id')->all();
                        sort($this->order_item_ids);
                    }
                    if ($order_item_ids != $this->order_item_ids) {
                        $fail('请确保评价每个商品的描述相符。');
                    }
                },
            ],
            'shipment_index' => [
                'bail',
                'required',
                'array',
                function ($attribute, $value, $fail) {
                    $order_item_ids = array_keys($value);
                    sort($order_item_ids);
                    if ($this->order_item_ids == []) {
                        $this->order_item_ids = Order::find($value)->items->pluck('id')->all();
                        // $this->order_item_ids = OrderItem::where('order_id', $value)->select('id')->get()->pluck('id')->all();
                        sort($this->order_item_ids);
                    }
                    if ($order_item_ids != $this->order_item_ids) {
                        $fail('请确保评价每个商品的物流服务。');
                    }
                },
            ],
            'content' => [
                'bail',
                'required',
                'array',
                function ($attribute, $value, $fail) {
                    $order_item_ids = array_keys($value);
                    sort($order_item_ids);
                    if ($this->order_item_ids == []) {
                        $this->order_item_ids = Order::find($value)->items->pluck('id')->all();
                        // $this->order_item_ids = OrderItem::where('order_id', $value)->select('id')->get()->pluck('id')->all();
                        sort($this->order_item_ids);
                    }
                    if ($order_item_ids != $this->order_item_ids) {
                        $fail('请确保填写每个商品的评价内容。');
                    }
                },
            ],
            'photos' => [
                'bail',
                'sometimes',
                'required',
                'array',
                function ($attribute, $value, $fail) {
                    $order_item_ids = array_keys($value);
                    sort($order_item_ids);
                    if ($this->order_item_ids == []) {
                        $this->order_item_ids = Order::find($value)->items->pluck('id')->all();
                        // $this->order_item_ids = OrderItem::where('order_id', $value)->select('id')->get()->pluck('id')->all();
                        sort($this->order_item_ids);
                    }
                    if ($order_item_ids != $this->order_item_ids) {
                        $fail('请确保上传每个商品的评价图片集。');
                    }
                },
            ],
            'composite_index.*' => 'bail|required|integer|min:0|max:5',
            'description_index.*' => 'bail|required|integer|min:0|max:5',
            'shipment_index.*' => 'bail|required|integer|min:0|max:5',
            'content.*' => 'bail|required|string|min:3',
            'photos.*' => 'sometimes|nullable|string',
        ];
    }

    public function attributes()
    {
        return [
            'order_id' => '订单ID',
            'composite_index' => '综合评分',
            'description_index' => '描述相符',
            'shipment_index' => '物流服务',
            'content' => '评价内容',
            'photos' => '评价图片集',
        ];
    }

    public function messages()
    {
        return [
            //
        ];
    }
}
