<?php

namespace App\Observers;


use App\Models\ShipmentCompany;
use Illuminate\Support\Facades\Cache;

class ShipmentCompanyObserver
{
    /*Eloquent 的模型触发了几个事件，可以在模型的生命周期的以下几点进行监控：
    retrieved、creating、created、updating、updated、saving、saved、deleting、deleted、restoring、restored
    事件能在每次在数据库中保存或更新特定模型类时轻松地执行代码。*/

    /*当模型已存在，不是新建的时候，依次触发的顺序是:
    saving -> updating -> updated -> saved(不会触发保存操作)
    当模型不存在，需要新增的时候，依次触发的顺序则是:
    saving -> creating -> created -> saved(不会触发保存操作)*/

    public function created(ShipmentCompany $company)
    {
        Cache::forget($company::$cache_key);
    }

    public function saved(ShipmentCompany $company)
    {
        Cache::forget($company::$cache_key);
    }

    public function deleted(ShipmentCompany $company)
    {
        Cache::forget($company::$cache_key);
    }
}
