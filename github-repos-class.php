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