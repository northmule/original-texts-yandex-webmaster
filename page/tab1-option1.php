<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<?php
//OrTextFunc::getUserId();
?>
<?php
$ortextfun = new OrTextFunc;
$ortextfun->IfElseUpdate(); //опции

if (!function_exists('curl_init')) {
    echo '<h2>Внимание! Возможно у вас на хостинге не установлен CURL, обратитесь к вашему хостинг-провайдеру</h2>';
}
?>

<!-- Modal Шаг 1 -->
<div class="modal fade" id="modalstep1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Переход на сервис Яндекс</h4>
            </div>
            <div class="modal-body modalstep1body">
                <span class="vnimanie">Обазательно прочтите весь текст до конца, затем действуйте!</span><br>
                <span class="atension">
                    В этом шаге вам нужно будет перейти на сервис Яндекс для получения «ID» и «Пароля»</span><br>
                В открывшемся окне, вам потребуется ввести следующие данные:<br>
                <strong>1)Логин и пароль от почты Яндекс</strong>
                <ul>
                    <li>Если у вас еще нет почты Яндекс, вы можете пройти простую регистрацию на сервисе</li>
                </ul>
                <strong>2) После авторизации на сервисе Яндекс, от вас потребуются следующие данные(пример на скриншоте ниже):</strong>
                <ul>
                    <li>Нужно будет заполнить только 4-е поля указанные ниже ("Название", "Описание", "Права", "Callback URL", другие
                        поля оставте пустыми)</li>
                    <li><strong>Название</strong> (Любое осмысленное слово, например навзание сайта или что либо другое)</li>
                    <li><strong>Описание</strong> (Для чего вам ключ)</li>
                    <li><strong>Права</strong> (Нужно выбрать "Яндекс.Вебмастер" и поставить галочку напротив "Добавлять сайты 
                        в сервис Яндекс.Вебмастер и получать информацию о статусе индексирования")</li>
                    <li><strong>Callback URL</strong> (Просто нажмите на ссылку под полем ввода "Подставить URL для разработки")</li>

                </ul>
                <p>
                    <span class="description">Скриншот с примером заполнения формы Яндекс (откроется в новом окне)</span>
                    <a href="<?php echo plugins_url() . '/' . OrTextBase::PATCH_PLUGIN . '/' . 'img/step1_original.png' ?>" target="_blank"><img class="step1_image" src="<?php echo plugins_url() . '/' . OrTextBase::PATCH_PLUGIN . '/' . 'img/step1_original.png' ?>"></a>
                </p>

                <span class="vnimanie">Теперь вы готовы пройти первый шаг по настройке плагина</span> <a href="https://oauth.yandex.ru/client/new" target="_blank">Ссылка для перехода</a>
                <p>
                    <span class="description">Скриншот с примером результата заполения (откроется в новом окне)</span>
                    <a href="<?php echo plugins_url() . '/' . OrTextBase::PATCH_PLUGIN . '/' . 'img/step1_original2.png' ?>" target="_blank"><img class="step1_image" src="<?php echo plugins_url() . '/' . OrTextBase::PATCH_PLUGIN . '/' . 'img/step1_original2.png' ?>"></a>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal Шаг2-->
<div class="modal fade" id="modalstep2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Переход на сервис Яндекс</h4>
            </div>
            <div class="modal-body modalstep1body">
                <span class="vnimanie">Обазательно прочтите весь текст до конца, затем действуйте!</span><br>
                <span class="atension">

                    В этом шаге вам нужно будет перейти на сервис Яндекс для получения «Кода подтверждения».</span><br>
                В открывшемся окне, вам потребуется нажать на кнопку "Разрешить"<br>
                После чего, вы получите код подтверждения, обязательно сохраните его в форме настроек плагина<br>

                <span class="vnimanie">Теперь вы готовы пройти второй шаг по настройке плагина</span> <a href="<?php echo $ortextfun->getYandexToken(); ?>" target="_blank">Ссылка для перехода</a>
                <p>
                    <span class="description">Скриншот с примером, в данном окне нужно нажать "Разрешить" (откроется в новом окне)</span>
                    <a href="<?php echo plugins_url() . '/' . OrTextBase::PATCH_PLUGIN . '/' . 'img/step2_original.png' ?>" target="_blank"><img class="step1_image" src="<?php echo plugins_url() . '/' . OrTextBase::PATCH_PLUGIN . '/' . 'img/step2_original.png' ?>"></a>
                </p>
                <p>
                    <span class="description">Скриншот с примером ТОКЕНА (откроется в новом окне)</span>
                    <a href="<?php echo plugins_url() . '/' . OrTextBase::PATCH_PLUGIN . '/' . 'img/step2_original2.png' ?>" target="_blank"><img class="step1_image" src="<?php echo plugins_url() . '/' . OrTextBase::PATCH_PLUGIN . '/' . 'img/step2_original2.png' ?>"></a>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                <!--                <button type="button" class="btn btn-primary">Сохранить изменения</button>-->
            </div>
        </div>
    </div>
</div>



<?php
$ortext_id = get_option('ortext_id');
$ortext_passwd = get_option('ortext_passwd');
$ortext_token = get_option('ortext_token');
$ortext_token_key = get_option('ortext_token_key'); // Токен яндекса
$ortext_token_time = get_option('ortext_token_time'); //Время жизни токена
if (!empty($ortext_token_time)) {
    $dateoff_token = date('d-m-Y H:i:s', $ortext_token_time); // Дата окончания токена в человеческом виде
} else {
    $dateoff_token = '';
}


$ortext_loadsite = get_option('ortext_loadsite'); //Текущий загруженный проект
$ortext_yasent = get_option('ortext_yasent'); // настройка для публикаций по умолчанию

$ortext_posttype = get_option('ortext_posttype'); //Типы постов



$plugins_url = admin_url() . 'options-general.php?page=' . OrTextBase::URL_ADMIN_MENU_PLUGIN; //URL страницы плагина
$dir_plugin_abdolut = plugin_dir_path(__FILE__);
?>
<div class="<?php echo OrTextBase::$pref; ?>_main_wrap">
    <div id="yandexOrtext" class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <h3><?php _e('Настройка вашего сайта на работу с сервисом ' . OrTextBase::NAME_SERVIC_ORIGINAL_TEXT) ?></h3>
                <a href="#modalstep1" class="btn btn-small btn-danger btn-block" data-toggle="modal">Шаг № 1 - Создайте приложение</a><br>
                <form method="post" action="options.php">
                    <?php wp_nonce_field('update-options'); ?>

                    <table class="form-table">
                        <h3>Введите ниже данные, полученные от сервиса Yandex</h3>
                        <tr valign="top">
                            <th scope="row">ID приложения</th>
                            <td>
                                <input id="toltipid1" data-toggle="tooltip" title="Введите сюда ID полученный после прохождения шага1" type="text" name="ortext_id" value="<?php echo $ortext_id; ?>" />
                                <span class="description">Данное поле нужно заполнить после прохождения <span id="toltipshag1" class="btn btn-mini btn-danger disabled" data-toggle="tooltip" title="Подсказанька">Шага № 1</span></span>

                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">Пароль приложения</th>
                            <td>
                                <input id="toltippasswsd1" data-toggle="tooltip" title="Введите сюда ПАРОЛЬ полученный после прохождения шага1" type="text" name="ortext_passwd" value="<?php echo $ortext_passwd; ?>" />
                                <span class="description">Данное поле нужно заполнить после прохождения <span class="btn btn-mini btn-danger disabled">Шага № 1</span></span>
                            </td>
                        </tr>
                        <?php if (!empty($ortext_passwd)) { ?>
                            <tr valign="top">
                                <th scope="row">Код подтверждения</th>
                                <td>
                                    <input id="toltiptoken1" data-toggle="tooltip" title="Введите сюда КОД ПОДТВЕРЖДЕНИЯ полученный после прохождения шага2" type="text" name="ortext_token" value="<?php echo $ortext_token; ?>" />
                                    <span class="description">Данное поле нужно заполнить после прохождения <span class="btn btn-mini btn-warning disabled">Шага № 2</span></span>
                                </td>
                            </tr>
                        <?php } ?>
                        </tr>
                    </table>

                    <input type="hidden" name="action" value="update" />
                    <input type="hidden" name="page_options" value="ortext_id, ortext_passwd, ortext_token" />
                    <?php if (empty($ortext_passwd)) { ?>
                        <p class="submit">
                            <input type="submit" class="btn btn-large btn-primary" value="<?php _e('Save Changes') ?>" />
                            <span class="description">После прохождения <span class="btn btn-mini btn-danger disabled">Шага № 1</span> сохраните результат</span>

                        </p>
                    <?php } else { ?>

                        <p id="toltitistep2" data-toggle="tooltip" title="Запрошенный вами код подтверждения после нажатия кнопки, будет действовать 10 минут, в течение этого времени нужно дойти до шага №3"> <a href="#modalstep2" class="btn btn-small btn-warning btn-block" data-toggle="modal">Шаг № 2 - Получите Код подтверждения (меняется каждые 10 мин)</a></p><br>
                        <p></p>

                        <p class="submit">
                            <input type="submit" class="btn btn-large btn-primary" value="<?php _e('Save Changes') ?>" />
                            <span class="description">После прохождения <span class="btn btn-mini btn-warning disabled">Шага № 2</span> сохраните результат</span>

                        </p>

                    <?php } ?>
                </form>

                <?php if (!empty($ortext_token)) { ?>
                    <?php if (empty($dateoff_token)) { ?>
                        <p class="step3clic"><a id="toltipstep3" data-toggle="tooltip" title="После нажатия на кнопку в систему запишется ТОКЕН, после чего вы увидите список ваших сайтов в Яндекс.ВебМастер" href="#" class="btn btn-small btn-primary btn-block getToken">Шаг № 3 - Получить токен</a></p><br>
                    <?php } ?> 
                    <?php if (!empty($ortext_token_key)) { ?>
                        <a id="toltipstep_tokenremove" data-toggle="tooltip" title="Для удаления токена из системы, а так же обнуления кода подтверждения - нажмите на кнопку" href="#" class="btn btn-small btn-danger btn-block removeToken">Удалить токен</a>
                    <?php } ?>
                    <p id="toltikodtoken" data-toggle="tooltip" title="Ваш токен, при помощи него плагин будет взаимодействовать с <?php echo OrTextBase::NAME_SERVIC_ORIGINAL_TEXT; ?>" class="btn btn-mini btn-success">Код токена: <?php echo $ortext_token_key; ?></p></br>
                    <p></p>
                    <p id="toltitimetoken" data-toggle="tooltip" title="До этого времени токен будет работать, по окончанию срока, нужно повторить Шаг2 и Шаг3" class="btn btn-mini btn-success">Токен будет работать до (день-месяц-год): <?php echo $dateoff_token; ?></p></br>
                    <?php
                }
                ?>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-xs-12">
                        <h3>Привет пользователь этого замечательного плагина!</h3>
                        <p class="text">Вы разработчик, дизайнер, верстальщик или специалист желающий тиражировать свои программные решения и получать с этого прибыль, тогда для вас есть
                        уникальная возможность повысить свой уровень как специалиста, а так же получить с этого прибыль ...</p>
                        <p><a href="https://coderun.ru/blog/kabinet-prodavca-i-vozmozhnost-prodat-svojj-cifrovojj-tovar/" class="btn btn-primary btn-small" role="button">Подробнее</a></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <hr>
                        <h2>Плагины которые могут быть вам интересны</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 col-md-4">
                        <div class="thumbnail">
                            <img src="https://coderun.ru/wp-content/uploads/2018/04/telegram_notice0.jpg" alt="300x200" style="width: 300px; height: 200px;">
                            <div class="caption">
                                <h3>Woocommerce — уведомления о новых заказах в Telegram</h3>
                                <p>Вы мгновенно получите уведомления в свой Telegram о новом заказе в Woocommerce и увидите базовую информацию о заказе и данные которые оставил клиент.
                                    Плагин очень легковесный для системы и прост в настройке.</p>
                                <p><a href="https://coderun.ru/product/woocommerce-uvedomleniya-o-novykh-zakazakh-v-telegram/" class="btn btn-primary" role="button">Посмотреть</a></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <div class="thumbnail">
                            <img src="https://coderun.ru/wp-content/uploads/2018/05/callback_user_gen.jpg" alt="300x200" style="width: 300px; height: 200px;">
                            <div class="caption">
                                <h3>WordPress плагин обратного звонка с сайта</h3>
                                <p>Простой и удобный плагин для организации заказа обратного звонка с сайта на базе WordPress.
                                    Плагин позволяет разместить кнопку в любой части экрана вашего сайта. Кнопка будет присутствовать на всех страницах сайта.</p>
                                <p><a href="https://coderun.ru/product/wordpress-plagin-obratnogo-zvonka-s-sajjta/" class="btn btn-primary" role="button">Посмотреть</a> </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <div class="thumbnail">
                            <img src="https://coderun.ru/wp-content/uploads/2018/12/wpgrabber_plus_5-e1545833132367.jpg" alt="300x200" style="width: 300px; height: 200px;">
                            <div class="caption">
                                <h3>Плагин парсинга контента WpGrabber Plus для WordPress</h3>
                                <p>Если вам нужен граббер контента, скраппер сайтов и лент rss или универсальный парсер сайтов, то это решение для вас. Плагин WpGrabber Plus является отдельной веткой знаменитого плагина WpGrabber для WordPress от команды разработчиков «JoomlaGrabber».</p>
                                <p><a href="https://coderun.ru/product/plagin-parsinga-kontenta-wpgrabber-plus-dlya-wordpress/" class="btn btn-primary" role="button">Посмотреть</a> </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>