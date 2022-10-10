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

<div class="front-info">
  <h2>It's Election Time in Winnipeg</h2>
  <p>The <a href="https://web2.gov.mb.ca/laws/statutes/2002/c03902e.php#19" target="_blank">City of Winnipeg Charter</a> states that the City of Winnipeg must hold an election on the “fourth Wednesday of October 2002, and in each fourth year thereafter.” As such, the next general election in Winnipeg is to be held <b>Wednesday, October 26th, 2022</b>.</p>
  <p>As a voter, you will have the opportunity to vote for at least three candidates: one candidate for mayor, one candidate for councillor, and one or more candidates for school trustee.</p>
  <p>This website is a voter resource built by <a href="https://www.winnipegelection.ca/about-winnipegelection-ca/#odm">Open Democracy Manitoba</a>, a non-profit organization that builds digital tools for democratic engagement. We are not affiliated with the City of Winnipeg.</p>
  <p>The <a href="https://www.winnipeg.ca/clerks/election/election-2022/" target="_blank">Official City of Winnipeg 2022 Municipal Council and School Boards Election website</a> contains important information for candidates, voters, and elections workers.</p>
  <p>Our site features an address lookup tool, interactive maps, election news, a calendar of election events, and educational resources about voting and government. Mayoral, City Council and School Board candidates are listed along with links to their web pages, contact details, and social media accounts. News articles that mention candidates are listed along with candidate responses to our election questionnaire.</p>
  <p>This is the fourth edition of WinnipegElection.ca. We helped inform your vote in the 2010, 2014, and 2018 Winnipeg Elections. We describe the methodologies and technologies used to run this site on our <a href="/frequently-asked-questions/">Frequently Asked Questions Page</a>.</p>
</div>

<?php if ($party_election): ?>
    <h2 class="front-constituency-header" id="mla-candidates"><?php echo Election_Data_Option::get_option( 'constituency-label', 'Constituencies' ); ?></h2>
    <!-- <p class="small grey no-left-margin"><?php echo Election_Data_Option::get_option( 'constituency-subtext' ); ?></p> -->
    <p class="small grey no-left-margin">If you do not know your <?php echo strtolower($electoral_division_term) ?>, please use <a target="_blank" href="https://www.electionsmanitoba.ca/en/Voting/WhatsMyElectoralDivision">Elections Manitoba's address lookup tool</a> and then return to our site.</p>
    <div class="front-constituency-maps">
      <?php foreach ( $constituencies as $constituency_id ) :
        $constituency = get_constituency( $constituency_id ); ?>
        <div>
          <a href="<?php echo $constituency['url']; ?>" title="Click to see the candidates.">
            <?php echo wp_get_attachment_image($constituency['map_id'], 'map_thumb', true, array( 'alt' => $constituency['name'] ) ); ?>
          </a>
          <p><a href="<?php echo $constituency['url']; ?>"><?php echo $constituency['name']; ?></a></p>
        </div>
      <?php endforeach; ?>
    </div>

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

<?php get_footer(); ?>
