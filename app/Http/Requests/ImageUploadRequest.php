<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\App;

class ImageUploadRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        return [
            'image' => 'required|image',
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
            'image' => '上传图片',
        ];
    }
}
