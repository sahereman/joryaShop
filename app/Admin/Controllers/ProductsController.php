<?php

namespace App\Admin\Controllers;

use App\Admin\Extensions\Ajax\Ajax_Delete;
use App\Admin\Extensions\Ajax\Ajax_Icon;
use App\Admin\Models\Product;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SkuGeneratorRequest;
use App\Http\Requests\Request;

/*商品属性 2019-03-01*/
// use App\Models\Attr;
/*商品属性 2019-03-01*/

use App\Models\ProductCategory;
use App\Models\ProductLocation;
use App\Models\ProductService;
use App\Models\ProductSku;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Form\Builder;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;

class ProductsController extends Controller
{
    use HasResourceActions;

    protected $mode = 'create';
    protected $product_id;

    protected $flag = true;
    protected $tempo = [];
    protected $attr_combo = [];

    /**
     * Index interface.
     * @param Content $content
     * @return Content
     */
    public function index(Content $content, Request $request)
    {
        return $content
            ->header('商品管理')
            ->description('商品 - 列表')
            ->body($this->grid($request));
    }

    /**
     * Show interface.
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        $this->mode = 'show';
        $this->product_id = $id;
        return $content
            ->header('商品管理')
            ->description('商品 - 详情')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        $this->mode = 'edit';
        $this->product_id = $id;
        return $content
            ->header('商品管理')
            ->description('商品 - 编辑')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('商品管理')
            ->description('商品 - 新增')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     * @return Grid
     */
    protected function grid($request)
    {
        $category = ProductCategory::find($request->input('cid'));

        $grid = new Grid(new Product);
        $grid->model()->with(['comments', 'skus', 'category', 'category.parent'])->orderBy('created_at', 'desc'); // 设置初始排序条件

        if ($category)
        {
            $grid->model()->where('product_category_id', $category->id);
        }

        /*筛选*/
        $grid->filter(function ($filter) {
            $filter->disableIdFilter(); // 去掉默认的id过滤器
            // $filter->like('name_zh', '名称(中文)');
            $filter->like('name_en', '标题');
        });

        $grid->id('ID');
        // $grid->thumb('缩略图')->image('', 60);
        $grid->thumb('缩略图')->image('', 60)->display(function ($data) {
            return '<a target="_blank" href="' . route('products.show', ['product' => $this->id]) . '">' . $data . '</a>';
        });
        /*$grid->category()->name_zh('分类')->display(function ($data) {
            return "<a href='" . route('admin.products.index', ['cid' => $this->product_category_id]) . "'>$data</a>";
        });*/
        $grid->category()->name_en('分类')->display(function ($data) {

            if(empty($this->category['parent']))
            {

                $str = "</s><a href='" . route('admin.products.index', ['cid' => $this->product_category_id]) . "'>$data</a>";
            }
            else
            {
                $str = "<a href='" . route('admin.products.index', ['cid' => $this->category['parent']['id']]) . "'>".$this->category['parent']['name_en']."</a>" .
                    "<br /><span> - </span><br /></s><a href='" . route('admin.products.index', ['cid' => $this->product_category_id]) . "'>$data</a>";
            }

            return $str;
        });
        /*$grid->name_zh('名称(中文)')->display(function ($data) {
            return "<span style='width: 120px;display: inline-block;overflow: hidden'>$data</span>";
        });*/
        $grid->name_en('标题')->display(function ($data) {
            return "<a target='_blank' href='" . route('products.show', ['product' => $this->id]) . "'><span style='width: 120px;display: inline-block;overflow: hidden'>$data</span></a>";
        });
        $grid->price('价格')->sortable();
        $grid->stock('库存')->sortable();
        $grid->sales('销量')->sortable();
        $grid->index('综合指数')->sortable();
        $grid->heat('人气')->sortable();
        $grid->comments('评论数')->count();
        $grid->skus('SKU数')->count();

        $grid->column('', '选项')->switchGroup([
            'on_sale' => '售卖状态', 'is_index' => '首页推荐'
        ]);


        $grid->actions(function ($actions) {
            $actions->append(new Ajax_Icon(route('admin.products.duplicate', [$actions->getKey()]), array(), 'fa-copy'));
        });


        return $grid;
    }

