<?php

class TF_Services_Elementor_Addon
{
    public function __construct()
    {
        // Hook into Elementor initialization
        add_action('elementor/widgets/widgets_registered', [$this, 'register_tf_services_widget']);
    }


    function register_tf_services_widget($widgets_manager)
    {

        require_once(__DIR__ . '/widgets/tf-services-widget.php');

        $widgets_manager->register(new \TF_Services_Widget());
    }
}

new TF_Services_Elementor_Addon();
