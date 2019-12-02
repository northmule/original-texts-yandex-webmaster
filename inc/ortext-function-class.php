<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Класс с функционалом и обработками
 */
class OrTextFunc {

    const YANDEX_ADD_APLICATION = 'https://oauth.yandex.ru/client/new'; //Урл создания нового приложения Яндекс
    const YANDEX_APP_APLICATION = 'https://oauth.yandex.ru/client/my'; // Урл к выбору прилжоения
    const YANDEX_Callback_FUNCTION = 'https://oauth.yandex.ru/verification_code'; // УРЛ Функции callback для приложения
    const YANDEX_TOKEN_URL = 'https://oauth.yandex.ru/authorize?response_type=code&client_id='; //УРЛ для получения токена
    const YANDEX_WEBMASTER_HOST = 'api.webmaster.yandex.net'; // УРЛ ВебМастера
    const YANDEX_API_REQUEST_TIMEOUT = 10; // Таймаунт запроса
    const YANDEX_MAX_POST_DAY = 100; //Максимальное количество текстов в сутки
    const YANDEX_MIN_SIZE_POST = 500; // Минимальное количество символов  в посте
    const YANDEX_MAX_SIZE_POST = 32000; // Максимальное количество символов в посте

    /**
     * Коды ошибок при запросе токена с описанием
     * @var type 
     */

    public static $arCodeErrorToken = array(
        'authorization_pending' => 'Пользователь еще не ввел код подтверждения.',
        'bad_verification_code' => 'Переданное значение параметра code не является 7-значным числом.',
        'invalid_client' => 'Приложение с указанным идентификатором (параметр client_id) не найдено или заблокировано. Этот код также возвращается, если в параметре client_secret передан неверный пароль приложения.',
        'invalid_grant' => 'Неверный или просроченный код подтверждения.',
        'invalid_request' => 'Неверный формат запроса (один из параметров не указан, указан дважды, или передан не в теле запроса).',
        'invalid_scope' => 'Права приложения изменились после генерации кода подтверждения.',
        'unauthorized_client' => 'Приложение было отклонено при модерации или только ожидает ее.',
        'unsupported_grant_type' => 'Недопустимое значение параметра grant_type.',
        'Basic auth required' => 'Тип авторизации, указанный в заголовке Authorization, отличен от Basic.',
        'Malformed Authorization header' => 'Заголовок Authorization не соответствует формату <client_id>:<client_secret>, или эта строка не закодирована методом base64.',
    );

    /**
     * Коды ошибок при отправке текста в Я
     * @var type 
     */
    public static $arCodeErrorSentText = array(
        '403' => 'ID пользователя, выдавшего токен, отличается от указанного в запросе.',
        '404' => 'Сайт отсутствует в списке сайтов пользователя или на него не подтверждены права.',
        '409' => 'Переданный текст уже был добавлен ранее.',
        '422' => 'Переданный текст слишком короткий или длинный.',
        '429' => 'Превышена квота добавления оригинальных текстов.',
        '201' => 'Текст добавлен в сервис Яндекс',
    );

    /**
     * Условия
     */
    public function IfElseUpdate() {
        $ortext_posttype = get_option('ortext_posttype'); //Типы постов
        if (empty($ortext_posttype)) { //установка опции по умолчанию
            update_option('ortext_posttype', array('post' => 'post'));
        }
        //Расширение журнала
        $jornalarray = get_option('ortext_jornal');
        if (is_array($jornalarray) && !isset($jornalarray[0]['parts'])) {
            update_option('ortext_jornal', array());
        }
    }

    /**
     * Получение от Яндекса Токена
     * 
     * @return string
     */
    public function getYandexToken() {
        $ortext_id = get_option('ortext_id');
        $url = self::YANDEX_TOKEN_URL . $ortext_id;
        return $url;
    }

