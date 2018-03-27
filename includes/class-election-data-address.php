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

global $ed_taxonomies;

// if($is_party_election){
//   $ed_taxonomies['address_party'] = "{$ed_post_types['address']}_party";
// }
//
// $ed_taxonomies['address_constituency'] = "{$ed_post_types['address']}_constituency";


/**
 * Sets up and handles the address custom post type.
 *
 *
 * @since      1.0.0
 * @package    Election_Data
 * @subpackage Election_Data/includes
 * @author     Robert Burton <RobertBurton@gmail.com>
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
	 * The definition of the taxonomy names.
	 *
	 * @var array
	 * @access public
	 * @since 1.0
	 *
	 */
	public $taxonomies;

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
		global $ed_taxonomies;
    global $is_party_election;

		$this->post_type = $ed_post_types['address'];
		// $this->taxonomies = array(
		// 	'party' => $ed_taxonomies['address_party'],
		// 	'constituency' => $ed_taxonomies['address_constituency'],
		// );
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
					'not_found' => __( 'No Addresses found' ),
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
			// 'taxonomy_filters' => array(
      //   ($is_party_election ? $this->taxonomies['party']:$this->taxonomies['constituency']),
      //   $this->taxonomies['constituency'] ),
			// 'sortable_taxonomies' => array( $this->taxonomies['party'], $this->taxonomies['constituency'] ),
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
					// 	'label' => __( 'Neighbourhood' ),
					// 	'id' => 'neighbourhood',
					// 	'desc' => __( "Enter the neighbourhood in which the address is located." ),
					// 	'type' => 'text',
					// 	'std' => '',
					// 	'imported' => true,
					// ),
					// 'old_ward' => array(
					// 	'label' => __( 'old_ward' ),
					// 	'id' => 'old_ward',
					// 	'desc' => __( "Ward this address was in during the last election." ),
					// 	'type' => 'text',
					// 	'std' => '',
					// 	'imported' => true,
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
			// 'taxonomy_args' => array(
			// 	$this->taxonomies['party'] => array(
			// 		'labels' => array(
			// 			'name' => _x( 'Parties', 'taxonomy general name' ),
			// 			'singular_name' => _x( 'Party', 'taxonomy general name' ),
			// 			'all_items' => __( 'All Parties' ),
			// 			'edit_item' => __( 'Edit Party' ),
			// 			'view_item' => __( 'View Party' ),
			// 			'update_item' => __( 'Update Party' ),
			// 			'add_new_item' => __( 'Add New Party' ),
			// 			'new_item_name' => __( 'New Party Name' ),
			// 			'search_items' => __( 'Search Parties' ),
			// 			'parent_item' => null,
			// 			'parent_item_colon' => null,
			// 		),
			// 		'public' => true,
			// 		'show_tagcloud' => false,
			// 		'show_admin_column' => true,
			// 		'hierarchical' => true,
			// 		'query_var' => 'party',
			// 		'rewrite' => array( 'slug' => 'parties', 'with_front' => false )
			// 	),
			// 	$this->taxonomies['constituency'] => array(
			// 		'labels' => array(
			// 			'name' => _x( 'Constituencies', 'taxonomy general name' ),
			// 			'singular_name' => _x( 'Constituency', 'taxonomy general name' ),
			// 			'all_items' => __( 'All Constituencies' ),
			// 			'edit_item' => __( 'Edit Constituency' ),
			// 			'view_item' => __( 'View Constituency' ),
			// 			'update_item' => __( 'Update Constituency' ),
			// 			'add_new_item' => __( 'Add New Constituency' ),
			// 			'new_item_name' => __( 'New Constituency Name' ),
			// 			'search_items' => __( 'Search Constituencies' ),
			// 			'parent_item' => null,
			// 			'parent_item_colon' => null,
			// 		),
			// 		'public' => true,
			// 		'show_tagcloud' => false,
			// 		'show_admin_column' => true,
			// 		'hierarchical' => true,
			// 		'query_var' => 'constituency',
			// 		'rewrite' => array( 'slug' => 'constituencies', 'with_front' => false )
			// 	),
			//),
			// 'taxonomy_meta' => array(
			// 	'party' => array(
			// 		'taxonomy' => $this->taxonomies['party'],
			// 		'fields' => array(
			// 			array(
			// 				'type' => 'color',
			// 				'id' => 'colour',
			// 				'std' => '#000000',
			// 				'desc' => __( 'Select a colour to identify the party.' ),
			// 				'label' => __( 'Colour' ),
			// 				'imported' => true,
			// 			),
			// 			array(
			// 				'type' => 'image',
			// 				'id' => 'logo',
			// 				'desc' => __( 'Select a logo for the party.' ),
			// 				'label' => __( 'Logo' ),
			// 				'std' => '',
			// 				'imported' => true,
			// 			),
			// 			array(
			// 				'type' => 'url',
			// 				'id' => 'website',
			// 				'desc' => __( "Enter the URL to the party's web site." ),
			// 				'label' => __( 'Web Site URL' ),
			// 				'std' => '',
			// 				'imported' => true,
			// 			),
			// 			array(
			// 				'type' => 'text',
			// 				'id' => 'phone',
			// 				'desc' => __( "Enter the party's phone number." ),
			// 				'label' => __( 'Phone Number' ),
			// 				'std' => '',
			// 				'imported' => true,
			// 			),
			// 			array(
			// 				'type' => 'text',
			// 				'id' => 'address',
			// 				'desc' => __( "Enter the party's address." ),
			// 				'label' => __( 'Address' ),
			// 				'std' => '',
			// 				'imported' => true,
			// 			),
			// 			array(
			// 				'type' => 'email',
			// 				'id' => 'email',
			// 				'desc' => __( "Enter the party's email address." ),
			// 				'label' => __( 'Email Address' ),
			// 				'std' => '',
			// 				'imported' => true,
			// 			),
			// 			array(
			// 				'type' => 'url',
			// 				'id' => 'facebook',
			// 				'desc' => __( "Enter the URL to the party's facebook page." ),
			// 				'label' => __( 'Facbook Page' ),
			// 				'std' => '',
			// 				'imported' => true,
			// 			),
			// 			array(
			// 				'type' => 'url',
			// 				'id' => 'youtube',
			// 				'desc' => __( "Enter the URL to the party's youtube channel or video" ),
			// 				'label' => __( 'Youtube Channel or Video' ),
			// 				'std' => '',
			// 				'imported' => true,
			// 			),
			// 			array(
			// 				'type' => 'url',
			// 				'id' => 'twitter',
			// 				'desc' => __( "Enter the URL to the party's twitter feed." ),
			// 				'label' => __( 'Twitter Feed' ),
			// 				'std' => '',
			// 				'imported' => true,
			// 			),
			// 			array(
			// 				'id' => 'qanda_token',
			// 				'type' => 'text_with_load_value_button',
			// 				'std_callback' => array( $this, 'qanda_random_token' ),
			// 				'imported' => true,
			// 				'desc' => __( 'The token required to edit the questionnaire.' ),
			// 				'label' => __( 'Questionnaire Token' ),
			// 				'button_label' => __( 'Generate Token' ),
			// 				'ajax_callback' => 'ed_qanda_random_token',
			// 				'std' => '',
			// 			),
			// 			array(
			// 				'id' => 'qanda_sent',
			// 				'type' => 'checkbox',
			// 				'std' => false,
			// 				'desc' => __( 'Indicates that a questionnaire has been sent out. Uncheck to have the party included when the questionnaire is next sent out.' ),
			// 				'label' => __( 'Quesitonnaire Sent' ),
			// 				'imported' => true,
			// 			),
			// 			array(
			// 				'type' => 'hidden',
			// 				'id' => 'qanda_party_id',
			// 				'std' => '',
			// 				'imported' => false,
			// 			),
			// 		),
			// 		'renamed' => array(
			// 			'description' => 'Alternate Name',
			// 		),
			// 	),
			// 	'constituency' => array(
			// 		'taxonomy' => $this->taxonomies['constituency'],
			// 		'fields' => array(
      //       array(
      //         'type' => 'number',
      //         'id' => 'number_of_winners',
      //         'desc' => __( "All electoral devisions to which addresss will be assigned should have at least one seat." ),
      //         'label' => __( "Number of Seats in this Race" ),
      //         'std' => 0,
      //         'imported' => true,
      //         'min' => 0,
      //         'step' => 1,
      //       ),
			// 			array(
			// 				'type' => 'image',
			// 				'id' => 'map',
			// 				'desc' => __( "A map of the child constituencies." ),
			// 				'label' => __( "Constituency Map" ),
			// 				'std' => '',
			// 				'imported' => true,
			// 			),
			// 			array(
			// 				'type' => 'text',
			// 				'id' => 'coordinates',
			// 				'desc' => __( 'HTML map coordinates for constituency location on parent constituencies map. You can generate these coordinates by using an online map tool available <a href="https://www.google.com/search?q=html+map+generator+online">here</a>' ),
			// 				'label' => __( 'Coordinates' ),
			// 				'std' => '',
			// 				'imported' => true,
			// 			),
			// 			array(
			// 				'type' => 'wysiwyg',
			// 				'id' => 'details',
			// 				'desc' => __( 'A description of the constituency. ' ),
			// 				'label' => __( 'Details' ),
			// 				'std' => '',
			// 				'imported' => true,
			// 			),
			// 		),
			// 		'hidden' => array( 'description' ),
      //     'renamed' => array(
			// 			'slug' => 'Slug (Friendly URL)', // transforms 'Slug' into something more descriptive
			// 		),
			// 	),
			// ),
		);

		$this->custom_post = new ED_Custom_Post_Type( $this->post_type, $args, $define_hooks );

		if ( $define_hooks ) {
			add_filter( 'pre_get_posts', array( $this, 'set_main_query_parameters' ) );
      add_action('wp_ajax_address_lookup' , array($this, 'return_candidates'));
      add_action('wp_ajax_nopriv_address_lookup' , array($this, 'return_candidates'));
			add_action('wp_ajax_delete' , array($this, 'delete'));
			add_action('wp_ajax_nopriv_delete' , array($this, 'delete'));
			// add_filter( 'posts_clauses', array($this,'intercept_query_clauses'), 20, 1 );
			// add_action( 'wp_ajax_ed_qanda_random_token', array( $this, 'ajax_qanda_random_token' ) );
			// add_action( "create_{$this->taxonomies['party']}", array( $this, 'create_party' ), 10, 2 );
			// add_action( "create_{$this->taxonomies['constituency']}", array( $this, 'create_constituency' ), 10, 2 );
			// add_action( "edited_{$this->taxonomies['constituency']}", array( $this, 'edited_constituency' ), 10, 2 );
      // add_action('wp_head', array($this, 'toggle_party_menu'));
		}
		// add_image_size( 'address', 9999, 100, false );
    //
		// add_image_size( 'map_thumb', 100, 9999, false );
		// add_image_size( 'map', 598, 9999, false );
		// add_image_size( 'party', 175, 175, false );
	}

	public function delete(){
		global $ed_post_types;

		$addresses = new WP_QUERY(array(
			'post_type' => $ed_post_types['address'],
			'posts_per_page' => 5000,
			'meta_query' => array(
				array(
					'key'     => 'new_ward',
					'value'   => 'Charleswood - Tuxedo',
				),
		)));

		while($addresses->have_posts()){
			$addresses->the_post();

			update_post_meta(get_the_ID(), 'school_division_name', 'St. James - Assiniboia');
		}

		echo $addresses->post_count;
	}

  public function return_candidates(){
    //print_r($_POST['form_data']);

    $form_data = $_POST['form_data'];

		$this->search_candidates($form_data);

    wp_die();
  }

	public function search_candidates( $data ){

		global $ed_post_types;
		global $ed_taxonomies;
		$street_address;
		$output = '';
		$constituency = '';
		$constituency_id = '';
		$candidate_references = array();


		foreach($data as $key=>$value){
			if($value['name'] != 'street_type' && $value['name'] != 'street_direction'){
					$street_address .= $value['value'] . " ";
			}
		}

		$addresses = new WP_QUERY(array(
			'post_type' => $ed_post_types['address'],
			'posts_per_page' => 1,
			's' => $street_address
		));

		if($addresses->have_posts()){
			while ($addresses->have_posts()) {
		    $addresses->the_post();
		    $post_id = get_the_ID();
				$title = get_the_title();

				//echo $title;
				$new_ward = get_post_meta($post_id, 'new_ward');
				$school_division = get_post_meta($post_id, 'school_division_name');
				$school_division_ward = get_post_meta($post_id, 'school_division_ward');

				$school_division_name = "{$school_division[0]} {$school_division_ward[0]}";

				$constituency = get_term_by('name' , $new_ward, $ed_taxonomies['candidate_constituency']);
				$school_ward = get_term_by('name', $school_division_name, $ed_taxonomies['candidate_constituency']);

				$constituency_id = $constituency->term_id;
				$school_ward_id = $school_ward->term_id;
				

			}

			wp_reset_query();

			if($constituency){
				$ward_candidates = new WP_Query(array(
					'tax_query' => array(
						array(
								'taxonomy' => $ed_taxonomies['candidate_constituency'],
								'field' => 'term_id',
								'terms' => $constituency_id,
						)
				)));

				echo ("<div class = 'flow_it politicians'><h2>Ward Candidates</h2></div>");
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

				echo ("<div class = 'flow_it politicians'><h2>School Trustees</h2></div>");
				display_constituency_candidates($school_ward_candidates, $school_ward_id, $candidate_references);
				wp_reset_query();
			}
		}
		else{
			echo ("Oops! Address Not Found.");
		}



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
