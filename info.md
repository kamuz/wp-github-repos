# WordPress плагин для отображения последних репозиторией

Сначала добавим новое приложение GitHub *Settings / Developer settings / GitHub Apps / New*. Там где нужно указать URL, пишете текущий адрес вашего сайта - потом это можно будет в любом момент изменить.

Далее стандартный код для старта плагина с виджетом:

*wp-content/plugins/kmz-github-repos/kmz-github-repos.php*

```php
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
```

Создайте файлы стилей и скриптов, добавьте код и проверьте загружаются ли данные файлы, после активации плагина.

*wp-content/plugins/kmz-github-repos/github-repos-class.php*

```php
<?php

class GitHub_Repos_Widget extends WP_Widget {
    /**
     * Setup the widgets name etc.
     */
    public function __construct() {
        parent::__construct(
            'github_repos_widget',
            __( 'GitHub Repos Widget', 'wpgithubrepos' ),
            array( 'description' => __( 'Outputs latest GitHub repos' ) )

        );
    }

    /**
     * Outputs the content of the widget
     */
    public function widget( $args, $instance ){
        echo "Content of Widget";
    }

    /**
     * Outputs the options form on admin
     */
    public function form( $instance ){
        echo "Options of Widget";
    }

    /**
     * Processing widget options on save
     */
    public function update($new_instance, $old_instance){
        
    }
}
```

Активируем плагин, смотрим добавился ли наш виджет и выводятся ли тестовые сообщения в админке в настройках виджета и во фронт-энд после добавления виджета в необходимую область.

Теперь выведем форму настроек виджета:

*wp-content/plugins/kmz-github-repos/github-repos-class.php*

```php
/**
 * Outputs the options form on admin
 */
public function form( $instance ){
    // Get title
    if(isset($instance['title'])){
        $title = $instance['title'];
    } else{
        $title = __('Latest GitHub Repos', 'wpgithubrepos');
    }

    // Get Username
    if(isset($instance['username'])){
        $username = $instance['username'];
    } else{
        $username = __('kamuz', 'wpgithubrepos');
    }

    // Get Count
    if(isset($instance['count'])){
        $count = $instance['count'];
    } else{
        $count = 5;
    }?>

    <p>
        <label for="<?php echo $this->get_field_id('title') ?>"><?php _e('Title', 'wpgithubrepos') ?></label>
        <input type="text" class="widefat" id="<?php echo $this->get_field_id('title') ?>" name="<?php echo $this->get_field_name('title') ?>" value="<?php echo esc_html($title) ?>">
    </p>

    <p>
        <label for="<?php echo $this->get_field_id('username') ?>"><?php _e('Username', 'wpgithubrepos') ?></label>
        <input type="text" class="widefat" id="<?php echo $this->get_field_id('username') ?>" name="<?php echo $this->get_field_name('username') ?>" value="<?php echo esc_html($username) ?>">
    </p>

    <p>
        <label for="<?php echo $this->get_field_id('count') ?>"><?php _e('Count', 'wpgithubrepos') ?></label>
        <input type="text" class="widefat" id="<?php echo $this->get_field_id('count') ?>" name="<?php echo $this->get_field_name('count') ?>" value="<?php echo esc_html($count) ?>">
    </p>

    <?php
}
```