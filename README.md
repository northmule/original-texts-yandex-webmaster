# original-texts-yandex-webmaster
Original texts Yandex WebMaster


Плагин WordPress который позволяет отправлять ваши записи в сервис Яндекс Оригинальные тексты.
Официально плагин опубликован в репозитарии WordPress - [ссылка](https://wordpress.org/plugins/original-texts-yandex-webmaster/)

**Actions**
- coderun_action_original_text_after_body_send
Вызывается после отправки всех частей текста в сервис яндекс и принимает 4-е параметра
~~~~
Пример вызова:
add_action('coderun_action_original_text_after_body_send',10,4)
~~~~
**Filters**
- coderun_filter_original_text_body_text
Вызывается для очистки текста перед отправкой и разбивкой на части
