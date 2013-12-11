<?php

/*
Plugin Name: Soundcite Embed
Plugin URI:
Description: Implements soundcite as an embeddable shortcode within a wordpress post
Version: 1.0
Author: WBUR
Author URI: http://www.wbur.org
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

/* Don't call directly */
if ( !function_exists( 'add_action' ) ) {
    exit;
}

require_once('config.php');

add_action('init', 'soundcite_init');
function soundcite_init() {

    if (!function_exists("add_shortcode"))
        return;

    add_shortcode('soundcite', 'soundcite_shortcode');
}

add_action('init', 'soundcite_shortcode_buttons');
function soundcite_shortcode_buttons() {
    if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
        return;
    }
    if (get_user_option('rich_editing') == 'true') {
        add_filter('mce_external_plugins', 'soundcite_add_plugin');
        add_filter('mce_buttons', 'soundcite_register_button');
    }
}

function soundcite_register_button($buttons) {
    array_push($buttons, "soundcite");
    return $buttons;
}

function soundcite_add_plugin($plugin_array) {
    $plugin_array['soundcite'] = SOUNDCITE_PLUGIN_URL . '/js/plugin.js';
    return $plugin_array;
}

function soundcite_convert_time($str_time = "00:00") {
    sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);
    $time_seconds = isset($hours) ? $hours * 3600 + $minutes * 60 + $seconds : $hours * 60 + $minutes * 60 + $seconds;
    $time_miliseconds = $time_seconds * 1000;
    return $time_miliseconds;
}

function soundcite_shortcode($attr, $content) {

    $libs = "";
    static $instance = 0;
    $instance++;

    $attr = shortcode_atts(array('id' => '', 'start' => '', 'end' => ''), $attr);

    if (!isset($attr['id']) || !isset($attr['start']) || !isset($attr['end']) || empty($content))
        return '';

    if ($instance == 1) {
        $libs = "
        <link href='" . constant( 'SOUNDCITE_CSS' ) . "' rel='stylesheet' type='text/css'>
        <script type='text/javascript' src='" . SOUNDCITE_SOUNDCLOUD_SDK_URL . "'></script>
        <script type='text/javascript' src='" . SOUNDCITE_JAVASCRIPT_URL . "'></script>
        ";
    }

    $id = $attr['id'];
    $start = soundcite_convert_time($attr['start']);
    $end = soundcite_convert_time($attr['end']);

    $html = $libs . "<span class=\"soundcite\" data-id=\"$id\" data-start=\"$start\" data-end=\"$end\">$content</span>";
    return $html;
}