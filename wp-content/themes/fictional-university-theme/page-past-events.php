<?php
    get_header();
?>
    <div class="page-banner">
        <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('images/ocean.jpg')?>)"></div>
        <div class="page-banner__content container container--narrow">
            <h1 class="page-banner__title">Past Events</h1>
            <div class="page-banner__intro">
            <p>Recap of our past events.</p>
            </div>
        </div>
    </div>

    <div class="container container--narrow page-section">
            <?php

                $pastEvents = new WP_Query(array(
                    'paged' => get_query_var('paged', 1),
                    // 'posts_per_page' => 2,
                    'post_type' => 'event',
                    'orderby' => 'meta_value',
                    'meta_key' => 'event_date',
                    'order' => 'ASC',
                    'meta_query' => array(
                    array( // meta query to only show events that are in the future
                        'key' => 'event_date',
                        'compare' => '<',
                        'value' => date('Ymd'), // current date in Ymd format
                        'type' => 'numeric'
                    )
                    )
                ));
                while($pastEvents->have_posts()){
                    $pastEvents->the_post(); 
                    $eventDate = new DateTime(get_field('event_date')); ?>
                    <div class="event-summary">
                        <a class="event-summary__date t-center" href="<?php the_permalink(); ?>">
                        <span class="event-summary__month"><?php echo $eventDate->format('M'); ?></span>
                        <span class="event-summary__day"><?php echo $eventDate->format('d'); ?></span>
                        </a>
                        <div class="event-summary__content">
                        <h5 class="event-summary__title headline headline--tiny"><a href="<?php the_permalink();?>"><?php the_title();?></a></h5>
                        <p><?php echo wp_trim_words(get_the_content(), 18); ?> <a href="<?php the_permalink();?>" class="nu gray">Learn more</a></p>
                        </div>
                    </div>
                <?php
                }
                echo paginate_links(array(
                    'total' => $pastEvents->max_num_pages,
                    'current' => max(1, get_query_var('paged')),
                    'format' => '?paged=%#%',
                    'prev_text' => __('&laquo; Previous'),
                    'next_text' => __('Next &raquo;')
                ));
            ?>
    </div>

<?php
    get_footer();
?>