<?php

namespace App\Admin\Controllers;

use App\Models\CountryProvince;
use App\Models\ShipmentTemplate;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class ShipmentTemplatesController extends Controller
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
            ->header('Index')
            ->description('description')
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
        $grid = new Grid(new ShipmentTemplate);

        $grid->actions(function ($actions) {
            $actions->disableView();
        });

        $grid->id('Id');
        $grid->name('Name');
        $grid->sub_name('Sub name');
        $grid->description('Description');

        $grid->from_country()->display(function ($item) {
            return $item['name_en'] . ' - ' . $item['name_zh'];
        });
        $grid->min_days('Min days');
        $grid->max_days('Max days');

        $grid->free_provinces('Free Provinces')->display(function ($data) {
            $str = '';
            $i = 1;
            foreach ($data as $province)
            {
                if ($i == 3 || $province == end($data))
                {
                    $str .= $province['name_en'] . ' ... ' . count($data) . ' provinces';
                    break;
                }
                $str .= $province['name_en'] . ' , ';
                $i++;
            }
            return $str;
        });

        $grid->plans('运费方案')->count()->display(function ($item) {
            $buttons = '';
            $buttons .= '<a class="btn btn-xs btn-primary" style="margin-right:8px" href="' . route('admin.shipment_template_plans.index', ['tid' => $this->id]) . '">运费方案 x ' . $item . '</a>';
            return $buttons;
        });

        return $grid;
    }

    /**
     * Make a form builder.
     * @return Form
     */
    protected function form()
    {
        $country = new CountryProvince();
        $form = new Form(new ShipmentTemplate);
        $form->tools(function (Form\Tools $tools)  {
            $tools->disableView();
        });

        $form->text('name', 'Name');
        $form->text('sub_name', 'Sub name');
        $form->text('description', 'Description');
        $form->select('from_country_id', 'From country')->options($country->country_pluck());
        $form->number('min_days', 'Min days');
        $form->number('max_days', 'Max days');

        $form->listbox('free_provinces', 'Free Provinces')->options($country->province_pluck())->rules('required');;


        return $form;
    }
}
