<?php

/**
 *	The front page.
 *
 * @package Election_Data_Theme
 * @since Election_Data_Theme 1.0
 * @author Heng Yu
 */


/* Left column*/
$left_column_title = Election_Data_Option::get_option('left_column_title') ?: "Who Am I Voting For?";
$left_column_excerpt = Election_Data_Option::get_option('left_column_excerpt') ?: "Find out more here about Mayoral, Council and Trustee candidate.";
$left_column_img = wp_get_attachment_image(Election_Data_Option::get_option('left_column_img'));
$left_column_url = Election_Data_Option::get_option('left_column_url') ?: "";

/* Center column*/
$center_column_title = Election_Data_Option::get_option('center_column_title') ?: "Where Do I Vote?";
$center_column_excerpt = Election_Data_Option::get_option('center_column_excerpt') ?: "You can find out where to vote by using the City of Winnipeg address look-up tool here.";
$center_column_img = wp_get_attachment_image(Election_Data_Option::get_option('center_column_img'));
$center_column_url = Election_Data_Option::get_option('center_column_url') ?: "";

/* Right column*/
$right_column_title = Election_Data_Option::get_option('right_column_title') ?: "What Am I Voting For?";
$right_column_excerpt = Election_Data_Option::get_option('right_column_excerpt') ?: "Not sure what you’re voting for, find out more here.";
$right_column_img = wp_get_attachment_image(Election_Data_Option::get_option('right_column_img'));
$right_column_url = Election_Data_Option::get_option('right_column_url') ?: "";

/* SEO text */
$front_page_seo = Election_Data_Option::get_option('front_page_seo') ?: "";


$party_election = Election_Data_Option::get_option('party_election');

if ($party_election) {
  $parties = get_parties_random();
}

$electoral_division_term = Election_Data_Option::get_option('electoral-division-term') ?: "Electoral Division";
$constituencies = get_root_constituencies();

get_header(); ?>


<?php if (!$party_election): ?>
    <?php if (!shortcode_exists("address-lookup-widget")): ?>
    <h2 class="no-party-front-page-header hidden_block_when_mobile">Your Election Candidates</h2>
    <h2 class="no-party-front-page-header visible_block_when_mobile">Election Candidates</h2>
    <?php endif; ?>
    <?php echo do_shortcode("[address-lookup-widget]"); ?>
    <div class="front-constituency-maps">
      <?php foreach ( $constituencies as $constituency_id ) :
        $constituency = get_constituency( $constituency_id ); ?>
        <div>
          <h3><a href="<?php echo $constituency['url']; ?>"><?php echo $constituency['name']; ?></a></h3>
          <a href="<?php echo $constituency['url']; ?>" title="Click to see the candidates.">
            <?php echo wp_get_attachment_image($constituency['map_id'], 'map_thumb', true, array( 'alt' => $constituency['name'] ) ); ?>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
<?php endif ?>

<div class="front-info">
  <h2>It's Election Time in the NWT</h2>
  <p><strong>Mark your calendars! The next NWT General Election is slated for Tuesday, November 14th, 2023.</strong></p>
  <p>As mandated by the <a href="https://www.electionsnwt.ca/en/legislation#the-elections-and-plebiscites-act">Elections and Plebiscites Act</a>, territorial elections occur every four years on the first Tuesday in October (but this year was delayed due to wildfires). This pivotal event gives every eligible resident of the Northwest Territories the opportunity to exercise their democratic rights.</p>
  <br><br>
</div>

