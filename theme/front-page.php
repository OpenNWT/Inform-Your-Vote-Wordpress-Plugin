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

<!--<div class="flow_it">
	<?php $summary = Election_Data_Option::get_option( 'summary' );
	if ( $summary ) : ?>
		<div class="one_column medium_row">
			<?php echo $summary; ?>
		</div>
	<?php endif;
	if ( $constituencies ) : ?>
		<div class="one_column medium_row scroll">
			<h2><?php echo Election_Data_Option::get_option( 'constituency-label', 'Constituencies' ); ?></h2>
			<p class="small grey"><?php echo Election_Data_Option::get_option( 'constituency-subtext' ); ?></p>
			<?php foreach ( $constituencies as $constituency_id ) :
				$constituency = get_constituency( $constituency_id ); ?>
				<div class="mini_maps">
					<a href="<?php echo $constituency['url']; ?>" title="Click to see the candidates.">
						<div class="mini_map_image float-left">
							<?php if($constituency['map_id']):?>
									<?php echo wp_get_attachment_image($constituency['map_id'], 'map_thumb', false, array( 'alt' => $constituency['name'] ) ); ?>
							<?php else:?>
									<?php echo wp_get_attachment_image( Election_data_option::get_option('missing_constituency')); ?>
							<?php endif;?>
					</div>
					</a>
					<div class="links float-left">
						<p class="small"><strong><a href="<?php echo $constituency['url']; ?>"><?php echo $constituency['name']; ?></a></strong></p>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif;
	$facebook = esc_attr( Election_Data_Option::get_option( 'facebook-page' ) );
	$twitter = esc_attr( Election_Data_Option::get_option( 'twitter' ) );
	$google_plus_one = Election_Data_Option::get_option( 'google-plus-one' );
	if ( $facebook || $twitter || $google_plus_one ) : ?>
		<div class="one_column medium_row social">
            <?php echo Election_Data_Option::get_option( 'about-us' ); ?>
            <?php if ( $twitter ) : ?>
				<p><a href="http://twitter.com/<?php echo $twitter; ?>" class="twitter-follow-button">Follow @<?php echo $twitter; ?></a></p>
            <?php endif ?>
			<?php if ( $facebook ) : ?>
                <iframe src="//www.facebook.com/plugins/like.php?href=<?= $facebook ?>&amp;width=270&amp;layout=standard&amp;action=like&amp;show_faces=true&amp;share=true&amp;height=80&amp;appId=61535010545" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:270px; height:80px;" allowTransparency="true"></iframe>
            <?php endif ?>
            <?php if ( $google_plus_one ) : ?>
				<p><g:plusone></g:plusone></p>
			<?php endif; ?>
		</div>
	<?php endif;
	$news_count = Election_Data_Option::get_option( 'news-count-front', 10 );
	if ( $news_count ) : ?>
		<div class="one_column latest_news_small">
			<h2>Latest Election News</h2>
			<?php display_news_titles( null, true, $news_count ); ?>
		</div>
	<?php endif;
	if ($is_party_election):
		if ( $parties ) : ?>
			<div class="two_columns">
				<h2><?php echo Election_Data_Option::get_option( 'party-label', 'The Political Parties' ); ?></h2>
				<div class="parties_thumb" >
					<?php foreach ( $parties as $party_id ) :
						$party = get_party( $party_id ); ?>
						<div class="party_thumb" >
							<p><a href="<?php echo $party['url']; ?>"><?php echo $party['name']; ?></a></p>
							<div>
							<a href="<?php echo $party['url']; ?>">
								<?php echo wp_get_attachment_image($party['logo_id'], 'party', false, array( 'alt' => "{$party['name']} Logo" ) ); ?>
							</a>
							</div>
						</div>
					<?php endforeach; ?>
	            </div>
	            <br>
				<p class="small grey"><?php echo Election_Data_Option::get_option( 'party-subtext' ); ?></p>
			</div>
		<?php endif; ?>
	<?php endif;?>
</div> -->

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
					<a href="<?php echo Election_Data_Option::get_option( 'where_url' );?>" target="_blank"><?php echo Election_Data_Option::get_option( 'where_title' );?></a>
				</h1>
				<p class="excerpt">
					<?php echo Election_Data_Option::get_option( 'where_excerpt' ); ?>
				</p>
				<p class="pimg">
					<a href="<?php echo Election_Data_Option::get_option( 'where_url' );?>" target="_blank"><?php echo wp_get_attachment_image(Election_Data_Option::get_option( 'where_img' ));?></a>
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
