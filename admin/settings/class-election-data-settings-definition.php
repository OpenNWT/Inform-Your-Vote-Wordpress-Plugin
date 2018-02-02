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
 * @author     Your Name <email@example.com>
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
	 * [get_default_tab_slug description]
	 * @return [type] [description]
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
		$tabs['front_page_tab'] = __( 'Front Page', self::$plugin_name );
		$tabs['news_scraping_tab'] = __( 'News Scraping', self::$plugin_name );
		$tabs['general_tab'] = __( 'General Settings', self::$plugin_name );
		$tabs['questions_tab'] = __( 'Question Settings', self::$plugin_name );
		$tabs['import_tab'] = __( 'Import', self::$plugin_name );
		$tabs['export_tab'] = __( 'Export', self::$plugin_name );
		//$tabs['second_tab']  = __( 'Second Tab', self::$plugin_name );

		return apply_filters( 'election_data_settings_tabs', $tabs );
	}

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
					'desc' => __( 'Removes all Election Data content from the site. Warning: This <strong>cannot</strong> be undone.' ),
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
				'news-scraping-subheading' => array(
					'name' => __( 'Sub Heading', self::$plugin_name ),
					'desc' => __( 'Display text that you want to appear above the news feed on candidate pages.', self::$plugin_name ),
					'type' => __( 'rich_editor' ),
				),
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
			'front_page_tab' => array(
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
					'name' => __( 'Constiuency Label', self::$plugin_name ),
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
				'news-count-front' => array(
					'name' => __( 'News Articles', self::$plugin_name ),
					'desc' => __( 'The number of news articles to display on the front-page.', self::$plugin_name ),
					'type' => 'number',
					'min' => 0,
					'step' => 1,
				),
			),
			'general_tab' => array(
				'site_title' => array(
					'name' => __('Site Title', self::$plugin_name),
					'desc' => __('The title you want to display as meta data.', self::$plugin_name),
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
				'footer' => array(
					'name' => __('Footer', self::$plugin_name),
					'desc' => __('Text which will be displayed on the footer of each page.', self::$plugin_name),
					'type' => 'textarea'
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
					'desc' => __( 'If you wish to use Google Analytics, please paste the script provided by them here.' ),
					'type' => 'textarea',
				),
			),
		);

		return self::apply_tab_slug_filters( $settings );
	}
}
