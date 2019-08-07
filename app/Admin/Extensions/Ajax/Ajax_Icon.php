<?php

namespace App\Admin\Extensions\Ajax;

use Encore\Admin\Admin;

class Ajax_Icon
{
    protected $url;

    protected $field;

    protected $button;

    public function __construct($url, $field = array(), $icon = 'fa-paper-plane')
    {
        $this->url = $url;
        $this->field = $field;
        $this->icon = $icon;
    }

    protected function script()
    {

        $submitConfirm = trans('admin.submit') . ' ？';
        $confirm = trans('admin.confirm');
        $cancel = trans('admin.cancel');

        return <<<SCRIPT
    
        $('.grid-diy-row').unbind('click').click(function() {
        
            var data_url = $(this).attr('ajax-url');
            var data = $(this).data();
            data._token = LA.token;

            swal({
              title: "$submitConfirm",
              type: "warning",
              showCancelButton: true,
              confirmButtonColor: "#DD6B55",
              confirmButtonText: "$confirm",
              closeOnConfirm: false,
              cancelButtonText: "$cancel"
            },
            function(){
                swal('请等待...', '');
                $.ajax({
                    method: 'POST',
                    url: data_url,
                    dataType:"json",   //返回格式为json
                    data: data,
                    success: function (data) {
                        $.pjax.reload('#pjax-container');
                        
                        if (typeof data === 'object') {
                            swal(data.messages, '');
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
        $s_text = "<a class='grid-diy-row' href='javascript:void(0);' ajax-url='{$this->url}' ";
        foreach ($this->field as $key => $value)
        {
            $s_text .= "data-{$key}='{$value}'";
        }
        $e_text = "><i class='fa {$this->icon}'></i></a>";

        return $s_text . $e_text;
    }

    public function __toString()
    {
        return $this->render();
    }
}