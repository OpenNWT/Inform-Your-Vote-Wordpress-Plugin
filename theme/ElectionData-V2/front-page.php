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

$constituencies = get_root_constituencies();

get_header(); ?>

<?php if (!$party_election): ?>
    <h2 id="candidates" class="no-party-front-page-header">Your Election Candidates</h2>
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
    <br>
    <br>
    <br>
    <br>
<?php endif ?>

<!--Three columns part -->
<div class="page-list">
	<ul>
		<li>
			<div class="page-article">

				<h1 class="ptitle">

					<a href="<?php echo $left_column_url;?>"><?php echo $left_column_title;?></a>
				</h1>
				<p class="excerpt">
          <a href="<?php echo $left_column_url;?>">
            <?php echo $left_column_excerpt; ?>
          </a>
				</p>
				<p class="pimg">
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
		</li>
		<li>
			<div class="page-article">
				<h1 class="ptitle">
					<a href="<?php echo $center_column_url;?>"><?php echo $center_column_title;?></a>
				</h1>
				<p class="excerpt">
          <a href="<?php echo $center_column_url;?>">
            <?php echo $center_column_excerpt; ?>
          </a>
				</p>
				<p class="pimg">
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
		</li>
		<li>
			<div class="page-article">
				<h1 class="ptitle">
					<a href="<?php echo $right_column_url;?>"><?php echo $right_column_title;?></a>
				</h1>
				<p class="excerpt">
					<a href="<?php echo $right_column_url;?>">
            <?php echo $right_column_excerpt; ?>
          </a>
				 </p>
				<p class="pimg">
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
		</li>
	</ul>
</div>

<?php if ($party_election): ?>
    <h2 class="front-constituency-header" id="mla-candidates"><?php echo Election_Data_Option::get_option( 'constituency-label', 'Constituencies' ); ?></h2>
    <!-- <p class="small grey no-left-margin"><?php echo Election_Data_Option::get_option( 'constituency-subtext' ); ?></p> -->
    <p class="small grey no-left-margin">If you do not know your electoral division, please use <a target="_blank" href="https://www.electionsmanitoba.ca/en/Voting/WhatsMyElectoralDivision">Elections Manitoba's address lookup tool</a> and then return to our site.</p>
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
