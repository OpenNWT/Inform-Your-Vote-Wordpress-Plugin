<?php
/**
* Demonstration page for the new election results listing.
*
* Authors: Joel Hurtig & Simranjeet Singh Hunjan
* @package Election_Data_Theme
* @since Election_Data_Theme 1.1
*/

global $is_party_election;

$constituency = get_constituency( $party_id = get_queried_object()->term_id );
$constituency_id = $constituency['id'];

global $ed_post_types; ?>
<a name="top"></a>
<?php get_header(); ?>

<section id="primary" class="content-area">
  <div id="content" class="site-content" role="main">
    <?php
    $c_constituencies = array(); //child constituencies
    $g_constituencies = array(); //grandchild constituencies

    foreach ( $constituency['children'] as $name => $child ) :
		$child_constituency = get_constituency( $child['id'] );
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
		else {
			$c_constituencies[$name] = ($constituency['children'][$name]);
		}

	endforeach;

	foreach ( $c_constituencies as $name => $child ) :
		$child_constituency = get_constituency( $child['id'] );
		?>
		<h3 style="text-align:center;"><a name = "<?php echo $name ?>"></a><?php echo $name; ?></h3>
		<ul>
        <?php foreach ( $child_constituency['children'] as $g_name => $g_child) :?>
          <p style="text-align:center;"><a href="<?php echo $g_child['url']; ?>"><?php echo $g_name; ?></a></p>
          <?php
          //store arrays and count
          $can_array = array();
          $sort_vote = array();
          //$total_votes = array();
		  $winner = true;
          $num_votes = 0;
          global $ed_post_types;

          $query_args = array(
            'post_type' => $ed_post_types['candidate'],
            'order' => 'ASC',
            'orderby' => array ('candidate_votes' => 'ASC'),
            'constituency' => $g_name,
          );
          $candidates = array();
          $query = new WP_Query( $query_args );
          while ( $query->have_posts() ) {
            $query->the_post();
            $candidates[$query->post->ID] = get_candidate( $query->post->ID, true );
          }

          foreach( $candidates as $can ){
            $can_array[] = array('candidate_votes' => $can['candidate_votes'], 'name' => $can['name'], 'id' => $can['id']);
            $num_votes += $can['candidate_votes'];
          }

          foreach( $can_array as $v=>$key ) {
            $sort_vote[] = $key['candidate_votes'];
          }
          array_multisort( $sort_vote, SORT_DESC, $can_array );

		  if (!empty($can_array) && $can_array[0]['candidate_votes'] != 0) :
          ?>
          <table class = "election_table">
            <tr> <th class="election_th">Candidate</th>
              <?php if ($is_party_election ): ?> <th class="election_th">Party</th> <?php endif; ?>
              <th class="election_th">Votes</th>
              <th class="election_th">Percentage</th>
            </tr>
            <?php
            foreach($can_array as $r=>$result) :
              $can_party = get_party_from_candidate( $result['id'] ); ?>
              <tr class="election_tr" style="color:<?php echo $can_party['colour'] ?>; <?php if ($winner) {echo "font-weight: bold;"; $winner = false;}; ?>">
                <td class="election_td"><?php echo $result['name']; ?></td>
                <?php if ( $is_party_election ):
                   $total_votes[$can_party['name']] += $result['candidate_votes']; ?>
                   <td class="election_td"><?php echo $can_party['name']; ?></td>
                <?php endif; ?>
                <td class="election_td"><?php echo $result['candidate_votes']; ?></td>
                <td class="election_td"><?php if ($result['candidate_votes']>0) {
                  echo round( ($result['candidate_votes'] / $num_votes), 3) * 100 . '%';
                }	?></td>
              </tr>
            <?php endforeach; //end result foreach ?>
			<tr><td>Number of votes: <?php echo $num_votes ?> </td></tr>
			<tr><td><a href="#top">Back to top</a></td></tr>
          </table>
          <br />
        <?php else : ?>
		<p style="text-align:center">No results for this constituency.</p>
		<?php endif;
		endforeach; //end children foreach  ?>
      </ul>
	<?php
	endforeach; //end c_constituencies foreach

	foreach ( $c_constituencies as $name => $child ) :
		$child_constituency = get_constituency( $child['id'] );

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

					if (!empty($can_array)) :
						$winner = 0; ?>
						<table class = "election_table">
						<p style = "text-align:center">
						<a="<?php echo $grandkid['name']?>"><?php echo $grandkid['name'] ?>
						</a></p>
						<tr>
							<td class = "election_th">Candidate</td>
							<td class = "election_th">Votes</td>
							<td class = "election_th">Won?</td>
						</tr>
						<?php
						foreach ( $can_array as $r=>$result ) :
							if ($winner < $winners_total) : ?>
								<tr style="font-weight:bold">
									<td class = "election_td"><?php echo $result['name'] ?></td>
									<td class = "election_td"><?php echo $result['candidate_votes']?></td>
									<td class = "election_td">Won</td>
								</tr>
								<?php $winner++;
							else : ?>
								<tr>
									<td class = "election_td"><?php echo $result['name'] ?></td>
									<td class = "election_td"><?php echo $result['candidate_votes']?></td>
									<td class = "election_td"></td>
								</tr>
							<?php
							endif; //for winners
						endforeach; //for results ?>
						</table> <?php
					endif; // for can_array
				}
			}
		endforeach; //end grandchildren foreach ?>
	<br />
</div>
</section>
<?php get_footer(); ?>
