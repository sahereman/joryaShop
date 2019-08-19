<?php

namespace App\Admin\Controllers;

use App\Mail\SendTemplateEmail;
use App\Models\EmailTemplate;
use App\Http\Controllers\Controller;
use App\Models\User;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class EmailTemplatesController extends Controller
{
    use HasResourceActions;


    public function preview(EmailTemplate $emailTemplate)
    {
        return new SendTemplateEmail($emailTemplate);
    }

    /**
     * Index interface.
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('邮件模板管理')
            // ->description('description')
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
            ->header('邮件模板管理')
            // ->description('description')
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
            ->header('邮件模板管理')
            // ->description('description')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new EmailTemplate);
        $grid->actions(function ($actions) {
            $actions->disableView();
        });

        $grid->id('ID');
        $grid->name('名称');

        return $grid;
    }

    /**
     * Make a form builder.
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new EmailTemplate);
        $form->tools(function (Form\Tools $tools) {
            $tools->disableView();
        });

        $form->text('name', '名称');
        $form->hidden('type', '模板类型')->default('html');
        $form->multipleFile('attachments', '邮件附件')->removable();
        $form->editor('template', '邮件内容');

        return $form;
    }
}
