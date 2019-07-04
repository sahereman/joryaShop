<?php

namespace App\Admin\Controllers;

use App\Admin\Extensions\Ajax\Ajax_Icon;
use App\Http\Controllers\Controller;
use App\Http\Requests\Request;
use App\Models\Product;
use App\Models\ProductAttr;
use App\Models\ProductSku;
use App\Models\ProductSkuAttrValue;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Form\Builder;
use Encore\Admin\Form\NestedForm;
use Encore\Admin\Form\Tools;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Route;

class ProductSkusController extends Controller
{
    use HasResourceActions;

    protected $mode = 'create';
    protected $product_id;
    protected $product_sku_id;

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
            ->header('商品 SKU 管理')
            ->description('商品 SKU - 列表')
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
        $this->product_sku_id = $id;
        $this->product_id = ProductSku::find($id)->product->id;

        return $content
            ->header('商品 SKU 管理')
            ->description('商品 SKU - 详情')
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
        $this->product_sku_id = $id;
        $this->product_id = ProductSku::find($id)->product->id;

        return $content
            ->header('商品 SKU 管理')
            ->description('商品 SKU - 编辑')
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
            ->header('商品 SKU 管理')
            ->description('商品 SKU - 新增')
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

        $grid = new Grid(new ProductSku);
        $grid->model()->where('product_id', $this->product_id);

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
            $actions->prepend('<a href="' . route('admin.product_skus.show', ['product_sku' => $actions->getKey()]) . '?product_id=' . $product_id . '">'
                . '<i class="fa fa-eye"></i>'
                . '</a>&nbsp;'
                . '<a href="' . route('admin.product_skus.edit', ['product_sku' => $actions->getKey()]) . '?product_id=' . $product_id . '">'
                . '<i class="fa fa-edit"></i>'
                . '</a>');
        });

        $grid->tools(function ($tools) {
            /*$tools->batch(function ($batch) {
                $batch->disableDelete();
            });*/
            $tools->append('<div class="btn-group pull-right" style="margin-right: 10px">'
                . '<a href="' . route('admin.product_skus.create') . '?product_id=' . $this->product_id . '" class="btn btn-sm btn-success">'
                . '<i class="fa fa-save"></i>&nbsp;&nbsp;新增'
                . '</a>'
                . '</div>');
        });

        $grid->id('Id');

        $grid->photo_url('Photo')->image('', 60);

        $grid->product()->name_en('Product')->display(function ($data) use ($product_id) {
            $str = "<a href='" . route('admin.products.show', ['product' => $product_id]) . "'>{$data}</a>";
            return $str;
        });

        // $grid->name_en('Name en');
        // $grid->name_zh('Name zh');

        $grid->attr_value_string('SKU 属性概况');

        $grid->price('单价');
        $grid->stock('库存');
        $grid->sales('销量');

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
        $show = new Show(ProductSku::findOrFail($id));

        $this->product_sku_id = $id;
        // $this->product_id = ProductSku::find($id)->product->id;
        $this->product_id = $show->getModel()->product_id;

        $show->panel()->tools(function ($tools) {
            // $tools->disableEdit();
            $tools->disableList();
            // $tools->disableDelete();
            $tools->append('<div class="btn-group pull-right" style="margin-right: 5px">'
                . '<a href="' . route('admin.product_skus.index') . '?product_id=' . $this->product_id . '" class="btn btn-sm btn-default">'
                . '<i class="fa fa-list"></i>&nbsp;列表'
                . '</a>'
                . '</div>');
        });

        $show->id('Id');

        // $show->product()->name_en('Product');
        $show->product_name('Product');

        // $show->name_en('Name en');
        // $show->name_zh('Name zh');

        if ($show->getModel()->photo) {
            $show->photo_url('Photo')->image();
        } else {
            $show->photo_url('Photo');
        }

        $show->price('单价');
        $show->stock('库存');
        $show->sales('销量');

        // $show->created_at('Created at');
        // $show->updated_at('Updated at');

        $show->attr_values('SKU 属性 - 列表', function ($attr_value) {
            /*禁用*/
            $attr_value->disableActions();
            $attr_value->disableRowSelector();
            $attr_value->disableExport();
            $attr_value->disableFilter();
            $attr_value->disableCreateButton();
            $attr_value->disablePagination();

            $attr_value->name('SKU 属性名称');
            $attr_value->value('SKU 属性值');
        });

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
        $form->html('<button class="btn btn-primary"><i class="fa fa-send"></i>&nbsp;提交</button>');

        if ($this->mode == Builder::MODE_CREATE) {
            if (request()->has('product_id') && Product::where('id', request()->input('product_id'))->exists()) {
                $this->product_id = request()->input('product_id');
            } else {
                $this->product_id = Product::first()->id;
            }
            $form->hidden('_from_')->default(Builder::MODE_CREATE);
        }
        if ($this->mode == Builder::MODE_EDIT) {
            $this->product_sku_id = Route::current()->parameter('product_skus');
            $this->product_id = ProductSku::find($this->product_sku_id)->product->id;
            $form->hidden('_from_')->default(Builder::MODE_EDIT);
        }

        $product_id = $this->product_id;
        $product = Product::with('attrs')->find($this->product_id);

        if ($this->mode == Builder::MODE_CREATE) {
            $form->tools(function (Tools $tools) use ($product_id) {
                // $tools->disableDelete();
                $tools->disableList();
                $tools->disableView();
                $tools->append('<div class="btn-group pull-right" style="margin-right: 5px">'
                    . '<a href="' . route('admin.product_skus.index') . '?product_id=' . $product_id . '" class="btn btn-sm btn-default">'
                    . '<i class="fa fa-list"></i>&nbsp;列表'
                    . '</a>'
                    . '</div>');
            });
        }
        if ($this->mode == Builder::MODE_EDIT) {
            $form->tools(function (Tools $tools) use ($product_id) {
                // $tools->disableDelete();
                $tools->disableList();
                // $tools->disableView();
                $tools->append('<div class="btn-group pull-right" style="margin-right: 5px">'
                    . '<a href="' . route('admin.product_skus.index') . '?product_id=' . $product_id . '" class="btn btn-sm btn-default">'
                    . '<i class="fa fa-list"></i>&nbsp;列表'
                    . '</a>'
                    . '</div>');
            });
        }


        $form->hidden('product_id')->default($this->product_id);
        $form->display('product_name', 'Product')->default($product->name_en);

        // $form->text('name_en', 'Name en');
        // $form->text('name_zh', 'Name zh');

        $form->image('photo', 'Photo')
            ->deletable(true)
            ->uniqueName()
            // ->removable()
            ->move('original/' . date('Ym', now()->timestamp));

        $form->decimal('price', '单价')->default(0.01);
        $form->number('stock', '库存');
        $form->number('sales', '销量');

        $product->attrs->each(function (ProductAttr $productAttr) use ($form) {
            $form->divider();
            $form->hidden("attr_value_options.{$productAttr->id}.name")->default($productAttr->name);
            $form->display("attr_value_options.{$productAttr->id}.name", 'SKU 属性名称')->default($productAttr->name);
            $form->text("attr_value_options.{$productAttr->id}.value", 'SKU 属性值')->rules('required');
            $form->number("attr_value_options.{$productAttr->id}.sort", '排序值')->default($productAttr->sort);
        });

        // $form->html('<script type="text/javascript">$("div.add.btn.btn-success.btn-sm").hide();</script>');

        $form->ignore(['_from_']);

        // 定义事件回调，当模型即将保存时会触发这个回调
        $form->saving(function (Form $form) {
            //
        });

        $form->saved(function (Form $form) {
            $this->product_sku_id = $form->model()->id;
            // $product = Product::with('skus.attr_values')->find($this->product_id);
            $product_sku = ProductSku::with('attr_values')->find($this->product_sku_id);

            $attr_value_options = request()->input('attr_value_options');
            $attr_value_count = count($attr_value_options);
            $flag = true;
            $attr_value_string = '';
            $attr_value_strings = [];
            ProductSku::where('product_id', $this->product_id)->where('id', '<>', $this->product_sku_id)->each(function (ProductSku $productSku) use (&$attr_value_strings) {
                $attr_value_strings[] = $productSku->attr_value_string;
            });
            foreach ($attr_value_options as $attr_value_option) {
                if (is_null($attr_value_option['value'])) {
                    $flag = false;
                    /* TODO ... */
                    /* Put Error Msg into MessageBag */
                }
                $attr_value_string .= $attr_value_option['name'] . ' (' . $attr_value_option['value'] . ') ; ';
            }
            $attr_value_string = substr($attr_value_string, 0, -3);
            if (in_array($attr_value_string, $attr_value_strings)) {
                $flag = false;
                /* TODO ... */
                /* Put Error Msg into MessageBag */
            }

            if (request()->input('_from_') == Builder::MODE_CREATE) {
                if ($flag) {
                    foreach ($attr_value_options as $product_attr_id => $attr_value_option) {
                        unset($attr_value_option['name']);
                        $attr_value_option['product_attr_id'] = $product_attr_id;
                        $attr_value_option['product_sku_id'] = $this->product_sku_id;
                        ProductSkuAttrValue::create($attr_value_option);
                    }
                    return redirect()->to(route('admin.product_skus.show', ['product_sku' => $this->product_sku_id]) . '?product_id=' . $this->product_id);
                }

                return redirect()->to(route('admin.product_skus.index') . '?product_id=' . $this->product_id);
            }

            if (request()->input('_from_') == Builder::MODE_EDIT) {
                if ($flag) {
                    $attr_value_count_consistent = false;
                    if ($product_sku->attr_values->count() == $attr_value_count) {
                        $attr_value_count_consistent = true;
                    }
                    foreach ($attr_value_options as $product_attr_id => $attr_value_option) {
                        unset($attr_value_option['name']);
                        $attr_value_option['product_attr_id'] = $product_attr_id;
                        $attr_value_option['product_sku_id'] = $this->product_sku_id;
                        if ($attr_value_count_consistent) {
                            ProductSkuAttrValue::where('product_sku_id', $this->product_sku_id)->where('product_attr_id', $attr_value_option['product_attr_id'])->update($attr_value_option);
                        } else if ($product_sku->attr_values->count() == 0) {
                            ProductSkuAttrValue::create($attr_value_option);
                        } else {
                            if (ProductSkuAttrValue::where('product_sku_id', $this->product_sku_id)->where('product_attr_id', $attr_value_option['product_attr_id'])->exists()) {
                                ProductSkuAttrValue::where('product_sku_id', $this->product_sku_id)->where('product_attr_id', $attr_value_option['product_attr_id'])->update($attr_value_option);
                            } else {
                                ProductSkuAttrValue::create($attr_value_option);
                            }
                        }
                    }
                    return redirect()->to(route('admin.product_skus.show', ['product_sku' => $this->product_sku_id]) . '?product_id=' . $this->product_id);
                }

                return redirect()->to(route('admin.product_skus.index') . '?product_id=' . $this->product_id);
            }
        });

        return $form;
    }
}
