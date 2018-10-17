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

        $show->id('Id');
        $show->product_category_id('Product category id');
        $show->name_en('Name en');
        $show->name_zh('Name zh');
        $show->description_en('Description en');
        $show->description_zh('Description zh');
        $show->content_en('Content en');
        $show->content_zh('Content zh');
        $show->thumb('Thumb');
        $show->photos('Photos');
        $show->shipping_fee('Shipping fee');
        $show->stock('Stock');
        $show->sales('Sales');
        $show->index('Index');
        $show->heat('Heat');
        $show->price('Price');
        $show->is_index('Is index');
        $show->on_sale('On sale');
        $show->created_at('Created at');
        $show->updated_at('Updated at');

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
            $form->multipleImage('photos', '相册')->removable()->uniqueName()->move('original/' . date('Ym', now()->timestamp))->rules('image');
            $form->switch('on_sale', '售卖状态');
            $form->switch('is_index', '首页推荐');

        })->tab('价格与库存', function ($form) {

            $form->hasMany('skus', 'SKU 列表', function (Form\NestedForm $form) {
                $form->text('name_zh', 'SKU 名称(中文)')->rules('required');
                $form->text('name_en', 'SKU 名称(英文)')->rules('required');
                $form->currency('price', '单价')->symbol('￥')->rules('required|numeric|min:0.01');
                $form->number('stock', '剩余库存')->min(0)->rules('required|integer|min:0');
                $form->display('sales', '销量')->setWidth(2);
            });

            $form->display('price', '价格')->setWidth(2);
            $form->display('stock', '库存')->setWidth(2);
            $form->display('sales', '销量')->setWidth(2);
            $form->currency('shipping_fee', '运费')->symbol('￥')->rules('required');

        })->tab('商品详细', function ($form) {

            $form->number('index', '综合指数')->min(0)->rules('required|integer|min:0');
            $form->number('heat', '人气')->min(0)->rules('required|integer|min:0');

            $form->divider();
            $form->editor('content_zh', '详情介绍(中文)');
            $form->editor('content_en', '详情介绍(英文)');

        });

        // 定义事件回调，当模型即将保存时会触发这个回调
        $form->saving(function (Form $form) {
            if (collect($form->input('skus'))->where(Form::REMOVE_FLAG_NAME, 0)->isEmpty())
            {
                $error = new MessageBag([
                    'title' => 'SKU 列表 必须填写',
                ]);
                return back()->withInput()->with(compact('error'));
            }

            $form->model()->price = collect($form->input('skus'))->where(Form::REMOVE_FLAG_NAME, 0)->min('price');
            $form->model()->stock = collect($form->input('skus'))->where(Form::REMOVE_FLAG_NAME, 0)->sum('stock');

        });

        return $form;
    }
}
