<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the dashboard.
 *
 * @link       http://opendemocracymanitoba.ca/
 * @since      1.0.0
 *
 * @package    Election_Data
 * @subpackage Election_Data/includes
 */

 /**
  * Stores the names of the post_types defined by the Election Data plugin.
  *
  * @since 1.0
  * @var	array	$ed_post_type	Single location storage of the custom post types.
  *
  */
 global $ed_post_types;
 $ed_post_types = array();

 /**
  * Stores the names of the taxonomies defined by the Election Data plugin.
  *
  * @since 1.0
  * @var	array $taxonomies	Single location storage of the custom taxonomies.
  *
  */
 global $ed_taxonomies;
 $taxonomies = array();

/**
 * The core plugin class.
 *
 * This is used to define internationalization, dashboard-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Election_Data
 * @subpackage Election_Data/includes
 * @author     Robert Burton
 */
class Election_Data {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Election_Data_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

  /**
	 * Instance of the Election_Data_Candidate class.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Election_Data_Candidate    $candidate   Instantiates a new Election_Data_Candidate class.
	 */
	protected $candidate;

  /**
   * Instance of the Election_Data_News_Article class.
   *
   * @since    1.0.0
	 * @access   protected
	 * @var      Election_Data_News_Article    $news_article   Instantiates a new Election_Data_News_Article class.
   */
	protected $news_article;

  /**
   * Instace of the Election_Data_Answer class.
   *
   * @since 1.0.0
   * @access protected
   * @var Election_Data_Answer $answer Instantiates a new Election_Data_Answer class.
   */
	protected $answer;

  /**
	 * Instance of the Election_Data_Address class.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Election_Data_Address    $address   Instantiates a new Election_Data_Address class.
	 */
	protected $address;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the Dashboard and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'election-data';
		$this->version = '1.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

		$this->candidate = new Election_Data_Candidate();

		$this->news_article = new Election_Data_News_Article();

		$this->answer = new Election_Data_Answer();

    $this->address = new Election_Data_Address();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Election_Data_Loader. Orchestrates the hooks of the plugin.
	 * - Election_Data_i18n. Defines internationalization functionality.
	 * - Election_Data_Admin. Defines all hooks for the dashboard.
	 * - Election_Data_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-election-data-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-election-data-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the Dashboard.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-election-data-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-election-data-public.php';

    /**
     * The class responsible for defining all the options in the plugin settings and makes them accessible
     * by wordpress' get_option method.
     */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-election-data-option.php';

    /**
     * A boolean variable to check if the current election is municipal or provincial.
     *
     * @since 1.0.0
     * @var boolean $is_party_election True, if it is a municipal election, false, if it is a provincial one.
     */
    global $is_party_election;
    (Election_Data_Option::get_option('party_election')) ? $is_party_election = true : $is_party_election = false;

    global $is_address_lookup_tool;
    (Election_Data_Option::get_option('address_dataset_available')) ? $is_address_lookup_tool = true : $is_address_lookup_tool = false;



    /**
     * This class stores all the callback functions to output the html for all options in plugin settings.
     */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/settings/class-election-data-callback-helper.php';

    /**
     * This class adds the meta-boxes for each tab in the plugin settings.
     */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/settings/class-election-data-meta-box.php';

    /**
     * This class is responsible for sanitizing all the input in the plugin settings.
     */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/settings/class-election-data-sanitization-helper.php';

    /**
     * This class defines all the options under each tab in the plugin settings.
     */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/settings/class-election-data-settings-definition.php';

    /**
     * This class is responsible for registering the settings of the all options in the plugin settings.
     */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/settings/class-election-data-settings.php';

    /**
     * This class defines the 'candidate' custom post type.
     * This class also defines the 'party' and 'constituency' custom taxonomies which are a part of 'candidate' custom post type.
     */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-election-data-candidate.php';

    /**
     * This class defines the 'address' custom post type.
     */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-election-data-address.php';