    /**
     * Make a show builder.
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $this->product_id = $id;
        $show = new Show(Product::findOrFail($id));

        $options = [
            'is_base_size_optional' => true,
            'is_hair_colour_optional' => true,
            'is_hair_density_optional' => true,
        ];

        $show->id('ID');
        // $show->name_zh('名称(中文)');
        $show->name_en('标题');
        // $show->description_zh('描述(中文)');
        $show->description_en('副标题');
        $show->thumb('缩略图')->image();
        $show->photos('相册')->as(function ($photos) {
            $text = '';
            foreach ($photos as $photo)
            {
                $url = starts_with($photo, ['http://', 'https://']) ? $photo : Storage::disk('public')->url($photo);
                $text .= '<img src="' . $url . '" style="margin:0 12px 12px 0;max-width:120px;max-height:200px" class="img">';
            }

            return $text;
        });

        // 2019-01-22
        $show->is_base_size_optional('SKU base_size 是否可选')->as(function ($item) use (&$options) {
            $options['is_base_size_optional'] = $item;
            return $item ? '<span class="label label-primary">ON</span>' : '<span class="label label-default">OFF</span>';
        });
        $show->is_hair_colour_optional('SKU hair_colour 是否可选')->as(function ($item) use (&$options) {
            $options['is_hair_colour_optional'] = $item;
            return $item ? '<span class="label label-primary">ON</span>' : '<span class="label label-default">OFF</span>';
        });
        $show->is_hair_density_optional('SKU hair_density 是否可选')->as(function ($item) use (&$options) {
            $options['is_hair_density_optional'] = $item;
            return $item ? '<span class="label label-primary">ON</span>' : '<span class="label label-default">OFF</span>';
        });
        // 2019-01-22

        $show->is_index('首页推荐')->as(function ($item) {
            return $item ? '<span class="label label-primary">ON</span>' : '<span class="label label-default">OFF</span>';
        });
        $show->on_sale('售卖状态')->as(function ($item) {
            return $item ? '<span class="label label-primary">ON</span>' : '<span class="label label-default">OFF</span>';
        });
        $show->price('展示价格');
        $show->shipping_fee('运费');
        $show->stock('总库存');
        $show->sales('总销量');
        $show->index('综合指数');
        $show->heat('人气');
        $show->location('仓库地址');
        $show->service('服务内容');
        $show->created_at('创建时间');
        $show->updated_at('更新时间');
        $show->divider();
        // $show->content_zh('详情介绍(中文)');
        $show->content_en('详情介绍(英文)');

        /* 2019-04-09 for SEO */
        $show->divider();
        $show->seo_title('SEO - 标题');
        $show->seo_keywords('SEO - 关键字');
        $show->seo_description('SEO - 描述');
        $show->divider();
        /* 2019-04-09 for SEO */

        /*商品属性 2019-03-01*/
        /*$show->divider();
                $show->attrs('商品属性列表')->as(function ($attrs) {
                    return $attrs->pluck('name_en');
                })->label();*/
        /*商品属性 2019-03-01*/

        $show->category('商品分类', function ($category) {
            /*禁用*/
            $category->panel()->tools(function ($tools) {
                $tools->disableList();
                $tools->disableEdit();
                $tools->disableDelete();
            });

            /*属性*/
            // $category->name_zh('名称(中文)');
            $category->name_en('名称(英文)');
        });

