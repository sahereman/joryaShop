<?php

namespace App\Admin\Controllers;

use App\Http\Requests\Request;
use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\MessageBag;

class ProductsController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     * @param Content $content
     * @return Content
     */
    public function index(Content $content, Request $request)
    {
        return $content
            ->header('产品管理')
            ->description('产品 - 列表')
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
        return $content
            ->header('产品管理')
            ->description('产品 - 详情')
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
        return $content
            ->header('产品管理')
            ->description('产品 - 编辑')
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
            ->header('产品管理')
            ->description('产品 - 新增')
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
        $grid->model()->orderBy('id', 'desc'); // 设置初始排序条件

        if ($category)
        {
            $grid->model()->where('product_category_id', $category->id);
        }

        /*筛选*/
        $grid->filter(function ($filter) {
            $filter->disableIdFilter(); // 去掉默认的id过滤器
            $filter->like('name_zh', '名称(中文)');
            $filter->like('name_en', '名称(英文)');
        });

        $grid->id('ID');
        $grid->thumb('缩略图')->image('', 60);
        $grid->category()->name_zh('分类')->display(function ($data) {
            return "<a href='" . route('admin.products.index', ['cid' => $this->product_category_id]) . "'>$data</a>";
        });
        $grid->name_zh('名称(中文)');
        $grid->name_en('名称(英文)');
        $grid->stock('库存')->sortable();
        $grid->price('价格')->sortable();
        $grid->sales('销量')->sortable();

        $grid->column('', '选项')->switchGroup([
            'on_sale' => '售卖状态', 'is_index' => '首页推荐'
        ]);

        $grid->index('综合指数')->sortable();
        $grid->heat('人气')->sortable();

        return $grid;
    }

    /**
     * Make a show builder.
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Product::findOrFail($id));


        $show->id('ID');
        $show->name_zh('名称(中文)');
        $show->name_en('名称(英文)');
        $show->description_zh('描述(中文)');
        $show->description_en('名称(英文)');
        $show->thumb('缩略图')->image('', 80);
        $show->photos('相册')->as(function ($photos) {
            $text = '';
            foreach ($photos as $photo)
            {
                $url = starts_with($photo, ['http://', 'https://']) ? $photo : Storage::disk('public')->url($photo);
                $text .= '<img src="' . $url . '" style="margin:0 12px 12px 0;max-width:120px;max-height:200px" class="img">';
            }

            return $text;
        });
        $show->is_index('首页推荐')->as(function ($item) {
            return $item ? '<span class="label label-primary">ON</span>' : '<span class="label label-default">OFF</span>';
        });
        $show->on_sale('售卖状态')->as(function ($item) {
            return $item ? '<span class="label label-primary">ON</span>' : '<span class="label label-default">OFF</span>';
        });
        $show->price('价格');
        $show->shipping_fee('运费');
        $show->stock('库存');
        $show->sales('销量');
        $show->index('综合指数');
        $show->heat('人气');
        $show->created_at('创建时间');
        $show->updated_at('更新时间');
        $show->divider();
        $show->content_zh('详情介绍(中文)');
        $show->content_en('详情介绍(英文)');

        $show->category('商品分类', function ($category) {
            $category->name_zh('名称(中文)');
            $category->name_en('名称(英文)');
        });

        $show->skus('SKU 列表', function ($sku) {
            /*禁用*/
            $sku->disableActions();
            $sku->disableRowSelector();
            $sku->disableExport();
            $sku->disableFilter();
            $sku->disableCreateButton();
            $sku->disablePagination();

            /*属性*/
            $sku->name_zh('SKU 名称(中文)');
            $sku->name_en('SKU 名称(英文)');
            $sku->price('单价');
            $sku->stock('剩余库存');
            $sku->sales('销量');
        });

        return $show;
    }

    /**
     * Make a form builder.
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Product);

        $form->tab('基础', function ($form) {

            $form->select('product_category_id', '商品分类')->options(ProductCategory::selectOptions())->rules('required|exists:product_categories,id');
            $form->text('name_zh', '名称(中文)')->rules('required');
            $form->text('name_en', '名称(英文)')->rules('required');
            $form->text('description_zh', '描述(中文)')->rules('required');
            $form->text('description_en', '描述(英文)')->rules('required');
            $form->multipleImage('photos', '相册')->uniqueName()->removable()->move('original/' . date('Ym', now()->timestamp))->rules('image');
            $form->switch('on_sale', '售卖状态');
            $form->switch('is_index', '首页推荐');

        })->tab('价格与库存', function ($form) {

            $form->display('price', '价格')->setWidth(2);
            $form->display('stock', '库存')->setWidth(2);
            $form->display('sales', '销量')->setWidth(2);
            $form->currency('shipping_fee', '运费')->symbol('￥')->rules('required');

            $form->hasMany('skus', 'SKU 列表', function (Form\NestedForm $form) {
                $form->text('name_zh', 'SKU 名称(中文)')->rules('required');
                $form->text('name_en', 'SKU 名称(英文)')->rules('required');
                $form->currency('price', '单价')->symbol('￥')->rules('required|numeric|min:0.01');
                $form->number('stock', '剩余库存')->min(0)->rules('required|integer|min:0');
                $form->display('sales', '销量')->setWidth(2);
            });

        })->tab('商品详细', function ($form) {

            $form->number('index', '综合指数')->min(0)->rules('required|integer|min:0');
            $form->number('heat', '人气')->min(0)->rules('required|integer|min:0');

            $form->divider();
            $form->editor('content_zh', '详情介绍(中文)');
            $form->editor('content_en', '详情介绍(英文)');


            $form->hidden('_from_')->default('edit');
            $form->ignore(['_from_']);
        });


        // 定义事件回调，当模型即将保存时会触发这个回调
        $form->saving(function (Form $form) {

            if (\request()->input('_from_') == 'edit' && collect($form->input('skus'))->where(Form::REMOVE_FLAG_NAME, 0)->isEmpty())
            {
                $error = new MessageBag([
                    'title' => 'SKU 列表 必须填写',
                ]);
                return back()->withInput()->with(compact('error'));
            }

            $form->ignore(['_from_']);

            $form->model()->price = collect($form->input('skus'))->where(Form::REMOVE_FLAG_NAME, 0)->min('price'); // 生成商品价格 - 最低SKU价格
            $form->model()->stock = collect($form->input('skus'))->where(Form::REMOVE_FLAG_NAME, 0)->sum('stock'); // 生成商品库存 - 求和SKU库存

        });
        return $form;
    }
}
