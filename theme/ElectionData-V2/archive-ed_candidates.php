<?php
/**
 * The template for displaying all single posts and attachments
 * Specifically, this is for displaying all candidates.
 */

get_header();

?>
<p><span class="small grey">Candidates are displayed in random order.</span></p>
<div id="primary">
    <div id="content" role="main">
		<?php display_all_candidates( $wp_query ); ?>
	</div>
</div>
<?php get_footer(); ?>