    /**
     * Получает список сайтов делая запрос в виде JSON
     * @return ассоциативный массив сайтов или false
     */
    public static function getWebsiteJson() {
        $ortext_id = get_option('ortext_id');
        $ortext_passwd = get_option('ortext_passwd');
        $ortext_token = get_option('ortext_token');
        $ortext_token_key = get_option('ortext_token_key'); // Токен яндекса

        $userID = self::getUserId(); //индификатор пользователя
        if (empty($userID)) {
            return false;
        }

        $url = 'https://' . self::YANDEX_WEBMASTER_HOST . '/v3/user/' . $userID . '/hosts/';
        $curlinfo = wp_remote_post(
                $url, array(
            'method' => 'GET',
            'headers' => array('Authorization' => 'OAuth ' . $ortext_token_key, 'content-type' => 'application/json'),
            'timeout' => 7,
            'redirection' => 5,
            'httpversion' => '1.1'
                )
        );
        if (is_wp_error($curlinfo)) { //Проверка переменной на содержание ошибки
            return false;
        } else {
            $response = $curlinfo['response'];
            switch ($response['code']) {
                case'200':
                    $result = json_decode($curlinfo['body'], TRUE);
                    $return = $result;
                    break;
                default:
                    $return = false;
                    break;
            }

            return $return['hosts'];
        }

        return "Ошибка";
    }

