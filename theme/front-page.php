<?php

/**
 *	The front page.
 *
 * @package Election_Data_Theme
 * @since Election_Data_Theme 1.0
 * @author Robert Burton
 */

$constituencies = get_root_constituencies();
$parties = get_parties_random();
global $is_party_election;

get_header(); ?>


<!-- Heng start -->
<div class="page-list">
	<ul>
		<li>
			<div class="page-article">
				<h1 class="ptitle">
					<a href="<?php echo Election_Data_Option::get_option( 'who_url' );?>"><?php echo Election_Data_Option::get_option( 'who_title' );?></a>
				</h1>
				<p class="excerpt">
					<?php echo Election_Data_Option::get_option( 'who_excerpt' ); ?>
				</p>
				<p class="pimg">
					<a href="<?php echo Election_Data_Option::get_option( 'who_url' );?>" ><?php echo wp_get_attachment_image(Election_Data_Option::get_option( 'who_img' ));?></a>
				</p>
			</div>
		</li>
		<li>
			<div class="page-article">
				<h1 class="ptitle">
					<a href="<?php echo Election_Data_Option::get_option( 'where_url' );?>"><?php echo Election_Data_Option::get_option( 'where_title' );?></a>
				</h1>
				<p class="excerpt">
					<?php echo Election_Data_Option::get_option( 'where_excerpt' ); ?>
				</p>
				<p class="pimg">
					<a href="<?php echo Election_Data_Option::get_option( 'where_url' );?>" ><?php echo wp_get_attachment_image(Election_Data_Option::get_option( 'where_img' ));?></a>
				</p>
			</div>
		</li>
		<li>
			<div class="page-article">
				<h1 class="ptitle">
					<a href="<?php echo Election_Data_Option::get_option('what_url');?>"><?php echo Election_Data_Option::get_option('what_title');?></a>
				</h1>
				<p class="excerpt">
					<?php echo Election_Data_Option::get_option( 'what_excerpt'); ?>
				 </p>
				<p class="pimg">
					<a href="<?php echo Election_Data_Option::get_option('what_url');?>"><?php echo wp_get_attachment_image(Election_Data_Option::get_option( 'what_img' ));?></a>
				</p>
			</div>
		</li>
	</ul>
</div>

<div class="latest-news">
	<div class="head-title">Latest News</div>
		<?php
			$news_count = Election_Data_Option::get_option( 'news-count-front', 10 );

			display_front_page_news(null, $news_count);?>
	<div class="view-all">
		<div class="view-all-lt"></div>
		<div class="view-all-con"><a href="<?php echo get_post_type_archive_link( $ed_post_types['news_article'] ); ?>" target="_blank">View All</a></div>
		<div class="view-all-rt"></div>
	</div>
</div>
<!-- Heng end -->
<?php get_footer(); ?>
