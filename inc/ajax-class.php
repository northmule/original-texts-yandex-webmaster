<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Класс для работы с JavaScript функциями отправляемыми через скрипты
 */
class OrtextJavaScript {

    /**
     * Конструктор класса
     */
    public function __construct() {
        
    }

    /**
     * Функции подключаемые через add_action
     */
    public function addaction() {
        //Управление галкой ведения журнала
        add_action('wp_ajax_incjornal', array($this, 'ajaxClearChek'));
        //add_action('wp_ajax_nopriv_incjornal', array($this, 'ajaxClearChek'));
        //Включатель сообщений об ошибках
        add_action('wp_ajax_incerrormessage', array($this, 'ajaxChekError'));
        //add_action('wp_ajax_nopriv_incerrormessage', array($this, 'ajaxChekError'));
        //Отправлялка через кнопку в редакторе записи
        add_action('wp_ajax_posttoyandex', array($this, 'ajaxSentYandex'));
        add_action('wp_ajax_posttoyandex_custom', array($this, 'ajaxSentYandexCustom'));
        //Получить токен
        add_action('wp_ajax_getToken', array($this, 'getToken'));
        //Удалить токен
        add_action('wp_ajax_removeToken', array($this, 'removeToken'));
        //Отправка текста через кнопки
        add_action('wp_ajax_sentOriginalText', array($this, 'sentOriginalText'));
    }

    /**
     * Отправка текста по ajax
     * @TODO нужна унификация
     */
    public function sentOriginalText() {
        $arResult = $this->getResult();

        if (empty($_REQUEST['postid'])) {
            $arResult['message'] = 'Не верное значение post_id';
            $arResult['result'] = 0;
            $this->viewJson($arResult);
        }
        if (!wp_verify_nonce($_REQUEST['nonce_key'], OrTextBase::NONCE_KEY)) {
            $arResult['message'] = 'Доступ запрещён';
            $arResult['result'] = 0;
            $this->viewJson($arResult);
        }


        $ortextprol = get_option('ortextprol');
        $array_preg = array();
        $array_replace = array();

        $post_id = abs(filter_var($_REQUEST['postid'], FILTER_SANITIZE_NUMBER_INT));
        $objPost = get_post($post_id);

        $title = $objPost->post_title;

        $textNostrip = strip_tags($objPost->post_content);
        $text = htmlspecialchars($textNostrip);
        $text = strip_shortcodes($text);

        $chek1 = get_post_meta($post_id, '_ortext_meta_value_key_reg1', true);
        $chek2 = get_post_meta($post_id, '_ortext_meta_value_key_reg2', true);
        $chek3 = get_post_meta($post_id, '_ortext_meta_value_key_reg3', true);
        $chek4 = get_post_meta($post_id, '_ortext_meta_value_key_reg4', true);

        if ($chek1 == 'on') {
            array_push($array_preg, $ortextprol['reg1']);
            array_push($array_replace, "");
        }
        if ($chek2 == 'on') {
            array_push($array_preg, $ortextprol['reg2']);
            array_push($array_replace, "");
        }
        if ($chek3 == 'on') {
            array_push($array_preg, $ortextprol['reg3']);
            array_push($array_replace, "");
        }
        if ($chek4 == 'on') {
            array_push($array_preg, $ortextprol['reg4']);
            array_push($array_replace, "");
        }

        if (!empty($array_preg)) { //Проверка наличия правил регулярных выражений
            $text = preg_replace($array_preg, $array_replace, $text);
        }

        $arStatusSent = OrTextFunc::sendTextOriginal2($text); //Отправка текста
        $strMessage = '';
        if (is_array($arStatusSent) && !empty($arStatusSent)) {
            foreach ($arStatusSent as $parts => $status_sent) {
                if ($status_sent['code'] == 000) {
                    $post_id = 000;
                    $title = 'Error plugin';
                    $post_type = 'function error';
                    $strMessage .= $title . '<br>';
                } else {
                    $strMessage .= 'Отправил в яндекс часть № - ' . $status_sent['parts'] . ' Статус ' . $status_sent['code'] . '<br>' . '"' . OrTextFunc::$arCodeErrorSentText[$status_sent['code']] . '"';
                }
                OrTextFunc::logJornal($post_id, $title, $status_sent['code'], $post_type, $status_sent['id'], $status_sent['quota'], $status_sent['parts'], $status_sent['ya_response']); //Логируем результаты
            }
            $arResult['message'] = $strMessage;
            update_post_meta($post_id, '_ortext_error', $arStatusSent);
        }


        $this->viewJson($arResult);
    }

    /**
     * Удаление токена
     */
    public function removeToken() {
        $arResult = $this->getResult();
        if (!wp_verify_nonce($_REQUEST['nonce_key'], OrTextBase::NONCE_KEY)) {
            $arResult['message'] = 'Доступ запрещён';
            $arResult['result'] = 0;
            $this->viewJson($arResult);
        }
        update_option('ortext_token_key', '');
        update_option('ortext_token_time', '');
        update_option('ortext_token', '');
        update_option('ortext_user_id', '');
        $arResult['message'] = 'Токен успешно удалён. Для возобнавления работы плагина и отправки текстов в сервис. Пройдите процедуру получения токена заново!';
        $this->viewJson($arResult);
    }

