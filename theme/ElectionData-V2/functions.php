<?php
/**
* Election Data functions and definitions
*
* @package Election_Data_Theme
* @since Election_Data_Theme 1.0
*
*/

/**
* Set the content width based on the theme's design and stylesheet.
*
* @since Election_Data_Theme 1.0
*/
if ( ! isset( $content_width ) )
$content_width = 654; /* pixels */

if ( ! function_exists( 'election_data_theme_setup' ) ):
  /**
  * Sets up theme defaults and registers support for various WordPress features.
  *
  * Note that this function is hooked into the after_setup_theme hook, which runs
  * before the init hook. The init hook is too late for some features, such as indicating
  * support post thumbnails.
  *
  * @since Election_Data_Theme 1.0
  */
  function election_data_theme_setup() {

    /**
    * Make theme available for translation
    * Translations can be filed in the /languages/ directory
    */
    load_theme_textdomain( 'election_data_theme', get_template_directory() . '/languages' );

  }
endif; // election_data_theme_setup
add_action( 'after_setup_theme', 'election_data_theme_setup' );

/**
* Enqueue scripts and styles
*
* @since Election_Data_Theme 1.0
*/
function election_data_theme_scripts() {
  global $ed_taxonomies;

  wp_enqueue_script( 'shuffle', get_template_directory_uri() . '/js/shuffle.js' );
  wp_enqueue_script( 'address_lookup_js', get_template_directory_uri() . '/js/address-lookup.js', array(), '1.1.0' );

  wp_enqueue_style( 'style', get_stylesheet_uri(), array(), '5.1.3');
  wp_enqueue_style( 'animate-cnd', 'https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css');
  wp_enqueue_style( 'franklin-gfont', 'https://fonts.googleapis.com/css?family=Libre+Franklin:400,700');
  wp_enqueue_style( 'font-awesome-regular', 'https://use.fontawesome.com/releases/v5.1.1/css/all.css');

  if ( is_front_page() ) {
    wp_enqueue_script( 'countdown', get_template_directory_uri() . '/js/countdown.js' );
    // TODO: Remove if not using Twitter or Google Plus according to settings.
    wp_enqueue_script( 'twitter', 'http://platform.twitter.com/widgets.js' );
    wp_enqueue_script( 'google', 'https://apis.google.com/js/platform.js' );
  }

  if ( is_tax( $ed_taxonomies['candidate_constituency'] ) ) {
    wp_enqueue_script( 'jquery-map-highlight', get_template_directory_uri() . '/js/jquery.maphilight.min.js', array( 'jquery' ) );
    wp_enqueue_script( 'map-highlight', get_template_directory_uri() . '/js/map_highlight.js', array( 'jquery-map-highlight' ) );
  }
}
add_action( 'wp_enqueue_scripts', 'election_data_theme_scripts' );

/**
* Configures the Election Data Menu.
*
*/
function configure_menu() {
  $menu_name = 'Election Data Navigation Menu';
  $menu = wp_get_nav_menu_object( $menu_name );
  if ( $menu ) {
    $locations = get_theme_mod( 'nav_menu_locations' );
    if ( empty( $locations['header-menu'] ) ) {
      $locations['header-menu'] = $menu->term_id;
      set_theme_mod( 'nav_menu_locations', $locations );
    }
  }
}
add_action( 'after_switch_theme', 'configure_menu' );

/**
* Initalises the menus to be displayed in the header and footer, along with theme support.
* @since Election_Data_Theme 1.0
*
*/
function election_data_init() {
  register_nav_menu('header-menu', __( 'Header Menu' ) );
  // register_nav_menu('footer-menu', __( 'Footer Menu' ) );
  add_theme_support( 'custom-header' );
  add_theme_support( 'post-thumbnails' );
}
add_action( 'init', 'election_data_init' );

