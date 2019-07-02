<?php

namespace App\Admin\Controllers;

use App\Models\ProductSku;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class ProductSkusController extends Controller
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
        $grid = new Grid(new ProductSku);

        $grid->id('Id');
        $grid->product_id('Product id');
        $grid->name_en('Name en');
        // $grid->name_zh('Name zh');
        /*$grid->base_size_en('Base size en');
        $grid->base_size_zh('Base size zh');
        $grid->hair_colour_en('Hair colour en');
        $grid->hair_colour_zh('Hair colour zh');
        $grid->hair_density_en('Hair density en');
        $grid->hair_density_zh('Hair density zh');*/
        $grid->photo('Photo');
        $grid->price('Price');
        $grid->stock('Stock');
        $grid->sales('Sales');
        $grid->created_at('Created at');
        $grid->updated_at('Updated at');

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
        $show = new Show(ProductSku::findOrFail($id));

        $show->id('Id');
        $show->product_id('Product id');
        $show->name_en('Name en');
        // $show->name_zh('Name zh');
        /*$show->base_size_en('Base size en');
        $show->base_size_zh('Base size zh');
        $show->hair_colour_en('Hair colour en');
        $show->hair_colour_zh('Hair colour zh');
        $show->hair_density_en('Hair density en');
        $show->hair_density_zh('Hair density zh');*/
        $show->photo('Photo');
        $show->price('Price');
        $show->stock('Stock');
        $show->sales('Sales');
        $show->created_at('Created at');
        $show->updated_at('Updated at');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new ProductSku);

        $form->number('product_id', 'Product id');
        $form->text('name_en', 'Name en');
        // $form->text('name_zh', 'Name zh');
        /*$form->text('base_size_en', 'Base size en')->default('Common');
        $form->text('base_size_zh', 'Base size zh')->default('普通');
        $form->text('hair_colour_en', 'Hair colour en')->default('Common');
        $form->text('hair_colour_zh', 'Hair colour zh')->default('普通');
        $form->text('hair_density_en', 'Hair density en')->default('Common');
        $form->text('hair_density_zh', 'Hair density zh')->default('普通');*/
        $form->image('photo', 'Photo')
            ->deletable(true)
            ->uniqueName()
            // ->removable()
            ->move('original/' . date('Ym', now()->timestamp));
        $form->decimal('price', 'Price')->default(0.01);
        $form->number('stock', 'Stock');
        $form->number('sales', 'Sales');

        return $form;
    }
}