    /**
     * Запрос токена
     */
    public function getToken() {
        $arResult = $this->getResult();
        if (!wp_verify_nonce($_REQUEST['nonce_key'], OrTextBase::NONCE_KEY)) {
            $arResult['message'] = 'Доступ запрещён';
            $arResult['result'] = 0;
            $this->viewJson($arResult);
        }
        $objToken = OrTextFunc::getTokenToYandex();
        if (empty($objToken)) {
            $arResult['result'] = 0;
            $arResult['message'] = 'Ошибка получения токена. Сервер вернул пустой результат';
        } elseif (!empty($objToken->access_token) && !empty($objToken->expires_in)) {
            $arResult['result'] = 1;
            $arResult['message'] = 'Токен получен! Теперь вы можете настроить сайт для отправки текстов';
            update_option('ortext_token_key', $objToken->access_token);
            update_option('ortext_token_time', (time() + intval($objToken->expires_in)));
        } else {
            $arResult['result'] = 0;
            if (isset(OrTextFunc::$arCodeErrorToken[$objToken->error])) {
                $error_description = OrTextFunc::$arCodeErrorToken[$objToken->error];
            } else {
                $error_description = $objToken->error_description;
            }
            $arResult['message'] = 'Ошибка получения токена. Сервер ответил, но не предоставил токен. Описание ошибки: "' . $error_description . '"';
        }
        $this->viewJson($arResult);
    }

    /**
     * Обаботка приходящих данных о галочке Журнала
     * Установка чекбокса
     */
    public function ajaxClearChek() {
        $arResult = $this->getResult();
        if (!wp_verify_nonce($_REQUEST['nonce_key'], OrTextBase::NONCE_KEY)) {
            $arResult['message'] = 'Доступ запрещён';
            $arResult['result'] = 0;
            $this->viewJson($arResult);
        }
        $text = $_POST['text'];
        if ($text === 'checked') {
            $opt = 1;
        } else {
            $opt = 0;
        }
        $arResult['message'] = 'Настройки сохранены';
        update_option('ortext_jornal_inc', $opt);
        $this->viewJson($arResult);
    }

    /**
     * Обаботка приходящих данных о галочке ведения сообщений ошибок
     * Установка чекбокса
     */
    public function ajaxChekError() {
        $arResult = $this->getResult();
        if (!wp_verify_nonce($_REQUEST['nonce_key'], OrTextBase::NONCE_KEY)) {
            $arResult['message'] = 'Доступ запрещён';
            $arResult['result'] = 0;
            $this->viewJson($arResult);
        }
        $text = $_POST['text'];
        if ($text === 'checked') {
            $opt = 1;
        } else {
            $opt = 0;
        }
        $arResult['message'] = 'Настройки сохранены';
        update_option('ortext_error_inc', $opt);
        $this->viewJson($arResult);
    }

    /**
     * Принимает вызов кнопки с редактора, для повторной отправки текста в яндекс
     */
    public function ajaxSentYandex() {
        $arResult = $this->getResult();
        if (!wp_verify_nonce($_REQUEST['nonce_key'], OrTextBase::NONCE_KEY)) {
            $arResult['message'] = 'Доступ запрещён';
            $arResult['result'] = 0;
            $this->viewJson($arResult);
        }
        $post_id = $_POST['text'];
        $coreclass = new OrTextBase();
        $arStatusSent = $coreclass->metaboxSentYandex($post_id, 'ajaxsent');
        foreach ($arStatusSent as $inf) {
            if ($inf['code'] == '201' || $inf['code'] == '409') {
                update_post_meta($post_id, '_ortext_error', $arStatusSent);
                $arResult['result'] = 1;
                $arResult['message'] = 'Текст отправлен в Яндекс';
            } else {
                $arResult['result'] = 0;
                $arResult['message'] = 'Ошибка отправки текста';
            }
        }
        $this->viewJson($arResult);
    }

    /**
     * Принимает вызов кнопки с редактора, для повторной отправки текста в яндекс
     */
    public function ajaxSentYandexCustom() {
        $arResult = $this->getResult();
        $content = $_POST['text'];
        $arStatusSent = OrTextFunc::sendTextOriginal2($content);

        if (is_array($arStatusSent)) {
            foreach ($arStatusSent as $parts => $status_sent) {
                if ($status_sent['code'] == 000) {
                    $post_id = 000;
                    $title = 'Error plugin';
                    $post_type = 'function error';
                }
                OrTextFunc::logJornal(0,
                        'Отправка кнопкой',
                        $status_sent['code'],
                        'custom field',
                        ( isset($status_sent['id']) ? $status_sent['id'] : 0),
                        ( isset($status_sent['quota']) ? $status_sent['quota'] : 0),
                        ( isset($status_sent['parts']) ? $status_sent['parts'] : 0),
                        ( isset($status_sent['ya_response']) ? $status_sent['ya_response'] : 0)
                ); //Логируем результаты
            }
        }

        foreach ($arStatusSent as $inf) {
            if ($inf['code'] == '201' || $inf['code'] == '409') {
                $arResult['result'] = 1;
                $arResult['message'] = 'Текст отправлен в Яндекс';
            } else {
                $arResult['result'] = 0;
                $arResult['message'] = 'Ошибка отправки текста';
            }
        }



        $this->viewJson($arResult);
    }

    /**
     * Вернёт базовый массив ответа от ajax
     * @return array
     */
    protected function getResult() {
        return array(
            'success' => 1,
            'result' => 1,
            'message' => '',
            'error' => '',
            'params' => array(),
        );
    }

    /**
     * Вывод json ответа и завершение скрипта
     * @param type $arResult
     */
    protected function viewJson($arResult) {
        ob_end_clean();
        header('Content-type: application/json; charset=UTF-8');
        echo json_encode($arResult, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        die();
    }

}
