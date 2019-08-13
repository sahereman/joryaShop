<?php

namespace App\Admin\Controllers;

use App\Models\Article;
use App\Http\Controllers\Controller;
use App\Models\ArticleCategory;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Validation\Rule;

class ArticlesController extends Controller
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
            ->header('文章管理')
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
            ->header('文章管理')
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
            ->header('文章管理')
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
            ->header('文章管理')
            ->description('新增')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Article);
        $grid->disableFilter();

        $grid->id('ID');
        $grid->category()->name_en('分类');

        $grid->name('名称')->display(function ($data) {
            return '<a target="_blank" href="' . route('seo_url', $this->slug) . '">' . $data . '</a>';
        });;
        $grid->slug('标示位');
        $grid->created_at('创建时间');
        $grid->updated_at('更新时间');

        return $grid;
    }

    /**
     * Make a show builder.
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Article::findOrFail($id));

        $show->id('ID');

        $show->category('分类', function ($category) {
            /*禁用*/
            $category->panel()->tools(function ($tools) {
                $tools->disableList();
                $tools->disableEdit();
                $tools->disableDelete();
            });

            /*属性*/
            // $category->name_zh('名称(中文)');
            $category->name_en('名称(英文)');
        });


        $show->name('名称');
        $show->slug('标示位');
        // $show->content_zh('内容(中文)');
        $show->divider();
        $show->content_en('内容(英文)');

        /* 2019-04-09 for SEO */
        $show->divider();
        $show->seo_title('SEO - 标题');
        $show->seo_keywords('SEO - 关键字');
        $show->seo_description('SEO - 描述');
        $show->divider();
        /* 2019-04-09 for SEO */

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
        /*for select-options*/
        /*$slugs = [
            'about' => '关于我们',
            'company_introduction' => '公司简介',
            'products_features' => '产品特色',
            'contact_us' => '联系我们',
            'helper' => '使用帮助',
            'guide' => '新手指南',
            'problem' => '常见问题',
            'user_protocol' => '用户协议',
            'refunding_service' => '售后服务',
            'refunding_consultancy' => '售后咨询',
            'refunding_policy' => '退货政策',
            'refunding_procedure' => '退货办理',
            'stock_order' => 'Stock Order',
            'custom_order' => 'Custom Order',
            'duplicate' => 'Duplicate',
            'repair' => 'Repair',
        ];*/

        /*$articles = Article::all();
        $articles->map(function ($article, $key) use (&$slugs) {
            $slug = $article->slug;
            if (isset($slugs[$slug])) {
                unset($slugs[$slug]);
            }
        });
        reset($slugs);*/

        $form = new Form(new Article);

        $form->select('category_id', '分类')->options(ArticleCategory::selectOptions())->rules('nullable|exists:article_categories,id');
        $form->text('name', '名称');

        $form->text('slug', '标示位')->rules(function ($form) {
            return [
                'required',
                Rule::unique('articles', 'slug')->ignore($form->model()->id),
            ];
        });

        /* 2019-04-09 for SEO */
        $form->text('seo_title', 'SEO - 标题');
        $form->text('seo_keywords', 'SEO - 关键字');
        $form->text('seo_description', 'SEO - 描述');
        /* 2019-04-09 for SEO */

        /*for select-options*/
        /*$form->select('slug', '标示位')->options($slugs)->rules(function ($form) {
            return [
                'required',
                Rule::unique('articles', 'slug')->ignore($form->model()->id),
            ];
        });*/
        /*->help(
            '可使用的标示 : ' .
            'about | company_introduction | products_features | contact_us | helper | guide | problem | user_protocol | refunding_service | refunding_consultancy | refunding_policy | refunding_procedure | stock_order | custom_order | duplicate | repair'
        );*/

        // $form->editor('content_zh', '内容(中文)');
        $form->hidden('content_zh', '内容(中文)')->default('lyrical');
        $form->editor('content_en', '内容(英文)');

        return $form;
    }
}
