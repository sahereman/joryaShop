<?php

namespace App\Admin\Controllers;

use App\Models\Param;
use App\Http\Controllers\Controller;
use App\Models\ProductParam;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Form\NestedForm;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class ParamsController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('商品参数管理')
            ->description('参数 - 列表')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('商品参数管理')
            ->description('参数 - 详情')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('商品参数管理')
            ->description('参数 - 编辑')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('商品参数管理')
            ->description('参数 - 新增')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Param);
        $grid->model()->orderBy('sort', 'desc'); // 设置初始排序条件

        $grid->id('Id');
        $grid->name('商品参数名称')->sortable();
        $grid->sort('排序值')->sortable();
        // $grid->created_at('Created at');
        // $grid->updated_at('Updated at');

        $grid->values('商品参数值 总数')->count();

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Param::findOrFail($id));

        // $show->id('Id');
        $show->name('商品参数名称');
        $show->sort('排序值');
        // $show->created_at('Created at');
        // $show->updated_at('Updated at');

        $show->values('商品参数值 - 列表', function ($value) {
            /*禁用*/
            $value->disableActions();
            $value->disableRowSelector();
            $value->disableExport();
            $value->disableFilter();
            $value->disableCreateButton();
            $value->disablePagination();

            $value->value('商品参数值');
            $value->sort('排序值');
        });

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Param);
        $form->html('<button class="btn btn-primary"><i class="fa fa-send"></i>&nbsp;提交</button>');

        $form->text('name', '商品参数名称');
        $form->number('sort', '排序值')->default(9)->rules('required|integer|min:0')->help('默认倒序排列：数值越大越靠前');

        $form->hasMany('values', '商品参数值 - 列表', function (NestedForm $form) {
            $form->text('value', '商品参数值');
            $form->number('sort', '排序值')->default(9)->rules('required|integer|min:0')->help('默认倒序排列：数值越大越靠前');
        });

        // 定义事件回调，当模型即将保存时会触发这个回调
        $form->saving(function (Form $form) {
            $param = $form->model();
            $param_name = request()->input('name');
            if ($param_name != $param->name) {
                ProductParam::where('name', $param->name)->update(['name' => $param_name]);
            }
        });

        return $form;
    }
}
