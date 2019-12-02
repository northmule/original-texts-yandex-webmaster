<?php
if (!defined('ABSPATH')) {
    exit;
}
$ortextfun = new OrTextFunc;
$ortextfun->IfElseUpdate(); //опции
$ortext_id = get_option('ortext_id');
$ortext_passwd = get_option('ortext_passwd');
$ortext_token = get_option('ortext_token');
$ortext_token_key = get_option('ortext_token_key'); // Токен яндекса
$ortext_token_time = get_option('ortext_token_time'); //Время жизни токена
$temp_off_token = time() + ($ortext_token_time);
$dateoff_token = date('d-m-Y', $temp_off_token); // Дата окончания токена в человеческом виде
$ortext_email = get_option('ortext_email'); //email Для уведомлений
$tek_data = date('d-m-Y'); //Тукущая дата, нужна для проверки
//$adminka_pugin = admin_url() . OrTextBase::URL_PLUGIN_CONTROL; //Админ панель плагина

$ortext_loadsite = get_option('ortext_loadsite'); //Текущий загруженный проект
$ortext_yasent = get_option('ortext_yasent'); // настройка для публикаций по умолчанию

$ortext_posttype = get_option('ortext_posttype'); //Типы постов

$ortext_options = get_option('ortext_options', []);//прочи настройки



$plugins_url = admin_url() . 'options-general.php?page=' . OrTextBase::URL_ADMIN_MENU_PLUGIN; //URL страницы плагина
$dir_plugin_abdolut = plugin_dir_path(__FILE__);

if (!empty($ortext_token_key)) { //Проверка есть ли токен, иначе не показываем информацию о проектах в вкладке
    $optionsprojectout = OrTextFunc::getWebsiteJson();


    echo "<br>Загружен проект: " . $ortext_loadsite;
    ?>

    <form method="post" action="options.php">
        <?php wp_nonce_field('update-options'); ?>

        <table class="form-table">
            <h3>Сайты доступные из Webmaster</h3>
            <tr valign="top">
                <th scope="row">Сайт для работы</th>
                <td>


                    <?php
                    if (!empty($optionsprojectout)) {
                        foreach ($optionsprojectout as $optionsprojectout1) {

                            $name = $optionsprojectout1['unicode_host_url']; //сайт
                            $siteid = $optionsprojectout1['host_id']; // его ID
                            $status = $optionsprojectout1['verified'] == 1 ? 'Прошёл проверку' : 'Не проверен'; // Статус проверки сайта
                            ?>
                            <p><input name="ortext_loadsite" type="radio" value="<?php echo $siteid; ?>" <?php $ortextfun->chekedOptions($ortext_loadsite, $siteid); ?>><?php echo "$name статус - $status id - $siteid"; ?></p><br>
                            <?php
                        }
                    }
                    ?>

                    <span class="description">Выберите сайт из списка. Все тексты публикуемые на сайте, будут попадать в 
                        "<?php echo OrTextBase::NAME_SERVIC_ORIGINAL_TEXT; ?>" именно этого ресурса. После выбора - нажмите кнопку "Сохранить"</span>
                </td>


            </tr>

            <tr valign="top">
                <th scope="row">Публиковать всегда?</th>
                <td>


                    <p><input name="ortext_yasent" type="checkbox" value="1" <?php $ortextfun->chekedOptions($ortext_yasent, 1); ?>></p>
                    <span class="description">Если установленно - записи при обновление и сохранение, всегда будут отправляться в Яндекс. Если 
                        не установленно - вы сможете выбирать нужное действие при публикации записи на вашем сайте</span>
                </td>


            </tr>

            <tr valign="top">
                <th scope="row">Типы записей</th>
                <td>
                    <?php
                    $array_posts = get_post_types('', 'names', 'and');
                    foreach ($array_posts as $v) {
                        ?>

                        <p><input name="ortext_posttype[<?php echo $v; ?>]" type="checkbox" value="<?php echo $v; ?>" <?php
                            if (isset($ortext_posttype[$v])) {
                                checked($ortext_posttype[$v], $v, 1);
                            }
                            ?>><?php echo $v; ?></p>
                            <?php
                        }
                        ?>
                    <span class="description">Выберите типы «записей» при добавление которых будет работать функция отправки в сервис «Оригинальные Тексты Яндекс». По умолчанию всегда активен тип записей «Post». Если вам необходимо что бы была возможность отправлять данные из «Произвольных типов записей» поставте напротив «галочку». Если вы не знаете что такое «Произвольный тип записей» - ни чего не трогайте.</span>
                </td>
            </tr>
            <tr>

            <tr valign="top">
                <th scope="row">Режим работы - через кнопку</th>
                <td>

                    <p><input name="ortext_options[]" type="checkbox" value="button_send_field" <?php
                        checked(in_array('button_send_field', $ortext_options), true, 1);
                        ?>>Рисовать кнопки рядом с input и textarea</p>

                    <span class="description">При помощи кнопок вы сможете отправлять текст из любого поля записи</span>
                </td>
            </tr>
            <tr>


        </table>

        <input type="hidden" name="action" value="update" />
        <input type="hidden" name="page_options" value="ortext_loadsite, ortext_yasent, ortext_posttype,ortext_options" />

        <p class="submit">
            <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
        </p>

    </form>
<?php } elseif (empty($ortext_token_key)) {
    ?>

    Пока у вас нет доступных проектов. Пройдите все шаги до получения Токена от Яндекс

<?php } ?>

