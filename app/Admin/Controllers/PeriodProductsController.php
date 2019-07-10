<?php

namespace App\Admin\Controllers;

use App\Admin\Models\PeriodProduct;
use App\Http\Controllers\Controller;
use App\Http\Requests\Request;
use App\Models\Product;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Form\Builder;
use Encore\Admin\Form\Tools;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Route;

class PeriodProductsController extends Controller
{
    use HasResourceActions;

    protected $mode = 'create';
    protected $product_id;
    protected $period_product_id;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content, Request $request)
    {
        $this->mode = 'index';
        if ($request->has('product_id') && Product::where('id', $request->input('product_id'))->exists()) {
            $this->product_id = $request->input('product_id');
        } else {
            $this->product_id = Product::first()->id;
        }

        return $content
            ->header('限时商品管理')
            ->description('限时商品 - 列表')
            ->body($this->grid($request));
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
        $this->mode = 'show';
        $this->period_product_id = $id;
        $this->product_id = PeriodProduct::find($id)->product_id;

        return $content
            ->header('限时商品管理')
            ->description('限时商品 - 详情')
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
        $this->mode = 'edit';
        $this->period_product_id = $id;
        $this->product_id = PeriodProduct::find($id)->product_id;

        return $content
            ->header('限时商品管理')
            ->description('限时商品 - 编辑')
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
        $this->mode = 'create';
        if (request()->has('product_id') && Product::where('id', request()->input('product_id'))->exists()) {
            $this->product_id = request()->input('product_id');
        } else {
            $this->product_id = Product::first()->id;
        }

        return $content
            ->header('限时商品管理')
            ->description('限时商品 - 新增')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid(Request $request)
    {
        if ($request->has('product_id') && Product::where('id', $request->input('product_id'))->exists()) {
            $this->product_id = $request->input('product_id');
        } else {
            $this->product_id = Product::first()->id;
        }

        $grid = new Grid(new PeriodProduct);
        $grid->model()->with('product')->where('product_id', $this->product_id);

        /*禁用*/
        // $grid->disableActions();
        $grid->disableRowSelector();
        // $grid->disableExport();
        // $grid->disableFilter();
        $grid->disableCreateButton();
        // $grid->disablePagination();

        $product_id = $this->product_id;
        $grid->actions(function ($actions) use ($product_id) {
            $actions->disableView();
            $actions->disableEdit();
            // $actions->disableDelete();
            $actions->prepend('<a href="' . route('admin.period_products.show', ['period_product' => $actions->getKey(), 'product_id' => $product_id]) . '">'
                . '<i class="fa fa-eye"></i>'
                . '</a>&nbsp;'
                . '<a href="' . route('admin.period_products.edit', ['period_product' => $actions->getKey(), 'product_id' => $product_id]) . '">'
                . '<i class="fa fa-edit"></i>'
                . '</a>');
        });

        /*$grid->tools(function ($tools) {
            $tools->batch(function ($batch) {
                $batch->disableDelete();
            });
        });*/

        $grid->id('Id');

        // $grid->product_id('Product id');
        $grid->product_name('商品名称')->display(function ($product_name) {
            return "<a href='" . route('admin.products.show', ['product' => $this->product_id]) . "'><span>{$product_name}</span></a>";
        });

        $grid->period('限时时段');

        $grid->status('Status');

        // $grid->started_at('Stopped at');
        // $grid->stopped_at('Stopped at');

        // $grid->created_at('Created at');
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
        $show = new Show(PeriodProduct::findOrFail($id));

        $this->period_product_id = $id;
        $this->product_id = $show->getModel()->product_id;

        $show->panel()->tools(function ($tools) use ($id) {
            $tools->disableEdit();
            $tools->disableList();
            $tools->disableDelete();
            $tools->append('<div class="btn-group pull-right" style="margin-right: 5px">'
                . '<a href="' . route('admin.period_products.edit', ['period_product' => $id, 'product_id' => $this->product_id]) . '" class="btn btn-sm btn-primary">'
                . '<i class="fa fa-edit"></i>&nbsp;编辑'
                . '</a>'
                . '</div>&nbsp;'
                . '<div class="btn-group pull-right" style="margin-right: 5px">'
                . '<a href="' . route('admin.period_products.index', ['product_id' => $this->product_id]) . '" class="btn btn-sm btn-default">'
                . '<i class="fa fa-list"></i>&nbsp;列表'
                . '</a>'
                . '</div>');
        });

        // $show->id('Id');

        // $show->product_id('Product id');
        $show->product_name('商品名称')->as(function ($product_name) {
            return "<a href='" . route('admin.products.show', ['product' => $this->product_id]) . "'><span>{$product_name}</span></a>";
        });;

        $show->period('限时时段');

        $show->status('Status');

        // $show->product_id('Product id');
        // $show->started_at('Started at');
        // $show->stopped_at('Stopped at');
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
        $form = new Form(new PeriodProduct);
        // $form->html('<button class="btn btn-primary"><i class="fa fa-send"></i>&nbsp;提交</button>');

        if ($this->mode == Builder::MODE_CREATE) {
            if (request()->has('product_id') && Product::where('id', request()->input('product_id'))->exists()) {
                $this->product_id = request()->input('product_id');
            } else {
                $this->product_id = Product::first()->id;
            }
            $form->hidden('_from_')->default(Builder::MODE_CREATE);
        }
        if ($this->mode == Builder::MODE_EDIT) {
            $this->period_product_id = Route::current()->parameter('period_product');
            $this->product_id = PeriodProduct::find($this->period_product_id)->product_id;
            $form->hidden('_from_')->default(Builder::MODE_EDIT);
        }

        $product_id = $this->product_id;
        $product = Product::find($this->product_id);
        $period_product_id = $this->period_product_id;

        if ($this->mode == Builder::MODE_CREATE) {
            $form->tools(function (Tools $tools) use ($product_id) {
                $tools->disableDelete();
                $tools->disableList();
                $tools->disableView();
                $tools->append('<div class="btn-group pull-right" style="margin-right: 5px">'
                    . '<a href="' . route('admin.period_products.index', ['product_id' => $product_id]) . '" class="btn btn-sm btn-default">'
                    . '<i class="fa fa-list"></i>&nbsp;列表'
                    . '</a>'
                    . '</div>');
            });
        }
        if ($this->mode == Builder::MODE_EDIT) {
            $form->tools(function (Tools $tools) use ($product_id, $period_product_id) {
                $tools->disableDelete();
                $tools->disableList();
                $tools->disableView();
                $tools->append('<div class="btn-group pull-right" style="margin-right: 5px">'
                    . '<a href="' . route('admin.period_products.show', ['period_product' => $period_product_id, 'product_id' => $product_id]) . '" class="btn btn-sm btn-primary">'
                    . '<i class="fa fa-eye"></i>&nbsp;查看'
                    . '</a>'
                    . '</div>&nbsp;'
                    . '<div class="btn-group pull-right" style="margin-right: 5px">'
                    . '<a href="' . route('admin.period_products.index', ['product_id' => $product_id]) . '" class="btn btn-sm btn-default">'
                    . '<i class="fa fa-list"></i>&nbsp;列表'
                    . '</a>'
                    . '</div>');
            });
        }

        $form->hidden('product_id')->default($this->product_id);
        $form->display('product_name', 'Product')->default($product->name_en);

        // $form->datetime('started_at', 'Started at')->default(date('Y-m-d H:i:s'));
        // $form->datetime('stopped_at', 'Stopped at')->default(date('Y-m-d H:i:s'));
        $form->datetimeRange('started_at', 'stopped_at', '限时时段');

        $form->ignore(['_from_']);

        // 定义事件回调，当模型即将保存时会触发这个回调
        $form->saving(function (Form $form) {
            //
        });

        $form->saved(function (Form $form) {
            $this->period_product_id = $form->model()->id;
            $this->product_id = $form->model()->product_id;
            return redirect()->route('admin.period_products.index', ['product_id' => $this->product_id]);
        });

        return $form;
    }
}
