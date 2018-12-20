<?php

namespace App\Http\Requests;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PostOrderCommentRequest extends Request
{
    protected $order_item_ids = [];

    /**
     * Get the validation rules that apply to the request.
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
                        $fail(trans('basic.comments.Plz_mark_every_composite_index'));
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
                        $fail(trans('basic.comments.Plz_mark_every_description_index'));
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
                        $fail(trans('basic.comments.Plz_mark_every_shipment_index'));
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
                        $fail(trans('basic.comments.Plz_make_every_comment'));
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
                        $fail(trans('basic.comments.Plz_upload_every_photo'));
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
            'order_id' => '订单ID',
            'composite_index' => '综合评分',
            'description_index' => '描述相符',
            'shipment_index' => '物流服务',
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
