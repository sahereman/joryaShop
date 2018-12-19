<?php

namespace App\Admin\Controllers;

use App\Models\CountryCode;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class CountryCodesController extends Controller
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
            ->header('手机号国家管理')
            ->description('列表')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed   $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('手机号国家管理')
            ->description('详情')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed   $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('手机号国家管理')
            ->description('编辑')
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
            ->header('手机号国家管理')
            ->description('新增')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new CountryCode);
        $grid->model()->orderBy('sort', 'desc'); // 设置初始排序条件

        $grid->country_name('国家名称');
//        $grid->country_iso('国家iso标示');
        $grid->country_code('国际电话区号');
        $grid->sort('排序')->sortable();

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed   $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(CountryCode::findOrFail($id));

        $show->id('ID');
        $show->country_name('国家名称');
//        $show->country_iso('国家iso标示');
        $show->country_code('国际电话区号');
        $show->sort('排序');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new CountryCode);

        $form->text('country_name', '国家名称');
//        $form->text('country_iso', '国家iso标示');
        $form->text('country_code', '国际电话区号');
        $form->number('sort', '排序');

        return $form;
    }
}
