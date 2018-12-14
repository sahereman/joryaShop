<?php

namespace App\Admin\Models;

use App\Models\Product as ProductModel;

class Product extends ProductModel
{
    protected $hidden = [
        //        'content_en',
        //        'content_zh',
    ];

}
