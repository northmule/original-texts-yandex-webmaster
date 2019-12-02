<?php

/*
  Plugin Name: Original texts Yandex WebMaster
  Plugin URI: http://www.zixn.ru/plagin-originalnye-teksty-yandex.html
  Description: Позволяет добавлять ваши записи в "Оригинальные тексты Yandex Webmaster"
  Version: 1.14
  Author: Djo
  Author URI: http://zixn.ru
 */

/*  Copyright 2019  Djo  (email: izm@zixn.ru)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 * 
 */
if (!defined('ABSPATH')) {
    exit;
}
add_action('plugins_loaded', 'ortext_plugin_init_core');

function ortext_plugin_init_core() {
    require_once (WP_PLUGIN_DIR . '/' . dirname(plugin_basename(__FILE__)) . '/inc/ortext-core-class.php');
    require_once (WP_PLUGIN_DIR . '/' . dirname(plugin_basename(__FILE__)) . '/inc/ortext-function-class.php'); //Основной функционал плагина
    require_once (WP_PLUGIN_DIR . '/' . dirname(plugin_basename(__FILE__)) . '/inc/ajax-class.php');
    require_once (WP_PLUGIN_DIR . '/' . dirname(plugin_basename(__FILE__)) . '/inc/ui-class.php');

    $ortextbase = new OrTextBase();
    $ortextjs = new OrtextJavaScript();
    $ortextjs->addaction();
    register_deactivation_hook(__FILE__, array($ortextbase, 'deactivationPlugin'));
    

//    function general_admin_notice() {
//        global $pagenow;
//        if ($pagenow == 'options-general.php') {
//            echo '<div class="notice notice-success is-dismissible">
//             <p></p>
//         </div>';
//        }
//    }
//
//    add_action('admin_notices', 'general_admin_notice');
}