        $show->skus('SKU列表', function ($sku) use (&$options) {
            /*禁用*/
            $sku->disableActions();
            $sku->disableRowSelector();
            $sku->disableExport();
            $sku->disableFilter();
            $sku->disableCreateButton();
            $sku->disablePagination();

            /*属性*/

            // 2019-01-22
            if ($options['is_base_size_optional'])
            {
                // $sku->base_size_zh('Base Size 名称(中文)');
                $sku->base_size_en('Base Size 名称(英文)');
            }
            if ($options['is_hair_colour_optional'])
            {
                // $sku->hair_colour_zh('Hair Colour 名称(中文)');
                $sku->hair_colour_en('Hair Colour 名称(英文)');
            }
            if ($options['is_hair_density_optional'])
            {
                // $sku->hair_density_zh('Hair Density 名称(中文)');
                $sku->hair_density_en('Hair Density 名称(英文)');
            }
            // 2019-01-22

            // $sku->name_zh('SKU 名称(中文)');
            // $sku->name_en('SKU 名称(英文)');
            $sku->price('单价');
            $sku->stock('库存');
            $sku->sales('销量');


        });

        $show->comments('评价列表', function ($comment) {
            /*禁用*/
            $comment->disableRowSelector();
            $comment->disableExport();
            $comment->disableFilter();
            $comment->disableCreateButton();

            $comment->actions(function ($actions) {
                $actions->disableView();
                $actions->disableEdit();
                $actions->disableDelete();
                if ($actions->row->deleted_at == null)// 可以删除的评论
                {
                    $actions->append(new Ajax_Delete(route('admin.product_comments.delete', [$actions->getKey()])));
                }
            });

            /*属性*/
            $comment->user()->name('买家');
            $comment->photo_urls('图片')->display(function ($urls) {
                $text = '';
                foreach ($urls as $url)
                {
                    $text .= '<img src="' . $url . '" style="margin:0 8px 8px 0;max-width:80px;max-height:80px" class="img">';
                }
                return $text;
            });
            $comment->content('内容')->display(function ($data) {
                return "<span style='width: 220px;display: inline-block;overflow: hidden'>$data</span>";
            });
            $comment->composite_index('综合评分');
            $comment->description_index('描述相符');
            $comment->shipment_index('物流服务');

            $comment->created_at('评价时间');
        });

