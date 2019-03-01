<?php

/*商品属性 2019-03-01*/
//namespace App\Models;
//
//use Encore\Admin\Traits\AdminBuilder;
//use Encore\Admin\Traits\ModelTree;
//use Illuminate\Database\Eloquent\Model;
//
//class Attr extends Model
//{
//    use ModelTree, AdminBuilder;
//
//    public function __construct(array $attributes = [])
//    {
//        parent::__construct($attributes);
//
//        /*初始化Tree属性*/
//        // $this->setTitleColumn('name_zh');
//        $this->setTitleColumn('name_en');
//        $this->setOrderColumn('sort');
//    }
//
//    /**
//     * The attributes that are mass assignable.
//     * @var array
//     */
//    protected $fillable = [
//        'parent_id',
//        'name_zh',
//        'name_en',
//        'sort',
//    ];
//
//    /**
//     * The attributes that should be hidden for serialization.
//     * @var array
//     */
//    protected $hidden = [
//        //
//    ];
//
//    /**
//     * The attributes that should be cast to native types.
//     * @var array
//     */
//    protected $casts = [
//        //
//    ];
//
//    /**
//     * The attributes that should be mutated to dates.
//     * @var array
//     */
//    protected $dates = [
//        //
//    ];
//
//    /**
//     * The accessors to append to the model's array form.
//     * @var array
//     */
//    protected $appends = [
//        //
//    ];
//
//    // Available values of an attr.
//    public function children()
//    {
//        return $this->hasMany(self::class, 'parent_id', 'id');
//    }
//
//    // The name of an attr.
//    public function parent()
//    {
//        return $this->belongsTo(self::class, 'parent_id');
//    }
//
//    // Many-to-many Relationship.
//    public function products()
//    {
//        // return $this->belongsToMany(Product::class);
//        return $this->belongsToMany(Product::class, 'attr_products', 'attr_id', 'product_id');
//    }
//}
/*商品属性 2019-03-01*/
