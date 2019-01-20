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
            <input type="text" class="widefat" id="<?php echo $this->get_field_id('title') ?>" name="<?php echo $this->get_field_id('title') ?>" value="<?php echo esc_html($title) ?>">
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('username') ?>"><?php _e('Username', 'wpgithubrepos') ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id('username') ?>" name="<?php echo $this->get_field_id('username') ?>" value="<?php echo esc_html($username) ?>">
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('count') ?>"><?php _e('Count', 'wpgithubrepos') ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id('count') ?>" name="<?php echo $this->get_field_id('count') ?>" value="<?php echo esc_html($count) ?>">
        </p>

        <?php
    }

    /**
     * Processing widget options on save
     */
    public function update($new_instance, $old_instance){
        
    }
}