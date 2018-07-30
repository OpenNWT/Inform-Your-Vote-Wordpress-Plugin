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
global $is_address_lookup_tool;

$constituencies = get_root_constituencies();
?>
<?php get_header(); ?>
<?php if ( $constituencies ) : ?>
		<div class="flow_it who_constituencies_lists">
			<h2><?php echo Election_Data_Option::get_option( 'constituency-label', 'Electoral Races' ); ?></h2>
			<p>
        Please select an electoral race to view the candidates.
        <?php if($is_address_lookup_tool):?>
          OR
          Click <a href="<?=site_url();?>/address-lookup/">here</a> to search using your address
        <?php endif;?>
      </p>
      <br><br>
			<?php foreach ( $constituencies as $constituency_id ) :
				$constituency = get_constituency( $constituency_id ); ?>
				<div class="mini_maps one_column">
            <h4><a href="<?php echo $constituency['url']; ?>"><?php echo $constituency['name']; ?></a></h4>
            <a class="map" href="<?php echo $constituency['url']; ?>" title="Click to see the candidates.">
              <?php if($constituency['map_id']):?>
                  <?php echo wp_get_attachment_image($constituency['map_id'], 'map_thumb', false, array( 'alt' => $constituency['name'] ) ); ?>
              <?php else:?>
                  <?php echo wp_get_attachment_image( Election_data_option::get_option('missing_constituency')); ?>
              <?php endif;?>
            </a>
				</div>
			<?php endforeach; ?>
		</div>
<?php endif;?>
<?php get_footer(); ?>