/**
* Displays the news articles about a candidate.
* @since Election_Data_Theme 1.0
*
* @param 	$candidate_ids			the Candidate to have news about. Default is null.
* @param 	$show_more
* @param		$count	            the amount of articles per page. Default is null.
*/
function display_news_titles ( $candidate_ids, $show_more, $count ) {
  $news = get_news( $candidate_ids, 1, $count );
  $articles = $news['articles'];
  news_titles( $articles, $show_more ? 'more' : '', $candidate_ids );
}

/**
* Outputs out a series of news article titles, which will include who published them.
* @since Election_Data_Theme 1.0
*
* @global $ed_post_types
* @global $ed_taxonomies
*
* @param    $article_query  Query containing article information.
* @param    $paging_type    The type of paging.
* @param    $candidate_ids  Relevant candidate ids. Default is null.
* @param    $paging_args    Paging arguments in an array. Default is an empty array.
*/
function news_titles( $article_query, $paging_type, $candidate_ids = null, $paging_args = array() ) {
  global $ed_post_types, $ed_taxonomies;
  $last_date = '';
  if ( $article_query->have_posts() ) :
    $date_format = get_option( 'date_format' );
    while ( $article_query->have_posts() ) :
      $article_query->the_post();
      $article_id = $article_query->post->ID;
      $date = get_the_date( $date_format, $article_id );
      if ( $date != $last_date ) :
        if ( $last_date != '' ) : ?>
      </ul>
    <?php endif;
    $last_date = $date; ?>
    <h4><?php echo $date; ?></h4>
    <ul class="news">
    <?php endif;
    $candidates = wp_get_post_terms( $article_id, $ed_taxonomies['news_article_candidate'] );
    $news_article_candidate_ids = array();
    foreach ( $candidates as $candidate ) :
      $news_article_candidate_ids[] = $candidate->term_id;
    endforeach;
    $args = array(
      'post_type' => $ed_post_types['candidate'],
      'meta_query' => array(
        array(
          'key' => 'news_article_candidate_id',
          'value' => $news_article_candidate_ids,
          'compare' => 'IN'
        ),
      ),
    );
    $mentions = array();
    $query = new WP_Query( $args );
    while ( $query->have_posts() ) :
      $query->the_post();
      $url = get_permalink( $query->post );
      $name = esc_attr( get_the_title( $query->post ) );
      $mentions[] = "<a href='$url'>$name</a>";
    endwhile;

    $sources = wp_get_post_terms( $article_id, $ed_taxonomies['news_article_source'] );
    $source = $sources[0];
    $source_label = esc_html( $source->description ? $source->description : $source->name ); ?>
    <li>
      <p class="link">
        <a href="<?php echo esc_attr( get_post_meta( $article_id, 'url', true ) ); ?>"><?php echo get_the_title( $article_id ); ?></a>
        - <span class="small grey"><?= $source_label ?></span>
      </p>
      <p class="mentions">Mentions:
        <?php echo implode (', ', $mentions); ?>
      </p>
    </li>
  <?php endwhile; ?>
</ul>
<?php if ( $paging_type == 'more' ) : ?>
  <p class="more"><a href="<?php echo get_post_type_archive_link( $ed_post_types['news_article'] ); ?>">More News...</a></p>
<?php elseif ( $paging_type ) :
  $page = get_current_page( $paging_type );
  display_news_pagination( get_paging_args( $paging_type, $page ) );
endif;
else : ?>
<em>No articles found yet.</em>
<?php endif;
}

/**
* Displays news pagination.
* @since Election_Data_Theme 1.0
*
* @param $args An array of arguments required to display paginated news.
*/
function display_news_pagination( $args ) {
  $default_args = array(
    'base' => @add_query_arg('page','%#%'),
    'mid_size' => 1,
  );
  $args = wp_parse_args( $args, $default_args );
  echo paginate_links( $args );
}

