<?php

function relatedPostsHTML($id){
    $profPost = new WP_Query(array(
        'post_type' => 'post',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => 'featuredprofessor',
                'compare' => '=',
                'value' => $id
            )
        )
    ));

    ob_start();
    if($profPost->have_posts()) {
        ?>
        <div class="related-posts" style="margin-top:24px;">
            <p><?php echo the_title();?> is mentioned in the following posts:</p>
            <ul>
        <?php
        while($profPost->have_posts()) {
            $profPost->the_post(); ?>
            <li><a href="<?php echo esc_url(get_permalink()); ?>"><?php echo esc_html(get_the_title()); ?></a></li>
            <?php
        } ?>
            </ul>
        </div>
        <?php
    }
    wp_reset_postdata();
    return ob_get_clean();
}