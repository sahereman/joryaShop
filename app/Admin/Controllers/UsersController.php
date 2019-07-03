<?php

namespace App\Admin\Controllers;

use App\Http\Requests\Request;
use App\Models\User;

use App\Notifications\AdminCustomNotification;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Show;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rule;

class UsersController extends Controller
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
            ->header('用户管理')
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
            ->header('用户详情')
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
            ->header('用户编辑')
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
            ->header('用户创建')
            ->body($this->form());
    }

    public function sendMessageShow($id = null, Content $content)
    {
        return $content
            ->header('发送站内信')
            ->body($this->sendMessageForm($id));
    }

    protected function sendMessageForm($id)
    {
        $form = new Form(new User());
        $form->setAction(route('admin.users.send_message.store'));
        $form->tools(function (Form\Tools $tools) {
            $tools->disableList();
            $tools->disableDelete();
            $tools->disableView();
        });

        if ($id == null)
        {
            $form->listbox('user_ids', '选择用户')->options(User::all()->pluck('name', 'id'));
        } else
        {
            $form->listbox('user_ids', '选择用户')->options(User::where('id', $id)->get()->pluck('name', 'id'));
        }


        $form->textarea('title', '标题');
        $form->text('link', '链接');


        return $form;
    }

    public function sendMessageStore(Request $request, Content $content)
    {
        $data = $this->validate($request, [
            'user_ids' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (User::whereIn('id', request()->input($attribute))->count() == 0)
                    {
                        $fail('请选择用户');
                    }
                },
            ],
            'title' => ['required',],
            'link' => ['required'],
        ], [], [
            'user_ids' => '用户',
            'title' => '标题',
            'link' => '链接',
        ]);


        $users = User::whereIn('id', $data['user_ids'])->get();

        $users->each(function ($user) use ($data) {

            $user->notify(new AdminCustomNotification(array(
                'title' => $data['title'],
                'link' => $data['link'],
            )));

        });

        return $content
            ->row("<center><h3>发送站内信成功</h3></center>")
            ->row("<center><a href='" . route('admin.users.index') . "'>返回用户列表</a></center>");
    }

    /**
     * Make a grid builder.
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new User);
        $grid->model()->orderBy('created_at', 'desc'); // 设置初始排序条件

        /*筛选*/
        $grid->filter(function ($filter) {
            $filter->disableIdFilter(); // 去掉默认的id过滤器
            $filter->like('name', '用户名');
        });

        $grid->id('ID')->sortable();
        $grid->avatar('头像')->image('', 40);
        $grid->name('用户名');
        $grid->column('format_phone', '手机号')->display(function () {
            return "+$this->country_code " . $this->phone;
        });
        $grid->email('邮箱');
        $grid->created_at('创建时间')->sortable();

        $grid->column('send_message', '发送消息')->display(function () {
            $buttons = '';
            $buttons .= '<a class="btn btn-xs btn-primary" style="margin-right:8px" href="' . route('admin.orders.show', [$this->id]) . '">邮件</a>';
            $buttons .= '<a class="btn btn-xs btn-primary" style="margin-right:8px" href="' . route('admin.users.send_message.show', ['id' => $this->id]) . '">站内信</a>';
            return $buttons;
        });

        // 不在页面显示 `新建` 按钮，因为我们不需要在后台新建用户
        $grid->disableCreateButton();

        return $grid;
    }

    /**
     * Make a form builder.
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new User);

        $form->tools(function (Form\Tools $tools) {
            // $tools->disableDelete();
        });

        $form->display('id', 'ID');
        $form->image('avatar', '头像')->uniqueName()->move('avatar/' . date('Ym', now()->timestamp))->rules('required|image');
        $form->text('name', '用户名');
        $form->text('email', '邮箱');
        $form->text('gender', '性别');
        $form->text('qq', 'QQ');
        $form->text('wechat', '微信');
        $form->text('facebook', 'Facebook');
        $form->divider();
        $form->display('country_code', '国家|地区码');
        $form->display('phone', '手机号');
        $form->divider();

        $form->display('created_at', '创建时间');
        $form->display('updated_at', '更新时间');

        return $form;
    }

    /**
     * Make a show builder.
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(User::findOrFail($id));

        $show->panel()->tools(function ($tools) {
            $tools->disableDelete();
        });;

        $show->id('ID');
        $show->divider();
        $show->avatar('头像')->image('', 120);

        $show->name('用户名');
        $show->email('邮箱');
        $show->gender('性别');
        $show->qq('QQ');
        $show->wechat('微信');
        $show->facebook('Facebook');
        $show->divider();
        $show->country_code('国家|地区码');
        $show->phone('手机号');
        $show->divider();
        $show->created_at('创建时间');
        $show->updated_at('更新时间');

        return $show;
    }
}