/**
* Displays new summaries for articles about a Candidate.
* @since Election_Data_Theme 1.0
*
* @param $candidate_ids      the id of the Candidate
* @param $type               the type, either Candidate or Party
* @param $articles_per_page  the number for pagination of articles
*/
function display_news_summaries ( $candidate_ids, $type, $articles_per_page ) {
  $page = get_current_page( $type );
  $args = get_paging_args( $type, $page );
  $args['add_fragment'] = "#news";

  if ( ! is_array( $candidate_ids ) ) {
    $candidate_ids = array( $candidate_ids );
  }
  global $ed_taxonomies;
  $news = get_news( $candidate_ids, $page, $articles_per_page );
  $articles = $news['articles'];
  $args['total'] = round( $news['count'] / $articles_per_page );
  if ( $articles->have_posts() ) {
    if ( $news['count'] > $articles_per_page ) {
      display_news_pagination( $args );
      echo '<br><br>';
    } ?>
    <?php while ( $articles->have_posts() ) :
      $articles->the_post();
      $article = $articles->post;
      $summaries = get_post_meta( $article->ID, 'summaries', true );
      $date_format = get_option( 'date_format' );
      foreach ( $candidate_ids as $candidate_id ) :
        if( empty( $summaries[$candidate_id] ) ){
          continue;
        }
        $summary = $summaries[$candidate_id];
        $sources = wp_get_post_terms( $article->ID, $ed_taxonomies['news_article_source'] );
        $source = $sources[0];
        $source_label = esc_html( $source->description ? $source->description : $source->name ); ?>
        <div class="news-article">
          <h3><a href="<?php echo esc_attr( get_post_meta( $article->ID, 'url', true ) ); ?>"><?php echo get_the_title( $article->ID ); ?></a></h3>
          <p class="date"><?php echo get_the_date( $date_format, $article->ID ); ?></p>
          <p class="summary" >
            <em><?php echo $source_label; ?></em>
            - <?php echo $summary; ?>
          </p>
        </div>
      <?php endforeach;
    endwhile;
    if ( $news['count'] > $articles_per_page ) {
      display_news_pagination( $args );
    }
  } else { ?>
    <em>No articles found yet.</em>
  <?php }
}

/**
*  Displays a given news article.
*  @since Election_Data_Theme 1.0
*  @param $article       An array containing the articles information.
*  @param $candidates    If the article is about a candidate or not. Default is false.
*/
function display_news_article( $article, $candidates = false ){
  $date_format = get_option( 'date_format' );
  if ( is_array( $candidates ) ) {
    $summary = $articles['summaries'][$candidate['id']];
    $mention_data = array();
    $summaries = array();
    foreach ( $candidates as $candidate ) {
      if ( isset( $articles['mentions'][$candidate['id']] ) ) {
        $mention_data[$candidate['id']] = $articles['mentions'][$candidate['id']];
      }
      if ( isset( $articles['summaries'][$candidate['id']] ) ) {
        $summaries[$candidate['id']] = $articles['summaries'][$candidate['id']];
      }
    }

    if ( $summaries ) {
      $summary = $summaries[array_rand( $summaries )];
    } else {
      $summary = $article['summary'];
    }
  } else {
    $summary = $article['summary'];
    $mention_data = $candidates ? $article['mentions'] : array();
  }

  $mentions = array();
  foreach ( $mention_data as $mention ) {
    $mentions[] = "<a href='{$mention['url']}'>{$mention['name']}</a>";
  }
  ?>
  <div class="news_article">
    <h3><a href="<?php echo $article['url']; ?>"><?php echo $article['title'] ?></a></h3>
    <p class="date"><?php echo get_the_date( $date_format, $article['id'] ); ?></p>
    <p class="summary"><em><?php echo $article['source_name']; ?></em> - <?php echo $summary ?></p>
    <?php if ( $mentions ) : ?>
      <p class="mentions">Mentions: <?php echo implode (', ', $mentions); ?></p>
    <?php endif; ?>
  </div>
<?php }

