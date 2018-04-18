<?php

/**
 *	The front page.
 *
 * @package Election_Data_Theme
 * @since Election_Data_Theme 1.0
 * @author Heng Yu
 */


/* Left column*/
$left_column_title = Election_Data_Option::get_option('left_column_title');
$left_column_excerpt = Election_Data_Option::get_option('left_column_excerpt');
$left_column_img = wp_get_attachment_image(Election_Data_Option::get_option('left_column_img'));
$left_column_url = Election_Data_Option::get_option('left_column_url') ?: "";

/* Center column*/
$center_column_title = Election_Data_Option::get_option('center_column_title');
$center_column_excerpt = Election_Data_Option::get_option('center_column_excerpt');
$center_column_img = wp_get_attachment_image(Election_Data_Option::get_option('center_column_img'));
$center_column_url = Election_Data_Option::get_option('center_column_url') ?: "";

/* Right column*/
$right_column_title = Election_Data_Option::get_option('right_column_title');
$right_column_excerpt = Election_Data_Option::get_option('right_column_excerpt');
$right_column_img = wp_get_attachment_image(Election_Data_Option::get_option('right_column_img'));
$right_column_url = Election_Data_Option::get_option('right_column_url') ?: "";

$news_count = Election_Data_Option::get_option( 'news-count-front', 3 );

get_header(); ?>

<!--Three columns part -->
<div class="page-list">
	<ul>
		<li>
			<div class="page-article">

				<h1 class="ptitle">

					<a href="<?php echo $left_column_url;?>"><?php echo $left_column_title;?></a>
				</h1>
				<p class="excerpt">
					<?php echo $left_column_excerpt; ?>
				</p>
				<p class="pimg">
					<a href="<?php echo $left_column_url;?>">
						<?php
							if($left_column_img) :
								echo $left_column_img;
							else :?>
							<img src="/wp-content/themes/ElectionData/ElectionData-V2/images/imagesself/group.png" alt="group" />
						<?php endif; ?>
					</a>
				</p>
			</div>
		</li>
		<li>
			<div class="page-article">
				<h1 class="ptitle">
					<a href="<?php echo $center_column_url;?>"><?php echo $center_column_title;?></a>
				</h1>
				<p class="excerpt">
					<?php echo $center_column_excerpt; ?>
				</p>
				<p class="pimg">
					<a href="<?php echo $center_column_url;?>">
						<?php
							if($center_column_img) :
								echo $center_column_img;
							else :?>
							<img src="/wp-content/themes/ElectionData/ElectionData-V2/images/imagesself/location.png" alt="locatoin" />
						<?php endif; ?>
					</a>
				</p>
			</div>
		</li>
		<li>
			<div class="page-article">
				<h1 class="ptitle">
					<a href="<?php echo $right_column_url;?>"><?php echo $right_column_title;?></a>
				</h1>
				<p class="excerpt">
					<?php echo $right_column_excerpt; ?>
				 </p>
				<p class="pimg">
					<a href="<?php echo $right_column_url;?>">
						<?php
							if($right_column_img) :
								echo $right_column_img;
							else :?>
							<img src="/wp-content/themes/ElectionData/ElectionData-V2/images/imagesself/document.png" alt="document" />
						<?php endif; ?>
					</a>
				</p>
			</div>
		</li>
	</ul>
</div>

<!--News part -->
<div class="latest-news">
	<div class="head-title">Latest News</div>
		<ul class="news-list">
			<?php display_front_page_news(null, $news_count);?>
		</ul>
	<div class="view-all">
		<div class="view-all-lt"></div>
		<div class="view-all-con">
			<a href="<?php echo get_post_type_archive_link( $ed_post_types['news_article'] ); ?>">View All</a>
		</div>
		<div class="view-all-rt"></div>
	</div>
</div>

<?php get_footer(); ?>
