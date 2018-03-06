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
        $translated = "Contenu Introuvable. L'outil de Recherche ci-contre vous remettra peut-être sur la voie...";
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

    <?php
    if ( !vdn_is_admin() ){
    ?>
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
 * Request google geocoding service
 */
function geocoding_sync($address, $API_KEY){
    $address = urlencode($address);
    $url = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key={$API_KEY}";
    $resp_json = file_get_contents($url);
    $resp = json_decode($resp_json, true);
    if($resp['status']=='OK'){
        // get the important data
        $lati = isset($resp['results'][0]['geometry']['location']['lat']) ? $resp['results'][0]['geometry']['location']['lat'] : "";
        $longi = isset($resp['results'][0]['geometry']['location']['lng']) ? $resp['results'][0]['geometry']['location']['lng'] : "";
        $formatted_address = isset($resp['results'][0]['formatted_address']) ? $resp['results'][0]['formatted_address'] : "";

        return array(
            'lat' => $lati,
            'lng' => $longi,
            'formatted_address' => $formatted_address);
    }
    return false;
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
    $clubs = get_posts(array(
        'name'        => $slug,
        'post_type'   => 'club',
        'numberposts' => 1
    ));
    return isset($clubs[0])?$clubs[0]:null;
}

function get_vdn_tags_html($post){
    $author_id=$post->post_author;
    $output = '';
    if(strtoupper(get_the_author_meta('user_nicename', $author_id))=='BSF'){
        $output .= '<span class="tag_by_BSF" title="Contenu proposé par Bibliothèques Sans Frontières">PAR BSF</span>';
    }
    // add flag if post from BSF
    if(get_the_author_meta('club', $author_id)!=''){
        $output .= '<span class="tag_by_club" title="Contenu proposé par un club VDN">CLUB</span>';
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