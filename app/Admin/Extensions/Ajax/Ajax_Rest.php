<?php

namespace App\Admin\Extensions;

use Encore\Admin\Admin;

class Ajax_Rest
{
    protected $url;
    protected $fields = [];
    protected $method = 'POST';
    protected $button = 'Submit';

    public function __construct($url, $fields = [], $method = 'POST', $button = 'Submit')
    {
        $this->url = $url;
        $this->fields = $fields;
        $this->method = $method;
        $this->button = $button;
    }

    protected function script()
    {
        $submitConfirm = $this->button . '?';
        $confirm = trans('admin.confirm');
        $cancel = trans('admin.cancel');

        return <<<SCRIPT

$('.grid-ajax-rest-row').unbind('click').click(function() {
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
        $.ajax({
            method: '{$this->method}',
            url: '{$this->url}',
            dataType:"json", // 返回格式为json
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
        $s_text = "<a class='btn btn-sm btn-primary grid-ajax-rest-row' style='margin-right: 10px' ";
        foreach ($this->fields as $key => $field) {
            $s_text .= "data-{$key}='{$field}'";
        }
        $e_text = "><i class='fa fa-hand-pointer-o'></i> {$this->button}</a>";

        return $s_text . $e_text;
    }

    public function __toString()
    {
        return $this->render();
    }
}
