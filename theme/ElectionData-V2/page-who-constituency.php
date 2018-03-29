<?php
/**
* Template Name: Who Page
* Displaying for choosing candidates from different constituencies.
* Authors: Heng Yu
* @package Election_Data_Theme
* @since Election_Data_Theme 1.1
*/

global $is_party_election;
global $ed_post_types;

$constituencies = get_root_constituencies();
?>
<?php get_header(); ?>
<?php if ( $constituencies ) : ?>
		<div class="flow_it who_constituencies_lists">
			<h2><?php echo Election_Data_Option::get_option( 'constituency-label', 'Constituencies' ); ?></h2>
			<?php foreach ( $constituencies as $constituency_id ) :
				$constituency = get_constituency( $constituency_id ); ?>
				<div class="mini_maps">
					<div class="mini_maps_con">
						<p class="small"><strong><a href="<?php echo $constituency['url']; ?>"><?php echo $constituency['name']; ?></a></strong></p>
						<a href="<?php echo $constituency['url']; ?>" title="Click to see the candidates.">
							<div class="mini_map_image float-left">
								<?php if($constituency['map_id']):?>
										<?php echo wp_get_attachment_image($constituency['map_id'], 'map', false, array( 'alt' => $constituency['name'] ) ); ?>
								<?php else:?>
										<?php echo wp_get_attachment_image( Election_data_option::get_option('missing_constituency')); ?>
								<?php endif;?>
							</div>
						</a>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
<?php endif;?>
<?php get_footer(); ?>
