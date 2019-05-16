<?php

namespace App\Admin\Controllers;

use App\Models\Banner;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class BannersController extends Controller
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
            ->header('Banner图管理')
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
            ->header('Banner图管理')
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
            ->header('Banner图管理')
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
            ->header('Banner图管理')
            ->description('新增')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Banner);
        $grid->model()->orderBy('type', 'desc'); // 设置初始排序条件


        $grid->id('ID');
        $grid->image('Banner图')->image('', 120);
        $grid->type('类型')->sortable();
        $grid->sort('排序值')->sortable();
        $grid->created_at('创建时间');

        return $grid;
    }

    /**
     * Make a show builder.
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Banner::findOrFail($id));

        $show->id('ID');
        $show->type('类型');
        $show->image('Banner图')->image('', 300);
        $show->link('链接');
        $show->sort('排序值');
        $show->created_at('创建时间');
        $show->updated_at('更新时间');

        return $show;
    }

    /**
     * Make a form builder.
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Banner);

        $form->select('type', '类型')->options([
            'index' => 'PC站首页',
            'mobile' => 'Mobile站首页'
        ])->rules('required');

        $form->image('image', 'Banner图')->rules('required|image')->help('PC首页尺寸:1920 * 780 , Mobile首页尺寸:960 * 390');

        $form->url('link', '链接');

        $form->number('sort', '排序值');

        //保存前回调
        $form->saving(function (Form $form) {

            switch ($form->input('type')) {
                case 'index' :
                    $form->builder()->field('image')->resize(1920, 780)->uniqueName()->move('banner');
                    break;
                case 'mobile' :
                    $form->builder()->field('image')->resize(960, 390)->uniqueName()->move('banner');
                    break;
                default :
                    $form->builder()->field('image')->uniqueName()->move('banner');
                    break;
            }

        });

        return $form;
    }
}
