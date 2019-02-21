<?php

namespace App\Admin\Controllers;

use App\Models\ExchangeRate;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class ExchangeRatesController extends Controller
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
            ->header('汇率管理')
            ->description('列表 - 基于美元(USD)作为基础货币')
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
            ->header('汇率管理')
            ->description('详情 - 基于美元(USD)作为基础货币')
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
            ->header('汇率管理')
            ->description('编辑 - 基于美元(USD)作为基础货币')
            ->body($this->form()->edit($id));
    }


    /**
     * Make a grid builder.
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ExchangeRate);
        $grid->disableFilter();
        $grid->disableExport();
        $grid->disableCreateButton();
        $grid->disableRowSelector();

        $grid->actions(function ($actions) {
            $actions->disableDelete();
        });


        $grid->id('ID');
        $grid->name('名称');
        $grid->currency('币种');
        $grid->rate('汇率');

        return $grid;
    }

    /**
     * Make a show builder.
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(ExchangeRate::findOrFail($id));
        $show->panel()->tools(function ($tools) {
            $tools->disableDelete();
        });

        $show->id('ID');
        $show->name('名称');
        $show->currency('币种');
        $show->rate('汇率');

        return $show;
    }

    /**
     * Make a form builder.
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new ExchangeRate);
        $form->tools(function (Form\Tools $tools) {
            $tools->disableDelete();
        });

        $form->text('name', '名称');
        $form->display('currency', '币种');
        $form->decimal('rate', '汇率')
            ->help('1.00 (USD) 兑换等值 (币种)');

        return $form;
    }
}
