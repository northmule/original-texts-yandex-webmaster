<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<script type="text/javascript">
    jQuery("document").ready(function () {
        jQuery("#toltipstatus").tooltip({placement: 'bottom'});
    });
</script>	


<h2>Журнал работы плагина</h2>
<?php
$plugins_url = admin_url() . 'options-general.php?page=' . OrTextBase::URL_ADMIN_MENU_PLUGIN . '&tab=jornal'; //URL страницы плагина
$ortextfun = new OrTextFunc();
$jornalarray = get_option('ortext_jornal');
$includ_jornal = get_option('ortext_jornal_inc'); //Включалка журнала
$ortext_error_inc = get_option('ortext_error_inc'); //Включатель сообщений об ошибках в редакторе




if (isset($_GET['clearjornal'])) {
    update_option('ortext_jornal', array());
    ?>
    <script type = "text/javascript">
        document.location.href = "<?php echo $plugins_url; ?>";
    </script>
    <?php
}
?>

<table class="form-table formclientsent">
    <tr valign="top">
        <th scope="row">Вести журнал?</th>
        <td>
            <input type="checkbox" name="ortext_jornal_inc" <?php checked($includ_jornal, '1', 1); ?>/>
            <span class="description">Галка стоит - запись в журнал идёт</span>
        </td>
    </tr>
    <tr valign="top">
        <th scope="row">Показывать сообщения об ошибках в редакторе</th>
        <td>
            <input type="checkbox" name="ortext_error_inc" <?php checked($ortext_error_inc, '1', 1); ?>/>
            <span class="description">Галка стоит - будет сообщение об ошибке</span>
        </td>
    </tr>
</table>

<a class="btn btn-primary" href="<?php echo $plugins_url . '&clearjornal'; ?>">Очистить журнал</a>
<p>Информация по ошибкам</p>
<table class="table table-bordered table-hover table-condensed">
    <thead>
        <tr>
            <th>Код ошибки</th> 
            <th>Расшифровка</th>

        </tr>
    </thead>
    <tbody>
        <?php foreach (OrTextFunc::$arCodeErrorSentText as $code => $value) { ?>
            <tr>
                <th><?php echo $code; ?></th>
                <th><?php echo $value; ?></th>
            </tr>
        <?php } ?>

    </tbody>
</table>

<table class="table table-bordered table-hover table-condensed">
    <thead>
        <tr>
            <th>Дата и время добавления</th> 
            <th>Номер записи (id поста по Wordpress)</th>
            <th>Часть текста (лимит <?php echo OrTextFunc::YANDEX_MAX_SIZE_POST ?> сим.)</th>
            <th>Заголовок записи</th>
            <th>Тип записи</th>
            <th>Статус добавления</th>
            <th>ID текста в Яндекс</th>
            <th>Остаток квоты текстов на сутки</th>
            <th>Не обработанный ответ от Яндекс</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($jornalarray as $jornalprint) { ?>
            <tr>
                <th><?php echo $jornalprint['time']; ?></th>
                <th><?php echo $jornalprint['idpost']; ?></th>
                <th><?php echo $jornalprint['parts']; ?></th>
                <th><?php echo $jornalprint['title']; ?></th>
                <th><?php echo $jornalprint['post_type']; ?></th>
                <th><?php echo $jornalprint['status']; ?></th>
                <th><?php echo $jornalprint['idyandex']; ?></th>
                <th><?php echo $jornalprint['quota']; ?></th>
                <th><?php echo $jornalprint['ya_response']; ?></th>
                <th><?php OrtextUi::buttonSentText($jornalprint['idpost']) ?></th>
            </tr>
        <?php } ?>
    </tbody>



</table>