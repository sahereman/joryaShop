<?php

namespace App\Admin\Controllers;

use App\Models\EmailLog;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class EmailLogsController extends Controller
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
            ->header('Index')
            ->description('description')
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
            ->header('Detail')
            ->description('description')
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
            ->header('Edit')
            ->description('description')
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
            ->header('Create')
            ->description('description')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new EmailLog);

        /*禁用*/
        // $grid->disableActions();
        // $grid->disableRowSelector();
        // $grid->disableExport();
        // $grid->disableFilter();
        $grid->disableCreateButton();
        // $grid->disablePagination();

        /*$grid->actions(function ($actions) {
            // $actions->disableView();
            // $actions->disableEdit();
            // $actions->disableDelete();
        });*/

        /*$grid->tools(function ($tools) {
            $tools->batch(function ($batch) {
                // $batch->disableDelete();
            });
        });*/

        /*筛选*/
        $grid->filter(function ($filter) {
            $filter->disableIdFilter(); // 去掉默认的id过滤器
            $filter->like('email', 'Client Email');
        });

        $grid->id('Id')->sortable();
        $grid->email('Client Email')->sortable();
        $grid->name('Client Name')->sortable();
        $grid->phone('Client Phone')->sortable();
        $grid->address('Client Address')->sortable();
        $grid->agent('Agent Info')->sortable();
        $grid->facebook('Communicated via Facebook')->switch();
        // $grid->content('Content');
        $grid->sent_at('Sent at')->sortable();
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
        $show = new Show(EmailLog::findOrFail($id));

        /*$show->panel()->tools(function ($tools) {
            // $tools->disableEdit();
            // $tools->disableList();
            // $tools->disableDelete();
        });*/

        $show->id('Id');
        $show->email('Email');
        $show->name('Client Name');
        $show->phone('Client Phone');
        $show->address('Client Address');
        $show->agent('Agent Info');
        $show->facebook('Communicated via Facebook')->as(function ($facebook) {
            return $facebook ? 'Yes' : 'No';
        });
        $show->sent_at('Sent at');
        $show->content('Content');
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
        $form = new Form(new EmailLog);
        $form->html('<button class="btn btn-primary"><i class="fa fa-send"></i>&nbsp;提交</button>');

        /*$form->tools(function (Form\Tools $tools) {
            // $tools->disableDelete();
            // $tools->disableList();
            // $tools->disableView();
        });*/

        $form->display('email', 'Email');
        $form->text('name', 'Client Name');
        $form->text('phone', 'Client Phone');
        $form->text('address', 'Client Address');
        $form->text('agent', 'Agent Info');
        $form->switch('facebook', 'Communicated via Facebook')->states([
            'on' => ['value' => 1, 'text' => 'Yes', 'color' => 'success'],
            'off' => ['value' => 0, 'text' => 'No', 'color' => 'danger'],
        ]);
        $form->display('content', 'Content');

        return $form;
    }
}
