<?php

namespace App\Admin\Controllers;

use App\Admin\Extensions\Ajax\Ajax_Icon;
use App\Models\Coupon;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Form\Builder;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class CouponsController extends Controller
{
    use HasResourceActions;

    protected $mode = 'create';
    protected $coupon_id;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        $this->mode = 'index';
        return $content
            ->header('优惠券管理')
            ->description('优惠券 - 列表')
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
        $this->mode = 'show';
        $this->coupon_id = $id;
        return $content
            ->header('优惠券管理')
            ->description('优惠券 - 详情')
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
        $this->coupon_id = $id;
        return $content
            ->header('优惠券管理')
            ->description('优惠券 - 编辑')
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
        return $content
            ->header('优惠券管理')
            ->description('优惠券 - 新增')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Coupon);
        $grid->model()->orderBy('sort', 'desc'); // 设置初始排序条件

        /*禁用*/
        // $grid->disableActions();
        // $grid->disableRowSelector();
        // $grid->disableExport();
        // $grid->disableFilter();
        // $grid->disableCreateButton();
        // $grid->disablePagination();

        $grid->actions(function ($actions) {
            // $actions->disableView();
            $actions->disableEdit();
            $actions->disableDelete();
        });

        $grid->tools(function ($tools) {
            $tools->batch(function ($batch) {
                $batch->disableDelete();
            });
        });

        $grid->id('Id')->sortable();
        $grid->name('Name')->sortable();
        $grid->type('类型')->display(function ($value) {
            return Coupon::$couponTypeMap[$value];
        })->sortable();
        // $grid->discount('Discount');
        // $grid->reduction('Reduction');
        // $grid->threshold('Threshold');

        $grid->number('数量')->display(function ($value) {
            if (is_null($value)) {
                return '不限量';
            }
            return $value;
        })->sortable();
        $grid->allowance('单人领取限额')->sortable();

        // $grid->supported_product_types('Supported product types');
        $grid->supported_product_type_string('支持商品类型');

        $grid->scenario('用户领取场景')->display(function ($value) {
            if ($value != Coupon::COUPON_SCENARIO_ADMIN) {
                return Coupon::$couponScenarioMap[$value];
            } else {
                // return '<a href="' . $this->getKey() . '">' . Coupon::$couponScenarioMap[$value] . '</a>';
                return '<a href="' . route('admin.users.send_coupon.show') . '?coupon_id=' . $this->id . '">' . Coupon::$couponScenarioMap[$value] . '</a>';
            }
        });

        $grid->period('限时时段');
        $grid->status('Status');

        // $grid->sort('Sort');
        // $grid->started_at('Started at');
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
        $show = new Show(Coupon::findOrFail($id));
        $coupon = $show->getModel();

        // $show->id('Id');
        $show->name('Name');
        $show->type('类型')->as(function ($value) {
            return Coupon::$couponTypeMap[$value];
        });

        if ($coupon->type = Coupon::COUPON_TYPE_DISCOUNT) {
            $show->discount('折扣');
        } else {
            $show->reduction('满减($)');
        }

        $show->threshold('消费金额阈值($)');

        $show->number('数量')->as(function ($value) {
            if (is_null($value)) {
                return '不限量';
            }
            return $value;
        });
        $show->allowance('单人领取限额');

        // $show->supported_product_types('Supported product types');
        $show->supported_product_type_string('支持商品类型');

        $show->scenario('用户领取场景')->as(function ($value) {
            return Coupon::$couponScenarioMap[$value];
        });
        $show->period('限时时段');
        $show->status('Status');
        $show->sort('排序值');

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
        $form = new Form(new Coupon);

        if ($this->mode == Builder::MODE_CREATE) {
            $form->hidden('_from_')->default(Builder::MODE_CREATE);
        }
        if ($this->mode == Builder::MODE_EDIT) {
            $form->hidden('_from_')->default(Builder::MODE_EDIT);
        }

        $form->text('name', 'Name')->setWidth(2);

        if ($this->mode == Builder::MODE_CREATE) {
            $form->select('type', '类型')->options(Coupon::$couponTypeMap)->default('discount');
            $form->decimal('discount', '折扣')->setWidth(2)->default(0.00)->rules('numeric|between:0,1');
            // $form->decimal('reduction', '满减($)')->default(0.00)->rules('numeric|min:0');
            $form->currency('reduction', '满减($)')->symbol('$')->default(0.00)->rules('numeric|min:0');
            // $form->decimal('threshold', '消费金额阈值($)')->default(0.00)->rules('numeric|min:0');
            $form->currency('threshold', '消费金额阈值($)')->symbol('$')->default(0.00)->rules('numeric|min:0');

            $states = [
                'on' => ['value' => 1, 'text' => '是', 'color' => 'success'],
                'off' => ['value' => 0, 'text' => '否', 'color' => 'danger'],
            ];
            $form->switch('is_limited', '是否限量')->states($states)->default(0);
            $form->number('number', '数量')->default(null)->rules('integer')->help('默认：不限量');
        }
        if ($this->mode == Builder::MODE_EDIT) {
            $form->display('type', '类型')->setWidth(2)->with(function ($value) {
                return Coupon::$couponTypeMap[$value];
            });
            $form->display('discount', '折扣')->setWidth(2)->default(0.00);
            $form->display('reduction', '满减($)')->setWidth(2)->default(0.00);
            $form->display('threshold', '消费金额阈值($)')->setWidth(2)->default(0.00);

            $form->display('is_limited', '是否限量')->setWidth(2)->with(function ($value) {
                return $value ? '是' : '否';
            });
            $coupon = Coupon::find($this->coupon_id);
            if ($coupon->is_limited) {
                $form->number('number', '数量')->default(null)->rules('integer');
            } else {
                $form->display('number', '数量')->setWidth(2)->with(function ($value) {
                    return is_null($value) ? '不限量' : $value;
                });
            }
        }

        /*$states = [
            'on' => ['value' => 1, 'text' => '是', 'color' => 'success'],
            'off' => ['value' => 0, 'text' => '否', 'color' => 'danger'],
        ];
        $form->switch('is_limited', '是否限量')->states($states)->default(0);*/

        $form->number('allowance', '单人领取限额')->default(1)->rules('integer|min:1');
        $form->checkbox('supported_product_types', '支持商品类型')->options(Product::$productTypeMap);
        $form->select('scenario', '用户领取场景')->options(Coupon::$couponScenarioMap)->default('register');
        $form->number('sort', '排序值')->default(0)->rules('integer|min:0');

        // $form->datetime('started_at', 'Started at')->default(date('Y-m-d H:i:s'));
        // $form->datetime('stopped_at', 'Stopped at')->default(date('Y-m-d H:i:s'));
        $form->datetimeRange('started_at', 'stopped_at', '限时时段');

        $form->ignore(['_from_']);

        // 定义事件回调，当模型即将保存时会触发这个回调
        $form->saving(function (Form $form) {
            //
        });

        $form->saved(function (Form $form) {
            $data = [];
            if (request()->input('type') == Coupon::COUPON_TYPE_DISCOUNT) {
                $data['reduction'] = 0.00;
            }
            if (request()->input('type') == Coupon::COUPON_TYPE_REDUCTION) {
                $data['discount'] = 0.00;
            }
            if (request()->input('is_limited') == 'off') {
                $data['number'] = null;
            }
            $form->model()->update($data);
        });

        return $form;
    }
}
