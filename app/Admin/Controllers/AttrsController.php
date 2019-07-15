<?php

namespace App\Admin\Controllers;

use App\Models\Attr;
use App\Http\Controllers\Controller;
use App\Models\ProductAttr;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Form\NestedForm;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class AttrsController extends Controller
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
            ->header('SKU 属性管理')
            ->description('属性 - 列表')
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
            ->header('SKU 属性管理')
            ->description('属性 - 详情')
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
            ->header('SKU 属性管理')
            ->description('属性 - 编辑')
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
            ->header('SKU 属性管理')
            ->description('属性 - 新增')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Attr);
        $grid->model()->orderBy('sort', 'desc'); // 设置初始排序条件

        $grid->id('Id');
        $grid->name('SKU 属性名称')->sortable();

        $grid->has_photo('是否有对应图片')->display(function ($has_photo) {
            return $has_photo ? '<span class="label label-primary">是</span>' : '<span class="label label-default">否</span>';
        });
        // 设置text、color、和存储值
        /*$states = [
            'on' => ['value' => 1, 'text' => '是', 'color' => 'primary'],
            'off' => ['value' => 0, 'text' => '否', 'color' => 'default'],
        ];
        $grid->column('has_photo', '是否有对应图片')->switch($states);*/

        $grid->sort('排序值')->sortable();
        // $grid->created_at('Created at');
        // $grid->updated_at('Updated at');

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
        $show = new Show(Attr::findOrFail($id));

        // $show->id('Id');
        $show->name('SKU 属性名称');
        $show->has_photo('是否有对应图片')->as(function ($has_photo) {
            return $has_photo ? '是' : '否';
        });
        $show->sort('排序值');
        // $show->created_at('Created at');
        // $show->updated_at('Updated at');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Attr);

        $form->text('name', 'SKU 属性名称');
        $form->switch('has_photo', '是否有对应图片');
        $form->number('sort', '排序值');

        $form->saved(function (Form $form) {
            $attr = $form->model();
            $attr_id = $attr->id;
            // if ($form->input('has_photo') == 'on') {
            if ($attr->has_photo == true) {
                Attr::where('id', '<>', $attr_id)->update(['has_photo' => false]);
                ProductAttr::where('name', $attr->name)->update(['has_photo' => true]);
                ProductAttr::where('name', '<>', $attr->name)->update(['has_photo' => false]);
            }
        });
        return $form;
    }
}
