
<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package Election_Data_Theme
 * @since Election_Data_Theme 2.0
 */
?><!DOCTYPE html>
<!--[if IE 8]>
<html id="ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 8) ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->

<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<meta property="og:image" content="http://www.manitobaelection.ca/wp-content/themes/ElectionData/images/mb_leg_og.png" />
<meta property="og:title" content="<?= wp_title( '|', true, 'right' ); ?>2016 Manitoba Election<?php if (is_front_page()): ?> - Inform Your Vote <?php endif ?>" />
<meta property="og:description" content="News and candidate information for the Manitoba Election on April 19th." />
<meta name="description" content="News and candidate information for the Manitoba Election this April 19th." />
<title><?php
/*
* Print the <title> tag based on what is being viewed.
*/
global $page, $paged;
 
wp_title( '|', true, 'right' );
 
// Add the blog name.
bloginfo( 'name' );
 
// Add the blog description for the home/front page.
$site_description = get_bloginfo( 'description', 'display' );
if ( $site_description && ( is_home() || is_front_page() ) )
echo " | $site_description";
 
// Add a page number if necessary:
if ( $paged >= 2 || $page >= 2 )
echo ' | ' . sprintf( __( 'Page %s', 'election_data_theme' ), max( $paged, $page ) );
 
?></title>
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->
<?php wp_head(); ?>
</head>
 
<body <?php body_class(); ?>>

	<div id="container">
		<header id="masthead" class="site-header" role="banner">
			<?php if ( get_header_image() ) : ?>
				<img src="<?php header_image(); ?>" height="<?php echo get_custom_header()->height; ?>" width="<?php echo get_custom_header()->width; ?>" alt="" class="header_image" />
			<?php endif; ?>
				<h1 class="site-title"><a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
				<h2 class="site-description">A Citizen Created Election Resource</h2>
		</header><!-- #masthead .site-header -->
		<?php wp_nav_menu( array( 'theme_location' => 'header-menu', 'container_class' => 'menu hidden_block_when_mobile', 'menu_class' => '' ) ); ?>
        <?php if (!is_front_page()): ?>
        <p class="visible_block_when_mobile"><br><a href="<?php echo home_url( '/' ); ?>">↩ Return Home</a></p>
        <?php endif ?>
		<div id="main" role="main">
