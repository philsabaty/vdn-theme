<?php

/**
 * Init theme as a child theme
 */
add_action( 'wp_enqueue_scripts', 'child_enqueue_styles',99);
function child_enqueue_styles() {
    wp_enqueue_script('jquery-ui-autocomplete');
    $parent_style = 'parent-style';
    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    // next line disabled because style.css is automatically loaded by wp 
    //wp_enqueue_style( 'child-style', get_stylesheet_directory_uri().'/style.css', array( $parent_style ), filemtime(get_stylesheet_directory().'/style.css') );
}
if ( get_stylesheet() !== get_template() ) {
    add_filter( 'pre_update_option_theme_mods_' . get_stylesheet(), function ( $value, $old_value ) {
         update_option( 'theme_mods_' . get_template(), $value );
         return $old_value; // prevent update to child theme mods
    }, 10, 2 );
    add_filter( 'pre_option_theme_mods_' . get_stylesheet(), function ( $default ) {
        return get_option( 'theme_mods_' . get_template(), $default );
    } );
}


/*
 * Add extra elements in frontend
 */
require dirname(__FILE__) . '/inc/extra-front-content.php';



/*
 * Misc aspects like translation, shortcodes...
 */
require dirname(__FILE__) . '/inc/misc.php';


