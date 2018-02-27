<?php
/**
* Demonstration page for the new election results listing.
*
* Authors: Joel Hurtig and Simranjeet Singh Hunjan
* @package Election_Data_Theme
* @since Election_Data_Theme 1.1
*/

global $is_party_election;

$constituency = get_constituency( $party_id = get_queried_object()->term_id );
$constituency_id = $constituency['id'];

get_header(); ?>

<section id="primary" class="content-area">
  <div id="content" class="site-content" role="main">

    <?php foreach ( $constituency['children'] as $name => $child ) : ?>
      <?php $child_constituency = get_constituency($child[ 'id' ]);
      if ($child_constituency[ 'children' ] ) :?>
      <h3><?php echo $name; ?></h3>
      <ul>
        <?php foreach ( $child_constituency['children'] as $g_name => $g_child) :?>
          <li><a href="<?php echo $g_child['url']; ?>"><?php echo $g_name; ?></a></li>
          <?php
          //store arrays and count
          $can_array = array();
          $sort_vote = array();
          //$total_votes = array();
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
          ?>
          <table>
            <tr> <th>Candidate</th>
              <?php if ($is_party_election ): ?> <th>Party</th> <?php endif ?>
              <th>Votes</th>
              <th>Percentage</th>
            </tr>
            <?php
            foreach($can_array as $r=>$result) :
              $can_party = get_party_from_candidate( $result['id'] ); ?>
              <tr style="color:<?php echo $can_party['colour'] ?>;">
                <td><?php echo $result['name']; ?></td>
                <?php if ( $is_party_election ):
                   $total_votes[$can_party['name']] += $result['candidate_votes']; ?>
                  <td><?php echo $can_party['name']; ?></td>
                <?php endif; ?>
                <td><?php echo $result['candidate_votes']; ?></td>
                <td><?php if ($result['candidate_votes']>0) {
                  echo round( ($result['candidate_votes'] / $num_votes), 3) * 100 . '%';
                }	?></td>
              </tr>
            <?php endforeach; ?>
          </table>
          <p>Number of votes: <?php echo $num_votes ?> </p> <br />
        <?php endforeach; ?>
      </ul>
    <?php else:?>
      <li><a href="<?php echo $child['url']; ?>"><?php echo $name; ?></a></li>
    <?php endif;?>
  <?php endforeach; ?>

  <?php if ($is_party_election ) {
			foreach ($total_votes as $party_name => $party_total) {
				echo '<p>Those under the ' . $party_name . ' banner received ' . $party_total . ' votes this election.';
			}
		}?>

</div><!-- #content .site-content -->
</section><!-- #primary .content-area -->
<?php
get_footer();
?>
