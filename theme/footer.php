<?php
/**
* The template for displaying the footer.
*
* Contains the closing of the id=main div and all content after
*
* @package Election_Data_Theme
* @since Election_Data_Theme 1.0
*/
?>

</div><!-- #main .site-main -->

<footer id="colophon" class="site-footer" role="contentinfo">

    <div id = "footer_left"><?php echo Election_Data_Option::get_option('footer-left');?></div>
    <div id = "footer_center"><?php echo Election_Data_Option::get_option('footer-center');?></div>
    <div id = "footer_right"><?php echo Election_Data_Option::get_option('footer-right');?></div>
    <div class="site-info">
          <?php echo Election_Data_Option::get_option('footer');?>
        <?php do_action( 'election_data_theme_credits' ); ?>
    </div><!-- .site-info -->
</footer><!-- #colophon .site-footer -->
</div><!-- #page .hfeed .site -->

<?php wp_footer(); ?>
<script>
<?php //this is the google analytics script
  echo Election_Data_Option::get_option('google-analytics'); ?>
</script>
</body>
</html>
