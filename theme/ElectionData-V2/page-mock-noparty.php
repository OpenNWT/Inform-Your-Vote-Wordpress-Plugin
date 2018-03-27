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
			?><pre><?php print_r($child_constituency); ?> </pre>
			<?php
			//if children array is empty, assume mayoral
			if ($child_constituency['children'] == array()) {
				$can_array = array();
				$sort_vote = array();
				$query_args = array(
					'post_type' => $ed_post_types['candidate'],
					'constituency' => $child_constituency['name'],
				);
				$candidates = array();
				$query = new WP_Query( $query_args );

				while ( $query->have_posts() ) {
					$query->the_post();
					$candidates[$query->post->ID] = get_candidate( $query->post->ID, true );
				}

				// for each candidate, grab their votes, name and id
				foreach( $candidates as $can ){
					$can_array[] = array( 'candidate_votes' => $can['candidate_votes'], 'name' => $can['name'], 'id' => $can['id'] );
				}

				// for each candidate, add their votes to a seperate array
				foreach( $can_array as $v=>$key ) {
					$sort_vote[] = $key['candidate_votes'];
				}
				// sort the candidates by votes
				array_multisort( $sort_vote, SORT_DESC, $can_array );

				// for each candidate, print out results
				foreach ( $can_array as $r=>$result ) {
					echo $result['name'] . ' received ' . $result['candidate_votes'] . ' votes this election.<br />';
				}
				echo '<br />';
			}

			if ( $child_constituency['grandchildren'] ) {

				// if grandchildren detected, grab the new id
				// then grab candidates linked to that id
				foreach ( $child_constituency['grandchildren'] as $grandkid ) {

					$winners_total = $grandkid['number_of_winners'];
					$can_array = array();
					$sort_vote = array();
					$query_args = array(
						'post_type' => $ed_post_types['candidate'],
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
					// sort the array, such that the candidate with the highest vote count is first
					array_multisort( $sort_vote, SORT_DESC, $can_array );

					$winner = 0;
					echo 'Within ' . $grandkid['name'] . ':<br />';
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