/**
* Outputs out the designated party.
* @since Election_Data_Theme 1.0
*
* @param $party    An array containing the party information.
*/
function display_party( $party ) {
  ?>
  <div class="party">
    <div class="head" style="background: linear-gradient(to bottom, <?php echo esc_attr( $party['colour'] ); ?> 48%, transparent 0);" >
      <?php echo wp_get_attachment_image($party['logo_id'], 'party', false, array( 'alt' => "{$party['name']} Logo" ) ); ?>
    </div>
    <div class="name" >
      <?php echo $party['name']; ?>
    </div>
    <div class="website <?php echo $party['website'] ? '' : 'hidden'; ?>" >
      <a href="<?php echo esc_attr( $party['website'] ); ?>">Party Website</a>
    </div>
    <div class="icons">
      <?php foreach ( $party['icon_data'] as $icon ) :
        if ( $icon['url'] ) : ?>
          <a href="<?php echo esc_attr( $icon['url'] ); ?>">
        <?php endif; ?>
        <?php if ($icon['fa_icon']): ?>
          <i title="<?= esc_attr($icon['alt']) ?>" class="<?= $icon['fa_icon'] ?>"></i>
        <?php endif ?>
        <?php if ( $icon['url'] ): ?>
          </a>
        <?php endif;
      endforeach; ?>
    </div>
    <div class="phone <?php echo $party['phone'] ? '' : 'hidden'; ?>">
      <?php echo esc_html( $party['phone'] ); ?>
    </div>
    <div class="address" <?php echo $party['address'] ? '' : 'hidden'; ?>>
      <?php echo esc_html( $party['address'] ); ?>
    </div>
    <div class="news">
      News: <a href="<?php echo esc_attr( $party['url'] ); ?>#news">The Latest <?php echo esc_html( $party['name'] ); ?> News</a>
    </div>
  </div>
<?php }

/**
* Outputs a given candidate to a page.
* @since Election_Data_Theme 1.0
*
* @param $candidate            the candiddate's id
* @param $constituency         the candidate's constituency
* @param $party                the candidate's party
* @param $show_fields          Default is empty array.
*
*
* TODO: Show fields needs fixing. Some no longer apply.
*/

function display_candidate( $candidate, $constituency, $party, $show_fields=array() ) {
  global $is_party_election;

  $display_constituency = in_array( 'constituency', $show_fields );
  $display_questionnaire = in_array( 'questionnaire', $show_fields );
  $questionnaire_available = ! empty($candidate['answers']);

  ?>
    <div class="politician card_height show_constituency <?= $display_questionnaire ? 'tall' : 'short' ?>">
    <div class="head" style="background: linear-gradient(to bottom, <?= $is_party_election ? esc_attr($party['colour']) : '#888' ?> 45%, transparent 0);" >

      <a href="<?php echo $candidate['url'] ?>">
        <?php echo wp_get_attachment_image($candidate['image_id'], 'candidate', false, array( 'alt' => $candidate['name'] ) ); ?>
      </a>

      <div class="info">
        <p><a href="<?php echo $candidate['url'] ?>"><?php echo esc_html( $candidate['name'] ); ?></a></p>

        <div class="icons">
          <?php foreach ( $candidate['icon_data'] as $icon ) :
            if ( $icon['url'] ) : ?>
              <a href="<?php echo esc_attr( $icon['url'] ); ?>">
            <?php endif; ?>
            <?php if ($icon['fa_icon']): ?>
              <i title="<?= esc_attr($icon['alt']) ?>" class="<?= $icon['fa_icon'] ?>"></i>
            <?php endif ?>
            <?php if ( $icon['url'] ): ?>
              </a>
            <?php endif;
          endforeach; ?>
        </div>

        <div class="status">
          <?php if ($display_constituency): ?>
              <a href="<?php echo $constituency['url']; ?>"><?php echo esc_html( $constituency['name'] ); ?></a>
          <?php endif ?>
          <?php if ($candidate['phone']): ?>
            Phone: <?= esc_html( $candidate['phone'] ) ?>
          <?php endif ?>
        </div>
      </div>
    </div>

    <div class="election-website minitile">
      <i class="far fa-address-card"></i>
      <?php if ($candidate['website']): ?>
        <span>
          <a href="<?php echo esc_html( $candidate['website'] ); ?>">Election Site</a>
        </span>
      <?php else: ?>
        <span class="no-site">No Election Site</span>
      <?php endif ?>
    </div>

    <div class="news minitile">
      <i class="far fa-newspaper"></i>
      <span>
        <a href="<?php echo "{$candidate['url']}#news"; ?>">
          <?php echo esc_html( $candidate['news_count'] ); ?> News Mentions
        </a>
      </span>
    </div>

      <!-- TODO: Add back for provincial election.
      <?php if ($candidate['party_leader']): ?>
        <p>Party Leader</p>
      <?php endif ?>
      -->

    <?php if ($display_questionnaire && $questionnaire_available): ?>
      <div class="qanda">
        <a href="<?= $candidate['qanda'] ?>">
          <span>Questionnaire:</span>
          Read Candidate Response
        </a>
      </div>
    <?php endif ?>

    <?php if ($candidate['incumbent_year']): ?>
      <div class="incumbent">
        Incumbent Since <?= esc_html( $candidate['incumbent_year'] ) ?>
      </div>
    <?php endif ?>
  </div>
<?php }

