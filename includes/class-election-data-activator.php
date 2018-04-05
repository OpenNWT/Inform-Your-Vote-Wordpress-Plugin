<?php

/**
 * Fired during plugin activation
 *
 * @link       http://opendemocracymanitoba.ca/
 * @since      1.0.0
 *
 * @package    Election_Data
 * @subpackage Election_Data/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Election_Data
 * @subpackage Election_Data/includes
 * @author     Robert Burton
 */
class Election_Data_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-election-data-candidate.php';

		$candidate = new Election_Data_Candidate( false );
		$candidate->initialize();

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-election-data-address.php';

		$address = new Election_Data_Address( false );
		$address->initialize();

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-election-data-news-article.php';

		$news_articles = new Election_Data_News_Article( false );
		$news_articles->initialize();

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-election-data-answer.php';

		$answers = new Election_Data_Answer( false );
		$answers->initialize();

		flush_rewrite_rules();

		$news_articles->setup_cron();

		$search_page = self::get_or_add_search_page();

		$menu_id = self::register_navigation( $news_articles, $search_page->ID );

		if ( ! self::setup_theme() ) {
			$warnings = Election_Data_Option::get_option( 'warnings', array() );
			$warnings[] = __ ( 'Unable to set up the Election Data Theme that is required for the plugin to work properly. Please copy the theme folder in the Election Data plugin to the wordpress theme folder and activate the Election Data Theme.' );
			Election_Data_Option::update_option ( 'warnings', $warnings);
		}
	}

	/**
	 * Copies the files from source to destination
	 *
	 * @param  string $src Source of files
	 * @param  string $dst Destination of files
	 */
	private static function recurse_copy($src, $dst) {
		$dir = opendir($src);
		@mkdir($dst);
		while(false !== ( $file = readdir($dir)) ) {
			if (( $file != '.' ) && ( $file != '..' )) {
				if ( is_dir($src . '/' . $file) ) {
					self::recurse_copy($src . '/' . $file,$dst . '/' . $file);
				}
				else {
					copy($src . '/' . $file,$dst . '/' . $file);
				}
			}
		}
		closedir($dir);
	}

	/**
	 * Checks if the same theme already exists.
	 * @param  WP_Theme_Object $a Source of the theme in the plugin folder.
	 * @param  WP_Theme_Object $b Destination of the theme in the themes folder.
	 * @return boolean            Return true if theme exists and false if it does not.
	 */
	public static function same_themes( $a, $b ) {
		$same = $a->exists() && $b->exists();
		if ( $same ) {
			$headers = array( 'Version', 'Author' );
			foreach ( $headers as $header ) {
				$same &= $a->get( $header ) == $b->get( $header );
			}
		}
		return $same;
	}

	/**
	 * Copy the theme from the theme folder under plugins to default wordpress' themes folder.
	 * @param  string $dest_name Destination path of the theme
	 * @return string            Basename of the destination path
	 */
	public static function copy_theme( $dest_name ) {
		if ( ! is_writable( get_theme_root() ) ) {
			return false;
		}
		$src_theme = wp_get_theme( 'theme', plugin_dir_path( __FILE__ ) . '..' );
		$dest_theme = wp_get_theme( $dest_name );

		// if ( $dest_theme->exists() ) {
		// 	if ( self::same_themes( $src_theme, $dest_theme ) ) {
		// 		return '';
		// 	}
		//
		// 	$dest = tempnam( get_theme_root(), 'ElectionData' );
		// 	unlink( $dest );
		// 	if ( dirname( $dest ) != get_theme_root() ) {
		// 		return false;
		// 	}
		// } else {
			$dest = get_theme_root() . '/ElectionData';
		// }

		self::recurse_copy( plugin_dir_path( __FILE__ ) . '../theme', $dest );

		return basename( $dest );
	}

	/**
	 * Initial setup of the theme
	 * @return boolean Returns true, if the theme was successfully set up or false if it was not.
	 */
	public static function setup_theme() {
		// Retrieve the name of the current theme
		$current_theme = get_stylesheet();
		Election_Data_Option::update_option( 'previous_theme', $current_theme );

		$theme_name = self::copy_theme( 'ElectionData' );
		if ( $theme_name === false ) {
			return false;
		}

		Election_Data_Option::update_option( 'theme_name', $theme_name );

		if ( ! $theme_name ) {
			$theme_name = 'ElectionData';
		}

		switch_theme( $theme_name );
		return true;
	}

	/**
	 * Creates a new search page if one doesn't exists already and returns it.
	 *
	 * @return WP_Page_Object Retturns the search page object.
	 */
	public static function get_or_add_search_page() {
		$search_pages = get_pages( array(
			'meta_key' => '_wp_page_template',
			'meta_value' => 'searchpage.php',
			'hierarchical' => 0,
		) );

		if ( count( $search_pages ) > 0 ) {
			return $search_pages[0];
		}

		$search_page = array(
			'post_title' => 'Search',
			'post_status' => 'publish',
			'post_type' => 'page',
		);

		$post = get_post( wp_insert_post( $search_page ) );
		update_post_meta( $post->ID, '_wp_page_template', 'searchpage.php' );
		return $post;
	}

	/**
	 * Creates a Navigation Menu with a basic structure, if the navigation menu does not exists already.
	 *
	 * @param  Election_Data_News_Article $news_articles  The "news article" custom post type class.
	 * @param  int                        $seach_page_id  Id of the search page.
	 * @return int                        Id of the navigation menu
	 */
	public static function register_navigation( $news_articles, $seach_page_id ) {

		$menu_name = __( 'Election Data Navigation Menu' );
		$menu = wp_get_nav_menu_object( $menu_name );

		if ( ! $menu ) {
			$menu_id = wp_create_nav_menu( $menu_name );
			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title' => __( 'Home' ),
				'menu-item-url' => home_url( '/' ),
				'menu-item-status' => 'publish',
			) );
			$id = wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title' => __( 'Candidates' ),
				'menu-item-status' => 'publish',
			) );
			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title' => __( 'Party' ),
				'menu-item-status' => 'publish',
				'menu-item-parent-id' => $id,
			) );
			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title' => __( 'Constituency' ),
				'menu-item-status' => 'publish',
				'menu-item-parent-id' => $id,
			) );
			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title' => __( 'Latest News' ),
				'menu-item-url' => get_post_type_archive_link( $news_articles->post_type ),
				'menu-item-status' => 'publish',
			) );
			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title' => __( 'Election Info' ),
				'menu-item-status' => 'publish',
			) );
			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title' => __( 'Search' ),
				'menu-item-status' => 'publish',
				'menu-item-object' => 'page',
				'menu-item-object-id' => $seach_page_id,
				'menu-item-type' => 'post_type',
			) );
			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title' => __( 'About' ),
				'menu-item-status' => 'publish',
			) );

		} else {
			$menu_id = $menu->term_id;
		}
		return $menu_id;
	}

	/**
	 * Displays the active warnings on the admin panel.
	 *
	 */
	public static function display_activation_warnings() {
		$warnings = Election_Data_Option::get_option( 'warnings' );

		if ( is_admin() && $warnings ) : ?>
			<div class="activation_warnings" >
				<?php foreach ( $warnings as $warning ) : ?>
					<div>
						<p><?php echo $warning ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif;

		Election_Data_Option::delete_option( 'warnings' );
	}

}
