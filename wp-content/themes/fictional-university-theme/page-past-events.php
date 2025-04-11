<?php
    get_header();
    pageBanner(array(
        'title' => 'Past Events',
        'subtitle' => 'A recap of our past events.'
    ));
?>
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
                    $eventDate = new DateTime(get_field('event_date'));
                    get_template_part('template-parts/event'); // Include the event template part
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