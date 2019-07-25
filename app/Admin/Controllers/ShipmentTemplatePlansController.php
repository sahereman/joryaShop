<?php

namespace App\Admin\Controllers;

use App\Models\CountryProvince;
use App\Models\ShipmentTemplate;
use App\Models\ShipmentTemplatePlan;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class ShipmentTemplatePlansController extends Controller
{
    use HasResourceActions;


    public $id = null;
    public $template_id = null;

    /**
     * Index interface.
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        $template = ShipmentTemplate::find(request()->input('tid'));

        if (!$template)
        {
            return redirect()->back();
        }

        return $content
            ->header($template->name)
            ->description("From Country : " . $template->from_country->name)
            ->body($this->grid($template));
    }

    /**
     * Edit interface.
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        $this->id = $id;
        $this->template_id = ShipmentTemplatePlan::find($id)->shipment_template_id;
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
        $this->template_id = request()->input('tid');
        return $content
            ->header('Create')
            ->description('description')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     * @return Grid
     */
    protected function grid($template)
    {
        $grid = new Grid(new ShipmentTemplatePlan);
        $grid->model()->where('shipment_template_id', $template->id);

        $grid->disableCreateButton();
        $grid->disableFilter();
        $grid->actions(function ($actions) {
            $actions->disableView();
        });

        $grid->tools(function (Grid\Tools $tools) {
            $tools->append('<div class="btn-group pull-right" style="margin-right: 10px">
                                <a href="' . route('admin.shipment_template_plans.create', ['tid' => request()->input('tid')]) . '" class="btn btn-sm btn-success">
                                    <i class="fa fa-save"></i>&nbsp;&nbsp;新增
                                </a>
                            </div>');

            $tools->append('<div class="btn-group pull-right" style="margin-right: 10px">
                                <a href="' . route('admin.shipment_templates.index') . '" class="btn btn-sm btn-default" >
                                <i class="fa fa-backward"></i>&nbsp;返回</a>
                            </div>');
        });


        $grid->country_provinces('To Provinces')->display(function ($data) {
            $str = '';
            $i = 1;
            foreach ($data as $province)
            {
                if ($i == 8 || $province == end($data))
                {
                    $str .= $province['name_en'] . ' ... ' . count($data) . ' provinces';
                    break;
                }
                $str .= $province['name_en'] . ' , ';
                $i++;
            }
            return $str;
        });

        $grid->base_unit('首件(/包)以内');
        $grid->base_price('首件(/包)以内费用');
        $grid->join_price('续件(/包)费用');


        return $grid;
    }

    /**
     * Make a form builder.
     * @return Form
     */
    protected function form()
    {
        $ctl = $this;
        $country = new CountryProvince();
        $form = new Form(new ShipmentTemplatePlan);

        $form->tools(function (Form\Tools $tools) use($ctl) {
            $tools->disableDelete();
            $tools->disableList();
            $tools->disableView();

            $tools->append('<div class="btn-group pull-right" style="margin-right: 5px">
                                <a href="' . route('admin.shipment_template_plans.index', ['tid' => $ctl->template_id]) . '" class="btn btn-sm btn-default">
                                <i class="fa fa-backward"></i>&nbsp;返回</a>
                            </div>');

        });

        $form->hidden('shipment_template_id')->default(request()->input('tid'));
        $form->listbox('country_provinces', 'To Provinces')->options($country->province_pluck($this->template_id, $this->id))->rules('required');;

        $form->text('base_unit', '首件(/包)以内')->setWidth(2)->default(1);
        $form->currency('base_price', '首件(/包)以内费用')->symbol('$')->default(1.00);
        $form->currency('join_price', '续件(/包)费用')->symbol('$')->default(1.00);

        return $form;
    }
}
