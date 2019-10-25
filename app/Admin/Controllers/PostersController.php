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
        // $grid->image('广告图')->image('', 120);
        $grid->image_url('广告图')->image('', 120);
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
        // $show->image('广告图')->image('', 300);
        /*$show->photos('广告图(单图|多图)')->as(function ($photos) {
            $text = '';
            foreach ($photos as $photo) {
                $url = starts_with($photo, ['http://', 'https://']) ? $photo : Storage::disk('public')->url($photo);
                $text .= '<img src="' . $url . '" style="margin:0 12px 12px 0;max-width:120px;max-height:200px" class="img">';
            }

            return $text;
        });*/
        $show->photo_urls('广告图(单图|多图)')->as(function ($photo_urls) {
            $text = '';
            foreach ($photo_urls as $photo_url) {
                $text .= '<img src="' . $photo_url . '" style="margin:0 12px 12px 0;max-width:120px;max-height:200px" class="img">';
            }

            return $text;
        });
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
            'about_lyricalhair_up' => 'About LyricalHair - Up (单图)',
            'about_lyricalhair_down' => 'About LyricalHair - Down (单图)',
            'about_lyricalhair_left' => 'About LyricalHair - Left (单图)',
            'about_lyricalhair_right' => 'About LyricalHair - Right (单图)',
            'why_lyricalhair' => 'Why LyricalHair (多图)',
        ];

        $form = new Form(new Poster);

        // $form->image('image', '广告图')->uniqueName()->move('posters')->rules('required|image');
        $form->multipleImage('photos', '广告图(单图|多图)')
            ->deletable(true)
            ->uniqueName()
            ->removable()
            ->move('posters')
            ->rules('required|image');

        $form->text('name', '名称')->rules('required')->help('名称可随意更改');

        $form->select('slug', '标示位')->options($slugs)->rules(function ($form) {
            return [
                'required',
                Rule::unique('posters', 'slug')->ignore($form->model()->id),
            ];
        });
        /*->help('可使用的标示 : pc_index_new_1 | pc_index_new_2 | pc_index_new_3 | ' .
            'pc_index_2f_1');*/

        $form->text('link', '链接');

        // $form->switch('is_show', '是否显示');

        return $form;
    }
}
