<?php
/*
Template name: Portfolio
*/

get_header();
?>

    <div id="portfolio">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

        <h2><?php the_title(); ?></h2>

        <?php the_content('<p class="serif">Read the rest of this page &raquo;</p>'); ?>

    <?php endwhile; endif; ?>
    </div><!-- home -->

    <div id="latest_works">

    <?php $subpages = wpascms_get_subpages('portfolio', 0);
    if(count($subpages) > 0):
        foreach($subpages as $row=>$subpage):
    ?>
        <div class="work">
            <h2><a href="<?php echo get_permalink($subpage->ID); ?>"><?php echo $subpage->post_title; ?></a></h2>
                <?php echo $subpage->post_content; ?>
        </div>
    <?php
        endforeach;
    endif;
    ?>
    </div><!-- latest_works -->

<?php
get_footer();
?>
