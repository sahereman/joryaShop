<?php

namespace App\Admin\Controllers;

use App\Models\User;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Show;

class UsersController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('用户列表');

            $content->body($this->grid());
        });
    }

    /**
     * Show interface.
     * @param $id
     * @return Content
     */
    public function show($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header('用户详情');

            $content->body(Admin::show(User::findOrFail($id), function (Show $show) {

                $show->id('ID');
                $show->divider();

                $show->avatar('头像')->image('', 120);
                $show->email('邮箱');
                $show->name('用户名');
                $show->created_at('创建时间');
                $show->updated_at('更新时间');

            }));
        });
    }

    /**
     * Edit interface.
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header('header');
            $content->description('description');

            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Create interface.
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header('header');
            $content->description('description');

            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(User::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->avatar('头像')->image('', 40);
            $grid->email('邮箱');
            $grid->name('用户名');
            $grid->created_at('创建时间')->sortable();

            // 不在页面显示 `新建` 按钮，因为我们不需要在后台新建用户
            $grid->disableCreateButton();

            $grid->actions(function ($actions) {
//                $actions->disableView();
//                $actions->disableEdit();
                $actions->disableDelete();
            });

            $grid->tools(function ($tools) {

                // 禁用批量删除按钮
                $tools->batch(function ($batch) {
                    $batch->disableDelete();
                });
            });
        });
    }

    /**
     * Make a form builder.
     * @return Form
     */
    protected function form()
    {
        return Admin::form(User::class, function (Form $form) {

            $form->display('id', 'ID');

            $form->image('avatar', 'Avatar')->uniqueName()->move('avatar/' . date('Ym', now()->timestamp))->rules('required|image');

            $form->editor('name', 'Name');

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}