    /**
     * Запрос токена у яндекса + время жизни токена
     * @return object     $result->access_token - Токен
     * $result->expires_in - Время жизни токена в секундах
     * 
     */
    public static function getTokenToYandex() {
        $url = 'https://oauth.yandex.ru/token';
        $ortext_id = get_option('ortext_id');
        $ortext_passwd = get_option('ortext_passwd');
        $ortext_code = get_option('ortext_token');

        $postData = array(
            'grant_type' => 'authorization_code',
            'code' => $ortext_code,
            'client_id' => $ortext_id,
            'client_secret' => $ortext_passwd
        );
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_PROTOCOLS, CURLPROTO_HTTP | CURLPROTO_HTTPS);
        curl_setopt($ch, CURLOPT_REDIR_PROTOCOLS, CURLPROTO_HTTP | CURLPROTO_HTTPS);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        $response = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($response);
        if (is_object($response)) {
            return $response;
        }
        return false;
    }

    /**
     * Отправка Текстов в Сервис Оригинальные тексты
     * @param string $strPost Текст для загрузки
     * @return array Многомерный массив с данными об отправки каждой части текста
     */
    public static function sendTextOriginal2($strPost) {
        $ortext_loadsite = get_option('ortext_loadsite'); //Текущий загруженный проект
        $ortext_token_key = get_option('ortext_token_key'); // Токен яндекса

        $arText = self::textChunk($strPost);
        $idYa = '';
        $quota = ''; //Квота на день (осталось)

        $arTmpResult[] = array(
            'code' => 000,
            'id' => $idYa,
            'quota' => $quota,
            'parts' => '',
        );
        $arResult = array();

        foreach ($arText as $parts => $text2) {
            $text = array('content' => $text2);
            $userID = self::getUserId(); //индификатор пользователя
            if (empty($userID)) {
                return false;
            }
            $url = 'https://' . self::YANDEX_WEBMASTER_HOST . '/v3/user/' . $userID . '/hosts/' . $ortext_loadsite . '/original-texts/';
            $curlinfo = wp_remote_post(
                    $url, array(
                'method' => 'POST',
                'headers' => array('Authorization' => 'OAuth ' . $ortext_token_key, 'content-type' => 'application/json'),
                'body' => json_encode($text, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
                'timeout' => 12,
                    //'redirection' => 5,
                    //'httpversion' => '1.1'
                    )
            );
            if (is_wp_error($curlinfo)) { //Проверка переменной на содержание ошибки
                return array($arTmpResult);
            } else {
                $arTmpResult = array();
                $response = $curlinfo['response'];
                $body = json_decode($curlinfo['body'], TRUE);

                if (array_key_exists($response['code'], self::$arCodeErrorSentText)) {
                    if ($response['code'] == '201') {
                        $arTmpResult['code'] = $response['code'];
                        $arTmpResult['id'] = $body['text_id'];
                        $arTmpResult['quota'] = $body['quota_remainder'];
                        $arTmpResult['parts'] = $parts;
                    } else {
                        $arTmpResult['code'] = $response['code'];
                        $arTmpResult['parts'] = $parts;
                    }
                } else {//Не определённая ошибка
                    $arTmpResult['code'] = 777;
                    $arTmpResult['parts'] = $parts;
                }
                if (isset($curlinfo['body'])) {
                    $arTmpResult['ya_response'] = $curlinfo['body'];
                }
            }

            $arResult[$parts] = $arTmpResult;
        }
        return $arResult;
    }

    /**
     * Получение индификатора пользователя от Яндекс или извлечение его из БД
     * @return int Индификатор пользователя Яндекс или false
     * 
     */
    public static function getUserId() {

        $storeUserId = get_option("ortext_user_id");
        if (empty($storeUserId)) {
            $url = 'https://api.webmaster.yandex.net/v3/user/';
            $ortext_loadsite = get_option('ortext_loadsite'); //Текущий загруженный проект
            $ortext_token_key = get_option('ortext_token_key'); // Токен яндекса
            $curlinfo = wp_remote_post(
                    $url, array(
                'method' => 'GET',
                'headers' => array('Authorization' => 'OAuth ' . $ortext_token_key, 'content-type' => 'application/json'),
                'timeout' => 7,
                'redirection' => 5,
                'httpversion' => '1.1'
                    )
            );

            if (is_wp_error($curlinfo)) { //Проверка переменной на содержание ошибки
                return false;
            } else {
                $response = $curlinfo['response'];
                switch ($response['code']) {
                    case'200':
                        $result = json_decode($curlinfo['body'], TRUE);
                        $return = $result['user_id'];
                        break;
                    default:
                        $return = false;
                        break;
                }
                if (!empty($return)) {
                    update_option('ortext_user_id', $return);
                }
                return $return;
            }
        } else {
            return $storeUserId;
        }
    }

    /**
     * Проверка Чекеда
     * @param string $options Опция из базы данных
     * @param string $value Текущее значение для сравнения (например значение из цикла)
     * @return echo checked или пусто
     */
    public function chekedOptions($options, $value) {
        if (!empty($options) or ! empty($value)) {
            if ($options == $value) {
                echo 'checked';
            } elseif ($options !== $value) {
                echo '';
            }
        }
    }

    /**
     * Получает текст очищенный от всякого мусора и
     * режет его на части
     * Разделение большого текста на части
     * @return array Массив текстов
     */
    protected static function textChunk($strText) {
        $valuePost = self::YANDEX_MAX_SIZE_POST - 10;
        $countParts = 0;
        $countAlfa = 0;
        $arResult = array();
        if (mb_strlen($strText) > $valuePost) {
            $countParts = ceil(mb_strlen($strText) / $valuePost);
            for ($i = 1; $i <= $countParts; $i++) {
                if ($i == 1) {
                    $arResult[$i] = mb_substr($strText, $countAlfa, $valuePost);
                    $countAlfa = $valuePost;
                } else {
                    $arResult[$i] = mb_substr($strText, $countAlfa, $valuePost);
                    $countAlfa += $valuePost;
                }
            }
        } else {
            $arResult[1] = $strText;
        }
        $arResult = array_filter($arResult);
        return $arResult;
    }

    /**
     * Функция логирования, для вкладки журнал
     * @param int $idpost ид поста
     * @param string $title Заголовок поста
     * @param string $status Статуст поста
     * @param string $post_type Тип поста
     * @param string $idyandex Ид добавленного текста в яндексе
     * @param string $parts - признак того что это часть большого текста
      @param array $ya_response - Ответ от яндекса
     */
    public static function logJornal($idpost, $title, $status, $post_type, $idyandex = '', $quota = '', $parts = '', $ya_response = array()) {
        $includ_jornal = get_option('ortext_jornal_inc');
        if ($includ_jornal == '1') {

            $ortext_jornal_old = get_option('ortext_jornal');

            $time = current_time('mysql');
            $ortext_jornal_temp = array(
                'time' => $time,
                'idpost' => $idpost,
                'title' => $title,
                'status' => $status,
                'post_type' => $post_type,
                'idyandex' => $idyandex,
                'quota' => $quota,
                'parts' => $parts,
                'ya_response' => $ya_response,
            );
            $ortext_jornal_new = array();
            $ortext_jornal_new = $ortext_jornal_old;
            array_push($ortext_jornal_new, $ortext_jornal_temp);
            update_option('ortext_jornal', $ortext_jornal_new);
        }
    }

    /**
     * Поиск строки по началу и концу
     */
    private static function cutTextStartEnd($text, $start, $end) {
        $posStart = stripos($text, $start);
        if ($posStart === false)
            return false;

        $text = substr($text, $posStart + strlen($start));
        $posEnd = stripos($text, $end);
        if ($posEnd === false)
            return false;

        $result = substr($text, 0, 0 - (strlen($text) - $posEnd));
        return $result;
    }

}
