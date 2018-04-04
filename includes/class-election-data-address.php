<?php
/**
 * The file that defines the address custom post type.
 *
 *
 * @link       http://opendemocracymanitoba.ca/
 * @since      1.0.0
 *
 * @package    Election_Data
 * @subpackage Election_Data/includes
 */
require_once plugin_dir_path( __FILE__ ) . 'class-custom-post.php';
require_once plugin_dir_path( __FILE__ ) . 'class-post-import.php';
require_once plugin_dir_path( __FILE__ ) . 'class-post-export.php';
global $is_party_election;
global $ed_post_types;
$ed_post_types['address'] = 'ed_addresses';
/**
 * Sets up and handles the address custom post type.
 *
 *
 * @since      1.0.0
 * @package    Election_Data
 * @subpackage Election_Data/includes
 * @author     Simranjeet Singh Hunjan
 */
class Election_Data_Address {
    /**
     * The ED_Custom_Post_Type object representing the addresss custom post type.
     *
     * @var object
     * @access protected
     * @since 1.0
     *
     */
    protected $custom_post;
    /**
     * Stores the name of the custom post type.
     *
     * @var string
     * @access public
     * @since 1.0
     *
     */
    public $post_type;
    /**
     * Constructor
     *
     * @access public
     * @since 1.0
     * @param boolean $define_hooks
     *
     */
    public function __construct( $define_hooks = true ) {
        global $ed_post_types;
        $this->post_type = $ed_post_types['address'];
        $args = array(
            'custom_post_args' => array(
                'labels' => array(
                    'name' => __( 'Addresses' ),
                    'singular_name' => __( 'Address' ),
                    'add_new_item' => __( 'Add New Address' ),
                    'edit_item' => __( 'Edit Address' ),
                    'new_item' => __( 'New Address' ),
                    'view_item' => __( 'View Address' ),
                    'search_items' => __( 'Search Address' ),
                    'not_found' => __( 'No Address found' ),
                    'not_found_in_trash', __( 'No Addresses found in Trash' ),
                ),
                'description' => __( 'An address for the address lookup tool.' ),
                'public' => true,
                'menu_position' => 5,
                //'menu_icon' => plugins_url( 'images/address.png', dirname( __FILE__ ) ), //TODO: Create a address image,
                'supports' => array( 'title', 'thumbnail' ),
                'taxonomies' => array( '' ),
                'has_archive' => true,
                'query_var' => __( 'address' ),
                'rewrite' => array( 'slug' => __( 'address' ), 'with_front' => false ),
            ),
            'admin_column_names' => array( 'title' => __( 'Street Address' ) ),
            'admin_field_names' => array( 'title' => __( 'Name' ), 'enter_title_here' =>  __( 'Enter the address' ) ),
            'hidden_admin_columns' => array( 'date' ),
            'hidden_admin_fields' => array( 'password', 'date' ),
            'hidden_admin_filters' => array( 'date' ),
            'custom_post_meta' => array(
                'meta_box' => array(
                    'id' => 'election_data_address_meta_box',
                    'title' => __( 'Address Details' ),
                    'post_type' => $this->post_type,
                    'context' => 'normal',
                    'priority' => 'high',
                ),
                'fields' => array(
                    'street_type' => array(
                        'label' => __( 'Street Type' ),
                        'id' => 'street_type',
                        'desc' => __( "Enter the address's street type" ),
                        'type' => 'text',
                        'std' => '',
                        'imported' => true,
                    ),
                    'street_direction' => array(
                        'label' => __( 'Street Direction' ),
                        'id' => 'street_direction',
                        'desc' => __( "Enter the street direction, blank if none." ),
                        'type' => 'text',
                        'std' => '',
                        'imported' => true,
                    ),
                    'unit_type' => array(
                        'label' => __( 'Unit Type' ),
                        'id' => 'unit_type',
                        'desc' => __( "Enter the unit type, blank if its a house or an office address." ),
                        'type' => 'text',
                        'std' => '',
                        'imported' => true,
                    ),
                    'unit_number' => array(
                        'label' => __( 'Unit Number' ),
                        'id' => 'unit_number',
                        'desc' => __( "Number of the unit, 0 if none" ),
                        'type' => 'number',
                        'std' => '',
                        'imported' => true,
                    ),
                    // 'neighbourhood' => array(
                    //  'label' => __( 'Neighbourhood' ),
                    //  'id' => 'neighbourhood',
                    //  'desc' => __( "Enter the neighbourhood in which the address is located." ),
                    //  'type' => 'text',
                    //  'std' => '',
                    //  'imported' => true,
                    // ),
                    // 'old_ward' => array(
                    //  'label' => __( 'old_ward' ),
                    //  'id' => 'old_ward',
                    //  'desc' => __( "Ward this address was in during the last election." ),
                    //  'type' => 'text',
                    //  'std' => '',
                    //  'imported' => true,
                    // ),
                    'geometry' => array(
                        'label' => __( 'Geometry' ),
                        'id' => 'geometry',
                        'desc' => __( "Enter the coordinates of the address, you can use google maps to get them." ),
                        'type' => 'text',
                        'std' => '',
                        'imported' => true,
                    ),
                    'new_ward' => array(
                        'label' => __( 'New Ward' ),
                        'id' => 'new_ward',
                        'desc' => __( 'Name of the ward in which this location will be in for the upcoming election.' ),
                        'type' => 'text',
                        'std' => '',
                        'imported' => true,
                    ),
                    'school_division_name' => array(
            'label' => __( 'School Division' ),
                        'id' => 'school_division_name',
            'desc' => __( 'Name of the school division.' ),
                        'type' => 'text',
                        'std' => '',
                        'imported' => true,
                    ),
                    'school_division_ward' => array(
            'label' => __( 'School Division Ward' ),
                        'id' => 'school_division_ward',
            'desc' => __( 'Name of the school division ward.' ),
                        'type' => 'text',
                        'std' => '',
                        'imported' => true,
                    ),
                ),
                'admin_columns' => array( 'street_type', 'street_direction', 'unit_number', 'new_ward', 'school_division_name', 'school_division_ward' ),
            ),
        );
        $this->custom_post = new ED_Custom_Post_Type( $this->post_type, $args, $define_hooks );
        if ( $define_hooks ) {
            add_filter( 'pre_get_posts', array( $this, 'set_main_query_parameters' ) );
      add_action('wp_ajax_address_lookup' , array($this, 'return_candidates'));
      add_action('wp_ajax_nopriv_address_lookup' , array($this, 'return_candidates'));
            add_action('wp_ajax_delete' , array($this, 'delete'));
            //add_action('wp_ajax_nopriv_delete' , array($this, 'delete'));
        }
    }
    // public function delete(){
    //  global $ed_post_types;
    //
    //  $addresses = new WP_QUERY(array(
    //      'post_type' => $ed_post_types['address'],
    //      'posts_per_page' => 5000,
    //      'meta_query' => array(
    //          array(
    //              'key' => 'new_ward',
    //              'value' => 'St. James'
    //      ),
    //      )));
    //
    //  while($addresses->have_posts()){
    //      $addresses->the_post();
    //
    //      update_post_meta(get_the_ID(), 'school_division_name', 'St. James - Assiniboia', " ");
    //  }
    //  echo $addresses->post_count;
    // }
    /**
     * Gets the address data from ajax post and returns the candidates.
     *
     * @access public
     * @since 1.1
     *
     */
  public function return_candidates(){
    $form_data = $_POST['form_data'];
        $this->search_candidates($form_data);
    wp_die();
  }
    /**
     * Search for candidates associated with the constituency in which the address lies.
     *
     * @param  array $data An array of address form data
     * @access public
     * @since 1.1
     */
    public function search_candidates( $data ){
        global $ed_post_types;
        global $ed_taxonomies;
        // variable declaration
        $street_address;
        $output = '';
        $constituency = '';
        $constituency_id = '';
        $candidate_references = array();

				print_r($data);
        foreach($data as $key=>$value){
            if($value['name'] != 'street_type' && $value['name'] != 'street_direction' && $value['name'] != 'page'){
                    $street_address .= $value['value'] . " ";
            }
        }
        $addresses = new WP_QUERY(array(
            'post_type' => $ed_post_types['address'],
            'name' => $street_address
        ));
        if($addresses->have_posts()){
            while ($addresses->have_posts()) {
            $addresses->the_post();
            $post_id = get_the_ID();
                $title = get_the_title();
                //echo $addresses->post_count;
                $new_ward = get_post_meta($post_id, 'new_ward');
                $school_division = get_post_meta($post_id, 'school_division_name');
                $school_division_ward = get_post_meta($post_id, 'school_division_ward');
                $school_division_name = "{$school_division[0]} {$school_division_ward[0]}";
                $string = [];
                $string[0] = "/ - /";
                $string[1] = "/ /";
                $constituency = get_term_by('slug' , preg_replace($string, "-", $new_ward[0]), $ed_taxonomies['candidate_constituency'], 'ARRAY_A');
                $school_ward = get_term_by('slug', preg_replace($string, "-", $school_division_name), $ed_taxonomies['candidate_constituency'], 'ARRAY_A');
                $constituency_id = $constituency["term_id"];
                $school_ward_id = $school_ward["term_id"];
            }
            wp_reset_query();
						$testing_page_name = (string)$data[3]['value'];
						$dontgohere = "Results";
            if( strcmp($testing_page_name, $dontgohere) !== 0 ) {
							echo "Hey the results equation didn't work out <br />" . $testing_page_name . '<br />';
                if( $constituency ){
                    $ward_candidates = new WP_Query(array(
                        'tax_query' => array(
                            array(
                                    'taxonomy' => $ed_taxonomies['candidate_constituency'],
                                    'field' => 'term_id',
                                    'terms' => $constituency_id,
                            )
                    )));
                    echo ("</div><div class = 'flow_it politicians result_head'><style>.candidates h2{text-align:center; line-height: 36px;}</style><h2>Candidates in {$new_ward[0]}</h2>");
                    display_constituency_candidates($ward_candidates, $constituency_id, $candidate_references);
                    wp_reset_query();
                }
                if($school_ward){
                    $school_ward_candidates = new WP_Query(array(
                        'tax_query' => array(
                            array(
                                    'taxonomy' => $ed_taxonomies['candidate_constituency'],
                                    'field' => 'term_id',
                                    'terms' => $school_ward_id,
                            )
                    )));
                    echo ("<div class = 'flow_it politicians result_head'><h2>School Trustee Candidates in {$school_division_name}</h2></div>");
                    display_constituency_candidates($school_ward_candidates, $school_ward_id, $candidate_references);
                    wp_reset_query();
                }
                $mayoral_candidates_query = new WP_QUERY(array(
                    'tax_query' => array(
                        array(
                                'taxonomy' => $ed_taxonomies['candidate_constituency'],
                                'field' => 'term_id',
                                'terms' => 781,
                        )
                )));
                echo ("<div class = 'flow_it politicians result_head'><h2>School Trustee Candidates in {$school_division_name}</h2></div>");
                display_constituency_candidates($mayoral_candidates_query, 781, $candidate_references);
                wp_reset_query();
            }
            else {
								echo 'The thing worked! <br/>';
                 self::display_election_results($constituency, $school_ward, $ward_candidate);
								//display_election_results($constituency, $school_ward, $ward_candidate);
            }
        }
        else{
            echo ("Oops! Address Not Found.");
        }
    }

