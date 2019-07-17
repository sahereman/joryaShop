<?php

namespace App\Admin\Controllers;

use App\Models\DistributionLevel;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class DistributionLevelsController extends Controller
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
            ->header('分销等级')
            ->body($this->grid());
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
            ->header('分销等级')
            ->body($this->form()->edit($id));
    }


    /**
     * Make a grid builder.
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new DistributionLevel);

        $grid->disableRowSelector();
        $grid->disableExport();
        $grid->disableFilter();
        $grid->disableCreateButton();

        $grid->actions(function ($actions) {
            $actions->disableView();
            $actions->disableDelete();
        });


        $grid->level('分销等级');
        $grid->profit_ratio('利润率%');
        $grid->column('', '分销说明')->display(function () {
            return '当前用户购买 100$ 的商品时,上' . $this->level . '级用户可获利 ' . bcmul(100, $this->profit_ratio / 100, 2) . '$';
        });

        return $grid;
    }

    /**
     * Make a form builder.
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new DistributionLevel);

        $form->display('level', '分销等级');
        $form->rate('profit_ratio', '利润率%')->rules('required|numeric|min:0|max:99.99');

        return $form;
    }
}
