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

$electoral_division_term = Election_Data_Option::get_option('electoral-division-term') ?: "Electoral Division";

get_header();?>

<?php if ( $constituency['children'] ) : ?>
  <h2 class="hidden_block_when_mobile">Select Your <?php echo $constituency['name']; ?> <?php echo $electoral_division_term ?></h2>
  <p>If you do not know your <?php echo strtolower($electoral_division_term) ?>, please use <a target="_blank" href="https://www.electionsnwt.ca/en/maps">Elections NWT's district lookup tool</a> and then return to our site.</p>
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
        <h3>
          <?php echo $constituency['name']; ?><br class="visible_block_when_mobile">
          <?php echo $electoral_division_term ?>s
        </h3>

        <ul>
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
        </ul>
        <br>
		</div>
   </div>
<?php else :
	$candidate_references = array();
	?>

  <p class="breadcrumbs">
    <a href="<?= Election_Data_Option::get_option( 'candidate-link', '/' ) ?>">Candidates</a> / 
    <?php if(isset($constituency['parent_name'])): ?>
      <a href="<?= $constituency['parent_url'] ?>"><?= $constituency['parent_name'] ?></a> /
    <?php endif ?>
    <a href="<?php echo $constituency['url']; ?>"><?php echo esc_html( $constituency['name'] ); ?></a> 
  </p>
	<h2 class="constituency_header"><?php echo $constituency['name']; ?></h2>
  <br>
  <!--
  <p>
    <a class="hover_underline" href="#news">Read news articles that mentions these candidates</a>.
  </p>
-->
  <p>
    <?php if ($constituency['number_of_winners'] < 2) : ?>
    There is <?php echo $wp_query->post_count; ?> candidate in this election race.
  <?php else : ?>
    There are <?php echo $wp_query->post_count; ?> candidates competing for <?php echo $constituency['number_of_winners'] ?> seats in this race.
  <?php endif ?>
    <span class="small grey">Candidates are displayed in random order.</span>
  </p>
  <?php if ($wp_query->post_count == $constituency['number_of_winners']): ?>
    <p class="grey"><b>
      <?php if ($constituency['number_of_winners'] == 1): ?>
        Because the candidate in this race is unopposed, they win the election race by acclamation.
      <?php else: ?>
        Because the number of candidates in this race matches the number of seats, all candidates win by acclamation.
      <?php endif ?>
    </b></p>
  <?php endif ?>
  <p>
    Candidates with publicly available email addresses were sent our 2023 Election Questionnaire on September 17, 2023.
  </p>
  <p>
    You can read the questionnaire responses below by following each candidate's response link.
  </p>
  <div class="flow_it politicians">
    <?php display_constituency_candidates( $wp_query, $constituency, $candidate_references ); ?>
  </div>
  <p class="small grey">
     Our candidate data retrieval processes are available in <a href="/frequently-asked-questions">our FAQ</a>.
  </p>
	<div class="flow_it" >
		<?php if ( !empty( $constituency['details'] ) ) : ?>
      <br>
      <br>
			<div class="three_columns constit_description">
      <?php if ($constituency['name'] === 'Mayoral Candidates'): ?>
        <h2>Roles and Responsibilities of Winnipeg's Mayor</h2>
      <?php else: ?>
        <h2>The <?php echo $constituency['name']; ?> <?php echo $electoral_division_term ?></h2>
      <?php endif ?>
				<p><?php echo $constituency['details']; ?></p>
      <br>
			</div>
		<?php endif; ?>
    <div class="three_columns latest_news_small">
      <h2 id="news">Latest Candidate News</h2>
      <p class="grey small">Articles that mention candidates from this race.</p>
      <?php $article_count = Election_Data_Option::get_option('news-count-constituency', 10);
      display_news_titles( $candidate_references, false, $article_count ); ?>
      <br><br>
      <p class="grey small"><?php echo Election_Data_Option::get_option( 'news-scraping-subheading' ) ?></p>
    </div>
  </div>
<?php endif; ?>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
