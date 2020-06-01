<?php

/*
  Plugin Name: Original texts Yandex WebMaster
  Plugin URI: http://www.zixn.ru/plagin-originalnye-teksty-yandex.html
  Description: Позволяет добавлять ваши записи в "Оригинальные тексты Yandex Webmaster"
  Version: 1.16
  Author: Djo
  Author URI: https://zixn.ru
 */

/*  Copyright 2020  Djo  (email: izm@zixn.ru)

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

//add_action('init', 'ortext_plugin_init_core_init');

function ortext_plugin_init_core_init() {

    if (!is_admin()) {
       // return false;
    }

    $base_path = WP_PLUGIN_DIR . '/' . dirname(plugin_basename(__FILE__));

    require_once ( $base_path . '/inc/ortext-core-class.php');
    require_once ($base_path . '/inc/ortext-function-class.php'); //Основной функционал плагина
    require_once ($base_path . '/inc/ajax-class.php');
    require_once ($base_path . '/inc/ui-class.php');

    $core = new OrTextBase();

    $core->addActios();
    
    add_action('wp_automatic_post_added',['OrTextBase','sendAutomaticPost']);
    

    if (wp_doing_ajax()) {
        $ortextjs = new OrtextJavaScript();
        $ortextjs->addaction();
    }

    register_deactivation_hook(__FILE__, array($core, 'deactivationPlugin'));

    register_activation_hook(__FILE__, array($core, 'activationPlugin'));
}


ortext_plugin_init_core_init();
