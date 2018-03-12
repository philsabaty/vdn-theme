<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package zerif-lite
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="entry-header">
		<span class="date updated published"><?php the_time( get_option( 'date_format' ) ); ?></span>
		<span class="vcard author byline"><a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" class="fn"><?php the_author(); ?></a></span>

		<?php zerif_page_header_trigger(); ?>

	</header><!-- .entry-header -->

	<div class="entry-content">

		<?php

			the_content(
				sprintf(
					/* translators: %s: Name of current post */
					__( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'zerif-lite' ),
					the_title( '<span class="screen-reader-text">', '</span>', false )
				)
			);

		?>
		<div class="row">
			<?php
            $parent_category_slug = 'fiches-thematiques';
			$parent_category = get_category_by_slug($parent_category_slug);
			$categories = get_categories( array(
				'parent' => $parent_category->term_id,
				'hide_empty' => false) );
			$count = 0;
			foreach($categories as $category) {
				//echo  do_shortcode('[catlist post_type="fiche" name="'.$category->slug.'"]');
				$max_fiches = 4;
				$cat_query = new WP_Query(array(
					'posts_per_page' => $max_fiches,
					'post_type'=>'fiche', 
					'cat' => $category->term_id
				));
				$fiches = $cat_query->posts;
				//echo('<pre>'.print_r($fiches, true).'</pre>');
				$title_href = empty($fiches) ? '' : ('href="'.get_site_url(null, "category/$parent_category_slug/{$category->slug}").'"');
				$noresult_info = "Seules quelques fiches sont visibles aux utilisateurs non-connectés. En créant un club, vous avez accés à l ensemble de notre base";
				echo "<div class='col-lg-6 col-sm-6 color_bsf_1 bloc_d_items widget'>";
				echo "<h2 class='widget-title'><a $title_href'>{$category->name}</a></h2>";
				if(empty($fiches)){
					echo "<span class='noresult'><abbr title='$noresult_info'>Aucune fiche disponible</abbr></span><br>";
				}else{
					echo '<ul>';
					foreach($fiches as $fiche) {
						echo "<li><a href='/fiche/{$fiche->post_name}'>{$fiche->post_title}</a>"/*.get_vdn_special_flags($fiche)*/."</li>";
					}
					echo "</ul>";
					if($cat_query->found_posts > $max_fiches){
						echo "<a class='moreresults' href='".get_site_url(null, "category/$parent_category_slug/{$category->slug}")."'>Plus : voir les {$cat_query->found_posts} résultats</a>";
					}

				}echo "</div>";
				if(++$count % 2 == 0){echo '<br style="clear:both">';}
			}
			?>
		</div>
		
		<?php
			wp_link_pages(
				array(
					'before' => '<div class="page-links">' . __( 'Pages:', 'zerif-lite' ),
					'after'  => '</div>',
				)
			);

		?>

	</div><!-- .entry-content -->

</article><!-- #post-## -->
