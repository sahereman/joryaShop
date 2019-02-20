<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\App;

class UserHistoryRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        return [
            'history_ids' => [
                'required',
                'string',
                'regex:/^\d+(\,\d+)*$/',
            ],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     * @return array
     */
    public function attributes()
    {
        if (!App::isLocale('zh-CN')) {
            return [];
        }
        return [
            'history_ids' => '足迹IDs',
        ];
    }
}
