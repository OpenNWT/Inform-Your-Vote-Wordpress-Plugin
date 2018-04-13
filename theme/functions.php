<?php


function election_data_the_scripts() {

    wp_enqueue_script( 'switch_theme', get_template_directory_uri() . '/js/switch-theme.js' );
    wp_enqueue_style( 'styles', get_stylesheet_uri(), '4.5.2a');

}

add_action( 'wp_enqueue_scripts', 'election_data_the_scripts' );

?>
