<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Request;
use App\Models\Product;
use App\Models\ProductFaq;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Form\Builder;
use Encore\Admin\Form\NestedForm;
use Encore\Admin\Form\Tools;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Route;

class ProductFaqsController extends Controller
{
    use HasResourceActions;

    protected $mode = 'create';
    protected $product_id;
    protected $product_faq_id;

    /**
     * Index interface.
     *
     * @param Content $content
     * @param Request $request
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
            ->header('商品 FAQ 管理')
            ->description('商品 FAQ - 列表')
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
        $this->product_faq_id = $id;
        $this->product_id = ProductFaq::find($id)->product_id;

        return $content
            ->header('商品 FAQ 管理')
            ->description('商品 FAQ - 详情')
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
        $this->product_faq_id = $id;
        $this->product_id = ProductFaq::find($id)->product_id;

        return $content
            ->header('商品 FAQ 管理')
            ->description('商品 FAQ - 编辑')
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
            ->header('商品 FAQ 管理')
            ->description('商品 FAQ - 新增')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @param Request $request
     * @return Grid
     */
    protected function grid(Request $request)
    {
        if ($request->has('product_id') && Product::where('id', $request->input('product_id'))->exists()) {
            $this->product_id = $request->input('product_id');
        } else {
            $this->product_id = Product::first()->id;
        }

        $grid = new Grid(new ProductFaq);
        $grid->model()->with('product')->orderBy('sort', 'desc')->where('product_id', $this->product_id); // 设置初始排序条件

        /*禁用*/
        // $grid->disableActions();
        // $grid->disableRowSelector();
        // $grid->disableExport();
        // $grid->disableFilter();
        $grid->disableCreateButton();
        // $grid->disablePagination();

        $product_id = $this->product_id;
        $grid->actions(function ($actions) use ($product_id) {
            $actions->disableView();
            $actions->disableEdit();
            // $actions->disableDelete();
            $actions->prepend('<a href="' . route('admin.product_faqs.show', ['product_faq' => $actions->getKey(), 'product_id' => $product_id]) . '">'
                . '<i class="fa fa-eye"></i>'
                . '</a>&nbsp;'
                . '<a href="' . route('admin.product_faqs.edit', ['product_faq' => $actions->getKey(), 'product_id' => $product_id]) . '">'
                . '<i class="fa fa-edit"></i>'
                . '</a>');
        });

        $grid->tools(function ($tools) {
            /*$tools->batch(function ($batch) {
                $batch->disableDelete();
            });*/
            $tools->append('<div class="btn-group pull-right" style="margin-right: 10px">'
                . '<a href="' . route('admin.product_faqs.create', ['product_id' => $this->product_id]) . '" class="btn btn-sm btn-success">'
                . '<i class="fa fa-save"></i>&nbsp;&nbsp;新增'
                . '</a>'
                . '</div>');
        });

        $grid->id('Id');

        // $grid->product_id('Product id');
        $grid->product_name('Product')->display(function ($data) use ($product_id) {
            $str = "<a href='" . route('admin.products.show', ['product' => $product_id]) . "'>{$data}</a>";
            return $str;
        });
        $grid->question('Question');
        // $grid->answer('Answer');
        $grid->sort('Sort');
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
        $show = new Show(ProductFaq::findOrFail($id));

        $this->product_faq_id = $id;
        $this->product_id = $show->getModel()->product_id;

        $show->panel()->tools(function ($tools) use ($id) {
            $tools->disableEdit();
            $tools->disableList();
            // $tools->disableDelete();
            $tools->append('<div class="btn-group pull-right" style="margin-right: 5px">'
                . '<a href="' . route('admin.product_faqs.edit', ['product_faq' => $id, 'product_id' => $this->product_id]) . '" class="btn btn-sm btn-primary">'
                . '<i class="fa fa-edit"></i>&nbsp;编辑'
                . '</a>'
                . '</div>&nbsp;'
                . '<div class="btn-group pull-right" style="margin-right: 5px">'
                . '<a href="' . route('admin.product_faqs.index', ['product_id' => $this->product_id]) . '" class="btn btn-sm btn-default">'
                . '<i class="fa fa-list"></i>&nbsp;列表'
                . '</a>'
                . '</div>');
        });

        // $show->id('Id');

        // $show->product_id('Product id');
        // $show->product()->name_en('Product');
        $show->product_name('Product');

        $show->question('Question');
        $show->answer('Answer');
        $show->sort('Sort');
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
        $form = new Form(new ProductFaq);
        $form->html('<button class="btn btn-primary"><i class="fa fa-send"></i>&nbsp;提交</button>');

        if ($this->mode == Builder::MODE_CREATE) {
            if (request()->has('product_id') && Product::where('id', request()->input('product_id'))->exists()) {
                $this->product_id = request()->input('product_id');
            } else {
                $this->product_id = Product::first()->id;
            }
            // $form->hidden('_from_')->default(Builder::MODE_CREATE);
        }
        if ($this->mode == Builder::MODE_EDIT) {
            $this->product_faq_id = Route::current()->parameter('product_faq');
            // $this->product_id = ProductFaq::find($this->product_faq_id)->product->id;
            $this->product_id = ProductFaq::find($this->product_faq_id)->product_id;
            // $form->hidden('_from_')->default(Builder::MODE_EDIT);
        }

        $product_id = $this->product_id;
        $product_faq_id = $this->product_faq_id;
        $product = Product::with('attrs')->find($this->product_id);

        if ($this->mode == Builder::MODE_CREATE) {
            $form->tools(function (Tools $tools) use ($product_id) {
                // $tools->disableDelete();
                $tools->disableList();
                $tools->disableView();
                $tools->append('<div class="btn-group pull-right" style="margin-right: 5px">'
                    . '<a href="' . route('admin.product_faqs.index', ['product_id' => $product_id]) . '" class="btn btn-sm btn-default">'
                    . '<i class="fa fa-list"></i>&nbsp;列表'
                    . '</a>'
                    . '</div>');
            });
        }
        if ($this->mode == Builder::MODE_EDIT) {
            $form->tools(function (Tools $tools) use ($product_id, $product_faq_id) {
                // $tools->disableDelete();
                $tools->disableList();
                $tools->disableView();
                $tools->append('<div class="btn-group pull-right" style="margin-right: 5px">'
                    . '<a href="' . route('admin.product_faqs.show', ['product_faq' => $product_faq_id, 'product_id' => $product_id]) . '" class="btn btn-sm btn-primary">'
                    . '<i class="fa fa-eye"></i>&nbsp;查看'
                    . '</a>'
                    . '</div>&nbsp;'
                    . '<div class="btn-group pull-right" style="margin-right: 5px">'
                    . '<a href="' . route('admin.product_faqs.index', ['product_id' => $product_id]) . '" class="btn btn-sm btn-default">'
                    . '<i class="fa fa-list"></i>&nbsp;列表'
                    . '</a>'
                    . '</div>');
            });
        }

        $form->hidden('product_id')->default($this->product_id);
        $form->display('product_name', 'Product')->default($product->name_en);

        $form->text('question', 'Question');
        $form->textarea('answer', 'Answer');
        $form->number('sort', '排序值')->default(9)->rules('required|integer|min:0')->help('默认倒序排列：数值越大越靠前');

        // $form->ignore(['_from_']);

        // 定义事件回调，当模型即将保存时会触发这个回调
        $form->saving(function (Form $form) {
            //
        });

        $form->saved(function (Form $form) {
            //
        });

        return $form;
    }
}
