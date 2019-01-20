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

Сохраняем параметры виджета при изменений значений виджета:

*wp-content/plugins/kmz-github-repos/github-repos-class.php*

```php
/**
 * Processing widget options on save
 */
public function update($new_instance, $old_instance){
    $instance = array(
        'title' => (!empty($new_instance['title']) ? strip_tags($new_instance['title']) : ''),
        'username' => (!empty($new_instance['username']) ? strip_tags($new_instance['username']) : ''),
        'count' => (!empty($new_instance['count']) ? strip_tags($new_instance['count']) : ''),
    );

    return $instance;
}
```

Выведем заголовок виджета:

*wp-content/plugins/kmz-github-repos/github-repos-class.php*

```php
/**
 * Output the content of the widget
 */
public function widget( $args, $instance ){
    $title = esc_attr($instance['title']);
    $username = esc_attr($instance['username']);
    $count = esc_attr($instance['count']);
    echo $args['before_widget'];

    if ( ! empty( $title ) ) {
        echo $args['before_title'] . $title . $args['after_title'];
    }

    echo $args['after_widget'];
}
```

Вернём список репозиториев в виде массива объектов:

*wp-content/plugins/kmz-github-repos/github-repos-class.php*

```php
/**
 * Output the content of the widget
 */
public function widget( $args, $instance ){
    $title = esc_attr($instance['title']);
    $username = esc_attr($instance['username']);
    $count = esc_attr($instance['count']);
    echo $args['before_widget'];

    if ( ! empty( $title ) ) {
        echo $args['before_title'] . $title . $args['after_title'];
    }

    echo '<pre>';
    var_dump( $this->showRepos($title, $username, $count));
    echo '</pre>';

    echo $args['after_widget'];
}

/**
 * Show GitHub Repos
 */
public function showRepos($title, $username, $count){
    // Docs - https://developer.github.com/v3/repos/
    $url = 'https://api.github.com/users/' . $username . '/repos?sort=created&per_page=' . $count;
    $options = array(
        'http' => array(
            'method' => 'GET',
            'user_agent' => $_SERVER['HTTP_USER_AGENT']
        )
    );
    // Create context and init GET request - http://php.net/manual/en/context.http.php#example-338
    $context = stream_context_create($options);
    // Get content
    $response = file_get_contents($url, false, $context);
    // Convert to array
    $repos = json_decode($response);

    return $repos ;
}
```

Теперь уже обойдём этот массив и выведем список репоизиториев в форматированном виде:

*wp-content/plugins/kmz-github-repos/github-repos-class.php*

```php
public function showRepos($title, $username, $count){
    // Docs - https://developer.github.com/v3/repos/
    $url = 'https://api.github.com/users/' . $username . '/repos?sort=created&per_page=' . $count;
    $options = array(
        'http' => array(
            'method' => 'GET',
            'user_agent' => $_SERVER['HTTP_USER_AGENT']
        )
    );
    // Create context and init GET request - http://php.net/manual/en/context.http.php#example-338
    $context = stream_context_create($options);
    // Get content
    $response = file_get_contents($url, false, $context);
    // Convert to array
    $repos = json_decode($response);

    $output = '<ul id="repos">';
    foreach($repos as $repo){
        $output .= '<li>';
            $output .= '<div class="repo-name">' . $repo->name . '</div>';
            $output .= '<div class="repo-description">' . $repo->description . '</div>';
            $output .= '<a class="repo-view" href="' . $repo->html_url . '" target="_blank">View on GitHub</a>';
        $output .= '</li>';
    }
    $output .= '</ul>';

    return $output;
}
```

Теперь можно добавить немного стилей для красоты:

*wp-content/plugins/kmz-github-repos/css/style.css*

```css
#repos .repo-name{
    font-weight: bold;
    margin-bottom: 5px;
}
#repos .repo-description{
    font-style: italic;
}
#repos .repo-view{
    text-decoration: underline;
    box-shadow: none;
}
```