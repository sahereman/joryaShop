<?php

namespace App\Admin\Controllers;

use App\Models\Feedback;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class FeedbacksController extends Controller
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
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Feedback);

        $grid->disableCreateButton();

        $grid->actions(function ($actions) {
            $actions->disableView();
            $actions->disableEdit();
        });

        $grid->id('Id');
        // $grid->user_id('User ID');
        // $grid->name('Name');
        // $grid->gender('Gender');
        $grid->email('Email');
        // $grid->phone('Phone');
        // $grid->content('Content');
        // $grid->type('Type');
        // $grid->is_check('Is Check');
        $grid->created_at('Created at');
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
        $show = new Show(Feedback::findOrFail($id));

        $show->id('Id');
        // $show->user_id('User ID');
        // $show->name('Name');
        // $show->gender('Gender');
        $show->email('Email');
        // $show->phone('Phone');
        // $show->content('Content');
        // $show->type('Type');
        // $show->is_check('Is Check');
        $show->created_at('Created at');
        // $show->updated_at('Updated at');

        return $show;
    }

    /**
     * Make a form builder.
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Feedback);

        // $form->number('user_id', 'User ID');
        // $form->text('name', 'Name');
        // $form->text('gender', 'Gender');
        $form->email('email', 'Email');
        // $form->mobile('phone', 'Phone');
        // $form->textarea('content', 'Content');
        // $form->text('type', 'Type');
        // $form->switch('is_check', 'Is Check');

        return $form;
    }
}