		/**
		*
		*
		*/
		public function display_election_results( $constituency, $school_ward = array(), $ward_candidates = array() ){
			echo "Display results for {$constituency['name']}";

			print_r($constituency);

			self::output_election_results( $constituency );
			echo '<br/> and that is the regular constituency result <br />';
			if (!empty ( $school_ward ) ) {
				self::output_election_results ( $school_ward );
			}
			if (!empty ($ward_candidates) ) {
				self::output_election_results ( $ward_candidates );
			}

}

public function output_election_results ( $result_input ) {
	$result_constituency = get_constituency( $result_input['term_id'] );
	$can_array = array();
	$sort_vote = array();
	$winner = 0;
	$winners_total = $result_constituency['number_of_winners'];
	$query_args = array(
		'post_type' => $ed_post_types['candidate'],
		'constituency' => $result_constituency['name'],
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
			<h3 style="text-align:center;"><?php echo $result_constituency['name'];?></h3>
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


}

    /**
     * Initializes the custom_post and taxonomies (Used during activation)
     *
     * @access public
     * @since 1.0
     *
     */
    public function initialize() {
        $this->custom_post->initialize();
    }
    /**
     * Sets up the main query for displaying addresss by constituency, or by party'
     *
     * @access public
     * @since 1.0
     *
     */
    public function set_main_query_parameters( $query ) {
        if( is_admin() || !$query->is_main_query() ) {
            return;
        }
    if ( is_post_type_archive( $this->post_type ) ) {
            $query->set( 'orderby', 'rand' );
            $query->set( 'nopaging', 'true' );
        }
    }
    /**
     * Exports the addresses to a single xml file.
     *
     * @access public
     * @since 1.0
     * @param string $xml
     *
     */
    public function export_xml( $xml ) {
    }
    /**
     * Exports the addresses to a csv file.
     *
     * @access protected
     * @since 1.0
     * @param file_handle $csv
     *
     */
    protected function export_address_csv( $csv ) {
        $post_fields = array(
            'post_title' => 'name',
            'post_name' => 'slug',
        );
        $taxonomies = array();
        Post_Export::export_post_csv( $csv, $this->post_type, $this->custom_post->post_meta, $post_fields, 'photo', $taxonomies );
    }
    /**
     * Exports the addresses to a csv file
     *
     * @access public
     * @since 1.0
     * @param string $type
     *
     */
    public function export_csv ( $type ) {
        $file_name = tempnam( 'tmp', 'csv' );
        $file = fopen( $file_name, 'w' );
        call_user_func( array( $this, "export_{$type}_csv" ), $file );
        fclose( $file );
        return $file_name;
    }
    /**
     * Imports the addresses from a csv file
     *
     * @access protected
     * @since 1.0
     * @param file_handle $csv
     * @param string $mode
     *
     */
    protected function import_address_csv( $csv, $mode ) {
        $post_fields = array(
            'post_title' => 'name',
            'post_name' => 'slug',
        );
        $taxonomies = array();
        return Post_import::import_post_csv( $csv, $mode, $this->post_type, $this->custom_post->post_meta, $post_fields, 'photo', $taxonomies );
    }
    /**
     * Imports the addresses from a CSV file.
     *
     * @access public
     * @since 1.0
     * @param string $type
     * @param file_handle $csv
     * @param string $mode
     *
     */
    public function import_csv( $type, $csv, $mode ) {
        return call_user_func( array( $this, "import_{$type}_csv" ), $csv, $mode );
    }
    /**
     * Erases all addresss, parties and constituencies from the database.
     * @access public
     * @since 1.0
     *
     */
    public function erase_data() {
        $this->custom_post->erase_data();
    }
}
