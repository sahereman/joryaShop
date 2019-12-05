// for admin/products

var is_base_size_optional = true,
    is_hair_colour_optional = true,
    is_hair_density_optional = true;

var product_is_base_size_optional_element = $("input.is_base_size_optional[type='hidden'][name='is_base_size_optional']"),
    product_is_hair_colour_optional_element = $("input.is_hair_colour_optional[type='hidden'][name='is_hair_colour_optional']"),
    product_is_hair_density_optional_element = $("input.is_hair_density_optional[type='hidden'][name='is_hair_density_optional']");

var sku_box = $("div#has-many-skus");

// $(function(){});
// $(document).ready(function () {});
// $(window).load(function () {}): // deprecated
$(document).ready(function () {
    sku_box.append("<div id='sku_template_tmp' style='display:none;'><div>");
    var sku_forms = sku_box.find("div.has-many-skus-forms div.has-many-skus-form.fields-group"),
        sku_template_tmp = sku_box.find("div#sku_template_tmp"),
        sku_template = sku_box.find("template.skus-tpl");

    // SKU-base-size
    if (product_is_base_size_optional_element) {
        // initialization
        is_base_size_optional = (product_is_base_size_optional_element.val() == 'on');
        if (!is_base_size_optional) {
            if (sku_forms = sku_box.find("div.has-many-skus-forms div.has-many-skus-form.fields-group")) {
                sku_forms.each(function (index, element) {
                    $(this).find("label[for='base_size_en']").parent().css('display', 'none');
                    $(this).find("label[for='base_size_zh']").parent().css('display', 'none');
                });
            }
            if (sku_template_tmp && sku_template) {
                sku_template_tmp.html(sku_template.html());
                sku_template_tmp.find("label[for='base_size_en']").parent().css('display', 'none');
                sku_template_tmp.find("label[for='base_size_zh']").parent().css('display', 'none');
                sku_template.html(sku_template_tmp.html());
                sku_template_tmp.html('');
            }
        }

        // event-binding
        product_is_base_size_optional_element.bind('change', function () {
            is_base_size_optional = (product_is_base_size_optional_element.val() == 'on');
            if (sku_forms = sku_box.find("div.has-many-skus-forms div.has-many-skus-form.fields-group")) {
                sku_forms.each(function (index, element) {
                    $(this).find("label[for='base_size_en']").parent().css('display', (is_base_size_optional ? 'block' : 'none'));
                    $(this).find("label[for='base_size_zh']").parent().css('display', (is_base_size_optional ? 'block' : 'none'));
                });
            }
            if (sku_template_tmp && sku_template) {
                sku_template_tmp.html(sku_template.html());
                sku_template_tmp.find("label[for='base_size_en']").parent().css('display', (is_base_size_optional ? 'block' : 'none'));
                sku_template_tmp.find("label[for='base_size_zh']").parent().css('display', (is_base_size_optional ? 'block' : 'none'));
                sku_template.html(sku_template_tmp.html());
                sku_template_tmp.html('');
            }
        });
    }

    // SKU-hair-colour
    if (product_is_hair_colour_optional_element) {
        // initialization
        is_hair_colour_optional = (product_is_hair_colour_optional_element.val() == 'on');
        if (!is_hair_colour_optional) {
            if (sku_forms = sku_box.find("div.has-many-skus-forms div.has-many-skus-form.fields-group")) {
                sku_forms.each(function (index, element) {
                    $(this).find("label[for='hair_colour_en']").parent().css('display', 'none');
                    $(this).find("label[for='hair_colour_zh']").parent().css('display', 'none');
                });
            }
            if (sku_template_tmp && sku_template) {
                sku_template_tmp.html(sku_template.html());
                sku_template_tmp.find("label[for='hair_colour_en']").parent().css('display', 'none');
                sku_template_tmp.find("label[for='hair_colour_zh']").parent().css('display', 'none');
                sku_template.html(sku_template_tmp.html());
                sku_template_tmp.html('');
            }
        }

        // event-binding
        product_is_hair_colour_optional_element.bind('change', function () {
            is_hair_colour_optional = (product_is_hair_colour_optional_element.val() == 'on');
            if (sku_forms = sku_box.find("div.has-many-skus-forms div.has-many-skus-form.fields-group")) {
                sku_forms.each(function (index, element) {
                    $(this).find("label[for='hair_colour_en']").parent().css('display', (is_hair_colour_optional ? 'block' : 'none'));
                    $(this).find("label[for='hair_colour_zh']").parent().css('display', (is_hair_colour_optional ? 'block' : 'none'));
                });
            }
            if (sku_template_tmp && sku_template) {
                sku_template_tmp.html(sku_template.html());
                sku_template_tmp.find("label[for='hair_colour_en']").parent().css('display', (is_hair_colour_optional ? 'block' : 'none'));
                sku_template_tmp.find("label[for='hair_colour_zh']").parent().css('display', (is_hair_colour_optional ? 'block' : 'none'));
                sku_template.html(sku_template_tmp.html());
                sku_template_tmp.html('');
            }
        });
    }

    // SKU-hair-density
    if (product_is_hair_density_optional_element) {
        // initialization
        is_hair_density_optional = (product_is_hair_density_optional_element.val() == 'on');
        if (!is_hair_density_optional) {
            if (sku_forms = sku_box.find("div.has-many-skus-forms div.has-many-skus-form.fields-group")) {
                sku_forms.each(function (index, element) {
                    $(this).find("label[for='hair_density_en']").parent().css('display', 'none');
                    $(this).find("label[for='hair_density_zh']").parent().css('display', 'none');
                });
            }
            if (sku_template_tmp && sku_template) {
                sku_template_tmp.html(sku_template.html());
                sku_template_tmp.find("label[for='hair_density_en']").parent().css('display', 'none');
                sku_template_tmp.find("label[for='hair_density_zh']").parent().css('display', 'none');
                sku_template.html(sku_template_tmp.html());
                sku_template_tmp.html('');
            }
        }

        // event-binding
        product_is_hair_density_optional_element.bind('change', function () {
            is_hair_density_optional = (product_is_hair_density_optional_element.val() == 'on');
            if (sku_forms = sku_box.find("div.has-many-skus-forms div.has-many-skus-form.fields-group")) {
                sku_forms.each(function (index, element) {
                    $(this).find("label[for='hair_density_en']").parent().css('display', (is_hair_density_optional ? 'block' : 'none'));
                    $(this).find("label[for='hair_density_zh']").parent().css('display', (is_hair_density_optional ? 'block' : 'none'));
                });
            }
            if (sku_template_tmp && sku_template) {
                sku_template_tmp.html(sku_template.html());
                sku_template_tmp.find("label[for='hair_density_en']").parent().css('display', (is_hair_density_optional ? 'block' : 'none'));
                sku_template_tmp.find("label[for='hair_density_zh']").parent().css('display', (is_hair_density_optional ? 'block' : 'none'));
                sku_template.html(sku_template_tmp.html());
                sku_template_tmp.html('');
            }
        });
    }


    //提交表单前删除 template标签
    $("form").on("submit", function (event) {

        //event.preventDefault();
        //event.stopPropagation();
        sku_template.each(function (index, element) {
            $(this).remove();
        });

    });


    $(".attr_value_photo_delete").on("click", function () {
        var attr_value_id = $(this).parent().parent().parent().find('.values.id').val();
        var close_button = $(this).parent().parent().parent().find('.close.fileinput-remove');
        $.ajax({
            type: "post",
            url: '/admin/attrs/' + attr_value_id + '/delete_value_photo',
            data: {
                key: 0,
                photo: '_file_del_',
                _file_del_: null,
                _token: $('input[name="_token"]').val(),
                _method: 'PUT',
            },
            success: function (data) {
                close_button.trigger('click');
            },
            error: function (error) {
                console.log(error);
            }
        });
    });


});

var file_input_element = $('input.photos[name="photos[]"]');

file_input_element.on('filesorted', function (event, params) {
    // console.log(params);
    // console.log(params.stack);
    // console.log('File sorted ', params.previewId, params.oldIndex, params.newIndex, params.stack);
    var token = $('input[name="_token"]').val(),
        product_photos = $('input.product_photos[name="product_photos"]').val(),
        product_sort_photos_url = $('input.product_sort_photos_url[name="product_sort_photos_url"]').val();
    var sorted_photos = params.stack,
        old_photos = JSON.parse(product_photos),
        new_photos = {};
    var photo_count = sorted_photos.length;
    for (var i = 0; i < photo_count; i++) {
        new_photos[i] = old_photos[sorted_photos[i].key];
    }
    var product_data = {
        _token: token,
        photos: new_photos,
    };
    $.ajax({
        type: "post",
        url: product_sort_photos_url,
        data: product_data,
        success: function (data) {
            console.log("Product Photos Sorted!");
        },
        error: function (error) {
            console.log(error);
        }
    });
});
