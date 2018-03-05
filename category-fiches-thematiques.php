<?php
/**
 * The template for displaying Archive pages.
 *
 * @package zerif-lite
 */
get_header(); ?>

<div class="clear"></div>
</header> <!-- / END HOME SECTION  -->
<?php zerif_after_header_trigger(); ?>
<div id="content" class="site-content">

<div class="container">

	<?php zerif_before_archive_content_trigger(); ?>

	<div class="content-left-wrap col-md-9">

		<?php zerif_top_archive_content_trigger(); ?>

		<div id="primary" class="content-area">

			<main id="main" class="site-main category-fiches-thematiques">

			<?php if ( have_posts() ) : ?>

				<header class="page-header">

					<?php
						/* Title */
						zerif_page_header_title_archive_trigger();
						echo '<a href="/les-ressources/">&larr; Revenir à l\'ensemble des ressources.</a><br>';
						/* Optional term description */
						zerif_page_term_description_archive_trigger();
					?>
					
				</header><!-- .page-header -->
				<div class="zone_recherche_ressources">
					<?php echo @do_shortcode('[vdn_fiche_search_form]') ?>
					<br style="clear:both">
				</div>
				<div class="row">
					<?php while ( have_posts() ) {
						the_post();
						get_template_part( 'content-fiche' );
						?>
						
						<!--<div class="col-lg-3 col-sm-3 bloc_recherche_fiche">
							<h2><?php /*the_title() */?></h2>
						</div>-->
					<?php } ?>
				</div>
				
				<?php
					echo get_the_posts_navigation(
						array(
							/* translators: Newer posts arrow */
							'next_text' => sprintf( __( 'Newer posts %s', 'zerif-lite' ), '<span class="meta-nav">&rarr;</span>' ),
							/* translators: Older posts arrow */
							'prev_text' => sprintf( __( '%s Older posts', 'zerif-lite' ), '<span class="meta-nav">&larr;</span>' ),
						)
					);

			else :

				get_template_part( 'content', 'none' );

			endif;
			?>

			</main><!-- #main -->

		</div><!-- #primary -->

		<?php zerif_bottom_archive_content_trigger(); ?>

	</div><!-- .content-left-wrap -->

	<?php zerif_after_archive_content_trigger(); ?>

	<?php zerif_sidebar_trigger(); ?>

</div><!-- .container -->

<?php get_footer(); ?>
