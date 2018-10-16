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
        });

        $grid->id('Id');
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
        ], ['on' => ['text' => 'YES'], 'off' => ['text' => 'NO'],]);

        //        // 是否上架
        //        $grid->on_sale('售卖状态')->switch([
        //            'on' => ['value' => true, 'text' => '已上架', 'color' => 'primary'],
        //            'off' => ['value' => false, 'text' => '已下架', 'color' => 'default'],
        //        ])->sortable();
        //
        //        // 是否首页推荐
        //        $grid->is_index('首页推荐')->switch([
        //            'on' => ['value' => true, 'text' => '已推荐', 'color' => 'primary'],
        //            'off' => ['value' => false, 'text' => '未推荐', 'color' => 'default'],
        //        ])->sortable();

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

        $form->select('product_category_id', '商品分类')->options(ProductCategory::selectOptions())->rules('required|exists:product_categories,id');

        $form->text('name_en', 'Name en');
        $form->text('name_zh', 'Name zh');
        $form->text('description_en', 'Description en');
        $form->text('description_zh', 'Description zh');
        $form->text('content_en', 'Content en');
        $form->text('content_zh', 'Content zh');
        $form->text('thumb', 'Thumb');
        $form->text('photos', 'Photos');
        $form->decimal('shipping_fee', 'Shipping fee');
        $form->number('stock', 'Stock');
        $form->number('sales', 'Sales');
        $form->number('index', 'Index');
        $form->number('heat', 'Heat');
        $form->decimal('price', 'Price');
        $form->switch('is_index', 'Is index');
        $form->switch('on_sale', 'On sale')->default(1);

        return $form;
    }
}
