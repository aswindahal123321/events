<?php
get_header()?>
<div class="uk-child-width-expand@s uk-padding-large" uk-grid>
   
<div class="uk-width-3-4@m">
        <div>
        <?php 
if ( have_posts() ) : while ( have_posts() ) : the_post(); 
the_post_thumbnail(); ?>
<h1><?php the_title();  ?></h1>
<?php    
  the_content(); // displays whatever you wrote in the wordpress editor
  endwhile; endif; //ends the loop
 ?>

        </div>
    </div>
    <div class="uk-width-1-4@m">
       <?php get_template_part( 'template-parts/sidebar' ) ?>
    </div>
</div>
<?php get_footer(); ?> 
