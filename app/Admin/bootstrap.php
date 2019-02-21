<?php

/**
 * Laravel-admin - admin builder based on Laravel.
 * @author z-song <https://github.com/z-song>
 * Bootstraper for Admin.
 * Here you can remove builtin form field:
 * Encore\Admin\Form::forget(['map', 'editor']);
 * Or extend custom form field:
 * Encore\Admin\Form::extend('php', PHPEditor::class);
 * Or require js and css assets:
 * Admin::css('/packages/prettydocs/css/styles.css');
 * Admin::js('/packages/prettydocs/js/main.js');
 */

use App\Admin\Extensions\Form\WangEditor;


app('view')->prependNamespace('admin', resource_path('views/admin'));

Admin::js('vendor/laravel-admin/laravel-admin-ext-chart/Chart.bundle.min.js');

// 2019-01-22
// Admin::js(asset('js/admin/product.js'));
Admin::js('js/admin/product.js');
// 2019-01-22

Encore\Admin\Form::forget(['map', 'editor']);
Encore\Admin\Form::extend('editor', WangEditor::class);
