<?php
/**
 * List View Template
 * The wrapper template for a list of events. This includes the Past Events and Upcoming Events views
 * as well as those same views filtered to a specific category.
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/list.php
 *
 * @package TribeEventsCalendar
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

do_action( 'tribe_events_before_template' );
?>

	<h1 class="entry-title">Agenda</h1>
	<p><br>Prenez connaissance de toutes les activités Voyageurs du Numérique de votre région et
		référencez les vôtres : formation d’animateurs organisées par Bibliothèques Sans
		Frontières, formation complémentaire sur un outil ou une méthode, apéro d’échange de
		bonnes pratiques, mais aussi les ateliers organisées par les clubs !
	</p>
	<?php 
	if(is_user_logged_in()){
		echo '<div style="text-align:center; clear:both">';
		echo '	<a href="/wp-admin/post-new.php?post_type=tribe_events" class="btn btn-primary custom-button red-btn">Ajoutez un événement</a><br>&nbsp;';
		echo '</div>';
	}
	echo do_shortcode('[vdn_event_map]');
	echo '<br>&nbsp;<br>'
	?>
		
	<!-- Tribe Bar -->
<?php tribe_get_template_part( 'modules/bar' ); ?>

	<!-- Main Events Content -->
<?php tribe_get_template_part( 'list/content' ); ?>

	<div class="tribe-clear"></div>

<?php
do_action( 'tribe_events_after_template' );
