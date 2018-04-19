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
			<h2 style="text-align:center; line-height: 36px;"><?php echo Election_Data_Option::get_option( 'constituency-label', 'Constituencies' ); ?></h2>
			<h3><pre style="text-align:center; line-height: 36px;">
Please select a consituency to view their candidates
OR
Click <a href="<?=site_url();?>/address-lookup/">here</a> to search using your address</pre></h3>
			<?php foreach ( $constituencies as $constituency_id ) :
				$constituency = get_constituency( $constituency_id ); ?>
				<div class="mini_maps" style="text-align:center; line-height: 36px;">
					<div class="mini_maps_con">
						<p class="small"><strong><a href="<?php echo $constituency['url']; ?>"><?php echo $constituency['name']; ?></a></strong></p>
						<a href="<?php echo $constituency['url']; ?>" title="Click to see the candidates.">
							<div class="mini_map_image float-left" style="margin-left:150px;">
								<?php if($constituency['map_id']):?>
										<?php echo wp_get_attachment_image($constituency['map_id'], 'map_thumb', false, array( 'alt' => $constituency['name'] ) ); ?>
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
