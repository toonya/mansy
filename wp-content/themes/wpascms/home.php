<?php
/*
Template name: Home
*/

get_header();
?>

    <div id="home">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

        <h2><?php the_title(); ?></h2>

        <?php the_content('<p class="serif">Read the rest of this page &raquo;</p>'); ?>

    <?php endwhile; endif; ?>
    </div><!-- home -->

    <div id="latest_works">
    <h1>Recent work</h1>
    <?php $subpages = wpascms_get_subpages();
    if(count($subpages) > 0):
        foreach($subpages as $row=>$subpage):
        if($row%2 == 0)
        {
            $class = "left_work";
        }
        else
        {
            $class = "right_work";
        }
    ?>
        <div class="<?php echo $class; ?>">
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
