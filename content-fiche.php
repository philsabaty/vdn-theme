<?php
/**
 * The template used for displaying CPT fiche
 *
 * @package zerif-lite
 */

//acf_form(array(
//		'post_title' => true, 'post_content' => true,
//		'field_groups' => array('acf_form-post-title', 'acf_form-post-content', 533)
//	)
//);

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemtype="http://schema.org/BlogPosting" itemtype="http://schema.org/BlogPosting">
	<?php
	if ( ! is_search() ) {

		$post_thumbnail_url = get_the_post_thumbnail( get_the_ID(), 'zerif-post-thumbnail' );

		if ( ! empty( $post_thumbnail_url ) ) {

			echo '<div class="post-img-wrap">';

				echo '<a href="' . esc_url( get_permalink() ) . '" title="' . the_title_attribute( 'echo=0' ) . '" >';

					echo $post_thumbnail_url;

				echo '</a>';

			echo '</div>';

			echo '<div class="listpost-content-wrap">';
		} else {

			echo '<div class="listpost-content-wrap-full">';
		}
	} else {
		echo '<div class="listpost-content-wrap-full">';
	}
	?>

	<div class="list-post-top">

	<header class="entry-header">

        <?php
        if ( is_search() || is_archive() || vdn_get_searched_category_id()!=null) {
            ?>
            <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

			<span class="tags-links">
				<?php the_selected_option_label('type'); ?> - <?php the_selected_option_label('niveau'); ?> - <?php the_field('public'); ?>
				<br>
				<?php
				// add flag if post from BSF
				if(get_the_author()=='BSF'){
					echo '<span class="tag_by_BSF" title="Contenu proposé par Bibliothèques Sans Frontières">PAR BSF</span>';
				}
				// add flag if post from BSF
				if(get_the_author_meta('club')!=''){
					echo '<span class="tag_by_club" title="Contenu proposé par un club VDN">CLUB</span>';
				}
				?>
				Publié par <?php the_author() ?> le <?php the_date( 'j F Y' ) ?><br>
				<?php
				$tags_list = get_the_tag_list( '', __( ', ', 'zerif-lite' ) );
				if ( $tags_list ) {
					printf('Tags : %s ', $tags_list);
				}
				?>
				
			</span>
		<?php
		}else{ ?>
            <h1 class="entry-title"><?php the_title(); ?></h1>

			<div class="entry-meta">
				<?php
                echo get_vdn_tags_html($post);
                /*
				if(get_the_author()=='BSF'){
					echo '<span class="tag_by_BSF" title="Contenu proposé par Bibliothèques Sans Frontières">PAR BSF</span>';
				}
				// add flag if post from BSF
				if(get_the_author_meta('club')!=''){
					echo '<span class="tag_by_club" title="Contenu proposé par un club VDN">CLUB</span>';
				}
                */
				echo 'Publié par '.get_the_author().' le '.get_the_date( 'j F Y' );
				//zerif_posted_on();
			
				?>
			</div><!-- .entry-meta -->

				<?php
				if ( 'fiche' == get_post_type() ) { // Hide category and tag text for pages on Search

					/* translators: used between list items, there is a space after the comma */
					$categories_list = get_the_category_list( __( ', ', 'zerif-lite' ) );

					if ( $categories_list && zerif_categorized_blog() ) {
						echo '<span class="cat-links">';
						printf( __( 'Posted in %1$s', 'zerif-lite' ), $categories_list );
						echo '</span>';
					}



					$tags_list = get_the_tag_list( '', __( ', ', 'zerif-lite' ) );
					//var_dump($tags_list);
					if ( $tags_list ) {

						echo '<br><span class="tags-links">';

						/* translators: Tags list */
						printf( 'Tags : %s', $tags_list );

						echo '</span>';

					}
				}

				?>
			<br><a target="_blank" rel="noindex,nofollow" href="<?php echo esc_url ( add_query_arg ( 'format', 'pdf', get_permalink ( $post->ID ) ) ) ?>">Télécharger au format PDF</a>
            <?php
            echo '<br><span class="edit-link">'.get_favorites_button().'</span>';
            edit_post_link( __( 'Edit', 'zerif-lite' ), '<br><span class="edit-link">', '</span>' );

        } ?>

		<?php if ( 'fiche' == get_post_type() ) : ?>


		<?php endif; ?>
	</header><!-- .entry-header -->

	<?php
	if ( is_search() || is_archive() ) {

		echo '<div class="entry-summary">';

		the_excerpt();
		
	} else {
			?>
			<br style="clear:both"/>
			<blockquote class="fiche-meta">
				Type : <?php the_selected_option_label('type'); ?>
                <br/>
                Niveau : <?php the_selected_option_label('niveau'); ?>
				<br/>
				Public : <?php the_field('public'); ?>
			</blockquote>
		<div class="entry-content">
		<?php
        
        // add a disclaimer message for non-BSF fiches
        if(get_the_author()!='BSF'){
            ?>
            <div class="vdn_nonbsf_disclaimer">
                Cette fiche a été rédigée par un membre de la communauté des Voyageurs du Numérique. <br>
                Afin d'inciter la créativité des participants, l'équipe de Voyageurs du Numérique publie ce contenu sans validation préalable.
                <br><br>Bibliothèques sans Frontières n'engage pas sa responsabilité sur le contenu de ces fiches. <br>
                Si un contenu vous semble inapprorié, merci de <a href="<?php echo site_url() ?>/ecrivez-nous/">nous le signaler</a>. <br><br>
                L'équipe des Voyageurs du Numérique.
            </div>
            <?php
        }
        $field_group = vdn_get_fiche_fields_group();
        $acfFields = ($field_group!=null)?$field_group['fields']:array();
        if( $acfFields ) foreach( $acfFields as $field ){
            //var_dump($field);
            $field_hash = $field['key'];
            $field_name = $field['name'];
            if ( in_array($field['type'], array('textarea', 'text')) 
                && get_field($field_name)!='') {
                echo "<br><br><strong>{$field['label']}</strong><br>".get_field($field_name);
            }
        }
        echo '<hr>';
        the_content();
        wp_link_pages(
			array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'zerif-lite' ),
				'after'  => '</div>',
			)
		);
	}
	?>

	<footer class="entry-footer">
		

	</footer><!-- .entry-footer -->

	</div><!-- .entry-content --><!-- .entry-summary -->

	</div><!-- .list-post-top -->

</div><!-- .listpost-content-wrap -->

</article><!-- #post-## -->
