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
$ballot = null;
?>
<?php get_header(); ?>
<?php if ( $constituencies ) : ?>
		<div class="flow_it who_constituencies_lists">
			<h2><?php echo Election_Data_Option::get_option( 'constituency-label', 'Your Winnipeg Election Candidates' ); ?></h2>
      <p>Find your election candidates by search or follow the maps below.</p>
      <p>If you need to find your voting location <a target="_blank" href="https://www.winnipeg.ca/clerks/election/election2018/registration/WhereDoIVote.stm#where-do-i-vote">use the city's Where Do I Vote tool</a>.</p>
      <br>
      <iframe style="width: 100%; height: 220px;" id="address_frame" src="/we2018address/index.html"></iframe>
			<p>
        <?php if($is_address_lookup_tool):?>
         <!-- OR
          Click <a href="<?=site_url();?>/address-lookup/">here</a> to search using your address -->
        <?php endif;?>
      </p>
      <h2>All Winnipeg Election Candidates</h2>
      <p>Click maps to list candidates by type and ward.</p>
			<?php foreach ( $constituencies as $constituency_id ) :
				$constituency = get_constituency( $constituency_id ); 
        if ($constituency['name'] == 'Ballot Question') { $ballot = $constituency; continue; } ?>
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

  <?php if ($ballot): ?>
    <br>
    <br>
    <br>
		<div class="flow_it who_constituencies_lists">
      <h2>The Ballot Question</h2>
      <br>
      <p>On July 19, 2018, Winnipeg City Council directed the City Clerk to place the following question on the ballot of the 2018 Municipal Election:</p>
      <p><a href="https://www.winnipegelection.ca/constituencies/portage-and-main-ballot-question/">“Do you support the opening of Portage and Main to pedestrian crossings? YES/NO”</a></p>
      <div class="mini_maps one_column">
          <h4><a href="<?php echo $ballot['url']; ?>"><?php echo $ballot['name']; ?></a></h4>
          <a class="map" href="<?php echo $ballot['url']; ?>" title="Click to see the candidates.">
            <?php if($ballot['map_id']):?>
                <?php echo wp_get_attachment_image($ballot['map_id'], 'map_thumb', false, array( 'alt' => $ballot['name'] ) ); ?>
            <?php else:?>
                <?php echo wp_get_attachment_image( Election_data_option::get_option('missing_constituency')); ?>
            <?php endif;?>
          </a>
      </div>
    </div>
  <?php endif; ?>

<?php endif;?>
<?php get_footer(); ?>
