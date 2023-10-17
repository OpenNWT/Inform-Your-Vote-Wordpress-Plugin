<?php
/**
 * The template for displaying all single posts and attachments
 * @author Robert Burton
 */

//get_header();
$candidate_id = get_the_ID();
$candidate = get_candidate( $candidate_id, true );
$party = null;
if ($is_party_election) {
  $party = get_party_from_candidate( $candidate_id );
}
$constituency = get_constituency_from_candidate( $candidate_id );
$candidate_news = get_news( $candidate['news_article_candidate_id'] );
$has_qanda = count( $candidate['answers'] ) > 0;
$contact = [];
if ($candidate['facebook'] || $candidate['youtube'] || $candidate['twitter'] || $candidate['instagram']) {
  $contact[] = 'on social media';
}
if ($candidate['email']) {
  $contact[] = 'at <a href="mailto:' . $candidate['email'] .'">' . $candidate['email'] . '</a>';
}
if ($candidate['phone']) {
  $contact[] = 'by calling ' . $candidate['phone'];
}
if (count($contact) == 2) {
  $contact[1] = 'or ' . $contact[1];
} elseif (count($contact) == 3) {
  $contact[2] = 'or ' . $contact[2];
}

get_header(); ?>
<p class="breadcrumbs">
  <a href="<?= Election_Data_Option::get_option( 'candidate-link', '/' ) ?>">Candidates</a> / 
  <?php if(isset($constituency['parent_name'])): ?>
    <a href="<?= $constituency['parent_url'] ?>"><?= $constituency['parent_name'] ?></a> /
  <?php endif ?>
  <a href="<?php echo $constituency['url']; ?>"><?php echo esc_html( $constituency['name'] ); ?></a> 
  / <a href="<?= $candidate['url'] ?>"><?= $candidate['name'] ?></a>
</p>
<div class="flow_it">
	<div class="politicians">
		<?php display_candidate( $candidate, $constituency, $party, [], 'constituency' ); ?>
	</div>
  <div class="two_columns_early_shrink short">
    <h2 class="title"><?php echo $candidate['name']; ?></h2>
    <p>
      <?php if ($is_party_election && $party['name']): ?>
        <?php if ($candidate['party_leader']): ?>
          Party leader and
        <?php endif ?>
        <a href="<?= $party['url'] ?>"><?= $party['name'] ?></a> 
        candidate
      <?php else: ?>
        Candidate
      <?php endif ?>

      <?php if(isset($constituency['parent_name'])): ?>
        in the <a href="<?php echo $constituency['url']; ?>"><?php echo esc_html( $constituency['name'] ); ?> <?= $constituency['parent_name'] ?></a> race.
      <?php else: ?>
        in the <a href="<?php echo $constituency['url']; ?>"><?php echo esc_html( $constituency['name'] ); ?></a> race.
      <?php endif ?>
    </p>
    <?php if (count($contact) > 0): ?>
      <p>
        <?= explode(' ', $candidate['name'])[0] ?>
        can be reached 
        <?= implode(', ', $contact) . '.' ?>
      </p>
    <?php endif ?>
    <?php if ($has_qanda): ?>
      <p>
        <b>
          Their response to our candidate questionnaire <a href="#qanda">can be read below</a>.
        </b>
      </p>
    <?php endif ?>
    <?php if ($candidate['news_count'] > 0): ?>
      <p>
        News that mentions this candidate is listed <a href="#news">at the bottom of the page</a>.
      </p>
    <?php endif ?>
    <br>

    <p class="grey">Our questionnaire and candidate data retrieval processes are available in <a href="/frequently-asked-questions">our FAQ</a>.</p>
  </div>
  <?php if ($has_qanda): ?>
    <div class="three_columns">
      <h2 id="qanda">Questionnaire Response</h2>
      <div class="questionnaire">
        <p class="visible_block_when_mobile" ><?php echo "{$candidate['name']} - {$constituency['name']}"; ?></p>
        <?php foreach ( $candidate['answers'] as $question => $answer ) :?>
          <p><strong><?php echo $question; ?></strong></p>
          <p><?php echo $answer; ?></p>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endif ?>
  <?php if ($candidate['news_count'] > 0): ?>
    <div class="three_columns">
      <h2 id="news">News that mentions <?php echo $candidate['name']; ?></h2>
      <p class="news-article-notice"><?php echo Election_Data_Option::get_option( 'news-scraping-subheading' ) ?></p>
      <?php $article_count = Election_Data_Option::get_option('news-count-candidate', 10);
      display_news_summaries( $candidate['news_article_candidate_id'], 'Candidate', $article_count ); ?>
    </div>
  <?php endif ?>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
