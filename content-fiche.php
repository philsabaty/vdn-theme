<?php
/**
 * The template used for displaying CPT fiche
 *
 * @package zerif-lite
 */
global $VDN_CONFIG;
$main_css_color = '#e95a51';
@$main_css_color = '#'.($VDN_CONFIG['vdn_fiche_types'][get_field('type', $post->ID)]['color']);
?>
<style>
	body.single-fiche article.fiche header{background-color: <?php echo $main_css_color; ?>;}
</style>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemtype="http://schema.org/BlogPosting" itemtype="http://schema.org/BlogPosting">
	<div class="listpost-content-wrap-full">
	<div class="list-post-top">

	<header class="entry-header">

        <?php
		$author_url = get_site_url(null, '/user/'.get_the_author_meta('nicename'));
		$author_link = (get_the_author()=='BSF')?get_the_author():"<a href='$author_url'>".get_the_author()."</a>";
		$post_thumbnail_url = get_the_post_thumbnail( get_the_ID(), array(150,150) );
		$fiche_target = ($VDN_CONFIG['open_fiches_in_new_tab'])?"target='_blank'":'';
		
        if ( is_search() || is_archive() || vdn_get_searched_category_id()!=null) {
			//echo '<div class="post-img-wrap">';
			echo '<a class="vdn_fiche_thumbnail" href="' . esc_url( get_permalink() ) . '" title="' . the_title_attribute( 'echo=0' ) . '" '.$fiche_target.'>'.$post_thumbnail_url.'</a>';
			//echo '</div>';
            ?>
            <h2 class="entry-title"><a href="<?php the_permalink(); ?>" <?php echo $fiche_target; ?>><?php the_title(); ?></a></h2>

			<span class="tags-links">
				<?php the_selected_option_label('type'); ?> - <?php the_selected_option_label('niveau'); ?> - <?php the_field('public'); ?>
				<br>
				<?php
				// add flag if post from BSF
				echo get_vdn_special_flags($post);
				?>
				Publié par <?php echo $author_link ?> le <?php echo get_the_date( 'j F Y' ) ?><br>
				<?php
				$tags_list = get_the_tag_list( '', __( ', ', 'zerif-lite' ) );
				if ( $tags_list ) {
					printf('Tags : %s ', $tags_list);
				}
				?>
				
			</span>
		<?php
		}else{
			//echo '<div class="post-img-wrap">';
			echo '<a class="vdn_fiche_thumbnail" href="' . esc_url( get_permalink() ) . '" title="' . the_title_attribute( 'echo=0' ) . '" >'.$post_thumbnail_url.'</a>';
			//echo '</div>';

			echo '<h1>'.get_the_title().'</h1>';
			$main_cat = get_first_thematique_category($post);
			$main_cat_link = ($main_cat)?"<a href='/category/fiches-thematiques/{$main_cat->slug}/'>{$main_cat->name}</a>":'';
			$categories_list = get_the_category_list( __( ', ', 'zerif-lite' ) );
			$categories_html = ($categories_list)?" dans $categories_list":'';
			$tags_list = ''; //get_the_tag_list( '#', __( ' #', 'zerif-lite' ) );
			$posttags = get_the_tags();
			if($posttags) {
				foreach(get_the_tags() as $tag) {
					$tags_list .= ' #'.$tag->name;
				}
			}
		
			if($main_cat_link!=''){echo "<h2 class='main_cat'>$main_cat_link</h2>";}
			?>
            

			<div class="entry-meta">
				<?php

				echo "<div class='vdn_info_fiche'>Publié par $author_link  le ".get_the_date( 'j F Y' )." $categories_html</div>";
				echo '<span class="">'.get_vdn_special_flags($post).'</span>';
				edit_post_link( __( 'Edit', 'zerif-lite' ), ' &nbsp; <span class="not_in_pdf">', '</span>' );
				echo ' &nbsp; <a class="not_in_pdf" target="_blank" rel="noindex,nofollow" href="'.esc_url ( add_query_arg ( 'format', 'pdf', get_permalink ( $post->ID ) ) ).'">Télécharger au format PDF</a>';
				echo ' &nbsp; <span class="not_in_pdf">'.get_favorites_button().'</span>';
				?>
				<br style="clear:left;"><br>

				<div class="row">
					<div class="col-sm-3" style="padding-left:0">
						<span class='lighter vdn_info_fiche'><?php the_selected_option_label('type'); ?></span>
					</div>
					<div class="col-sm-3">
						<span class='lighter vdn_info_fiche'><?php the_selected_option_label('niveau'); ?></span>
					</div>
					<div class="col-sm-6">
						<span class='lighter tags-links vdn_info_fiche' style="white-space: normal;">Mots clés : <?php echo ($tags_list)?$tags_list:'(aucun)'; ?></span>
					</div>
				</div>
				<br>&nbsp;
			</div><!-- .entry-meta -->

		<?php } ?>
	</header><!-- .entry-header -->

	<?php
	if ( is_search() || is_archive() ) {
		echo '<div class="entry-summary">';
		the_excerpt();
		
	} else {
		echo '<div class="entry-content">';
			
		?>
		<div class="row" style="border-bottom:2px <?php echo $main_css_color; ?> solid;">
			<div class="col-sm-6 cartouche_donnees_fiche" style="border-color:<?php echo $main_css_color; ?>; color:<?php echo $main_css_color; ?>;">
				<div><strong>Public</strong> : <?php the_field('public'); ?></div>
				<div><strong>Durée de préparation</strong> : <?php the_field('duree_preparation'); ?></div>
				<div><strong>Durée de l'activité</strong> : <?php the_field('duree_animation'); ?></div>
				<div><strong>Nombre de participants</strong> : <?php the_field('nombre_de_participants'); ?></div>
				<div><strong>Taux d'encadrement</strong> : <?php the_field('taux_encadrement'); ?></div>
			</div>
			<div class="col-sm-6">
				<strong>Matériel utilisé</strong> :<br/> <?php the_field('materiel_utilise'); ?><br><br>
				<strong>Contenus utilisés</strong> :<br/> <?php the_field('contenus_utilises'); ?>
			</div>
		</div>
		<br>&nbsp;<br>
		<div style="color:<?php echo $main_css_color; ?>; padding: 10px 10px 10px 10px;">
			<h3>Objectifs pédagogiques :</h3>
			<?php the_field('objectifs_pedagogiques'); ?>
		</div>
		<br>&nbsp;
		<div class="row" style="border:1px #000 solid;padding:10px;">
			<div class="col-sm-6" style="border-right:1px #000 solid;">
				<strong>Compétences travaillées</strong> :<br/> <?php the_field('competences_travaillees'); ?>
			</div>
			<div class="col-sm-6">
				<strong>Pré-requis</strong> :<br/> <?php the_field('pre-requis'); ?>
			</div>
		</div>
		<br style="clear:both;">
		<hr style="height:2px; border-color:<?php echo $main_css_color; ?>; background-color:<?php echo $main_css_color; ?>;">
		<?php
        the_content();
		display_bsf_content_disclaimer(get_the_ID());
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
