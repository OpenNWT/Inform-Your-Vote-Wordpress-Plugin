<?php

/**
 *	Standard template for displaying candidates in a constituency.
 *  @author Robert Burton
 */

$constituency = get_constituency( $party_id = get_queried_object()->term_id );
$constituency_id = $constituency['id'];

$child_constituencies = "";

if($constituency['grandchildren']){
	$child_constituencies = 'grandchildren';
} else{
	$child_constituencies = "children";
}

get_header();?>

<?php if ( $constituency['children'] ) : ?>
	<h2>Select Your <?php echo $constituency['name']; ?> Constituency</h2>
	<p class="small grey hidden_block_when_mobile">Find by name or click the map.</p>
  <?php if ($constituency['name'] == 'Trustee Candidates'): ?>
    <br>
    <h3>School trustee candidates will not be displayed until the September 12th to September 18th nomination period.</h3>
    <br><br>
  <?php endif ?>
	<div class='flow_it'>
		<?php if ( $constituency['map_id'] ) : ?>
			<div class='two_columns hidden_block_when_mobile'>
				<?php echo wp_get_attachment_image($constituency['map_id'], 'map', false, array( 'alt' => $constituency['name'], 'usemap' => '#constituency_map', 'class' => 'highmap'	) ); ?>
				<map id="constituency_map" name="constituency_map">

						<?php foreach ( $constituency[$child_constituencies] as $name => $child ) :?>
							<?php if ( $child['coordinates'] ) : ?>
								<area alt='<?php echo $name; ?>' coords='<?php echo $child['coordinates']; ?>' href='<?php echo $child['url']; ?>' shape='poly' title='<?php echo $name; ?>'>
							<?php endif; ?>
						<?php endforeach; ?>

				</map>
			</div>
		<?php endif;?>
		<div class='one_column map_nav'>
				<h3>Select a <?php echo $constituency['name']; ?> Constituency</h3>

					<?php foreach ( $constituency['children'] as $name => $child ) :?>
						<?php $child_constituency = get_constituency($child['id']);?>

						<?php if($child_constituency['children']):?>
							<h3><?php echo $name; ?></h3>
							<ul>
								<?php foreach ( $child_constituency['children'] as $name => $child) :?>
									<li><a href="<?php echo $child['url']; ?>"><?php echo $name; ?></a></li>
								<?php endforeach;?>
							</ul>
						<?php else:?>
							<li><a href="<?php echo $child['url']; ?>"><?php echo $name; ?></a></li>
						<?php endif;?>

					<?php endforeach; ?>
            <br>
		</div>
   </div>
<?php else :
	$candidate_references = array();
	?>

  <p class="breadcrumbs">
    <a href="/who-constituency">Candidates</a> / 
    <?php if(isset($constituency['parent_name'])): ?>
      <a href="<?= $constituency['parent_url'] ?>"><?= $constituency['parent_name'] ?></a> /
    <?php endif ?>
    <a href="<?php echo $constituency['url']; ?>"><?php echo esc_html( $constituency['name'] ); ?></a> 
  </p>
	<h2><?php echo $constituency['name']; ?></h2>
  <?php if ($constituency['parent_name'] == 'Trustee Candidates'): ?>
    <br>
    <h3>School trustee candidates will not be displayed until the September 12th to September 18th nomination period.</h3>
    <br><br>
  <?php else: ?>
    <p>
      <?php if ($constituency['number_of_winners'] < 2) : ?>
      There are <?php echo $wp_query->post_count; ?> candidates in this electoral division.
    <?php else : ?>
      There are <?php echo $wp_query->post_count; ?> candidates competing for <?php echo $constituency['number_of_winners'] ?> seats in this race.
    <?php endif ?>
      <em class="small grey">Candidates are displayed in random order.</em>
    </p>
    <div class="flow_it politicians">
      <?php display_constituency_candidates( $wp_query, $constituency, $candidate_references ); ?>
    </div>
    <p>
      <em class="small grey">Our candidate data retrieval process is available in our FAQ.</em>
    </p>
  <?php endif ?>
	<div class="flow_it" >
		<?php if ( !empty( $constituency['details'] ) ) : ?>
			<div class="three_columns constit_description">
				<b><?php echo $constituency['name']; ?></b>
				<p><?php echo $constituency['details']; ?></p>
			</div>
		<?php endif; ?>
    <div class="three_columns latest_news_small">
      <h2 id="news">Latest Candidate News</h2>
      <p class="grey small">Articles that mention candidates from this race.</p>
      <?php $article_count = Election_Data_Option::get_option('news-count-constituency', 10);
      display_news_titles( $candidate_references, false, $article_count ); ?>
      <p class="grey small"><?php echo Election_Data_Option::get_option( 'news-scraping-subheading' ) ?></p>
    </div>
  </div>
<?php endif; ?>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