    /**
     * This class defines all code necessary to run during the plugin's activation.
     */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-election-data-activator.php';

    /**
     * This class defines the 'news news_article' custom post type.
     * The class also defines 'candidate' and 'source' custom taxonomies which are a part of 'news article' custom post type.
     */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-election-data-news-article.php';

    /**
     * This class defines the 'answer' custom post type.
     * The class also defines 'question', 'candidate' and 'party 'custom taxonomies which are a part of 'news article' custom post type.
     */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-election-data-answer.php';

    /*
    Instantiation of the Election_Data_Loader class.
     */
		$this->loader = new Election_Data_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Election_Data_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Election_Data_i18n();
		$plugin_i18n->set_domain( $this->get_plugin_name() );

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the dashboard functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Election_Data_Admin( $this->get_plugin_name(), $this->get_version() );

		// loads the admin scripts
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		// Adds the options page and menu item.
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_admin_menu' );

		// Adds an action link pointing to the options page.
		$plugin_basename = plugin_basename( plugin_dir_path( realpath( dirname( __FILE__ ) ) ) . $this->plugin_name . '.php' );
		$this->loader->add_action( 'plugin_action_links_' . $plugin_basename, $plugin_admin, 'add_action_links' );

		// Builds the settings page.
		$settings_callback = new Election_Data_Callback_Helper( $this->plugin_name );
		$settings_sanitization = new Election_Data_Sanitization_Helper( $this->plugin_name );
		$plugin_settings = new Election_Data_Settings( $this->get_plugin_name(), $settings_callback, $settings_sanitization );
		$this->loader->add_action( 'admin_init' , $plugin_settings, 'register_settings' );

    // Adds the Meta box for each tab.
		$plugin_meta_box = new Election_Data_Meta_Box( $this->get_plugin_name() );
		$this->loader->add_action( 'load-toplevel_page_' . $this->get_plugin_name() , $plugin_meta_box, 'add_meta_boxes' );