/**
* Display the results of a given search query.
* @since Election_Data_Theme 1.0
*
* @global $ed_post_types
* @param $search_query   a query containing the relevant search information.
*/
function display_search_results( $search_query ) {
  global $ed_post_types;
  while ( $search_query->have_posts() ) {
    $search_query->the_post();
    switch ( $search_query->post->post_type ) {
      case $ed_post_types['candidate']:
      $candidate_id = $search_query->post->ID;
      $candidate = get_candidate( $candidate_id );
      $constituency = get_constituency_from_candidate( $candidate_id );
      $party  = get_party_from_candidate( $candidate_id );
      display_candidate( $candidate, $constituency, $party, array( 'party', 'constituency', 'questionnaire' ) );
      break;
      case $ed_post_types['news_article']:
      //$news_article = get_news_article( $search_query->post );
      //display_news_article( $news_article );
      break;
    }
  }
}

/**
* Display all the candidates registered.
* @since Election_Data_Theme 1.0
*
* @param $candidate_query  the query containing all the candidates.
*/
function display_all_candidates( $candidate_query ) {
  while ( $candidate_query->have_posts() ) {
    $candidate_query->the_post();
    $candidate_id = $candidate_query->post->ID;
    $candidate = get_candidate( $candidate_id );
    $constituency = get_constituency_from_candidate( $candidate_id );
    $party  = get_party_from_candidate( $candidate_id );
    display_candidate( $candidate, $constituency, $party, array( 'party', 'constituency', 'questionnaire' ) );
  }
}

/**
* Display all the candidates in a given party.
* @since Election_Data_Theme 1.0
*
* @param $candidate_query  the query containing all the relevant candidates
* @param $party            the party that the candidates belong to
* @param $candidates       an array that will have the candidate's news article id in it
*/
function display_party_candidates( $candidate_query, $party, &$candidates ) {
  while ( $candidate_query->have_posts() ) {
    $candidate_query->the_post();
    $candidate_id = $candidate_query->post->ID;
    $candidate = get_candidate( $candidate_id );
    $constituency = get_constituency_from_candidate( $candidate_id );
    $candidates[] = $candidate['news_article_candidate_id'];
    display_candidate( $candidate, $constituency, $party, array( 'constituency', 'questionnaire'  ) );
  }
}

/**
* Display all the candidates in a constituency.
* @since Election_Data_Theme 1.0
*
* @param $candidate_query  the query containing all the relevant candidates
* @param $constituency     the constituency of the candidate
* @param $candidates       an array that will have the candidate's news article id in it
*/
function display_constituency_candidates( $candidate_query, $constituency, &$candidates ) {
  global $is_party_election;
  while ( $candidate_query->have_posts() ) {
    $candidate_query->the_post();
    $candidate_id = $candidate_query->post->ID;
    $candidate = get_candidate( $candidate_id, true );
    if ($is_party_election) {
      $party = get_party_from_candidate( $candidate_id );
    } else {
      $party = null;
    }
    $candidates[] = $candidate['news_article_candidate_id'];
    display_candidate( $candidate, $constituency, $party, array( 'party', 'questionnaire' ) );
  }
}