        return $show;
    }

    /**
     * Make a form builder.
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Product());
        $form->html('<button class="btn btn-primary"><i class="fa fa-send"></i>&nbsp;提交</button>');

        if ($this->mode == Builder::MODE_CREATE)
        {
            $form->hidden('_from_')->default(Builder::MODE_CREATE);
        }

        if ($this->mode == Builder::MODE_EDIT && $this->product_id)
        {
            $form->hidden('_from_')->default(Builder::MODE_EDIT);
            $product_id = $this->product_id;
            $form->tools(function (Form\Tools $tools) use ($product_id) {
                $tools->append('<div class="btn-group pull-right" style="margin-right: 5px">'
                    . '<a href="' . route('admin.products.sku_generator_show', ['product' => $product_id]) . '" class="btn btn-sm btn-success">'
                    . '<i class="fa fa-archive"></i>&nbsp;SKU生成器'
                    . '</a>'
                    . '</div>');
            });

            $form->hidden('product_sort_photos_url', 'Product-Sort-Photos-Url')->default(route('admin.products.sort_photos', ['product' => $this->product_id]));
            $product = Product::find($this->product_id);
            // $form->hidden('product_photos', 'Product Photos')->default(json_encode($product->photos));
            $form->hidden('product_photos', 'Product Photos')->default(collect($product->photos)->toJson());
        }
        $form->select('product_category_id', '商品分类')->options(ProductCategory::selectOptions())->rules('required|exists:product_categories,id');
        $form->select('location', '仓库地址')->options(ProductLocation::availableOptions())->rules('required|exists:product_locations,description');
        $form->select('service', '服务内容')->options(ProductService::availableOptions())->rules('required|exists:product_services,description');
        // $form->text('name_zh', '名称(中文)')->rules('required');
        $form->hidden('name_zh', '名称(中文)')->default('lyrical');
        $form->text('name_en', '标题')->rules('required');

        /* 2019-04-09 for SEO */
        $form->text('seo_title', 'SEO - 标题');
        $form->text('seo_keywords', 'SEO - 关键字');
        $form->text('seo_description', 'SEO - 描述');
        /* 2019-04-09 for SEO */

        // $form->text('description_zh', '描述(中文)')->rules('required');
        $form->hidden('description_zh', '描述(中文)')->default('lyrical');
        $form->text('description_en', '副标题')->rules('required');
        $form->multipleImage('photos', '相册')
            ->deletable(true)
            ->uniqueName()->removable()
            // ->rules('required')
            ->move('original/' . date('Ym', now()->timestamp))
            ->help('相册尺寸:420 * 380')->rules('image');

        // 2019-01-22
        // $form->switch('is_base_size_optional', 'SKU base_size 是否可选')->value(1);
        $form->switch('is_base_size_optional', 'SKU base_size 是否可选')->default(1);
        // $form->switch('is_hair_colour_optional', 'SKU hair_colour 是否可选')->value(1);
        $form->switch('is_hair_colour_optional', 'SKU hair_colour 是否可选')->default(1);
        // $form->switch('is_hair_density_optional', 'SKU hair_density 是否可选')->value(1);
        $form->switch('is_hair_density_optional', 'SKU hair_density 是否可选')->default(1);
        // 2019-01-22

        // $form->switch('on_sale', '售卖状态')->value(1);
        $form->switch('on_sale', '售卖状态')->default(1);
        $form->switch('is_index', '首页推荐');
        // })->tab('价格与库存', function (Form $form) {
        $form->display('price', '展示价格')->setWidth(2);
        $form->display('stock', '总库存')->setWidth(2);
        $form->display('sales', '总销量')->setWidth(2);
        // $form->currency('shipping_fee', '运费')->symbol('￥')->rules('required');
        $form->currency('shipping_fee', '运费')->symbol('$')->default(0);

        $form->number('index', '综合指数')->min(0)->rules('required|integer|min:0');
        $form->number('heat', '人气')->min(0)->rules('required|integer|min:0');

        $form->hasMany('skus', 'SKU 列表', function (Form\NestedForm $form) {

            // $form->text('name_zh', 'SKU 名称(中文)')->rules('required');
            $form->hidden('name_zh', 'SKU 名称(中文)')->default('lyrical');
            // $form->text('name_en', 'SKU 名称(英文)')->rules('required')->default('');
            $form->hidden('name_en', 'SKU 名称(英文)')->default('lyrical');
            // $form->currency('price', '单价')->symbol('￥')->rules('required|numeric|min:0.01');

            // 2019-01-22
            // $form->text('base_size_zh', 'Base Size 名称(中文)')->default('');
            $form->hidden('base_size_zh', 'Base Size 名称(中文)')->default('lyrical');
            $form->text('base_size_en', 'Base Size 名称(英文)')->default('');
            // $form->text('hair_colour_zh', 'Hair Colour 名称(中文)')->default('');
            $form->hidden('hair_colour_zh', 'Hair Colour 名称(中文)')->default('lyrical');
            $form->text('hair_colour_en', 'Hair Colour 名称(英文)')->default('');
            // $form->text('hair_density_zh', 'Hair Density 名称(中文)')->default('');
            $form->hidden('hair_density_zh', 'Hair Density 名称(中文)')->default('lyrical');
            $form->text('hair_density_en', 'Hair Density 名称(英文)')->default('');
            // 2019-01-22

            $form->image('photo', 'Photo')
                ->deletable(true)
                ->uniqueName()
                //                ->removable()
                ->move('original/' . date('Ym', now()->timestamp));

            $form->html('<a class="btn btn-primary sku_photo_delete">Photo Delete</a>', 'Photo Delete');


            $form->currency('price', '单价')->symbol('$')->rules('required|numeric|min:0.01');
            $form->number('stock', '库存')->min(0)->rules('required|integer|min:0');
            // $form->display('sales', '销量')->setWidth(2);
        });

        // })->tab('商品详细', function (Form $form) {

        $form->divider();
        // $form->editor('content_zh', '详情介绍(中文)');
        $form->hidden('content_zh', '详情介绍(中文)')->default('lyrical');
        $form->editor('content_en', '详情介绍(英文)');

        /*商品属性 2019-03-01*/
        /*$form->divider();
        $attr_options = [];
        Attr::where('parent_id', 0)->orderBy('sort')->each(function ($parent) use (&$attr_options) {
            $attr_options = array_add($attr_options, $parent->id, '---&nbsp;&nbsp;&nbsp;&nbsp;' . $parent->name_en . '&nbsp;&nbsp;&nbsp;&nbsp;---');
            $parent->children->each(function ($child) use (&$attr_options) {
                $attr_options = array_add($attr_options, $child->id, $child->name_en);
            });
        });
        $form->multipleSelect('attrs', '商品属性列表')->options($attr_options);*/
        /*商品属性 2019-03-01*/

        // });

        $form->html('<script type="text/javascript" src="/vendor/laravel-admin/product.js"></script>');

        $form->ignore(['_from_']);

        // 定义事件回调，当模型即将保存时会触发这个回调
        $form->saving(function (Form $form) {
            if ((request()->has('is_index') || request()->has('on_sale')) && count(request()->all()) == 3)
            {
                return $form;
            }


            if (request()->input('photos') != '_file_del_')
            {
                $skus = collect($form->input('skus'))->where(Form::REMOVE_FLAG_NAME, 0);
                $base_size_option = collect($form->input('is_base_size_optional'))->values();
                $is_base_size_optional = ($base_size_option[0] == 'on');
                $hair_colour_option = collect($form->input('is_hair_colour_optional'))->values();
                $is_hair_colour_optional = ($hair_colour_option[0] == 'on');
                $hair_density_option = collect($form->input('is_hair_density_optional'))->values();
                $is_hair_density_optional = ($hair_density_option[0] == 'on');

                if (request()->input('_from_') == Builder::MODE_EDIT && $skus->isEmpty())
                {
                    $error = new MessageBag([
                        'title' => 'SKU 列表 必须填写',
                    ]);
                    // return back()->withInput()->with(compact('error'));
                    return back()->with(compact('error')); // The method withInput() is buggy with unwanted results.
                }

                if ($skus->isNotEmpty())
                {
                    $count = $skus->count();

                    // name_zh
                    /*$sku_name_zhs = $skus->unique('name_zh');
                    if ($sku_name_zhs->count() < $count) {
                        $error = new MessageBag([
                            'title' => 'SKU 列表：存在SKU-中文名称重复问题，请确保同款商品下的各个SKU-中文名称唯一',
                        ]);
                        // return back()->withInput()->with(compact('error'));
                        return back()->with(compact('error')); // The method withInput() is buggy with unwanted results.
                    }*/

                    // name_en
                    /*$sku_name_ens = $skus->unique('name_en');
                    if ($sku_name_ens->count() < $count) {
                        $error = new MessageBag([
                            'title' => 'SKU 列表：存在SKU-英文名称重复问题，请确保同款商品下的各个SKU-英文名称唯一',
                        ]);
                        // return back()->withInput()->with(compact('error'));
                        return back()->with(compact('error')); // The method withInput() is buggy with unwanted results.
                    }*/

                    // base_size  hair_colour hair_density
                    if ($is_base_size_optional || $is_hair_colour_optional || $is_hair_density_optional)
                    {
                        $sku_parameter = '';
                        $is_first_time = true;
                        if ($is_base_size_optional)
                        {
                            $sku_parameter = $is_first_time ? 'Base Size' : ' & Base Size';
                            $is_first_time = false;
                        }
                        if ($is_hair_colour_optional)
                        {
                            $sku_parameter .= $is_first_time ? 'Hair Colour' : ' & Hair Colour';
                            $is_first_time = false;
                        }
                        if ($is_hair_density_optional)
                        {
                            $sku_parameter .= $is_first_time ? 'Hair Density' : ' & Hair Density';
                            $is_first_time = false;
                        }

                        $sku_parameter_ens = $skus->map(function ($item, $key) use ($is_base_size_optional, $is_hair_colour_optional, $is_hair_density_optional) {
                            $parameter_en = '* ' . ($is_base_size_optional ? $item['base_size_en'] : '');
                            $parameter_en .= ' * ' . ($is_hair_colour_optional ? $item['hair_colour_en'] : '');
                            $parameter_en .= ' * ' . ($is_hair_density_optional ? $item['hair_density_en'] : '') . ' *';
                            return $parameter_en;
                        });
                        if ($sku_parameter_ens->unique()->count() < $count)
                        {
                            $error = new MessageBag([
                                'title' => 'SKU 列表：存在SKU-英文参数组合(' . $sku_parameter . ')重复问题，请确保同款商品下的各个SKU-英文参数组合(' . $sku_parameter . ')唯一',
                            ]);
                            // return back()->withInput()->with(compact('error'));
                            return back()->with(compact('error')); // The method withInput() is buggy with unwanted results.
                        }

                        /*$sku_parameter_zhs = $skus->map(function ($item, $key) use ($is_base_size_optional, $is_hair_colour_optional, $is_hair_density_optional) {
                            $parameter_zh = '* ' . ($is_base_size_optional ? $item['base_size_zh'] : '');
                            $parameter_zh .= ' * ' . ($is_hair_colour_optional ? $item['hair_colour_zh'] : '');
                            $parameter_zh .= ' * ' . ($is_hair_density_optional ? $item['hair_density_zh'] : '') . ' *';
                            return $parameter_zh;
                        });
                        if ($sku_parameter_zhs->unique()->count() < $count) {
                            $error = new MessageBag([
                                'title' => 'SKU 列表：存在SKU-中文参数组合(' . $sku_parameter . ')重复问题，请确保同款商品下的各个SKU-中文参数组合(' . $sku_parameter . ')唯一',
                            ]);
                            // return back()->withInput()->with(compact('error'));
                            return back()->with(compact('error')); // The method withInput() is buggy with unwanted results.
                        }*/
                    }
                }

                /*商品属性 2019-03-01*/
                /*$attrs = collect($form->input('attrs'))->reject(function ($attr) {
                    return is_null($attr);
                });
                if ($attrs->isNotEmpty()) {
                    $parent_attr_ids = [];
                    $attrs->each(function ($attr, $key) use (&$parent_attr_ids, &$form) {
                        $attr_model = Attr::find($attr);
                        if ($attr_model->parent_id == 0) {
                            $attrs = $form->input('attrs');
                            array_forget($attrs, $key);
                            $form->input('attrs', $attrs);
                            // $error = new MessageBag([
                            // 'title' => '不可选择父级商品属性',
                            // ]);
                            // return back()->with(compact('error')); // The method withInput() is buggy with unwanted results.
                        }
                        if (in_array($attr_model->parent_id, $parent_attr_ids)) {
                            $error = new MessageBag([
                                'title' => '同一父级商品属性下，只可选择一个子级商品属性',
                            ]);
                            return back()->with(compact('error')); // The method withInput() is buggy with unwanted results.
                        }
                        $parent_attr_ids[] = $attr_model->parent_id;
                    });
                }*/
                /*商品属性 2019-03-01*/
                $form->model()->price = collect($form->input('skus'))->where(Form::REMOVE_FLAG_NAME, 0)->min('price'); // 生成商品价格 - 最低SKU价格
                $form->model()->stock = collect($form->input('skus'))->where(Form::REMOVE_FLAG_NAME, 0)->sum('stock'); // 生成商品库存 - 求和SKU库存
            }
        });

        $form->saved(function (Form $form) {

            if (request()->input('_from_') == Builder::MODE_EDIT)
            {
                return redirect()->route('admin.products.index');
            }
            if (request()->input('_from_') == Builder::MODE_CREATE)
            {
                $product_id = $form->model()->id;
                return redirect()->route('admin.products.sku_generator_show', ['product' => $product_id]);
            }

        });

        return $form;
    }

    public function duplicate(Request $request, Product $product)
    {

        //        dd($product->toArray());
        $product_id = Product::create($product->toArray())->id;

        $product->skus()->each(function ($item) use ($product_id) {
            $item->product_id = $product_id;
            ProductSku::create($item->toArray());
        });
        return response()->json([
            'messages' => '产品复制成功'
        ], 200);
    }

    public function sortPhotos(Request $request, Product $product)
    {
        if ($request->ajax())
        {
            $photos = $request->input('photos');
            $product->photos = $photos;
            $result = $product->save();
            if ($result)
            {
                return response()->json([
                    'code' => 200,
                    'message' => 'success',
                ], 200);
            } else
            {
                return response()->json([
                    'code' => 422,
                    'message' => 'Unprocessable Entity',
                ], 422);
            }
        } else
        {
            return redirect()->back(302);
        }
    }

    /**
     * @param $attrs array
     * Demo:
     * $attrs = [
     *   'basic_size' => [['data' => 1], ['data' => 2], ['data' => 3]],
     *   'hair_colour' => [['data' => 'red', 'photo' => 'url-string'], ['data' => 'black', 'photo' => 'url-string'], ['data' => 'blue', 'photo' => 'url-string']],
     *   'hair_density' => [['data' => '10%'], ['data' => '20%'], ['data' => '30%']],
     * ];
     * @return array
     */
    protected function getAttrCombo($attrs)
    {
        foreach ($attrs as $attr => $options)
        {
            if ($this->flag)
            {
                $this->flag = false;
                foreach ($options as $option)
                {
                    $this->tempo[] = [$attr => $option];
                }
            } else
            {
                $this->attr_combo = [];
                foreach ($options as $option)
                {
                    foreach ($this->tempo as $item)
                    {
                        $item[$attr] = $option;
                        $this->attr_combo[] = $item;
                    }
                }
                $this->tempo = $this->attr_combo;
            }
        }
        return $this->attr_combo;
    }

    public function skuGeneratorShow(Request $request, Product $product, Content $content)
    {
        $errors = $request->session()->get('errors');
        $messages = [];
        if ($errors instanceof ViewErrorBag)
        {
            $messages = $errors->getMessages();
        }
        return $content
            ->header('商品管理')
            ->description('商品 - SKU生成器')
            ->body(view('admin.product.sku_generator', [
                'product' => $product,
                'messages' => $messages,
            ]));
    }

    /**
     * Demo of attrs-json-string:
     * {
     *   'base_size': [{'data':1},{'data':2},{'data':3}],
     *   'hair_colour': [{'data':'red', 'photo':'url-string'},{'data':'black', 'photo':'url-string'},{'data':'blue', 'photo':'url-string'}],
     *   'hair_density': [{'data':'10%'},{'data':'20%'},{'data':'30%'}],
     * }
     */
    public function skuGeneratorStore(SkuGeneratorRequest $request, Product $product)
    {
        $attrs = json_decode($request->input('attrs'), true);
        $attrs = collect($attrs)->map(function ($item, $key) {
            return collect($item)->unique('data')->toArray();
        })->toArray();
        $attr_combo = $this->getAttrCombo($attrs);
        $sku_count = count($attr_combo);
        $product->skus()->delete();
        foreach ($attr_combo as $option)
        {
            $sku_data = [];
            $sku_data['product_id'] = $product->id;
            $sku_data['price'] = $request->input('price', $product->price);
            $sku_data['stock'] = $request->input('stock', $product->stock);
            if ($product->is_base_size_optional)
            {
                $sku_data['base_size_en'] = $option['base_size']['data'];
            }
            if ($product->is_hair_colour_optional)
            {
                $sku_data['hair_colour_en'] = $option['hair_colour']['data'];
                $sku_data['photo'] = $option['hair_colour']['photo'];
            }
            if ($product->is_hair_density_optional)
            {
                $sku_data['hair_density_en'] = $option['hair_density']['data'];
            }
            ProductSku::create($sku_data);
        }
        $product->update([
            'price' => $request->input('price', $product->price),
            'stock' => $request->input('stock') ? $request->input('stock') * $sku_count : 0,
        ]);
        return redirect()->route('admin.products.edit', ['product' => $product->id]);
    }
}
