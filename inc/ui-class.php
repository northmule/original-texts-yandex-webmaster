<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Запчасти для фронта
 */
class OrtextUi {

    /**
     * Отобразит кнопку отправки текста в Я
     * @param int $post_id
     * @param array $arg
     */
    public static function buttonSentText($post_id, $arg = array()) {
        ?>
        <button data-postid="<?php echo $post_id; ?>" class="btn btn-success sentOriginalText"><img src="<?php echo plugins_url() . '/' . OrTextBase::PATCH_PLUGIN . '/img/load.gif'; ?>" class="view"> </i>  Отправить</button>
            <?php
        }

        public static function buttonFieldPost() {
            ?>
        <script>
            jQuery(document).ready(function () {
                jQuery('textarea, input[type="text"]').map(function (i, e) {
                    var name = jQuery(e).attr('name');

                    var button = '<button class="preview sentOriginalTextCustomField">В яндекс</button>';

                    if (typeof name == 'undefined') {
                        // return;
                    } else {
                        jQuery(e).after(button);
                    }

                });

                jQuery(document).on('click', '.sentOriginalTextCustomField', function (e) {
                    e.preventDefault();
                    
                    var self = jQuery(this).prev();
                    
                    var button=this;

                    var content = jQuery(self).val();
                    
                     if (content.length < 1) {
                        return false;
                    }
                    jQuery.ajax({
                        type: "POST",
                        url: ajaxurl,
                        data: {
                            action: 'posttoyandex_custom',
                            text: content,
                        }
                    }).done(function (response) {
                        if (response.result) {
                            jQuery(button).fadeOut();
                        } else {
                            jQuery(button).text('Ошибка!');
                        }
                    }).fail(function () {

                    });



                })
            })
        </script>
        <?php
    }

}
