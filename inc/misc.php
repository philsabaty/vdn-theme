<?php
/*
 * Misc aspects like translation, shortcodes...
 */

/**
 * Change some text messages
 */
add_filter( 'gettext', 'cyb_filter_gettext', 10, 3 );
function cyb_filter_gettext( $translated, $original, $domain ) {
    if ( $original == "It looks like nothing was found at this location. Maybe try one of the links below or a search?" ) {
        $translated = "Contenu Introuvable. <br/>\n Peut-être que le contenu recherché n'est accessible qu'une fois connecté avec votre compte.";
    }
    if ( $original == "Oops! That page can&rsquo;t be found." ) {
        $translated = "Page introuvable.";
    }
    if ( $translated == "Password" ) {
        $translated = "mot de passe";
    }
    return $translated;
}

/**
 * Hides the some fields in admin pages.
 * @link http://theeventscalendar.com/?p=1051416
 */
add_action( 'admin_head', 'vdn_custom_admin_css' );
function vdn_custom_admin_css() {
    ?>
    <style>
        #preview-action,
        #event_cost.eventtable,
        #tribe_events_event_options,
        #event_tribe_organizer.eventtable{ 
            display: none !important;
        }
    </style>

    <?php
    if ( !vdn_is_admin() ){
    ?>
    <style>
        #groups-permissions {
            visibility: hidden;
        }
    </style>
    <?php
}
}

/* Use post categories in tribe events */
add_filter('tribe_events_register_event_type_args', 'change_event_type_args');
function change_event_type_args ( $args ) {
    $args['taxonomies'][] = 'category';
    return $args;
}

/*
 * Returns int group_id for "ReferentsClub".
 * Group will be created if it doesn't exist.
 */
function get_referentclub_group_id(){
    $group_name = 'ReferentsClubs';
    require_once( ABSPATH . 'wp-includes/pluggable.php' );
    if ( $group = Groups_Group::read_by_name( 'ReferentsClubs' ) ) {
        return $group->group_id;
    }else{
        $group_id = Groups_Group::create(array('name'=>$group_name));
        return $group_id;
    }
}

function is_user_referentclub($user_id=0){
    $user_id = ($user_id==0)?get_current_user_id():$user_id;
    $referentclub_group_id = get_referentclub_group_id();
    return Groups_User_Group::read($user_id , $referentclub_group_id );
}

function make_user_referentclub($user_id=0){
    $user_id = ($user_id==0)?get_current_user_id():$user_id;
    if( ! is_user_referentclub($user_id) ){
        $referentclub_group_id = get_referentclub_group_id();
        Groups_User_Group::create( array( 'user_id' => $user_id, 'group_id' => $referentclub_group_id ) );
    }
}

function unmake_user_referentclub($user_id=0){
    $user_id = ($user_id==0)?get_current_user_id():$user_id;
    if( is_user_referentclub($user_id) ){
        $referentclub_group_id = get_referentclub_group_id();
        Groups_User_Group::delete( $user_id, $referentclub_group_id  );
    }
}

function get_referent_for_club($club_slug){
    $club = vdn_get_club_by_slug($club_slug);
    return ($club!=null)?$club->post_author:null;   
}

function vdn_get_club_by_slug($slug) {
    global $wpdb;
    $clubs = $wpdb->get_results("
            SELECT $wpdb->posts.* 
            FROM $wpdb->posts
            WHERE $wpdb->posts.post_name = '$slug' 
            AND $wpdb->posts.post_status = 'publish' 
            AND $wpdb->posts.post_type = 'club'
        LIMIT 1", OBJECT);
    
    /* 
    // this method is nicer but doesn't work in /category/fiches-thematiques/xxx urls (!!??)
    $clubs = get_posts(array(
        'name'        => $slug,
        'post_type'   => 'club',
        'numberposts' => 1
    ));
    */
    return isset($clubs[0])?$clubs[0]:null;
}

/*
 * Get html for special VDN flags
 */
function get_vdn_special_flags($post){
    $author_id=$post->post_author;
    $output = '';
    if(strtoupper(get_the_author_meta('user_nicename', $author_id))=='BSF'){
        $output .= '<span class="flag_by_BSF" title="Contenu proposé par Bibliothèques Sans Frontières">PAR BSF</span>';
    }
    // add flag if post from BSF
    if(get_the_author_meta('club', $author_id)!=''){
        $output .= '<span class="flag_by_club" title="Contenu proposé par un club VDN">CLUB</span>';
    }
    return $output;
}

/*
 * Add a default static thumbnail for some post types
 */
add_filter( 'post_thumbnail_html', function ( $html, $post_id, $post_thumbnail_id, $size, $attr ) {
    $post = get_post($post_id);
    if ( empty( $html ) ){
        $image_path = '/assets/images/logo_club_400x400.jpg';
        if ( in_array($post->post_type, array('fiche'))){
            $image_path = '/assets/images/logo_fiches_phil_carre.jpg';
        }else{
            
        }
        $html = sprintf(
            '<img src="%s" height="%s" width="%s" />',
            get_stylesheet_directory_uri().$image_path,
            400, 400
        );
    }
    return $html;
}, 20, 5 );

/*
 * Change "Categorie:les actus" to "Le blog des Voyageurs du Numérique"
 */
add_filter( 'get_the_archive_title', function ( $title ) {
    if ( is_category() && single_cat_title( '', false )=='Les actus') {
        $title = "Le blog des Voyageurs du Numérique";
    }
    return $title;
}, 11);


/*
 * Find first thematique category for a post
 */
function get_first_thematique_category($post){
    $parent_category_slug = 'fiches-thematiques';
    $parent_category = get_category_by_slug($parent_category_slug);
    $thematiques_categories = get_categories( array('parent' => $parent_category->term_id));
    foreach($thematiques_categories as $thematiques_category) {
        if(in_category($thematiques_category->slug, $post)){
            //die(print_r($thematiques_category, true));
            return $thematiques_category;
        }
    }
    return null;
}
