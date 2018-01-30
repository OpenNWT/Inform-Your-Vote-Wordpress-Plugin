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
        ManitobaElection.ca 2016 - Built with <span class="love">♥</span> by Open Democracy Manitoba - All Content is Public Domain.
        <?php do_action( 'election_data_theme_credits' ); ?>
    </div><!-- .site-info -->
</footer><!-- #colophon .site-footer -->
</div><!-- #page .hfeed .site -->
 
<?php wp_footer(); ?>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-23791334-1', 'auto');
  ga('send', 'pageview');

</script>
</body>
</html>
