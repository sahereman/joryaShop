<?php

namespace App\Admin\Controllers;

use App\Http\Requests\Request;
use App\Models\ProductCategory;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class ProductCategoriesController extends Controller
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
            ->description('分类 - 列表')
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
            ->description('分类 - 详情')
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
            ->description('分类 - 编辑')
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
            ->description('分类 - 新增')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     * @return Grid
     */
    protected function grid($request)
    {

        $parent_category = ProductCategory::find($request->input('pid'));

        //        dd($parent_category);

        $grid = new Grid(new ProductCategory);
        if ($parent_category)
        {
            $grid->model()->where('parent_id', $parent_category->id);
        } else
        {
            $grid->model()->where('parent_id', 0);
        }

        $grid->id('ID');
        $grid->name_en('名称(中文)');
        $grid->name_zh('名称(英文)');

        if ($parent_category)
        {
            $grid->parent_category('上级分类')->display(function ($parent_category) {
                return $parent_category['name_zh'];
            });
        } else
        {
            // 是否首页显示
            $states = [
                'on' => ['value' => true, 'text' => '已开启', 'color' => 'primary'],
                'off' => ['value' => false, 'text' => '已关闭', 'color' => 'default'],
            ];
            $grid->is_index('首页显示')->switch($states);

            // 选项
            $grid->column('option', '选项')->display(function () {
                return '<a href="' . route('admin.product_categories.index', ['pid' => $this->id]) . '" class="btn btn-xs btn-primary" style="margin-right: 10px">查看下级分类 <span class="badge">' . count($this->child_categories) . '</span></a>';
            });
        }

        return $grid;
    }

    /**
     * Make a show builder.
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(ProductCategory::findOrFail($id));

        $show->id('ID');
        $show->name_zh('名称(中文)');
        $show->name_en('名称(英文)');
        $show->description_zh('描述(中文)');
        $show->description_en('描述(英文)');
        $show->created_at('创建时间');
        $show->updated_at('更新时间');


        $show->parent_category('上级栏目', function ($parent_category) {
            $parent_category->id('ID');
            $parent_category->name_zh('名称(中文)');
            $parent_category->name_en('名称(英文)');
        });

        return $show;
    }

    /**
     * Make a form builder.
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new ProductCategory);

        $form->select('parent_id', '上级分类')->options([1 => 'foo', 2 => 'bar', 'val' => 'Option name']);


//        $form->number('parent_id', 'Parent id');
//        $form->text('name_en', 'Name en');
//        $form->text('name_zh', 'Name zh');
//        $form->text('description_en', 'Description en');
//        $form->text('description_zh', 'Description zh');

        // 是否首页显示
        $states = [
            'on' => ['value' => true, 'text' => '已开启', 'color' => 'primary'],
            'off' => ['value' => false, 'text' => '已关闭', 'color' => 'default'],
        ];
        $form->switch('is_index', '首页显示')->states($states);


        return $form;
    }
}
