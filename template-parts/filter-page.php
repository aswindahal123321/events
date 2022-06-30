<?php

/** Template Name: Events Page */
get_header();
$event_type = get_query_var("event_type");
$event_month = get_query_var("event_month");

?>

<div class="uk-child-width-expand@s uk-padding-large filter-img" uk-grid>
    <div>
        <div>
            <div class="uk-container">
                <div class="movie-filter ">
                    <div class="filter-section uk-padding">
                        <form class="filter-form" action="/task/filter-events?" method="get">

                            <div class="uk-child-width-expand@s" uk-grid>
                                <div>
                                    <div>
                                        <h2 style="color: white;">Event Type</h2>
                                    </div>
                                    <div class="uk-padding-remove">
                                        <select size="1" id="" name="event_type"></h4>
                                            <option value="">All</option>
                                            <option>
                                                <?php
                                                $terms = get_terms(array(
                                                    'taxonomy' => 'event_type',
                                                    'hide_empty' => false,
                                                ));

                                                foreach ($terms as $term) {
                                                    ($term);
                                                    if ($event_type == $term->slug) :
                                                        echo '<option value="' . $term->slug . '" selected>' . $term->name . '</option>';
                                                    else :
                                                        echo '<option value="' . $term->slug . '" >' . $term->name . '</option>';
                                                    endif;
                                                }
                                                ?>
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div>
                                    <div>
                                        <h2 style="color: white;">Event Month</h2>
                                    </div>
                                    <div class="uk-padding-remove">
                                        <select size="1" id="" name="event_month"></h4>
                                            <option value="">All</option>
                                            <option>
                                                <?php
                                                $terms = get_terms(array(
                                                    'taxonomy' => 'event_month',
                                                    'hide_empty' => false,
                                                ));

                                                foreach ($terms as $term) {
                                                    ($term);
                                                    if ($event_month == $term->slug) :
                                                        echo '<option value="' . $term->slug . '" selected>' . $term->name . '</option>';
                                                    else :
                                                        echo '<option value="' . $term->slug . '" >' . $term->name . '</option>';
                                                    endif;
                                                }
                                                ?>
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div>
                                    <div class="uk-text-center reset-button" style="margin-bottom: 5px;">
                                        <a href="/task/filter-events/">Reset</a>
                                    </div>
                                    <div>
                                        <input class="search-button uk-align-center" type="submit" value="Submit">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div>
                    <ul class="uk-grid uk-margin-top">
                        <?php
                        $tax_query = array();
                        if ($event_type != "") {
                            $tax_query = array('relation' => 'AND');
                        }

                        if ($event_type != "") {
                            $tax_query[] = array(
                                'taxonomy' => 'event_type',
                                'field' => 'name',
                                'terms' => $event_type,
                            );
                        }
                        if ($event_month != "") {
                            $tax_query[] = array(
                                'taxonomy' => 'event_month',
                                'field' => 'name',
                                'terms' => $event_month,
                            );
                        }
                        $the_query = new WP_Query(array(
                            'post_type' => 'events',
                            'tax_query' => $tax_query
                        ));
                        while ($the_query->have_posts()) : $the_query->the_post(); ?>

                            <li class="bottom uk-width-1-4@m uk-width-1-2@s">
                                <a href="<?php the_permalink() ?>">
                                    <a href="<?php the_permalink() ?>">
                                        <?php the_post_thumbnail() ?>
                                    </a> </a>
                                <h2 class="uk-text-center uk-margin-top">
                                    <a style="font-size: 1.3rem;" href="<?php the_permalink() ?>"><?php the_title() ?></a>
                                    <div>
                                </h2>
                                <div class="tags" style="margin-top: 20px;">
                                    <p class="uk-text-center">
                                    </p>
                                </div>
                                <!-- <p><?php echo wp_trim_words(get_the_excerpt(), 10, ''); ?></p> -->
                                <p>


                                </p>
                            </li>
                        <?php endwhile;
                        wp_reset_postdata(); ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>