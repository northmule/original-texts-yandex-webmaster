jQuery(document).ready(function () {
    //Установка галочки ведения журнала
    jQuery('[name="ortext_jornal_inc"]').on('click', function () {
        var text = ' ';
        if (jQuery(this).prop("checked") == true) {
            text = 'checked';
        }
        jQuery.ajax({
            type: "POST",
            url: ortext_variable.ajax_url,
            data: {
                action: 'incjornal',
                text: text,
                nonce_key: ortext_variable.nonce
            }
        }).done(function (result) {
            new Noty({
                theme: 'metroui',
                type: (result['result'] ? 'success' : 'error'),
                layout: 'topRight',
                text: result['message']
            }).show();
        }).fail(function () {
            new Noty({
                theme: 'metroui',
                type: 'error',
                layout: 'topRight',
                text: 'Сетевая ошибка запроса'
            }).show();
        });
    });
    //Установка галочки сообщений об ошибках
    jQuery('[name="ortext_error_inc"]').on('click', function () {
        var text = ' ';
        if (jQuery(this).prop("checked") == true) {
            text = 'checked';
        }
        jQuery.ajax({
            type: "POST",
            url: ortext_variable.ajax_url,
            data: {
                action: 'incerrormessage',
                text: text,
                nonce_key: ortext_variable.nonce
            }
        }).done(function (result) {
            new Noty({
                theme: 'metroui',
                type: (result['result'] ? 'success' : 'error'),
                layout: 'topRight',
                text: result['message']
            }).show();

        }).fail(function () {
            new Noty({
                theme: 'metroui',
                type: 'error',
                layout: 'topRight',
                text: 'Сетевая ошибка запроса'
            }).show();
        });
    });

    //Отправка через кнопку в редакторе( в случае ошибки)
    jQuery('#ortext_send_editor').on('click', function () {
        var postID = jQuery('#ortextPostID').val();
        jQuery.ajax({
            type: "POST",
            url: ortext_variable.ajax_url,
            data: {
                action: 'posttoyandex',
                text: postID,
                nonce_key: ortext_variable.nonce
            }
        }).done(function (result) {
            if (result['result']) {
                jQuery('#ortext_messagerror').fadeOut();
            } else {
                jQuery('#returnError').text(result['message']);
            }
        }).fail(function () {

        });
    });

    //Получить токен 
    jQuery('.getToken').on('click', function () {
        jQuery.ajax({
            type: "POST",
            url: ortext_variable.ajax_url,
            data: {
                action: 'getToken',
                nonce_key: ortext_variable.nonce
            }
        }).done(function (result) {
            new Noty({
                theme: 'metroui',
                type: (result['result'] ? 'success' : 'error'),
                layout: 'topRight',
                text: result['message']
            }).show();
        }).fail(function () {
            new Noty({
                theme: 'metroui',
                type: 'error',
                layout: 'topRight',
                text: 'Сетевая ошибка запроса'
            }).show();
        });

        return false;
    });
    //Удалить токен 
    jQuery('.removeToken').on('click', function () {
        jQuery.ajax({
            type: "POST",
            url: ortext_variable.ajax_url,
            data: {
                action: 'removeToken',
                nonce_key: ortext_variable.nonce
            }
        }).done(function (result) {
            new Noty({
                theme: 'metroui',
                type: (result['result'] ? 'success' : 'error'),
                layout: 'topRight',
                text: result['message']
            }).show();
        }).fail(function () {
            new Noty({
                theme: 'metroui',
                type: 'error',
                layout: 'topRight',
                text: 'Сетевая ошибка запроса'
            }).show();
        });

        return false;
    });
    //Отправка текста из журнала
    jQuery('.sentOriginalText').on('click', function () {
        var self = this;
        jQuery(self).children('.view').show();
        jQuery.ajax({
            type: "POST",
            url: ortext_variable.ajax_url,
            data: {
                action: 'sentOriginalText',
                postid: jQuery(self).attr('data-postid'),
                nonce_key: ortext_variable.nonce
            }
        }).done(function (result) {
            new Noty({
                theme: 'metroui',
                type: (result['result'] ? 'success' : 'error'),
                layout: 'topRight',
                text: result['message']
            }).show();
        }).fail(function () {
            new Noty({
                theme: 'metroui',
                type: 'error',
                layout: 'topRight',
                text: 'Сетевая ошибка запроса'
            }).show();
        }).always(function () {
            jQuery(self).children('.view').hide();
        });

        return false;
    });
});

