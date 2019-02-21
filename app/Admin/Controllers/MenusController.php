<?php

namespace App\Admin\Controllers;

use App\Models\Menu;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class MenusController extends Controller
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
            ->header('导航菜单管理')
            ->description('列表')
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
            ->header('导航菜单管理')
            ->description('详情')
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
            ->header('导航菜单管理')
            ->description('编辑')
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
            ->header('导航菜单管理')
            ->description('新增')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Menu);
        $grid->model()->orderBy('slug', 'desc'); // 设置初始排序条件


        $grid->name_zh('名称(中文)');
        $grid->name_en('名称(英文)');
        $grid->slug('标示')->sortable();
        $grid->link('链接');
        $grid->sort('排序值')->sortable();

        return $grid;
    }

    /**
     * Make a show builder.
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Menu::findOrFail($id));

        $show->id('ID');
        $show->name_zh('名称(中文)');
        $show->name_en('名称(英文)');
        $show->slug('标示');
        $show->link('链接');
        $show->sort('排序值');

        return $show;
    }

    /**
     * Make a form builder.
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Menu);

        $form->text('name_zh', '名称(中文)')->rules('required');
        $form->text('name_en', '名称(英文)')->rules('required');

        $form->select('slug', '标示位')->options([
            'pc' => 'PC站',
            'mobile' => 'Mobile站'
        ])->rules('required');

        $form->url('link', '链接');
        $form->number('sort', '排序值');

        return $form;
    }
}
