<?php
/*
 * Add extra elements in frontend
 */

/**
 * Change template chain to use template of parent category if exists
 */
add_filter( 'category_template', 'new_subcategory_hierarchy' );
function new_subcategory_hierarchy() {
    $category = get_queried_object();

    $parent_id = $category->category_parent;

    $templates = array();

    if ( $parent_id == 0 ) {
        // Use default values from get_category_template()
        $templates[] = "category-{$category->slug}.php";
        $templates[] = "category-{$category->term_id}.php";
        $templates[] = 'category.php';
    } else {
        // Create replacement $templates array
        $parent = get_category( $parent_id );

        // Current first
        $templates[] = "category-{$category->slug}.php";
        $templates[] = "category-{$category->term_id}.php";

        // Parent second
        $templates[] = "category-{$parent->slug}.php";
        $templates[] = "category-{$parent->term_id}.php";
        $templates[] = 'category.php';
    }
    return locate_template( $templates );
}

/**
 * Add account-related navigation links
 */
add_filter('wp_nav_menu_items', 'add_login_logout_link', 10, 2);
function add_login_logout_link($items, $args) {
    $url = is_user_logged_in() ? get_site_url(null, '/user/') : get_site_url(null, '/login/') ;
    $items .= '
		<li>
            <a href="'.$url.'">
			<div class="btn">
				<div class="red-btn">
					<div class="button2">Mon compte</div>
				</div>
			</div>
        </a>
		</li>';
    return $items;
}

/**
 * Show labels for ACF-defined dropdowns.
 * (used in content-fiche.php tempalte)
 */
function get_selected_option_label($field_name){
    $field = get_field_object($field_name);
    $value = get_field($field_name);
    $label = $field['choices'][ $value ];
    return $label;
}
function the_selected_option_label($field_name){
    echo get_selected_option_label($field_name);
}

/**
 * Redirect /category/club_xxxx to /club/xxxx 
 */
add_action( 'template_redirect', 'redirect_category_to_club' );
function redirect_category_to_club(){
    if ( is_category() ) {
        $category = get_queried_object();
        if($category->taxonomy=='category' && strpos($category->slug, 'club_')===0 ){
            $club_slug = substr($category->slug, strlen('club_'));
            //die(print_r($category, true));
            $url = site_url( "/club/$club_slug" );
            wp_safe_redirect( $url, 301 );
            exit();
        }
    }
}

/*
 * Fill Club-selection input with existing clubs.
 * (this callback is set in Ultimate members admin settings)
 * DISABLED : club is no longer managed by Ultimate members plugin
 */
/*
function fill_um_select_dropdown_options_with_clubs(){
    // get all clubs
    $query = new WP_Query( array( 'post_type' => 'club' ) );
    $dropdown_options = [];

    $dropdown_options['-1'] = "Sans club de rattachement";
    foreach($query->posts as $post) {
        $dropdown_options["{$post->post_name}"] = $post->post_title;

    }

    return $dropdown_options;
}
*/

/**
 * Redirect users after login.
 * Disabled : overidden by Ultimate Members plugin
 */
//add_filter('login_redirect', 'login_default_page');
//function login_default_page() {
//    $current_user = wp_get_current_user();
//    if( is_user_logged_in() ){
//        $club = um_profile('club');
//        if($club!=null){
//            $url = get_site_url(null, '/club/'.$club);
//        }else{
//            $url = get_site_url(null, '/user/'.$current_user->display_name);
//        }
//        return $url;
//    }
//}


/**
 * Add search form in sidebar (replaced by widget)
 */
//add_filter( 'wp_nav_menu_items','add_account_links', 10, 2 );
//function add_account_links( $items, $args ) {
//	  $items .= '<li class="widget widget_search">' . get_search_form( false ) . '</li>';
//	  $items .= '<li class="widget widget_search">' .do_shortcode('[wi_autosearch_suggest_form]'). '</li>';
//    return $items;
//}

/**
 * Add a default featured image for CTP Fiche (disabled)
 */
//add_filter( 'dfi_thumbnail_id', 'dfi_posttype_fiche', 10, 2 );
//function dfi_posttype_fiche ( $dfi_id, $post_id ) {
//    $post = get_post($post_id);
//    if ( 'fiche' === $post->post_type ) {
//        return $dfi_id; // the original featured  image id
//    }
//    return 0; // invalid image id
//}

//add_filter( 'edit_post_link', '__return_null' );

