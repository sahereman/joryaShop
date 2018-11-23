<?php

namespace App\Admin\Extensions\Ajax;

use Encore\Admin\Admin;

class Ajax_Delete
{
    protected $url;


    public function __construct($url)
    {
        $this->url = $url;
    }

    protected function script()
    {

        $deleteConfirm = trans('admin.delete_confirm');
        $confirm = trans('admin.confirm');
        $cancel = trans('admin.cancel');

        return <<<SCRIPT

$('.grid-delete-row').unbind('click').click(function() {

    var data_url = $(this).data('url');

    swal({
      title: "$deleteConfirm",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#DD6B55",
      confirmButtonText: "$confirm",
      closeOnConfirm: false,
      cancelButtonText: "$cancel"
    },
    function(){
        $.ajax({
            method: 'post',
            url: data_url,
            dataType:"json",   //返回格式为json
            data: {
                _token:LA.token,
                _method: 'DELETE'
            },
            success: function (data) {
                $.pjax.reload('#pjax-container');

                swal(data.messages, '', 'success');
            },
            error: function (data) {
                if(data.status == 422)
                {
                   swal(data.responseJSON.exception.message, '', 'error'); 
                }
                else
                {
                    swal('系统内部错误', '', 'error'); 
                }
                
            }
        });
    });
});

SCRIPT;
    }

    protected function render()
    {
        Admin::script($this->script());

        return "<a class='btn btn-xs btn-danger grid-delete-row' data-url='{$this->url}' style='margin-right: 8px'>删除</a>";
    }

    public function __toString()
    {
        return $this->render();
    }
}