/**
* Displays stats about the answers from a party.
* @since Election_Data_Theme 1.0
*
*/
function display_party_answer_stats() {
  $parties = get_all_parties();
  echo "<div>Parties:</div>";
  echo "<div><table><tr><th>Party</th><th>Answered</th></tr>";
  $total = 0;
  foreach ( $parties as $party ) {
    $answer_count = count( $party['answers'] );
    if ( $answer_count > 0 ) {
      $total += 1;
      echo "<tr><td>{$party['name']}</td><td>$answer_count</td></tr>";
    }
  }
  echo "</table></div>";
  echo "<div>Number of parties that responded: $total</div>";
}

/**
* Displays stats about the answers from a candidate.
* @since Election_Data_Theme 1.0
*
*/
function display_candidate_answer_stats() {
  $candidates = get_all_candidates();
  echo "<div>Candidates:</div>";
  echo "<div><table><tr><th>Party</th><th>Candidate</th><th>Answered</th></tr>";
  $totals = array();
  $total = 0;
  foreach ( $candidates as $candidate_id => $candidate ) {
    $answer_count = count( $candidate['answers'] );
    if ( $answer_count > 0 ) {
      $party = get_party_from_candidate( $candidate_id );
      if ( array_key_exists( $party['name'], $totals ) ) {
        $totals[$party['name']] += 1;
      } else {
        $totals[$party['name']] = 1;
      }
      $total += 1;
      echo "<tr><td>{$party['name']}</td><td>{$candidate['name']}</td><td>$answer_count</td></tr>";
    }
  }
  echo "</table></div>";
  echo "<div>Responses by Party:</div><div><table><tr><th>Party</th><th>Responded</th></tr>";
  foreach ( $totals as $party => $count ) {
    echo "<tr><td>$party</td><td>$count</td></tr>";
  }
  echo "</table></div>";
  echo "<div>Number of candidates that responded: $total</div>";
}

/**
* Displays stats about news articles.
* @since Election_Data_Theme 1.0
*
*/
function display_news_article_stats() {
  $sources = get_source_count();
  echo "<div>Sources:</div>";
  echo "<div><table><tr><th>Source</th><th>Approved</th><th>New</th><th>Rejected</th></tr>";
  foreach ( $sources as $source => $counts ) {
    echo "<tr><td>$source</td><td>$counts[0]</td><td>$counts[1]</td><td>$counts[2]</td></tr>";
  }
  echo "<table></div>";
}

/**
* Add a menu for the mobile device
* Author: Heng Yu
*/
class new_walker extends Walker_Nav_Menu
{
  function start_lvl( &$output, $depth = 0, $id = 0, $args = array()) {
    if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
      $t = '';
      $n = '';
    } else {
      $t = "\t";
      $n = "\n";
    }
    $output .= $n."<input class=\"submenu\" id=\"submenu".$id."\" type=\"checkbox\"><label for=\"submenu".$id."\"></label><ul class=\"sub-menu\" id=\"subp".$id."\"><li class=\"back\"><label for=\"submenu".$id."\"></label></li>".$n;
  }

  function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
    if ( ! $element ) {
      return;
    }

    $id_field = $this->db_fields['id'];
    $id       = $element->$id_field;

    //display this element
    $this->has_children = ! empty( $children_elements[ $id ] );
    if ( isset( $args[0] ) && is_array( $args[0] ) ) {
      $args[0]['has_children'] = $this->has_children; // Back-compat.
    }

    $cb_args = array_merge( array(&$output, $element, $depth), $args);
    call_user_func_array(array($this, 'start_el'), $cb_args);

    // descend only when the depth is right and there are childrens for this element
    if ( ($max_depth == 0 || $max_depth > $depth+1 ) && isset( $children_elements[$id]) ) {

      foreach ( $children_elements[ $id ] as $child ){

        if ( !isset($newlevel) ) {
          $newlevel = true;
          //start the child delimiter
          $cb_args = array_merge( array(&$output, $depth,$id), $args);
          call_user_func_array(array($this, 'start_lvl'), $cb_args);
        }
        $this->display_element( $child, $children_elements, $max_depth, $depth + 1, $args, $output );
      }
      unset( $children_elements[ $id ] );
    }

    if ( isset($newlevel) && $newlevel ){
      //end the child delimiter
      $cb_args = array_merge( array(&$output, $depth), $args);
      call_user_func_array(array($this, 'end_lvl'), $cb_args);
    }

    //end this element
    $cb_args = array_merge( array(&$output, $element, $depth), $args);
    call_user_func_array(array($this, 'end_el'), $cb_args);
  }
}

