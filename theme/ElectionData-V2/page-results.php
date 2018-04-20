<?php

/**
* A page to display results in.
* @since Election_Data_Theme 1.1
* @author Joel Hurtig
*/

global $is_party_election;
global $ed_post_types;
?>
<a name="top"></a>
<?php get_header(); ?>

<div class = "address_lookup_page">
  <div class="search_text">
    <span class="enter_address_text">Enter Your Address To Reveal The Results Of Your Area.</span><br />
  </div>

  <form id="address_lookup_form" method="POST" action="">
    <input type="text" name="street_number"  id="street_number" placeholder='Street Number'>
    <input type="text" name="street_name"  id="street_name" placeholder="Street Name">
    <input type="hidden" name="page" id="page" value="<?=wp_title('',true);?>" >
    <input type="submit" name="submit" id="submit" value="Find">
    <!-- <input type="button" name = "delete" id = "delete" value = "Delete" > -->
  </form>

  <div class="loading">
    <img class="gif" src="/wp-content/themes/ElectionData/ElectionData-V2/images/loading.gif" />
  </div>

  <div id ="candidates" class = "animated fadeIn">

  </div>
  <br />

</div>
</div>

<a href="?results=all"> Reveal All Results</a>
<div id = "all_results_html">
  <?php $all_res = filter_input(INPUT_GET, 'results', FILTER_SANITIZE_STRING);
  if ($all_res == 'all'):
    ?>
    <a name="top"></a>
    <section id="primary" class="content-area">
      <div id="content" class="site-content" role="main">
        <?php
        $c_constituencies = array(); //child constituencies

        foreach ( $constituency['children'] as $name => $child ) :
          $child_constituency = get_constituency( $child['id'] );
          if ( $child_constituency['children'] == array() ) : //empty array means no children

            $can_array = array();
            $sort_vote = array();
            $winner = 0;
            $winners_total = $child_constituency['number_of_winners'];
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
              $num_votes += $can['candidate_votes'];
            }
            // for each candidate, add their votes to a seperate array
            foreach( $can_array as $v=>$key ) {
              $sort_vote[] = $key['candidate_votes'];
            }
            // sort the candidates by votes
            array_multisort( $sort_vote, SORT_DESC, $can_array );

            // for each candidate, print out results
            if ( !empty( $can_array ) ):
              $winner = 0; ?>
              <div>
                <h3 style="text-align:center;"><?php echo $child_constituency['name'];?></h3>
                <table class = "election_table">
                  <tr> <th class="election_th">Candidate</th>
                    <?php if ($is_party_election ): ?> <th class="election_th">Party</th> <?php endif; ?>
                    <th class="election_th">Votes</th>
                    <th class="election_th">Percentage</th>
                  </tr>
                  <?php
                  foreach( $can_array as $r=>$result ) :
                    $can_party = get_party_from_candidate( $result['id'] ); ?>
                    <tr class="election_tr" style="color:<?php echo $can_party['colour'] ?>;">
                      <?php if ( $winner < $winners_total ) : ?>
                        <tr style="font-weight:bold">
                          <td class="election_td"><?php echo $result['name'] ?></td>
                          <td class="election_td"></td>
                          <td class="election_td"><?php echo $result['candidate_votes']?></td>
                          <td class="election_td"><?php if ($result['candidate_votes']>0) {
                            echo round( ( $result['candidate_votes'] / $num_votes ), 3 ) * 100 . '%';
                          }	?>			</td>
                        </tr>
                        <?php $winner++;
                        else : ?>
                        <tr>
                          <td class = "election_td"><?php echo $result['name'] ?></td>
                          <td class = "election_td"></td>
                          <td class = "election_td"><?php echo $result['candidate_votes']?></td>
                          <td class="election_td"><?php if ($result['candidate_votes']>0) {
                            echo round( ( $result['candidate_votes'] / $num_votes ), 3 ) * 100 . '%';
                          }	?>			</td>
                        </tr>
                        <?php
                      endif; //for winners
                    endforeach; //end result foreach ?>
                    <tr><td>Number of votes: <?php echo $num_votes ?> </td></tr>
                    <tr><td><a href="#top">Back to top</a></td></tr>
                  </table>
                  <br />
                </div>
                <?php
              else: //else no candidates with votes
                ?> <p style="text-align:center">No results for this constituency.</p>
                <?php
              endif; //endif for candidate array
              else : //else it has children, so add it to the children array
                $c_constituencies[$name] = $constituency['children'][$name];
              endif;

            endforeach;

            foreach ( $c_constituencies as $name => $child ) :
              $child_constituency = get_constituency( $child['id'] ); ?>
              <a name = "<?php echo $name ?>"></a>
              <h3 style="text-align:center;"><?php echo $name; ?></h3>
              <div id="<?php echo $name?>">
                <?php foreach ( $child_constituency['children'] as $c_name => $c_child ): ?>
                  <a name="<?php echo $c_name; ?>"></a>
                  <p style="text-align:center;"><a href="<?php echo $c_child['url']; ?>"><?php echo $c_name; ?></a></p>
                  <?php
                  $g_constituencies = get_constituency( $c_child['id'] );
                  $winners_total = $g_constituencies['number_of_winners'];
                  $can_array = array();
                  $sort_vote = array();
                  $num_votes = 0;

                  $query_args = array(
                    'post_type' => $ed_post_types['candidate'],
                    'order' => 'ASC',
                    'orderby' => array ('candidate_votes' => 'ASC'),
                    'constituency' => $c_name,
                  );
                  $candidates = array();
                  $query = new WP_Query( $query_args );
                  while ( $query->have_posts() ) {
                    $query->the_post();
                    $candidates[$query->post->ID] = get_candidate( $query->post->ID, true );
                  }

                  foreach( $candidates as $can ){
                    $can_array[] = array( 'candidate_votes' => $can['candidate_votes'], 'name' => $can['name'], 'id' => $can['id'] );
                    $num_votes += $can['candidate_votes'];
                  }
                  foreach( $can_array as $v=>$key ) {
                    $sort_vote[] = $key['candidate_votes'];
                  }
                  array_multisort( $sort_vote, SORT_DESC, $can_array );

                  if ( !empty( $can_array ) && $can_array[0]['candidate_votes'] != 0 ) :
                    $winner = 0;
                    ?>
                    <?php if ( $winners_total > 1 ): ?>
                      <p style="text-align:center">There are <?php echo $winners_total ?> winners in this constituency.</p>
                    <?php endif; ?>
                    <table class = "election_table">
                      <tr> <th class="election_th">Candidate</th>
                        <?php if ($is_party_election ): ?> <th class="election_th">Party</th> <?php endif; ?>
                        <th class="election_th">Votes</th>
                        <th class="election_th">Percentage</th>
                      </tr>
                      <?php
                      foreach( $can_array as $r=>$result ) :
                        $can_party = get_party_from_candidate( $result['id'] ); ?>
                        <?php if ( $winner < $winners_total ) : ?>
                          <tr style="color: <?php echo $can_party['colour'] ?>;font-weight: bold;">
                            <td class="election_td"><?php echo $result['name'] ?></td>
                            <?php if ( $is_party_election ): ?>
                              <td class="election_td"><?php echo $can_party['name']; ?></td>
                            <?php endif; ?>
                            <td class="election_td"><?php echo $result['candidate_votes']?></td>
                            <td class="election_td"><?php if ($result['candidate_votes']>0) {
                              echo round( ( $result['candidate_votes'] / $num_votes ), 3 ) * 100 . '%';
                            }	?>			</td>
                          </tr>
                          <?php $winner++;
                          else : ?>
                          <tr style="color:<?php echo $can_party['colour'] ?>;">
                            <td class="election_td"><?php echo $result['name'] ?></td>
                            <?php if ( $is_party_election ): ?>
                              <td class="election_td"><?php echo $can_party['name']; ?></td>
                            <?php endif; ?>
                            <td class="election_td"><?php echo $result['candidate_votes']?></td>
                            <td class="election_td"><?php if ($result['candidate_votes']>0) {
                              echo round( ( $result['candidate_votes'] / $num_votes ), 3 ) * 100 . '%';
                            }	?>			</td>
                          </tr>
                          <?php
                        endif; //for winners
                      endforeach; //for results ?>
                      <tr><td>Number of votes: <?php echo $num_votes ?> </td></tr>
                      <tr><td><a href="#top">Back to top</a></td></tr>
                    </table>
                    <br />
                  <?php else : ?>
                    <p style="text-align:center">No results for this constituency.</p>
                  <?php endif;

                  if ( !empty( $g_constituencies['children'] ) ) :

                    foreach ($g_constituencies['children'] as $g_child) :
                      $new_constituency = get_constituency( $g_child['id'] );
                      ?>
                      <a name="<?php echo $new_constituency['name'] ?>"></a>
                      <p style="text-align:center;"><a href="<?php echo $new_constituency['url']; ?>"><?php echo $new_constituency['name']; ?></a></p>
                      <?php
                      $winners_total = $new_constituency['number_of_winners'];
                      $can_array = array();
                      $sort_vote = array();
                      $query_args = array(
                        'post_type' => $ed_post_types['candidate'],
                        'constituency' => $new_constituency['name'], //NOTE: This must match the slug associated with the constituency
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

                      if ( !empty( $can_array ) ) :

                        $winner = 0; ?>
                        <div id="<?php echo $new_constituency['name']?>">
                          <?php if ( $winners_total > 1 ): ?>
                            <p style="text-align:center">There are <?php echo $winners_total ?> winners in this constituency.</p>
                          <?php endif; ?>
                          <table class = "election_table">
                            <tr>
                              <th class = "election_th">Candidate</td>
                                <?php if ($is_party_election ): ?> <th class="election_th">Party</th> <?php endif; ?>
                                <th class = "election_th">Votes</td>
                                  <th class = "election_th">Percentage</td>
                                  </tr>
                                  <?php foreach( $can_array as $r=>$result ) :
                                    $can_party = get_party_from_candidate( $result['id'] ); ?>
                                    <tr class="election_tr" style="color:<?php echo $can_party['colour'] ?>;">
                                      <?php if ( $winner < $winners_total ) : ?>
                                        <tr style="font-weight:bold">
                                          <td class="election_td"><?php echo $result['name'] ?></td>
                                          <?php if ( $is_party_election ): ?>
                                            <td class="election_td"><?php echo $can_party['name']; ?></td>
                                          <?php endif; ?>
                                          <td class="election_td"><?php echo $result['candidate_votes']?></td>
                                          <td class="election_td"><?php if ($result['candidate_votes']>0) {
                                            echo round( ( $result['candidate_votes'] / $num_votes ), 3 ) * 100 . '%';
                                          }	?>			</td>
                                        </tr>
                                        <?php $winner++;
                                        else : ?>
                                        <tr>
                                          <td class="election_td"><?php echo $result['name'] ?></td>
                                          <?php if ( $is_party_election ): ?>
                                            <td class="election_td"><?php echo $can_party['name']; ?></td>
                                          <?php endif; ?>
                                          <td class="election_td"><?php echo $result['candidate_votes']?></td>
                                          <td class="election_td"><?php if ($result['candidate_votes']>0) {
                                            echo round( ( $result['candidate_votes'] / $num_votes ), 3 ) * 100 . '%';
                                          }	?>			</td>
                                        </tr>
                                        <?php
                                      endif; //for winners
                                    endforeach; //for results ?>
                                    <tr><td>Number of votes: <?php echo $num_votes ?> </td></tr>
                                    <tr><td><a href="#top">Back to top</a></td></tr>
                                  </table>
                                  <br />
                                <?php else: ?>
                                  <p style="text-align:center">No results for this constituency.</p>	<?php
                                endif; //endif for empty candidate array
                              endforeach; //end grandchildren foreach

                            endif; // end grandchildren if
                          endforeach; //end children foreach  ?>
                        </div>
                        <?php
                      endforeach; //end c_constituencies foreach ?>
                    </div>
                  </section>

                <?php endif; ?>


              </div>

              <?php get_footer(); ?>
