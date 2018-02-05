<?php
global $ed_taxonomies;
$answer_party = get_queried_object();
$party = get_candidate_party_from_answer_party( $answer_party );
$party_id = $party['id'];
$can_edit = can_edit_answers( 'party', $party_id );
$questions = get_qanda_questions( 'party', get_queried_object() );

if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
    $nonce_verified = wp_verify_nonce( $_POST['update_party_qanda_nonce'], "update_party_qanda_{$answer_party->term_id}" );
	if ( $can_edit && $nonce_verified ) {
		foreach ( $questions as $answer_id => $question ) {
			$answer = array(
				'ID' => $answer_id,
				'post_content' => $_POST["question_$answer_id"],
			);
			wp_update_post( $answer );
		}
        $party = get_candidate_party_from_answer_party( $answer_party );
        $questions = get_qanda_questions( 'party', get_queried_object() );
	} else {
		wp_die( "Unauthorized access" );
	}
}

$has_qanda = count( $party['answers'] ) > 0;

get_header();
add_filter('mce_buttons','my_editor_buttons',10,2);
add_filter('tiny_mce_before_init','tinymce_call_wordcount',10,2);

function tinymce_call_wordcount(array $init) {
        $init['setup'] = "function(ed){
                ed.on('KeyUp', function(){
                        handle_word_count(this.getContent(),this.id);
                });
                ed.on('Init', function() {
                        handle_word_count(this.getContent(),this.id);
                });
            }";
        return $init;
}

$wp_editor_args = array("media_buttons" => false, "quicktags" => false);

function my_editor_buttons($buttons, $editor_id) {
        return array('bold','italic','underline','bullist','numlist');
}
wp_enqueue_script('wordcounts', get_template_directory_uri() . '/js/questionnaire.js');

?>
<h2 class="title"><?php echo $party['long_title']; ?></h2>
<div class="flow_it">
	<div class="one_column_flow">
		<div class="flow_it">
			<div class="parties">
				<?php display_party( $party ); ?>
			</div>
		</div>
	</div>
	<?php if ( $can_edit && ! empty( $questions ) ) : ?>
		<div class="three_columns q">
			<h2>Questionnaire</h2>
			<p>Please enter your responses for the questions listed below. Questions that do not have a response will not be displayed on the site.<p>
			<form id="party" class="post-edit front-end-form" method="post" enctype="multipart/form-data">
				<input type="hidden" name="party_id" value="<?php echo $answer_party->term_id; ?>" />
				<?php wp_nonce_field( "update_party_qanda_{$answer_party->term_id}", 'update_party_qanda_nonce' ); ?>

				<?php foreach ( $questions as $answer_id => $question ) : ?>
					<p><strong><?php echo $question; ?></strong></p>
					<p class="word-count" id="question_<?php echo $answer_id; ?>_count">Word Count: <span class="total">0</span>
					<p><?php wp_editor( isset( $party['answers'][$question] ) ? $party['answers'][$question] : '', "question_$answer_id", $wp_editor_args ); ?></p>
				<?php endforeach; ?>
				<input type="submit" id="submit_answers" value="Update Answers" />
				<p><span class="submit-warning" style="display:none; color: red;">Please limit all of your questions to 200 words or less</span>
			</form>
		</div>
	<?php endif; ?>
	<?php if ( $has_qanda ) : ?>
	<div class="three_columns questionnaire">
		<h2 id='qanda'><?php echo $can_edit ? 'Current ' : ''; ?>Questionnaire Response</h2>
		<?php foreach ( $party['answers'] as $question => $answer ) : ?>
			<p><strong><?php echo $question; ?></strong></p>
			<p><?php echo $answer; ?></p>
		<?php endforeach; ?>
	</div>
	<?php endif; ?>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
