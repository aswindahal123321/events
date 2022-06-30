<?php
get_header() ?>
<div class="uk-padding-large">
    <?php
    if (have_posts()) : while (have_posts()) : the_post();
            the_post_thumbnail(); ?>
            <h1><?php the_title();  ?></h1>
    <?php
            the_content(); // displays whatever you wrote in the wordpress editor
        endwhile;
    endif; //ends the loop
    ?>
</div>
<?php get_footer() ?>