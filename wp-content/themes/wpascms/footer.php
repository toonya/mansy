<?php
/**
 * @package WordPress
 * @subpackage Wpascms_Theme
 */
?>
    <div id="footer">
        <p>
            <?php bloginfo('name'); ?> is proudly powered by
            <a href="http://wordpress.org/">WordPress</a>
            <br />
            <a href="<?php bloginfo('rss2_url'); ?>">Entries (RSS)</a> and <a href="<?php bloginfo('comments_rss2_url'); ?>">Comments (RSS)</a>.
        </p>
    </div>

</div><!-- container -->

<?php wp_footer(); ?>

</body>
</html>
