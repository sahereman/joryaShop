<?php

namespace App\Admin\Controllers;

use App\Models\CustomAttr;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Form\NestedForm;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class CustomAttrsController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('定制商品 SKU 属性管理')
            ->description('定制商品 SKU 属性 - 列表')
            ->body($this->grid());
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
            ->header('定制商品 SKU 属性管理')
            ->description('定制商品 SKU 属性 - 详情')
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
            ->header('定制商品 SKU 属性管理')
            ->description('定制商品 SKU 属性 - 编辑')
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
            ->header('定制商品 SKU 属性管理')
            ->description('定制商品 SKU 属性 - 新增')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new CustomAttr);
        $grid->model()->orderBy('sort', 'desc'); // 设置初始排序条件
        if (request()->input('type'))
        {
            $grid->model()->where('type', request()->input('type'));
        }

        $grid->id('Id');

        $grid->type('属性类型')->display(function ($data) {
            $str = "<a href='" . route('admin.custom_attrs.index', ['type' => $data]) . "'>$data</a>";
            return $str;
        });

        $grid->photo('Photo')->image('', 60);

        $grid->name('属性名称');
        $grid->is_required('是否必填')->display(function ($value) {
            return $value ? '<span class="label label-primary">是</span>' : '<span class="label label-default">否</span>';
        });
        $grid->sort('排序值')->sortable();

        // $grid->created_at('Created at');
        // $grid->updated_at('Updated at');

        return $grid;
    }

    /**
     * Make a show builder.
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(CustomAttr::findOrFail($id));

        // $show->id('Id');
        $show->type('属性类型');
        $show->name('属性名称');
        $show->is_required('是否必填')->as(function ($value) {
            return $value ? '是' : '否';
        });

        $show->photo('Photo')->image('', 60);

        $show->sort('排序值');
        $show->values('属性值 - 列表', function ($value) {
            /*禁用*/
            $value->disableActions();
            $value->disableRowSelector();
            $value->disableExport();
            $value->disableFilter();
            $value->disableCreateButton();
            $value->disablePagination();

            $value->value('属性值');
            $value->delta_price('加价[+|-]');

            $value->sort('排序值');
        });

        // $show->created_at('Created at');
        // $show->updated_at('Updated at');

        return $show;
    }

    /**
     * Make a form builder.
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new CustomAttr);
        $form->html('<button class="btn btn-primary"><i class="fa fa-send"></i>&nbsp;提交</button>');

        $form->select('type', '属性类型')->options(CustomAttr::$customAttrTypeMap);
        $form->text('name', '属性名称');
        $form->switch('is_required', '是否必填');

        $form->image('photo', 'Photo')
            ->deletable(true)
            ->uniqueName()
            // ->removable()
            ->move('original/' . date('Ym', now()->timestamp));

        $form->number('sort', '排序值')->default(9)->rules('required|integer|min:0')->help('默认倒序排列：数值越大越靠前');

        $form->hasMany('values', '属性值 - 列表', function (NestedForm $form) {
            $form->text('value', '属性值');
            $form->decimal('delta_price', '加价[+|-]')->help('取值为正数(+)时，为加价；取值为负数(-)时，为减价。');

            $form->number('sort', '排序值')->default(9)->rules('required|integer|min:0')->help('默认倒序排列：数值越大越靠前');
        });

        return $form;
    }
}
