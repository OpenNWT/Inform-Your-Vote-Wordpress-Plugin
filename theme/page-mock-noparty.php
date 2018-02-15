<?php
/**
* Demonstration page for the new election results listing with no parties.
*
* Authors: Joel Hurtig
* @package Election_Data_Theme
* @since Election_Data_Theme 1.1
*/

$constituency = get_constituency( $party_id = get_queried_object()->term_id );
$constituency_id = $constituency['id'];

global $ed_post_types;

get_header(); ?>

<section id="primary" class="content-area">
	<div id="content" class="site-content" role="main">

	<?php

	foreach ( $constituency['children'] as $name => $child ) :

		$child_constituency = get_constituency($child[ 'id' ]);
		//print_r($child_constituency['grandchildren']);
		if ($child_constituency['grandchildren']) {
			// if grandchildren detected, grab the new id
			// then grab candidates linked to that id
			foreach ($child_constituency['grandchildren'] as $grandkid) {
				//print_r($child_constituency['grandchildren']);
				//$test = $child_constituency['grandchildren'];
				//echo $grandkid['name'] . ', ' . $grandkid['id'] ;
				//echo ' has been echoed... ';
				//echo ' at ' . $name .  '<br />';

				$winners_total = $grandkid['number_of_winners'];
				$can_array = array();
				$sort_vote = array();

				$query_args = array(
					'post_type' => $ed_post_types['candidate'],
					//'order' => 'ASC',
					//'orderby' => array ('candidate_votes' => 'ASC'),
					'constituency' => $grandkid['name'], //NOTE: This must match the slug associated with the constituency
				);
				$candidates = array();
				$query = new WP_Query( $query_args );

				while ( $query->have_posts() ) {
					$query->the_post();
					$candidates[$query->post->ID] = get_candidate( $query->post->ID, true );
				}

				foreach( $candidates as $can ){
					$can_array[] = array('candidate_votes' => $can['candidate_votes'], 'name' => $can['name'], 'id' => $can['id']);
				}

				foreach( $can_array as $v=>$key ) {
					$sort_vote[] = $key['candidate_votes'];
				}
				array_multisort( $sort_vote, SORT_DESC, $can_array );

				$winner = 0;
				foreach ( $can_array as $r=>$result ) {
					if ($winner < $winners_total) {
						echo $result['name'] . ' received ' . $result['candidate_votes'] . ' votes this election. They have won a seat.<br/>';
						$winner++;
					} else {
						echo $result['name'] . ' received ' . $result['candidate_votes'] . ' votes this election.<br />';
					}
				}
			}
		}

	endforeach; ?>


  </div>
</section>

<?php get_footer(); ?>
