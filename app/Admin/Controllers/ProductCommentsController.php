<?php

namespace App\Admin\Controllers;

use App\Exceptions\InvalidRequestException;
use App\Models\ProductComment;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class ProductCommentsController extends Controller
{
    use HasResourceActions;

    //删除评论
    public function delete(ProductComment $comment)
    {
        if ($comment->deleted_at != null) {
            throw new InvalidRequestException('评论已被删除,无需重复操作');
        }

        $comment->photos = [];
        $comment->content = '该评论已被删除!';
        $comment->deleted_at = now();
        $comment->save();

        // 返回上一页
        return response()->json([
            'messages' => '评论删除成功'
        ], 200);
    }

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
        $grid = new Grid(new ProductComment);

        $grid->id('Id');
        $grid->user_id('User id');
        $grid->order_id('Order id');
        $grid->order_item_id('Order item id');
        $grid->product_id('Product id');
        $grid->composite_index('Composite index');
        $grid->description_index('Description index');
        $grid->shipment_index('Shipment index');
        $grid->content('Content');
        $grid->photos('Photos');
        $grid->created_at('Created at');
        $grid->updated_at('Updated at');

        return $grid;
    }

    /**
     * Make a show builder.
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(ProductComment::findOrFail($id));

        $show->id('Id');
        $show->user_id('User id');
        $show->order_id('Order id');
        $show->order_item_id('Order item id');
        $show->product_id('Product id');
        $show->composite_index('Composite index');
        $show->description_index('Description index');
        $show->shipment_index('Shipment index');
        $show->content('Content');
        $show->photos('Photos');
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
        $form = new Form(new ProductComment);

        $form->number('user_id', 'User id');
        $form->number('order_id', 'Order id');
        $form->number('order_item_id', 'Order item id');
        $form->number('product_id', 'Product id');
        $form->number('composite_index', 'Composite index')->default(5);
        $form->number('description_index', 'Description index')->default(5);
        $form->number('shipment_index', 'Shipment index')->default(5);
        $form->text('content', 'Content');
        $form->text('photos', 'Photos');

        return $form;
    }
}
