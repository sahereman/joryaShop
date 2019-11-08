<?php

namespace App\Admin\Controllers;

use App\Models\FakeReview;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class FakeReviewsController extends Controller
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
     * @param mixed   $id
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
     * @param mixed   $id
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
        $grid = new Grid(new FakeReview);
        $grid->model()->orderBy('sort', 'desc'); // 设置初始排序条件

        $grid->id('ID')->sortable();
        // $grid->photo('Photo');
        $grid->photo('商品图片')->image('', 120);
        // $grid->review('Review');
        $grid->name('用户名')->sortable();
        $grid->reviewed_at('用户评论时间')->sortable();
        $grid->sort('排序值')->sortable();

        // $grid->created_at('Created at');
        // $grid->updated_at('Updated at');

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
        $show = new Show(FakeReview::findOrFail($id));

        $show->id('ID');
        // $show->photo('Photo');
        $show->photo('商品图片')->image('', 300);
        $show->review('用户评论内容');
        $show->name('用户名');
        $show->reviewed_at('用户评论时间');
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
        $form = new Form(new FakeReview);

        // $form->text('photo', '商品图片');
        $form->image('photo', '商品图片')->uniqueName()->move('reviews')->rules('required|image');
        $form->text('review', '用户评论内容');
        $form->text('name', '用户名');
        $form->datetime('reviewed_at', '用户评论时间')->default(date('Y-m-d H:i:s'));
        $form->number('sort', '排序值')->default(9)->rules('required|integer|min:0')->help('默认倒序排列：数值越大越靠前');

        return $form;
    }
}