/**
* Displays the news articles at the front page.
* @since Election_Data_Theme 1.0
*
* @param 	$candidate_ids			the Candidate to have news about. Default is null.
* @param	$count	            	the amount of articles per page. Default is null.
*/
function display_front_page_news($candidate_ids, $count){
  global $ed_post_types, $ed_taxonomies;
  $news = get_news( $candidate_ids, 1, $count );
  $articles = $news['articles'];
  $last_date = '';
  $active_theme = wp_get_theme();
  if($articles->have_posts()):
    $date_format = get_option( 'date_format' );
    while ( $articles->have_posts() ) :
      $articles->the_post();
      $article_id = $articles->post->ID;
      $date = get_the_date( $date_format, $article_id );
      $time = get_the_date(get_option('time_format'), $article_id);

      $candidates = wp_get_post_terms( $article_id, $ed_taxonomies['news_article_candidate'] );
      $news_article_candidate_ids = array();
      foreach ( $candidates as $candidate ) :
        $news_article_candidate_ids[] = $candidate->term_id;
      endforeach;
      $summary = get_post_meta($article_id, 'summaries', true);
      $args = array(
        'post_type' => $ed_post_types['candidate'],
        'meta_query' => array(
          array(
            'key' => 'news_article_candidate_id',
            'value' => $news_article_candidate_ids,
            'compare' => 'IN'
          ),
        ),
      );
      $mentions = array();
      $all_candidates = array();
      $query = new WP_Query( $args );
      while ( $query->have_posts() ) :
        $query->the_post();
        $url = get_permalink( $query->post );
        $name = esc_attr( get_the_title( $query->post ) );
        $mentions[] = "<a href='$url'>$name</a>";
        $all_candidates[] = $name;
      endwhile;
      $sources = wp_get_post_terms( $article_id, $ed_taxonomies['news_article_source'] );
      $source = $sources[0];
      $source_label = esc_html( $source->description ? $source->description : $source->name );
      ?>
        <div class="one_column fancy-news">
          <div class="news-title-time">
            <a class="news-title" href="<?php echo esc_attr( get_post_meta( $article_id, 'url', true ) ); ?>"><?php echo get_the_title( $article_id ); ?></a>
            <span class="news-date-time"><?= $source_label ?> - <?php echo $date;?></span>
          </div>

           <?php
           if($active_theme == 'Election Data - V2'):

               $summary_candidate = get_term_by('name', $all_candidates[rand(0, (count($all_candidates)-1))], $ed_taxonomies['news_article_candidate'], "ARRAY_A");
           ?>
           <div class="news-summary">
               <p><a href="<?php echo esc_attr( get_post_meta( $article_id, 'url', true ) ); ?>"><?=$summary[$summary_candidate['term_id']] ?></a></p>
              </div>
           <?php endif;?>

          <p class="news-mention">Mentions: <?php echo implode (', ', $mentions); ?></p>
        </div>
    <?php endwhile; ?>
<?php else : ?>
  <em>No articles found yet.</em>
<?php endif;
}