    // Ajax request to erase all the site data.
		$this->loader->add_action( 'wp_ajax_election_data_erase_site', $this, 'erase_data' );
		$this->loader->add_action( 'admin_notices', 'Election_Data_Activator', 'display_activation_warnings' );
		$this->loader->add_action( 'admin_menu', $this, 'remove_menus', 110 );
    $this->loader->add_action( 'wp_ajax_delete_address_data', $this, 'erase_address_data' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Election_Data_Public( $this->get_plugin_name(), $this->get_version() );

    // Loads the public script files and style files. (.css and .js)
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_filter( 'pre_get_posts', $this, 'set_main_query_parameters' );
    $this->loader->add_action('wp_ajax_setup_theme_for_user', $this, 'setup_theme_for_user');

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Election_Data_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

  /**
   * Removes some default menu pages added by WordPress.
   *
   * @return void
   */
	public function remove_menus() {
		remove_menu_page( 'edit.php' );
		remove_menu_page( 'edit-comments.php' );
		remove_submenu_page( 'themes.php', 'theme-editor.php' );
	}

  /**
   * Setups the theme based on the admin choice in the front page.
   * Ajax request is recieved from the front page.
   */
  function setup_theme_for_user(){

    $theme = $_POST['theme'];
    echo "Theme {$theme} has been activated".
    switch_theme($theme);

    wp_die();
  }


  /**
   * Imports the data provided by the csv file.
   *
   * @param  resource $csv  A valid file pointer to a file successfully opened by fopen(), popen(), or fsockopen().
   * @param  string   $mode An option on how data is being imported. ('overwrite', 'no_overwrite')
   * @return boolean        True, if the data was successfully imported,
   *                        False, if the import failed.
   */
	public static function import_csv( $csv, $mode ) {
		$headings = fgetcsv( $csv );
		$found = true;
		$fields = array( 'setting_name', 'type', 'value' );
		foreach ( $fields as $field ) {
			$found |= in_array( $field, $headings );
		}

		if ( ! $found ) {
			return false;
		}
		$importable_settings = array(
			'plugin' => array(
				'location' => '',
				'time' => '',
				'frequency' => '',
				'source' => '',
				'source-api' => '',
				'summary' => '',
				'facebook-page' => '',
				'twitter' => '',
				'google-plus-one' => '',
				'constituency-label' => '',
				'constituency-subtext' => '',
				'party-label' => '',
				'party-subtet' => '',
				'news-count-front' => '',
				'news-count-party' => '',
				'news-count-party-leader' => '',
				'news-count-candidate' => '',
			),
			'wordpress' => array(
				'blogname' => '',
				'blogdescription' => '',
				'timezone_string' => '',
				'date_format' => '',
				'posts_per_page' => '',
				'permalink_structure' => '',

			),
		);
		while ( ( $data = fgetcsv( $csv ) ) !== false ) {
			$data = array_combine( $headings, $data );
			if ( isset( $importable_settings[$data['type']][$data['setting_name']] ) ) {
				switch ( $data['type'] ) {
					case 'plugin':
						$current_value = Election_Data_Option::get_option( $data['setting_name'], '' );
						if ( $current_value == $data['value'] || ( 'no_overwrite' == $mode && $current_value ) ) {
							continue 2;
						}
						Election_Data_Option::update_option( $data['setting_name'], $data['value'] );
						break;
					case 'wordpress':
						$current_value = get_option( $data['setting_name'], '' );
						if ( $current_value == $data['value'] || ( 'no_overwrite' == $mode && $current_value ) ) {
							continue 2;
						}
						update_option( $data['setting_name'], $data['value'] );
						break;
				}
			}
		}

		return true;
	}

  /**
   * Imports the data being provided by the imported file.
   *
   * @param  string   $file_type  Type of file
   * @param  string   $file_data  Data in the file
   * @param  string   $mode       A brief description on how data is being imported
   * @return boolean              True, if the data was successfully imported,
   *                              False, if the import failed.
   */
	public static function import( $file_type, $file_data, $mode ) {

		// set_time_limit( 60 * 60 );

		$candidate = new Election_Data_Candidate( false );
    $address = new Election_Data_Address( false );
		$news_article = new Election_Data_News_Article( false );
		$answer = new Election_Data_Answer( false );

		$file_name = $file_data['tmp_name'];

		switch ( $file_type ) {
			case 'xml':
				$success = true;
				break;
			case 'csv_zip':
				$mode = 'overwrite';
				$success = true;
				$zip = new ZipArchive();
				$zip->open( $file_name );
				$candidate_types = array( 'party', 'constituency', 'candidate' );
        $address_types = array( 'address' );
				$news_types = array( 'news_source', 'news_article', 'news_mention' );
				$answer_types = array( 'question', 'answer' );
				$all_types = array_merge( $candidate_types, $address_types, $news_types, $answer_types );
				$all_types[] = 'settings';
				foreach ( $all_types as $type ) {
					$success &= $zip->locateName( "$type.csv" ) !== false;
				}
				if ( ! $success ) {
					$zip->close();
					return false;
				}


				foreach ( $candidate_types as $type ) {
					$csv = $zip->getStream( "$type.csv" );
					$success |= $candidate->import_csv( $type, $csv, $mode );
					fclose( $csv );
					wp_cache_flush();
					gc_collect_cycles();
				}

        foreach ( $address_types as $type ) {
					$csv = $zip->getStream( "$type.csv" );
					$success |= $address->import_csv( $type, $csv, $mode );
					fclose( $csv );
					wp_cache_flush();
					gc_collect_cycles();
				}

				foreach( $news_types as $type ) {
					$csv = $zip->getStream( "$type.csv" );
					$success |= $news_article->import_csv( $type, $csv, $mode );
					fclose( $csv );
					wp_cache_flush();
					gc_collect_cycles();
				}

				foreach ($answer_types as $type ) {
					$csv = $zip->getStream( "$type.csv" );
					$success |= $answer->import_csv( $type, $csv, $mode );
					fclose ( $csv );
					wp_cache_flush();
					gc_collect_cycles();
				}

				$csv = $zip->getStream( 'settings.csv' );
				$success != self::import_csv( $csv, $mode );
				$zip->close();
				break;
			case 'csv_candidate':
			case 'csv_party':
			case 'csv_constituency':
				$type = substr( $file_type, 4 );
				$csv = fopen( $file_name, 'r' );
				$success = $candidate->import_csv( $type, $csv, $mode );
				break;
      case 'csv_address':
				$type = substr( $file_type, 4 );
				$csv = fopen( $file_name, 'r' );
				$success = $address->import_csv( $type, $csv, $mode );
				break;
			case 'csv_news_source':
			case 'csv_news_article':
			case 'csv_news_mention':
				$type = substr( $file_type, 4 );
				$csv = fopen( $file_name, 'r' );
				$success = $news_article->import_csv( $type, $csv, $mode );
				break;
			case 'csv_answer':
			case 'csv_question':
				$type = substr( $file_type, 4 );
				$csv = fopen( $file_name, 'r' );
				$success = $answer->import_csv( $type, $csv, $mode );
				break;
			case 'csv_settings':
				$csv = fopen( $file_name, 'r' );
				$success = self::import_csv( $csv, $mode );
				break;
			default:
				$success = false;
				break;
		}

		return $success;
	}

  /**
   * EMPTY FUNCTION.... FLAGGED
   * @param  [type] $xml [description]
   * @return [type]      [description]
   */
	public static function export_xml( $xml ) {
	}

  /**
   * Exports the site data in a csv format file.
   *
   * @return string Name of the exported file.
   */
	public static function export_csv() {
		plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-election-data-option.php';
		plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-post-export.php';

		$file_name = tempnam( 'tmp', 'csv' );
		$csv = fopen( $file_name, 'w' );
		$headings = array( 'setting_name', 'value', 'type' );
		$headings_data = array_combine( $headings, $headings );

		Post_Export::write_csv_row( $csv, $headings_data, $headings );

		$exportable_settings = array(
			'plugin' => array(
				array( 'Election_Data_Option', 'get_option' ),
				array ( 'location', 'time', 'frequency', 'source', 'source-api', 'summary', 'facebook-page', 'twitter', 'google-plus-one', 'constituency-label', 'constituency-subtext', 'party-label', 'party-subtet', 'news-count-front', 'news-count-party', 'news-count-party_leader', 'news-count-candidate' ),
			),
			'wordpress' => array(
				'get_option',
				array('blogname', 'blogdescription', 'timezone_string', 'date_format', 'posts_per_page', 'permalink_structure' ),
			)
		);

		foreach ( $exportable_settings as $type => $settings ) {
			$get_option = $settings[0];
			foreach ( $settings[1] as $setting ) {
				$data = array( 'setting_name' => $setting, 'type' => $type );
				$data['value'] = $get_option( $setting );
				Post_Export::write_csv_row( $csv, $data, $headings );
			}
		}

		fclose( $csv );
		return $file_name;
	}

  /**
   * Exports the site data as a given file type.
   * @param  string $file_type The format of file in which data will be exported.
   */
	public static function export( $file_type ) {
		set_time_limit( 60 * 60 );

		$candidate = new Election_Data_Candidate( false );
    $address = new Election_Data_Address( false );
		$news_article = new Election_Data_News_Article( false );
		$answer = new Election_Data_Answer( false );

		switch ( $file_type ) {
			case 'xml':
				$file = tempnam( 'tmp', 'xml' );
				$xml = new XMLWriter();
				$xml->openURI( "file://$file" );
				$xml->startDocument( '1.0' );
				$candidate->export_xml( $xml );
        $address->export_xml( $xml );
				$news_article->export_xml ( $xml );
				self::export_xml( $xml );
				$xml->endDocument();
				$xml->flush();
				$content_type = 'application/xml';
				$file_name = 'Election_Data.xml';
				break;
			case 'csv_zip':
				$file = tempnam( 'tmp', 'zip' );
				$zip = new ZipArchive();
				$zip->open( $file, ZipArchive::OVERWRITE );
        $csv_files = array();

				$types = array( 'candidate', 'party', 'constituency' );
				foreach ( $types as $type ) {
					$csv_file = $candidate->export_csv( $type );
					$zip->addFile( $csv_file, "$type.csv" );
					$csv_files[] = $csv_file;
				}

        $types = array( 'address' );
				foreach ( $types as $type ) {
					$csv_file = $address->export_csv( $type );
					$zip->addFile( $csv_file, "$type.csv" );
					$csv_files[] = $csv_file;
				}

				$types = array( 'news_article', 'news_source', 'news_mention' );
				foreach ( $types as $type ) {
					$csv_file = $news_article->export_csv( $type );
					$zip->addFile( $csv_file, "$type.csv" );
					$csv_files[] = $csv_file;
				}

				$types = array( 'answer', 'question' );
				foreach ( $types as $type ) {
					$csv_file = $answer->export_csv( $type );
					$zip->addFile( $csv_file, "$type.csv" );
					$csv_files[] = $csv_file;
				}
				$csv_file = self::export_csv();
				$zip->addFile( $csv_file, 'settings.csv' );
				$csv_files[] = $csv_file;

				$zip->close();
				foreach ( $csv_files as $csv_file ) {
					unlink( $csv_file );
				}
				$content_type = 'application/zip';
				$file_name = 'Election_Data.zip';
				break;
			case 'csv_candidate':
			case 'csv_party':
			case 'csv_constituency':
				$type = substr( $file_type, 4 );
				$file = $candidate->export_csv( $type );
				$content_type = 'text/csv';
				$file_name = "$type.csv";
				break;
      case 'csv_address':
				$type = substr( $file_type, 4 );
				$file = $address->export_csv( $type );
				$content_type = 'text/csv';
				$file_name = "$type.csv";
				break;
			case 'csv_news_article':
			case 'csv_news_source':
			case 'csv_news_mention':
				$type = substr( $file_type, 4 );
				$file = $news_article->export_csv( $type );
				$content_type = 'text/csv';
				$file_name = "$type.csv";
				break;
			case 'csv_question':
			case 'csv_answer':
				$type = substr( $file_type, 4 );
				$file = $answer->export_csv( $type );
				$content_type = 'text/csv';
				$file_name = "answer_$type";
				break;
			case 'csv_settings':
				$file = self::export_csv();
				$content_type = 'text/csv';
				$file_name = 'settings.csv';
				break;
			default:
				return;
		}

		header( "Content-Type: $content_type" );
		header( 'Content-Length: ' . filesize( $file ) );
		header( "Content-Disposition: attachment; filename=\"$file_name\"" );
		readfile( $file );
		unlink( $file );
		exit();

	}

  /**
   * Erases all the site data.
   * @return [type] [description]
   */
	function erase_data()
	{
		$this->candidate->erase_data();
		$this->news_article->erase_data();
		$this->answer->erase_data();
    $this->address->erase_data();
		wp_die();
	}

  /**
   * Erases all addresses.
   */
  function erase_address_data(){
    $this->address->erase_data();
    wp_die();
  }

  /**
   * Sets all the main query parameters.
   * @param string $query Query parameter to set as the main parameter.
   */
	public function set_main_query_parameters( $query ) {
		if ( is_admin() || ! $query->is_main_query() ) {
			return;
		}

		if ( is_search() ) {
			$query->set( 'orderby', array( 'post_type', 'date' ) );
			$query->set( 'order', array( 'ASC', 'DESC' ) );
			$query->set( 'post_type', $this->candidate->post_type );
		}
	}
}
