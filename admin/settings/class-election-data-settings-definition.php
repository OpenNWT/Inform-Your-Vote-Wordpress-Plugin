<?php
/**
 *
 *
 * @link       http://opendemocracymanitoba.ca/
 * @since      1.0.0
 *
 * @package    Election_Data
 * @subpackage Election_Data/includes
 */

/**
 * The Settings definition of the plugin.
 *
 *
 * @package    Election_Data
 * @subpackage Election_Data/includes
 * @author     Robert Burton
 */
class Election_Data_Settings_Definition {

	// @TODO: change plugin-name
	public static $plugin_name = 'election-data';

	/**
	 * [apply_tab_slug_filters description]
	 *
	 * @param  array $default_settings [description]
	 *
	 * @return array                   [description]
	 */
	static private function apply_tab_slug_filters( $default_settings ) {

		$extended_settings[] = array();
		$extended_tabs       = self::get_tabs();

		foreach ( $extended_tabs as $tab_slug => $tab_desc ) {

			$options = isset( $default_settings[ $tab_slug ] ) ? $default_settings[ $tab_slug ] : array();

			$extended_settings[ $tab_slug ] = apply_filters( 'election_data_settings_' . $tab_slug, $options );
		}

		return $extended_settings;
	}

	/**
	 * Get the deafult tab slugs.
	 * @return string Key names of the item.
	 */
	static public function get_default_tab_slug() {

		return key( self::get_tabs() );
	}

	/**
	 * Retrieve settings tabs
	 *
	 * @since    1.0.0
	 * @return    array    $tabs    Settings tabs
	 */
	static public function get_tabs() {

		$tabs                = array();
		$tabs['general_tab'] = __( 'General Settings', self::$plugin_name );
		$tabs['news_scraping_tab'] = __( 'News Scraping', self::$plugin_name );
		$tabs['front_page_tab'] = __( 'Front Page', self::$plugin_name );
		$tabs['header_image_tab'] = __('Header Image', self::$plugin_name);
		$tabs['footer_tab'] = __('Footer', self::$plugin_name );
		$tabs['version_one_theme_tab'] = __('V1 Theme', self::$plugin_name );
		$tabs['meta_data_tab'] = __( 'MetaData', self::$plugin_name );
		$tabs['questions_tab'] = __( 'Question Settings', self::$plugin_name );
		$tabs['import_tab'] = __( 'Import', self::$plugin_name );
		$tabs['export_tab'] = __( 'Export', self::$plugin_name );
		$tabs['address_lookup'] = __( 'Address Lookup Tool', self::$plugin_name );
		//$tabs['second_tab']  = __( 'Second Tab', self::$plugin_name );

		return apply_filters( 'election_data_settings_tabs', $tabs );
	}

	/**
	 * For javascript updates.
	 *
	 * @since    1.0.0
	 */
	static public function get_js_updates() {
		$settings = self::get_settings();

		$types = array();
		foreach ( $settings as $tab ) {
			foreach ( $tab as $setting => $options ) {
				$types[$options['type']][] = array( $setting, $options );
			}
		}

		$localizations = array();
		$dependancies = array();

		foreach ( $types as $type => $settings ) {
			$method = "localize_$type";
			if ( method_exists( 'Election_Data_Callback_Helper', $method ) ) {
				$localizations += Election_Data_Callback_Helper::$method( $settings );
			}
			$method = "js_dependancies_$type";
			if (method_exists( 'Election_Data_Callback_Helper', $method ) ) {
				$dependancies += Election_Data_Callback_Helper::$method();
			}
		}

		return array( $localizations, $dependancies );
	}

