<?php
/*
Plugin Name: KMZ GitHub Repos
Description: Custom widget for display latest GitHub repos
Version: 0.1
Author: Vladimir Kamuz
Author URI: https://wpdev.pp.ua
Plugin URI: https://github.com/kamuz/wp-github-repos
Licence: GPL2
Text Domain: wpgithubrepos
*/

/**
 * Exit if Access Directly
 */
if(!defined('ABSPATH')){
    exit;
}

/**
 * Load Class
 */
require_once(plugin_dir_path(__FILE__) . '/github-repos-class.php');

/**
 * Load Scripts and Styles
 */
function kmz_gr_css_js(){
    wp_enqueue_style('kmz_gr_style', plugin_dir_url(__FILE__) . 'css/style.css');
    wp_enqueue_script('kmz_gr_script', plugin_dir_url(__FILE__) . 'js/script.js', array('jquery'), '0.0.1', true);
}
add_action('wp_enqueue_scripts', 'kmz_gr_css_js');

/**
 * Register widget
 */
function kmz_register_github_repos_widget() {
    register_widget( 'GitHub_Repos_Widget' );
}
add_action('widgets_init', 'kmz_register_github_repos_widget');