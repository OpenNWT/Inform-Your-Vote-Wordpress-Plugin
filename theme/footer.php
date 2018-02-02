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
    <div class="site-info">
          <?php echo Election_Data_Option::get_option('footer');?>
        <?php do_action( 'election_data_theme_credits' ); ?>
    </div><!-- .site-info -->
</footer><!-- #colophon .site-footer -->
</div><!-- #page .hfeed .site -->

<?php wp_footer(); ?>
<script>
<?php  echo Election_Data_Option::get_option('google-analytics'); ?>

</script>
</body>
</html>