	/**
	 * 'Whitelisted' Election_Data settings, filters are provided for each settings
	 * section to allow extensions and other plugins to add their own settings
	 *
	 *
	 * @since    1.0.0
	 * @return    mixed    $value    Value saved / $default if key if not exist
	 */
	static public function get_settings() {

		$settings[] = array();

		$settings = array(
			'address_lookup' => array(
				'address_dataset_available' => array(
				'name' => __( 'Address Dataset', self::$plugin_name ),
				'desc' => __( 'Check the option if you have the correct address dataset and if you want to use the Address Lookup Tool'),
				'type' => 'checkbox',
				),
				'api_key' => array(
					'name' => __('API Key', self::$plugin_name),
					'desc' => __('You will need a google maps api key. Click <a href="https://developers.google.com/maps/documentation/static-maps/get-api-key">here</a> to get one now..', self::$plugin_name),
					'type' => 'text'
				),
				'import' => array(
					'name' => __( 'Import Data', self::$plugin_name ),
					'desc' => __( 'Import the addresses using a CSV file.', self::$plugin_name ),
					'type' => 'import',
					'options' => array(
						'id' => 'ed_import',
						'formats' => array(
							//'xml' => __( 'XML file', self::$plugin_name ),
							'csv_address' => __( 'CSV file containing addresses.', self::$plugin_name ),
						),
						'required_modules' => array(
							'xml' => array( 'xmlreader' ),
							'csv_zip' => array( 'zip' ),
						),
						'skip_if_modules_loaded' => array(),
						'default' => 'csv_zip',
					),
					'no_value' => true,
				),
				'clear' => array(
					'desc' => __( 'Deletes all the addresses. Warning: This <strong>cannot</strong> be undone.<br>After clicking the button, the process will continue to run in the backend, so please <strong>donot</strong> restart the server until its done.<br>' ),
					'type' => 'button',
					'options' => array(
						'id' => 'button_delete_address_data',
						'label' => __( 'Delete Addresses', self::$plugin_name ),
						'action' => 'delete_address_data',
						'message' => __( 'Are you sure? This will remove all the address.', self::$plugin_name ),
					),
				),
			),
			'import_tab' => array(
				'import' => array(
					'name' => __( 'Import Data', self::$plugin_name ),
					'desc' => __( 'Import the contents of CSV or XML file into the site.', self::$plugin_name ),
					'type' => 'import',
					'options' => array(
						'id' => 'ed_import',
						'formats' => array(
							//'xml' => __( 'XML file', self::$plugin_name ),
							'csv_zip' => __( 'Zip file generated by a previous export ( always overwrites existing data )', self::$plugin_name ),
							'csv_party' => __( 'CSV file containing parties', self::$plugin_name ),
							'csv_constituency' => __( 'CSV file containing constituencies', self::$plugin_name ),
							'csv_candidate' => __( 'CSV file containing candidates', self::$plugin_name ),
							'csv_address' => __( 'CSV file containing addresses.', self::$plugin_name ),
							'csv_news_source' => __( 'CSV file containing news sources', self::$plugin_name ),
							'csv_news_article' => __( 'CSV file containing news articles', self::$plugin_name ),
							'csv_news_mention' => __( 'CSV file containing news_mentions', self::$plugin_name ),
							'csv_question' => __( 'CSV file containing questions for candidates and parties', self::$plugin_name ),
							'csv_answer' => __( 'CSV file containing the candidate/party answeres to the questions', self::$plugin_name ),
							'csv_settings' => __( 'CSV file containing site settings', self::$plugin_name ),
						),
						'required_modules' => array(
							'xml' => array( 'xmlreader' ),
							'csv_zip' => array( 'zip' ),
						),
						'skip_if_modules_loaded' => array(
							'csv_news_source' => array( 'zip' ),
							'csv_news_article' => array( 'zip' ),
							'csv_news_mention' => array( 'zip' ),
							'csv_settings' => array( 'zip' ),
							'csv_question' => array( 'zip' ),
							'csv_answer' => array( 'zip' ),
						),
						'default' => 'csv_zip',
					),
					'no_value' => true,
				),
				'clear' => array(
					'desc' => __( 'Removes all Election Data content from the site. Warning: This <strong>cannot</strong> be undone.<br> After clicking the button, the process will continue to run in the backend, so please <strong>donot</strong> restart the server until its done.<br>' ),
					'type' => 'button',
					'options' => array(
						'id' => 'button_erase_site',
						'label' => __( 'Erase Site', self::$plugin_name ),
						'action' => 'election_data_erase_site',
						'message' => __( 'Are you sure? This will remove all election related data from the site.', self::$plugin_name ),
					),
					'no_value' => true,
				),
			),
			'export_tab' => array(
				'export' => array(
					'name' => __( 'Export Data', self::$plugin_name ),
					'desc' => __( 'Export the sote content to CSV or XML files suitable for importing on another installation.', self::$plugin_name ),
					'type' => 'export',
					'options' => array(
						'id' => 'ed_export',
						'formats' => array(
							//'xml' => __( 'XML file (A single XML file containing all of the site data.)', self::$plugin_name ),
							'csv_zip' => __( 'Zip file (Multiple CSV files stored in a zip file.)', self::$plugin_name ),
							'csv_party' => __( 'CSV file containing parties', self::$plugin_name ),
							'csv_constituency' => __( 'CSV file containing constituencies', self::$plugin_name ),
							'csv_candidate' => __( 'CSV file containing candidates', self::$plugin_name ),
							'csv_address' => __( 'CSV file containing addresses.', self::$plugin_name ),
							'csv_news_source' => __( 'CSV file containing news sources', self::$plugin_name ),
							'csv_news_article' => __( 'CSV file containing news articles', self::$plugin_name ),
							'csv_news_mention' => __( 'CSV file containing news mentions', self::$plugin_name ),
							'csv_question' => __( 'CSV file containing questions for candidates and parties', self::$plugin_name ),
							'csv_answer' => __( 'CSV file containing the candidate/party answeres to the questions', self::$plugin_name ),
							'csv_settings' => __( 'CSV file containing site settings', self::$plugin_name ),
						),
						'required_modules' => array(
							'xml' => array( 'xmlwriter' ),
							'csv_zip' => array( 'zip' ),
						),
						'skip_if_modules_loaded' => array(
							'csv_news_source' => array( 'zip' ),
							'csv_news_article' => array( 'zip' ),
							'csv_news_mention' => array( 'zip' ),
							'csv_settings' => array( 'zip' ),
							'csv_question' => array( 'zip' ),
							'csv_answer' => array( 'zip' ),
						),
						'default' => 'csv_Zip',
					),
					'no_value' => true,
				),
			),
			'news_scraping_tab' => array(
/*				'default_tab_settings'       => array(
					'name' => '<strong>' . __( 'Header', self::$plugin_name ) . '</strong>',
					'type' => 'header'
				),*/
				'location' => array(
					'name' => __( 'Location', self::$plugin_name ),
					'desc' => __( 'The location of the election.', self::$plugin_name ),
					'type' => 'text'
				),
				'time' => array(
					'name' => __( 'Scrape Time', self::$plugin_name ),
					'desc' => __( 'The time of the initial scrape. ie. 2am CDT', self::$plugin_name ),
					'type' => 'text',
					'std' => '2am'
				),
				'number_of_results' => array(
					'name' => __( 'Results per Candidate', self::$plugin_name ),
					'desc' => __( 'Max number of articles to retrieve per candidate.', self::$plugin_name ),
					'type' => 'text',
					'std' => '20'
				),
				'cutoff_date' => array(
					'name' => __( 'Cutoff Date', self::$plugin_name ),
					'desc' => __( 'Scraper will not retrieve results beyond this date. (yyyy-mm-dd) Default is blank (no cutoff).', self::$plugin_name ),
					'type' => 'text'
				),
				'news-scraping-subheading' => array(
					'name' => __( 'Sub Heading', self::$plugin_name ),
					'desc' => __( 'Display text that you want to appear above the news feed on candidate pages.', self::$plugin_name ),
					'type' => __( 'rich_editor' ),
				),
				'source' => array(
					'name' => __( 'Source', self::$plugin_name ),
					'desc' => __( 'Choose between Google News Scraper and RSS Search. Google News produces lots of false positives.', self::$plugin_name ),
					'type' => 'select',
					'std' => 'api',
					'options' => array (
						'api' => __( 'RSS Search', self::$plugin_name ),
						'google' => __( 'Google News Scraper', self::$plugin_name ),
					),
				),
				'source-api' => array(
					'name' => __( 'Source API URL', self::$plugin_name ),
					'desc' => __( 'API URL for searching RSS feeds', self::$plugin_name ),
					'type' => 'text',
					'std' => '',
				),
				'frequency' => array(
					'name' => __( 'Frequency', self::$plugin_name ),
					'desc' => __( 'The frequency of the scraping.', self::$plugin_name ),
					'options' => array(
						'daily' => __( 'Once every 24 hours', self::$plugin_name ),
						'twicedaily' => __( 'Once every 12 hours', self::$plugin_name ),
					),
					'type' => 'select',
					'std' => 'daily'
				),
				'scrape' => array(
					'desc' => __( 'Manually perform the scraping of news articles.', self::$plugin_name ),
					'type' => 'button',
					'options' => array(
						'id' => 'button_scrape_news',
						'label' => __( 'Scrape News', self::$plugin_name ),
						'action' => 'election_data_scrape_news',
					),
					'no_value' => true,
				),
                'scrub' => array(
                    'desc' => __( 'Remove articles that do not contain the candidate\'s name in the article', self::$plugin_name ),
                    'type' => 'button',
                    'options' => array(
                        'id' => 'button_scrub_news',
                        'label' => __( 'Remove News', self::$plugin_name ),
                        'action' => 'election_data_scrub_news',
                    ),
                    'no_value' => true,
                ),
			),
			'questions_tab' => array(
				'answers' => array(
					'desc' => __( 'Create all missing answer posts.', self::$plugin_name ),
					'type' => 'button',
					'options' => array(
						'id' => 'button_create_answers',
						'label' => __( 'Create Answers', self::$plugin_name ),
						'action' => 'election_data_create_answers',
					),
					'no_value' => true,
				),
				'send-all-email' => array(
					'desc' => __( 'Send questionnaire to Candidates/Parties.', self::$plugin_name ),
					'type' => 'button',
					'options' => array(
						'id' => 'button_send_all_email',
						'label' => __( 'Send All Emails', self::$plugin_name ),
						'action' => 'election_data_send_all_email',
					),
					'no_value' => true,
				),
                'send-candidate-email' => array(
                    'desc' => __( 'Send questionnaire to Candidates.', self::$plugin_name ),
                    'type' => 'button',
					'options' => array(
						'id' => 'button_send_candidate_email',
						'label' => __( 'Send Candidate Emails', self::$plugin_name ),
						'action' => 'election_data_send_candidate_email',
					),
					'no_value' => true,
				),
                'send-party-email' => array(
                    'desc' => __( 'Send questionnaire to Parties.', self::$plugin_name ),
                    'type' => 'button',
					'options' => array(
						'id' => 'button_send_party_email',
						'label' => __( 'Send Party Emails', self::$plugin_name ),
						'action' => 'election_data_send_party_email',
					),
					'no_value' => true,
				),
				'reset-party-questionnaire' => array(
					'desc' => __( 'Unset the sent questionnaire checkbox for all parties.', self::$plugin_name ),
					'type' => 'button',
					'options' => array(
						'id' => 'button_unset_party',
						'label' => __( 'Reset Parties', self::$plugin_name ),
						'action' => 'election_data_reset_party_questionnaire',
						'message' => __( 'Are you sure you want to unset the questionnaire sent checkbox for all parties. This means that e-mails will be resent to parties that have already been sent one.', self::$plugin_name ),
					),
				),
				'reset-candidate-questionnaire' => array(
					'desc' => __( 'Unset the sent questionnaire checkbox for all candidates.', self::$plugin_name ),
					'type' => 'button',
					'options' => array(
						'id' => 'button_unset_candidate',
						'label' => __( 'Reset Candidates', self::$plugin_name ),
						'action' => 'election_data_reset_candidate_questionnaire',
						'message' => __( 'Are you sure you want to unset the questionnaire sent checkbox for all candidates. This means that e-mails will be resent to candidates that have already been sent one.', self::$plugin_name ),
					),
				),
                'reset-unanswered-questionnaire' => array(
					'desc' => __( 'Unset the sent questionnaire checkbox for all candidates and parties that have not responded.', self::$plugin_name ),
					'type' => 'button',
					'options' => array(
						'id' => 'button_unset_unanswered',
						'label' => __( 'Reset Unanswered Questionnaires', self::$plugin_name ),
						'action' => 'election_data_reset_questionnaire_unanswered',
						'message' => __( 'Are you sure you want to unset the questionnaire sent checkbox. This means that e-mails will be resent to candidates and parties that have already been sent one.', self::$plugin_name ),
					),
				),
				'smtp-server' => array(
					'name' => __( 'SMTP Server', self::$plugin_name ),
					'desc' => __( 'SMTP server to use for sending emails', self::$plugin_name ),
					'type' => 'text',
				),
				'smtp-port' => array(
					'name' => __( 'SMTP Port', self::$plugin_name ),
					'desc' => __( 'Port number for the SMTP server', self::$plugin_name ),
					'std' => '25',
					'type' => 'text',
				),
				'smtp-encryption' => array(
					'name' => __( 'SMTP Encryption', self::$plugin_name ),
					'desc' => __( 'The type of encrypyion (if any) used by the smtp server.', self::$plugin_name ),
					'type' => 'select',
					'options' => array(
						'' => 'None',
						'tls' => 'TLS',
						'ssl' => 'SSL',
					),
				),
				'smtp-user' => array(
					'name' => __( 'SMTP User', self::$plugin_name ),
					'desc' => __( 'User name for the SMTP server', self::$plugin_name ),
					'type' => 'text',
				),
				'smtp-password' => array(
					'name' => __( 'SMTP Password', self::$plugin_name ),
					'desc' => __( 'Password for the SMTP server', self::$plugin_name ),
					'type' => 'password',
				),
				'email-delay' => array(
					'name' => __( 'Email delay (in milliseconds)', self::$plugin_name ),
					'desc' => __( 'Allows you to throttle for certain email services, leave blank for none', self::$plugin_name ),
					'type' => 'text',
				),
				'email-limit' => array(
					'name' => __( 'Batch limit for emails', self::$plugin_name ),
					'desc' => __( 'Will stop sending emails after limit reached, leave blank for none', self::$plugin_name ),
					'type' => 'text',
				),
				'from-email-address' => array(
					'name' => __( 'From Email Address', self::$plugin_name ),
					'desc' => __( 'The email address to be used when sending emails.', self::$plugin_name ),
					'type' => 'text',
				),
				'from-email-name' => array(
					'name' => __( 'From Name', self::$plugin_name ),
					'desc' => __( 'The name to use in the from field of the email.', self::$plugin_name ),
					'type' => 'text',
				),
				'reply-to' => array(
					'name' => __( 'Optional Reply To', self::$plugin_name ),
					'desc' => __( 'If set, replying to email will go to this email address.', self::$plugin_name ),
					'type' => 'text',
				),
				'subject-candidate' => array(
					'name' => __( 'Candidate Email Subject', self::$plugin_name ),
					'desc' => __( "The subject of the emails to the candidates. In the subject the following substitutions will occur:<br><list><li>*candidate* → The name of the candidate</li><li>*party* → The name of the candidate's party</li><li>*party_alt* → The alternate name of the candidate's party</li><li>*question* → The questions for the candidate</li><li>*question_url* → The url that can be used to edit the answers</li></list>", self::$plugin_name ),
					'type' => 'text',
				),
				'email-candidate' => array(
					'name' => __( 'Candidate Email', self::$plugin_name ),
					'desc' => __( "The email that will be sent to the candidates to let them know about the questionnaire. In the email the following substitutions will occur:<br><list><li>*candidate* → The name of the candidate</li><li>*party* → The name of the candidate's party</li><li>*party_alt* → The alternate name of the candidate's party</li><li>*question* → The questions for the candidate</li><li>*question_url* → The url that can be used to edit the answers</li></list>", self::$plugin_name ),
					'type' => 'rich_editor',
				),
				'subject-party' => array(
					'name' => __( 'Party Email Subject', self::$plugin_name ),
					'desc' => __( 'The subject of the emails to the parties. In the subject the following substitutions will occur:<br><list><li>*party* → The name of the party</li><li>*party_alt* → The alternate name of the party</li><li>*question* → The questions for the party</li><li>*question_url* → The url that can be used to edit the answers</li></list>', self::$plugin_name ),
					'type' => 'text',
				),
				'email-party' => array(
					'name' => __( 'Party Email', self::$plugin_name ),
					'desc' => __( 'The email that will be sent to the parites to let them know about the questionnaire. In the email the following substitutions will occur:<br><list><li>*party* → The name of the party</li><li>*party_alt* → The alternate name of the party</li><li>*question* → The questions for the party</li><li>*question_url* → The url that can be used to edit the answers</li></list>', self::$plugin_name ),
					'type' => 'rich_editor',
				),
			),
			'version_one_theme_tab' => array(
				'summary' => array(
					'name' => __( 'Summary', self::$plugin_name ),
					'desc' => __( 'A summary that will appear on the front page of the site. Can include links to important sites, election dates, etc.', self::$plugin_name ),
					'type' => 'rich_editor',
				),
				'about-us' => array(
					'name' => __( 'About Us', self::$plugin_name ),
					'desc' => __( 'This will populate the contents of the top-right slide of the front page.'),
					'type' => 'rich_editor',
				),
				'facebook-page' => array(
					'name' => __( 'Facebook page', self::$plugin_name ),
					'desc' => __( 'A facebook page that you would like featured on the front-page.', self::$plugin_name ),
					'type' => 'text',
				),
				'twitter' => array(
					'name' => __( 'Twitter Account', self::$plugin_name ),
					'desc' => __( 'A twitter account you would like featured on the front page.', self::$plugin_name ),
					'type' => 'text',
				),
				'google-plus-one' => array(
					'name' => __( 'Google Plus One', self::$plugin_name ),
					'desc' => __( 'Check if you would like a Google +1 button.', self::$plugin_name ),
					'type' => 'checkbox',
					'std' => true,
				),
				'constituency-label' => array(
					'name' => __( 'Constituency Label', self::$plugin_name ),
					'desc' => __( 'The label you would like to use for the constituency section.', self::$plugin_name ),
					'type' => 'text',
				),
				'constituency-subtext' => array(
					'name' => __( 'Constituency Description', self::$plugin_name ),
					'desc' => __( 'An optional description for the constituency section.', self::$plugin_name ),
					'type' => 'text',
				),
				'party-label' => array(
					'name' => __( 'Party Label', self::$plugin_name ),
					'desc' => __( 'The label you would like to use for the party section.', self::$plugin_name ),
					'type' => 'text',
				),
				'party-subtext' => array(
					'name' => __( 'Party Description', self::$plugin_name ),
					'desc' => __( 'An optional description for the party section.', self::$plugin_name ),
					'type' => 'text',
				),
			),
			'front_page_tab' => array(
				'news-count-front' => array(
					'name' => __( 'News Articles', self::$plugin_name ),
					'desc' => __( 'The number of news articles to display on the front-page.', self::$plugin_name ),
					'type' => 'number',
					'min' => 0,
					'step' => 1,
					'placeholder' => '3',
				),
				'left_column_title' => array(
				'name' => __( 'Left Column Title', self::$plugin_name ),
				'desc' => __( 'The title for the left column.' ),
				'type' => 'text',
				'placeholder' => 'Who Am I Voting For?',
				'std' => 'Who Am I Voting For?',
				),
				'left_column_url' => array(
				'name' => __( 'Left Column Url', self::$plugin_name ),
				'desc' => __( 'The url that the left column links to.'),
				'type' => 'text',
				'placeholder' => 'sample-page or https://www.google.com',
				),
				'left_column_excerpt' => array(
				'name' => __( 'Left Column Excerpt', self::$plugin_name ),
				'desc' => __( 'The content for the left column.'),
				'type' => 'textarea',
				'std' => 'Find out more here about Mayoral, Council and Trustee candidate.',
				),
				'left_column_img' => array(
				'name' => __( 'Left Column Image Logo', self::$plugin_name ),
				'desc' => __( 'The image to display on the left column.'),
				'type' => 'image',
				),
				'center_column_title' => array(
				'name' => __( 'Center Column Title', self::$plugin_name ),
				'desc' => __( 'The title for the center column.' ),
				'type' => 'text',
				'placeholder' => 'Where Do I Vote?',
				'std' => 'Where Do I Vote?',
				),
				'center_column_url' => array(
				'name' => __( 'Center Column Url', self::$plugin_name ),
				'desc' => __( 'The url that the center column links to.'),
				'type' => 'text',
				'placeholder' => 'sample-page or https://www.google.com',
				),
				'center_column_excerpt' => array(
				'name' => __( 'Center Column Excerpt', self::$plugin_name ),
				'desc' => __( 'The content for the center column.'),
				'type' => 'textarea',
				'std' => 'You can find out where to vote by using the City of Winnipeg address look-up tool here.',
				),
				'center_column_img' => array(
				'name' => __( 'Center Column Image Logo', self::$plugin_name ),
				'desc' => __( 'The image to display on the center column.'),
				'type' => 'image',
				),
				'right_column_title' => array(
				'name' => __( 'Right Column Title', self::$plugin_name ),
				'desc' => __( 'The title for the right column.' ),
				'type' => 'text',
				'placeholder' => 'What Am I Voting For?',
				'std' => 'What Am I Voting For?',
				),
				'right_column_url' => array(
				'name' => __( 'Right Column Url', self::$plugin_name ),
				'desc' => __( 'The url that the right column links to.'),
				'type' => 'text',
				'placeholder' => 'sample-page or https://www.google.com',
				),
				'right_column_excerpt' => array(
				'name' => __( 'Right Column Excerpt', self::$plugin_name ),
				'desc' => __( 'The content for the right column.'),
				'type' => 'textarea',
				'std' => 'Not sure what you’re voting for, find out more here.',
				),
				'right_column_img' => array(
				'name' => __( 'Right Column Image Logo', self::$plugin_name ),
				'desc' => __( 'The image to display on the right column.'),
				'type' => 'image',
				),
        'front_page_seo' => array(
					'name' => __( 'Front Page SEO Text', self::$plugin_name ),
					'desc' => __( 'Display text at the bottom of the front page, mainly for SEO purposes.', self::$plugin_name ),
					'type' => __( 'rich_editor' ),
				),

			),
			'header_image_tab' => array(
				'candidates_party_header_img' => array(
				'name' => __( 'Party Header Image', self::$plugin_name ),
				'desc' => __( 'The header image to display on the party page. If it is not selected, it will display the same image as the front page.'),
				'type' => 'image',
				),
				'candidates_constituency_header_img' => array(
				'name' => __( 'Constituency Header Image', self::$plugin_name ),
				'desc' => __( 'The header image to display on the constituency page. If it is not selected, it will display the same image as the front page.'),
				'type' => 'image',
				),
				'candidates_header_img' => array(
				'name' => __( 'Candidates Header Image', self::$plugin_name ),
				'desc' => __( 'The header image to display on the candidates page. If it is not selected, it will display the same image as the front page.'),
				'type' => 'image',
				),
				'zoom_to_top' => array(
					'name' => __('Zoom to Top', self::$plugin_name),
					'desc' => __('For different screen sizes should the header image zoom to the top of the image. (Default bottom)', self::$plugin_name),
					'type' => 'checkbox'
				),

			),
			'footer_tab' => array(
				'footer' => array(
					'name' => __('Footer', self::$plugin_name),
					'desc' => __('Text which will be displayed on the footer of each page.', self::$plugin_name),
					'type' => 'textarea',
				),
				'footer-left' => array(
					'name' => __('Footer Left', self::$plugin_name),
					'desc' => __('Text which will be displayed in the left of the footer of each page.', self::$plugin_name),
					'type' => 'rich_editor',
				),
				'footer-center' => array(
					'name' => __('Footer Center', self::$plugin_name),
					'desc' => __('Text which will be displayed in the center of the footer of each page.', self::$plugin_name),
					'type' => 'rich_editor',
				),
				'footer-right' => array(
					'name' => __('Footer Right', self::$plugin_name),
					'desc' => __('Text which will be displayed in the right of the footer of each page.', self::$plugin_name),
					'type' => 'rich_editor',
				),
			),
			'meta_data_tab' => array(
				'site_title' => array(
					'name' => __('Site Title', self::$plugin_name),
					'desc' => __('This will be the title of your site.', self::$plugin_name),
					'type' => 'text'
				),
				'site_description' => array(
					'name' => __('Site Description', self::$plugin_name),
					'desc' => __('A brief description of the site.', self::$plugin_name),
					'type' => 'textarea'
				),
				'site_image' => array(
					'name' => __('Site Image', self::$plugin_name),
					'desc' => __('An image that represents your site.', self::$plugin_name),
					'type' => 'image'
				),
			),
			'general_tab' => array(
				'election_date' => array(
				'name' => __( 'Election Date', self::$plugin_name ),
				'desc' => __( 'To display the election date, use this format YYYY-MM-DD.'),
				'type' => 'text',
				'placeholder' => '2018-10-24',
				),
				'party_election' => array(
					'name' => __('Party Election', self::$plugin_name),
					'desc' => __('Check if the current election is based on political parties.', self::$plugin_name),
					'type' => 'checkbox'
				),
				'missing_constituency' => array(
					'name' => __( 'Missing Constituency Map Image', self::$plugin_name ),
					'desc' => __( 'The image to display if a parent constitency does not have any child ones.', self::$plugin_name ),
					'type' => 'image',
				),
				'missing_candidate' => array(
					'name' => __( 'Missing Candidate Image', self::$plugin_name ),
					'desc' => __( 'The image to display if a candidate does not have a featured image.', self::$plugin_name ),
					'type' => 'image',
				),
				'missing_party' => array(
					'name' => __( 'Missing Party Logo', self::$plugin_name ),
					'desc' => __( 'The image to display if a party does not have a logo.', self::$plugin_name ),
					'type' => 'image',
				),
				'news-count-party' => array(
					'name' => __( 'Party News Articles', self::$plugin_name ),
					'desc' => __( 'The number of news articles to display on the party page.', self::$plugin_name ),
					'type' => 'number',
					'min' => 0,
					'step' => 1,
				),
				'news-count-party-leader' => array(
					'name' => __( 'Party Leader News Articles', self::$plugin_name ),
					'desc' => __( 'The number of news articles about the party leader to display on the party page.', self::$plugin_name ),
					'type' => 'number',
					'min' => 0,
					'step' => 1,
				),
				'news-count-candidate' => array(
					'name' => __( 'Candidate News Articles', self::$plugin_name ),
					'desc' => __( 'The number of news articles to display at a time on the candidate page.', self::$plugin_name ),
					'type' => 'number',
					'min' => 0,
					'step' => 1,
				),
				'news-count-constituency' => array(
					'name' => __( 'Constituency News Articles', self::$plugin_name ),
					'desc' => __( 'The number of news articles to display at a time on the constituency page.', self::$plugin_name ),
					'type' => 'number',
					'min' => 0,
					'step' => 1,
				),
				'google-analytics' => array(
					'name' => __( 'Google Analytics Script', self::$plugin_name ),
					'desc' => __( 'To track visitor analytics, sign up for a Google Analytics account and paste their provided tracking script here.' ),
					'type' => 'textarea',
				),
				'candidate-link' => array(
				'name' => __( 'Candidates Link for Breadcrumbs', self::$plugin_name ),
				'desc' => __( 'For breadcrumbs shown on candidate and constituency pages.'),
				'type' => 'text',
				'placeholder' => '/',
				),
        'electoral-division-term' => array(
				'name' => __( 'Custom Term for Electoral Divisions', self::$plugin_name ),
				'desc' => __( 'In a municipal election this term should likely be "Ward".'),
				'type' => 'text',
				'placeholder' => 'Electoral Division',
				),

			),
		);

		return self::apply_tab_slug_filters( $settings );
	}
}