<!--Three columns part -->
<div class="front-tiles">
  <div class="tile">
    <h1>
      <a href="<?php echo $left_column_url;?>"><?php echo $left_column_title;?></a>
    </h1>
    <p class="description">
      <a href="<?php echo $left_column_url;?>">
        <?php echo $left_column_excerpt; ?>
      </a>
    </p>
    <p class="img">
      <a href="<?php echo $left_column_url;?>">
        <?php
          if($left_column_img) :
            echo $left_column_img;
          else :?>
          <img src="/wp-content/themes/ElectionData/ElectionData-V2/images/imagesself/group.png" alt="group" />
        <?php endif; ?>
      </a>
    </p>
  </div>

  <div class="tile">
    <h1>
      <a href="<?php echo $center_column_url;?>"><?php echo $center_column_title;?></a>
    </h1>
    <p class="description">
      <a href="<?php echo $center_column_url;?>">
        <?php echo $center_column_excerpt; ?>
      </a>
    </p>
    <p class="img">
      <a href="<?php echo $center_column_url;?>">
        <?php
          if($center_column_img) :
            echo $center_column_img;
          else :?>
          <img src="/wp-content/themes/ElectionData/ElectionData-V2/images/imagesself/location.png" alt="locatoin" />
        <?php endif; ?>
      </a>
    </p>
  </div>

  <div class="tile">
    <h1>
      <a href="<?php echo $right_column_url;?>"><?php echo $right_column_title;?></a>
    </h1>
    <p class="description">
      <a href="<?php echo $right_column_url;?>">
        <?php echo $right_column_excerpt; ?>
      </a>
     </p>
    <p class="img">
      <a href="<?php echo $right_column_url;?>">
        <?php
          if($right_column_img) :
            echo $right_column_img;
          else :?>
          <img src="/wp-content/themes/ElectionData/ElectionData-V2/images/imagesself/document.png" alt="document" />
        <?php endif; ?>
      </a>
    </p>
  </div>
</div>

<h2 class="front-constituency-header" id="mla-candidates"><?php echo Election_Data_Option::get_option( 'constituency-label', 'Constituencies' ); ?></h2>
<div class="front-info">
  <p>We list all NWT territorial election candidates, complete with contact details, web pages, and social media accounts. You'll also find news articles that mention the candidates.</p>
  <p><?php echo Election_Data_Option::get_option( 'constituency-subtext' ); ?> If you do not know your electoral division, please use <a target="_blank" href="https://www.electionsnwt.ca/en/maps">Elections NWT's district lookup tool</a> and then return to our site.</p>
</div>
<div class="front-constituency-maps">
  <?php foreach ( $constituencies as $constituency_id ) :
    $constituency = get_constituency( $constituency_id ); ?>
    <?php if ( $constituency['children'] ): ?>
    <div>
      <a href="<?php echo $constituency['url']; ?>" title="Click to see the candidates.">
        <?php echo wp_get_attachment_image($constituency['map_id'], 'map_thumb', true, array( 'alt' => $constituency['name'] ) ); ?>
      </a>
      <p><a href="<?php echo $constituency['url']; ?>"><?php echo $constituency['name']; ?></a></p>
    </div>
    <?php endif ?>
  <?php endforeach; ?>
</div>

<?php if ($party_election): ?>
    <h2 class="front-party-logos-header"><?php echo Election_Data_Option::get_option( 'party-label', 'The Political Parties' ); ?></h2>
    <div class="front-party-logos" >
      <?php foreach ( $parties as $party_id ) :
        $party = get_party( $party_id ); ?>
        <div>
          <a class="party-logo" href="<?php echo $party['url']; ?>">
            <?php echo wp_get_attachment_image($party['logo_id'], 'party', false, array( 'alt' => "{$party['name']} Logo" ) ); ?>
          </a>
          <p><a href="<?php echo $party['url']; ?>"><?php echo $party['name']; ?></a></p>
        </div>
      <?php endforeach; ?>
    </div>
    <p class="small grey no-left-margin"><?php echo Election_Data_Option::get_option( 'party-subtext' ); ?></p>
<?php endif ?>

<?php if ($front_page_seo): ?>
  <div class="front-info">
    <?= $front_page_seo ?>
  </div>
<?php endif ?>


<?php get_footer(); ?>
