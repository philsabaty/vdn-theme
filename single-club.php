<?php
/**
 * Template Name: Club
 */
global $VDN_CONFIG;
get_header(); ?>

<div class="clear"></div>

</header> <!-- / END HOME SECTION  -->

<?php
	zerif_after_header_trigger();
	$zerif_change_to_full_width = get_theme_mod( 'zerif_change_to_full_width' );

?>

<div id="content" class="site-content">

	<div class="container">

		<?php zerif_before_page_content_trigger(); ?>
		<?php
		if ( ( function_exists( 'is_cart' ) && is_cart() ) || ( function_exists( 'is_account_page' ) && is_account_page() ) || ( function_exists( 'is_checkout' ) && is_checkout() ) || ! empty( $zerif_change_to_full_width ) ) {
			echo '<div class="content-left-wrap col-md-12">';
		} else {
			echo '<div class="content-left-wrap col-md-9">';
		}
		?>
		<?php zerif_top_page_content_trigger(); ?>
		<div id="primary" class="content-area">

			<main itemscope itemtype="http://schema.org/WebPageElement" itemprop="mainContentOfPage" id="main" class="site-main">

				<div class="clubContainer">
                    <?php
                    while ( have_posts() ) :
                        the_post();
                        $club_slug = $post->post_name; //assuming $post is set by the_post()
                        $club_code = 'club_'.$club_slug;
                    ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?> >
                        <div class="listpost-content-wrap-full">
                            <div class="list-post-top">
    
                                <header class="entry-header">
                                    <h1 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
                                 </header><!-- .entry-header -->
                                <div class="entry-content">
                                    <div class="row">
                                        <div class="col-lg-6 col-sm-6">
                                            <?php the_post_thumbnail(); ?>
                                        </div>
                                        <div class="col-lg-6 col-sm-6 bloc_d_items info_club">
                                            <h3>Informations</h3>
                                            <?php edit_post_link( "Modifier les infos de mon club", '<br><span class="edit-link">', '</span>' ); ?>
                                            <ul>
                                                <?php
                                                $referent_name = get_the_author_meta('display_name');
                                                $referent_url = get_site_url(null, '/user/'.get_the_author_meta('user_nicename'));
                                                $fields = get_field_objects();
                                                $structure_url = isset($fields['site_web_structure']['value']) ? $fields['site_web_structure']['value']:'';
                                                if($structure_url!='' && (strpos($structure_url, 'http')!==0)){
                                                    $structure_url = 'http://'.$structure_url;
                                                }
                                                $structure_href = ($structure_url!='') ? ('href="'.$structure_url.'"'):'';
                                                /*
                                                foreach( $fields as $field_name => $field ){
                                                    if($field['type']=='text'){
                                                        echo '<li><label>' . $field['label'] . ' :&nbsp;</label>' . $field['value'] . '</li>';
                                                    }
                                                }
                                                */
                                                if( $fields && isset($fields['regularite_des_ateliers']) && $fields['regularite_des_ateliers']!=''){
                                                    ?>
                                                    <li>
                                                        <h4>Ateliers</h4>
                                                        <?php echo $fields['regularite_des_ateliers']['value'];?>
                                                    </li>
                                                    <?php
                                                }
                                                ?>
                                                <li>
                                                    <h4>Structure de rattachement</h4>
                                                    <a <?php echo $structure_href;?>><?php echo $fields['structure']['value'];?></a><br>
                                                    <?php echo $fields['adresse']['value'];?> <br>
                                                    <?php echo $fields['code_postal']['value'];?> <?php echo $fields['ville']['value'];?><br>
                                                    
                                                </li>
                                                <li>
                                                    <h4>Référent</h4>
                                                    <a href="<?php echo $referent_url;?>"><?php echo $referent_name;?></a> <br>
                                                    <?php
                                                    if(is_user_logged_in() && ($fields['contact_du_referent']['value']!='')){
                                                        echo "<a href='mailto:{$fields['contact_du_referent']['value']}' style='color:#404040'>{$fields['contact_du_referent']['value']}</a><br>";
                                                    }
                                                    if(is_user_logged_in() && ($fields['telephone_du_referent']['value']!='')){
                                                        echo "<a href='tel:{$fields['telephone_du_referent']['value']}' style='color:#404040'>{$fields['telephone_du_referent']['value']}</a><br>";
                                                    }
                                                    ?>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-sm-12">
                                            <?php 
                                            if(get_field('presentation')!=''){
                                                echo '<h3><br>Présentation</h3>'.get_field('presentation');
                                            } ?>
                                        </div>
                                    </div>
                                </div><!-- .entry-content -->
    
                            </div><!-- .list-post-top -->
                            <br>
                            <div class="row">
                                <div class="col-lg-6 col-sm-6 color_bsf_1 bloc_d_items widget">
                                    <h2 class="widget-title">Ressources</h2>
                                    <ul>
                                    <?php
                                    $search_posts = get_posts(array('category_name'	=> $club_code,'post_type' => 'fiche', 'numberposts' => -1));
                                    if(empty($search_posts)){
                                        echo "<i>Aucune fiche pour l'instant</i>";
                                    }
                                    foreach($search_posts as $search_post){
                                        echo "<li><a href='".get_permalink($search_post)."'>{$search_post->post_title}</a></li>";
                                    }
                                    if(get_user_club()==$club_slug){
                                        echo '<li><a href="'.get_site_url(null, '/wp-admin/post-new.php?post_type=fiche').'"><strong>Ajoutez une fiche</strong></a></li>';
                                    }
                                    ?>
                                        
                                    </ul>
                                </div>
                                <div class="col-lg-6 col-sm-6 color_bsf_1 bloc_d_items widget">
                                    <h2 class="widget-title">Agenda</h2>
                                    <ul>
                                        <?php
                                        $search_posts = get_posts(array('category_name'	=> $club_code,'post_type' => 'tribe_events', 'numberposts' => -1));
                                        if(empty($search_posts)){
                                            echo "<i>Aucun évènement pour l'instant</i>";
                                        }
                                        foreach($search_posts as $search_post){
                                            echo "<li><a href='".get_permalink($search_post)."'>{$search_post->post_title}</a></li>";
                                        }
                                        if(get_user_club()==$club_slug){
                                            echo '<li><a href="'.get_site_url(null, '/wp-admin/post-new.php?post_type=tribe_events').'"><strong>Ajoutez un événement</strong></a></li>';
                                        }
                                        ?>
                                        
                                    </ul>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-sm-6 color_bsf_1 bloc_d_items widget">
                                    <h2 class="widget-title">Blog</h2>
                                    <ul>
                                        <?php
                                        $search_posts = get_posts(array('category_name'	=> $club_code,'post_type' => 'post', 'numberposts' => -1));
                                        if(empty($search_posts)){
                                            echo "<i>Aucun article pour l'instant</i>";
                                        }
                                        foreach($search_posts as $search_post){
                                            echo "<li><a href='".get_permalink($search_post)."'>{$search_post->post_title}</a></li>";
                                        }
                                        if(get_user_club()==$club_slug){
                                            echo '<li><a href="'.get_site_url(null, '/wp-admin/post-new.php?post_type=post').'"><strong>Ajoutez un article</strong></a></li>';
                                        }
                                        ?>
                                    </ul>
                                </div>
                                <div class="col-lg-6 col-sm-6 color_bsf_1 bloc_d_items widget">
                                    <h2 class="widget-title">Membres du club</h2>
                                    <ul>
                                        <?php

                                        $user_query = new WP_User_Query( array( 
                                            'meta_key' => 'club', 
                                            'meta_value' => $club_slug ,  
                                            'fields' => 'all'  ) );
                                        $users = $user_query->get_results();
                                        if(empty($users)){
                                            echo "<i>Aucun membre pour l'instant</i>";
                                        }else{
                                            foreach($users as $id=>$user){
                                                $userLink = get_site_url(null, '/user/'.$user->user_nicename);
                                                $user_extra = '';
                                                if($user->ID==get_current_user_id()){
                                                    $membership_confirm = "return confirm('Etes-vous sûr(e) de vouloir quitter ce club ?')";
                                                    $user_extra = "<small><a onclick=\"$membership_confirm\" href=\"javascript:send_membership_form('-1')\" style='color:red'>(partir ?)</a></small>";
                                                }
                                                echo "<li><a href='$userLink'>{$user->display_name}</a> $user_extra</li>";
                                            }
                                        }
                                        if(get_user_club()!=$club_slug){
                                            $membership_confirm = (get_user_club()!=null)?"return confirm('Vous êtes déjà membre d un club. Voulez-vous en changer pour celui-ci ?')":'';
                                            echo "<li><strong><a onclick=\"$membership_confirm\" href=\"javascript:send_membership_form('$club_slug')\">Devenir membre</a></strong></li>";
                                        }
                                        ?>
                                        

                                    </ul>
                                </div>
                            </div>
                            <?php                                    
                            ?>
                            <form method="POST" action="" id="membership_form">
                                <input type="hidden" id="membership_form_club_slug" name="club_slug" value="">
                            </form>
                            <script>
                                function send_membership_form(club_slug){
                                    document.getElementById('membership_form_club_slug').value = club_slug;
                                    document.getElementById('membership_form').submit();
                                }
                            </script>
                            <?php
                            if(isset($_POST['club_slug'])){
                                if($_POST['club_slug']=='-1'){
                                    unset_user_club();
                                }else{
                                    set_user_club($club_slug);
                                }
                                wp_redirect(get_site_url(null, "/club/$club_slug"));
                            }
                            ?>
                            
                        </div><!-- .listpost-content-wrap -->
    
                    </article><!-- #post-## -->
                    <?php
                    //get_template_part( 'content', 'page' );
    
                    if ( comments_open() || '0' != get_comments_number() ) :
    
                        comments_template();
    
                        endif;
    
                    endwhile;
                    ?>
				</div>
			</main><!-- #main -->

		</div><!-- #primary -->

	<?php
	if ( ( function_exists( 'is_cart' ) && is_cart() ) || ( function_exists( 'is_account_page' ) && is_account_page() ) || ( function_exists( 'is_checkout' ) && is_checkout() ) || ! empty( $zerif_change_to_full_width ) ) {
		zerif_bottom_page_content_trigger();
		echo '</div>';
		zerif_after_page_content_trigger();
	} else {
		zerif_bottom_page_content_trigger();
		echo '</div>';
		zerif_after_page_content_trigger();
		
		//zerif_sidebar_trigger();
        ?>
        
        <div class="sidebar-wrap col-md-3 content-left-wrap">
            <?php get_sidebar(); ?>
        </div>
        <?php
	}
		?>
	</div><!-- .container -->

<?php get_footer(); ?>
