<?php
/**
 * The template for displaying all single posts and attachments
 * Specifically, this is for dealing with news articles.
 */

get_header();

?>

<div class="latest-news">
	<h1>Latest Election News</h1>
  <br><br>
    <div class="flow_it">
			<?php display_front_page_news(null, 90);?>
    </div>
	</div>
</div>

<?php get_footer(); ?>
