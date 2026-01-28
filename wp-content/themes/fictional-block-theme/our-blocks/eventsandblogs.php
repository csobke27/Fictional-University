<div class="full-width-split group">
    <div class="full-width-split__one">
    <div class="full-width-split__inner">
        <h2 class="headline headline--small-plus t-center">Upcoming Events</h2>
        <?php
        $homepageEvents = new WP_Query(array(
            'posts_per_page' => 2,
            'post_type' => 'event',
            'orderby' => 'meta_value',
            'meta_key' => 'event_date',
            'order' => 'ASC',
            'meta_query' => array(
            array( // meta query to only show events that are in the future
                'key' => 'event_date',
                'compare' => '>=',
                'value' => date('Ymd'), // current date in Ymd format
                'type' => 'numeric'
            )
            )
        ));

        while($homepageEvents->have_posts()){
            $homepageEvents->the_post(); 
            get_template_part('template-parts/content-event'); // Include the event template part
        }
        ?>
        <p class="t-center no-margin"><a href="<?php echo get_post_type_archive_link('event'); ?>" class="btn btn--blue">View All Events</a></p>
        <?php wp_reset_postdata(); // Reset the post data to the main query ?>
    </div>
    </div>
    <div class="full-width-split__two">
    <div class="full-width-split__inner">
        <h2 class="headline headline--small-plus t-center">From Our Blogs</h2>
        <?php 
        $homepagePosts = new WP_Query(array(
            'posts_per_page' => 2
        ));
        while($homepagePosts->have_posts()){
            $homepagePosts->the_post();
            ?>
            <div class="event-summary">
            <a class="event-summary__date event-summary__date--beige t-center" href="<?php the_permalink(); ?>">
                <span class="event-summary__month"><?php the_time('M'); ?></span>
                <span class="event-summary__day"><?php the_time('d'); ?></span>
            </a>
            <div class="event-summary__content">
                <h5 class="event-summary__title headline headline--tiny"><a href="<?php the_permalink();?>"><?php the_title();?></a></h5>
                <p>
                <?php 
                    if(has_excerpt()){
                    echo get_the_excerpt();
                    } else {
                    echo wp_trim_words(get_the_content(), 18);
                    }
                ?> 
                <a href="<?php the_permalink();?>" class="nu gray">Read more</a></p>
            </div>
            </div>
            <!-- <li><?php the_title(); ?></li> -->
            <?php
        }
        wp_reset_postdata(); // Reset the post data to the main query
        ?>
        <p class="t-center no-margin"><a href="<?php echo esc_url(site_url('/blog')); ?>" class="btn btn--yellow">View All Blog Posts</a></p>
    </div>
    </div>
</div>