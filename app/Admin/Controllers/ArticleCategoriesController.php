<?php

namespace App\Admin\Controllers;

use App\Models\ArticleCategory;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Encore\Admin\Tree;

class ArticleCategoriesController extends Controller
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
            ->header('文章管理')
            ->description('类别 - 列表')
            ->body($this->tree());
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
            ->header('文章管理')
            ->description('类别 - 详情')
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
            ->header('文章管理')
            ->description('类别 - 编辑')
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
            ->header('文章管理')
            ->description('类别 - 新增')
            ->body($this->form());
    }


    protected function tree()
    {
        return ArticleCategory::tree(function (Tree $tree) {
            $tree->branch(function ($branch) {
                //                return "ID:{$branch['id']} - {$branch['name_en']} " . $text;
                return "ID:{$branch['id']} - {$branch['name_en']} ".'<span class="label label-success">文章数: '.ArticleCategory::find($branch['id'])->articles->count().'</span>';
            });
        });
    }


    //    protected function grid()
    //    {
    //        $grid = new Grid(new ArticleCategory);
    //
    //        $grid->id('Id');
    //        $grid->parent_id('Parent id');
    //        $grid->name_en('Name en');
    //        $grid->name_zh('Name zh');
    //        $grid->description_en('Description en');
    //        $grid->description_zh('Description zh');
    //        $grid->sort('Sort');
    //        $grid->created_at('Created at');
    //        $grid->updated_at('Updated at');
    //
    //        return $grid;
    //    }

    /**
     * Make a show builder.
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(ArticleCategory::findOrFail($id));

        $show->id('Id');
        $show->parent_id('Parent id');
        $show->name_en('Name en');
        $show->name_zh('Name zh');
        $show->description_en('Description en');
        $show->description_zh('Description zh');
        $show->sort('Sort');
        $show->created_at('Created at');
        $show->updated_at('Updated at');

        return $show;
    }

    /**
     * Make a form builder.
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new ArticleCategory);

        $form->select('parent_id', '上级分类')->options(ArticleCategory::selectOptions())->rules('required');

        $form->hidden('name_zh', '名称(中文)')->default('lyrical');
        $form->text('name_en', '名称(英文)')->rules('required');


        $form->hidden('description_zh', '描述(中文)')->default('lyrical');
        $form->text('description_en', '描述(英文)')->rules('required|max:255');

        $form->number('sort', '排序值')->default(9)->rules('required|integer|min:0')->help('默认倒序排列：数值越大越靠前');

        return $form;
    }
}
