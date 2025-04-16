<?php 
    get_header(); 
    while(have_posts()){
        the_post();
        pageBanner();
        ?>
        <div class="container container--narrow page-section">
            <div class="metabox metabox--position-up metabox--with-home-link">                    
                    <p>
                        <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('campus'); ?>"><i class="fa fa-home" aria-hidden="true"></i> All Campuses</a>
                    </p>
                </div>
            <div class="generic-content">
                <?php the_content();?>
            </div>
            <?php

$relatedPrograms = new WP_Query(array(
    'posts_per_page' => -1,
    'post_type' => 'program',
    'order' => 'ASC',
    'orderby' => 'title',
    'meta_query' => array(
      array(
        'key' => 'related_campus',
        'compare' => 'LIKE',
        'value' => '"' . get_the_ID() . '"'
      )
     )
  ));
    if($relatedPrograms->have_posts()){
    ?>
        <hr class="section-break">
        <h2 class="headline headline--medium">Programs at the <?php the_title();?> Campus</h2>
        <?php
        echo '<ul class="min-list link-list">';
        while($relatedPrograms->have_posts()){
        $relatedPrograms->the_post(); 
        ?>
        <li class="">
            <a href="<?php the_permalink();?>"><?php the_title(); ?></a>
        </li>
       
        <?php
        
        }
        echo '</ul>';
    }
            wp_reset_postdata(); // Reset the post data to the main query
            ?>
        </div>
    <?php
    }
    get_footer();
 ?>