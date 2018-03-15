<div class="um <?php echo $this->get_class( $mode ); ?> um-<?php echo $form_id; ?> um-role-<?php echo um_user('role'); ?> ">

	<div class="um-form">
	
		<?php 
        do_action('um_profile_before_header', $args );
        $club_slug = get_user_club(um_user('ID'));
        

        if(get_current_user_id()==um_user('ID') && $club_slug==null){
            ?>
            <p>
                <b>Vous n'êtes membre d'aucun club !</b>
                <br>Pour ne pas rester seul, rendez-vous sur la <a href="<?php echo get_site_url(null, '/les-clubs/'); ?>">liste des clubs</a>, puis cliquez sur "Devenir Membre".

            </p>
            <?php
        }
        if($club_slug!=null) {
            $club = vdn_get_club_by_slug($club_slug);
            $role_in_club = (get_referent_for_club($club_slug)==um_user('ID'))?'Référent(e)':'Membre';
            echo "<div><strong>$role_in_club du club <a href='".get_site_url(null, '/club/'.$club_slug)."'>{$club->post_title}</a></strong></div>";
        }


        if ( um_is_on_edit_profile() ) { ?><form method="post" action=""><?php } ?>
            <a name="contributions">&nbsp;</a>
            <?php if ( um_is_on_edit_profile() ) { ?></form><?php } ?>
        <div class="row vdn-profile">
            <?php
            echo '<div class="col-sm-5 col-lg-5 bloc_d_items widget info_club">';
            echo '<h2 style="border-bottom : 1px #ea5951 solid;">'.((get_current_user_id()==um_user('ID'))?'Mes':'Ses').' fiches</h2>';
            $user_posts = get_posts( array('post_type'=>'fiche', 'author' => um_user('ID')) );

            if(empty($user_posts)){
                echo "<span class='noresult'>Pas encore de publications.</span><br>";
            }
            echo '<ul>';
            foreach($user_posts as $user_post){
                echo "<li><a href='".get_permalink($user_post)."'>{$user_post->post_title}</a></li>";
            }
            if(get_current_user_id()==um_user('ID')) {
                echo '<li><a href="/wp-admin/post-new.php?post_type=fiche"><strong>Créer une fiche</strong></a></li>';
            }
            echo '</ul>';
            echo '</div>';
            ?>
            <?php
            //echo '<div class="col-sm-5 col-lg-5">';
            echo '<div class="col-sm-5 col-lg-5 bloc_d_items widget info_club">';
            echo '<h2 style="border-bottom : 1px #ea5951 solid;">'.((get_current_user_id()==um_user('ID'))?'Mes':'Ses').' favoris</h2>';
            $favorite_post_ids = get_user_favorites(um_user('ID'));
            if(!empty($favorite_post_ids)){
                echo '<ul>';
                foreach($favorite_post_ids as $fav_post_id){
                    $fav_post = get_post($fav_post_id);
                    echo "<li><a href='".get_permalink($fav_post)."'>{$fav_post->post_title}</a></li>";
                }
            }else{
                echo "<span class='noresult'>Pas encore de favoris.</span><br>";
            }
            echo '</ul>';
            echo '</div>';
            ?>
        </div>
        <br>&nbsp;<br>
        <div class="row vdn-profile">
            <?php
            echo '<div class="col-sm-5 col-lg-5 bloc_d_items widget info_club">';
            echo '<h2 style="border-bottom : 1px #ea5951 solid;">'.((get_current_user_id()==um_user('ID'))?'Mes':'Ses').' événements</h2>';
            $user_posts = get_posts( array('post_type'=>'tribe_events', 'author' => um_user('ID')) );
            if(empty($user_posts)){
                echo "<span class='noresult'>Pas encore de publications.</span><br>";
            }
            echo '<ul>';
            foreach($user_posts as $user_post){
                echo "<li><a href='".get_permalink($user_post)."'>{$user_post->post_title}</a></li>";
            }
            if(get_current_user_id()==um_user('ID')) {
                echo '<li><a href="/wp-admin/post-new.php?post_type=tribe_events"><strong>Créer un événement</strong></a></li>';
            }
            echo '</ul>';

            echo '</div>';
            ?>

            <?php
            echo '<div class="col-sm-5 col-lg-5 bloc_d_items widget info_club">';
            echo '<h2 style="border-bottom : 1px #ea5951 solid;">'.((get_current_user_id()==um_user('ID'))?'Mes':'Ses').' articles</h2>';
            $user_posts = get_posts( array('post_type'=>'post', 'author' => um_user('ID')) );
            if(empty($user_posts)){
                echo "<span class='noresult'>Pas encore de publications.</span><br>";
            }
            echo '<ul>';
            foreach($user_posts as $user_post){
                echo "<li><a href='".get_permalink($user_post)."'>{$user_post->post_title}</a></li>";
            }
            if(get_current_user_id()==um_user('ID')) {
                echo '<li><a href="/wp-admin/post-new.php?post_type=post"><strong>Créer un article</strong></a></li>';
            }
            echo '</ul>';

            echo '</div>';
            ?>
        </div>

			<?php //do_action('um_profile_navbar', $args ); ?>
			<div class="row vdn-profile">
                <hr>
				<div class="col-lg-4 col-sm-4" style="padding-top:30px;">
					<?php do_action('um_profile_header', $args ); ?>

					
				</div>
				<div class="col-lg-7 col-sm-7" style="overflow-x:hidden;">
					<?php
                    if ( um_is_on_edit_profile() ){
                        ?>
                        <p>
                            <b>Votre profil aide notre équipe à mieux vous connaitre.</b> <br>
                            Dans les informations demandées ci-dessous, la plupart ne seront <u>pas visibles</u> par les autres utilisateurs.<br>
                            Seuls votre structure de rattachement et la ville seront visibles par tous.
                        </p>
                        <?php
                    }
                    if(get_current_user_id()!=um_user('ID') && !vdn_is_admin()){
                        ?>
                        <style type="text/css">
                            .um-field-adresse,
                            .um-field-telephone,
                            .um-field-profession,
                            .um-field-age,
                            .um-field-sexe{
                                display:none;
                            }
                        </style>
                        <?php
                    }
                    if(get_current_user_id()==um_user('ID')) {
                        ?>
                        <h3>Bienvenue !</h3>
                        <p>
                            Pour bien démarrer sur Voyageurs du Numérique, commencez par nous donner quelques
                            informations. 
                            <br><br>la plupart ne seront <u>pas visibles</u> par les autres utilisateurs.
                            Seuls votre structure de rattachement et la ville seront visibles par tous.
                            <br>
                            <a href="<?php echo get_site_url(null, '/user/' . um_user('user_login') . '/?profiletab=main&um_action=edit'); ?>"><b>Cliquez
                                    ici pour modifier votre profil</b></a>
                        </p>
                        <?php
                    }
						$nav = $ultimatemember->profile->active_tab;
						$subnav = ( get_query_var('subnav') ) ? get_query_var('subnav') : 'default';
	
						print "<div class='um-profile-body $nav $nav-$subnav'>";
	
						// Custom hook to display tabbed content
						do_action("um_profile_content_{$nav}", $args);
						do_action("um_profile_content_{$nav}_{$subnav}", $args);
	
						print "</div>";

					
					?>
				</div>
			</div> <!-- /.row -->
    </div>
        