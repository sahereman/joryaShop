<?php

namespace App\Admin\Controllers;

use App\Models\Poster;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Validation\Rule;

class PostersController extends Controller
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
            ->header('广告位管理 ')
            ->description('列表')
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
            ->header('广告位管理')
            ->description('详情')
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
            ->header('广告位管理')
            ->description('编辑')
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
            ->header('广告位管理')
            ->description('新增')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Poster);
        $grid->disableFilter();

        $grid->id('ID');
        $grid->image('广告图')->image('', 120);
        $grid->name('名称');
        $grid->slug('标示');
        $grid->link('链接');

        // $grid->is_show('是否显示')->switch();

        return $grid;
    }

    /**
     * Make a show builder.
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Poster::findOrFail($id));

        $show->id('ID');
        $show->image('广告图')->image('', 300);;
        $show->name('名称');
        $show->slug('标示');
        $show->link('链接');
        $show->created_at('创建时间');
        $show->updated_at('更新时间');

        /*$show->is_show('是否显示')->as(function ($item) {
            return $item ? '<span class="label label-primary">ON</span>' : '<span class="label label-default">OFF</span>';
        });*/

        return $show;
    }

    /**
     * Make a form builder.
     * @return Form
     */
    protected function form()
    {
        $slugs = [
            /*PC*/
            'pc_index_left_top' => 'PC站首页新品 LT 图',
            'pc_index_left_bottom' => 'PC站首页新品 LB 图',
            'pc_index_right' => 'PC站首页新品 R 图',
            'pc_index_floor_2' => 'PC站首页楼层 2楼 图',
            'pc_index_floor_4' => 'PC站首页楼层 4楼 图',
            'pc_index_floor_6' => 'PC站首页楼层 6楼 图',
            /*Mobile*/
            'mobile_index_floor_1' => '手机站首页楼层 1楼 图',
            'mobile_index_floor_2' => '手机站首页楼层 2楼 图',
            'mobile_index_floor_3' => '手机站首页楼层 3楼 图',
            'mobile_index_floor_4' => '手机站首页楼层 4楼 图',
            'mobile_index_floor_5' => '手机站首页楼层 5楼 图',
            'mobile_index_floor_6' => '手机站首页楼层 6楼 图',
        ];

        $form = new Form(new Poster);

        $form->image('image', '广告图')->uniqueName()->move('posters')->rules('required|image');

        $form->text('name', '名称')->rules('required')->help('名称可随意更改');

        $form->select('slug', '标示位')->options($slugs)->rules(function ($form) {
            return [
                'required',
                Rule::unique('posters', 'slug')->ignore($form->model()->id),
            ];
        });
        /*->help('可使用的标示 : pc_index_new_1 | pc_index_new_2 | pc_index_new_3 | ' .
            'pc_index_2f_1');*/

        $form->url('link', '链接');

        // $form->switch('is_show', '是否显示');

        return $form;
    }
}
