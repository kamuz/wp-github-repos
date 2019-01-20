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

        echo $this->showRepos($title, $username, $count);

        echo $args['after_widget'];
    }

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

}