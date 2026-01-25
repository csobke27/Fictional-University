<?php

function generateProfessorHTML($id){
    $profPost = new WP_Query(array(
        'post_type' => 'professor',
        'p' => $id
    ));
    while($profPost->have_posts()) {
        $profPost->the_post();
        ob_start();?>
        <div class="professor-callout">
            <div class="professor-callout__photo" style="background-image: url('<?php echo get_the_post_thumbnail_url(get_the_ID(), 'professorPortrait'); ?>')"></div>
            <div class="professor-callout__text">
                <h5 class="professor-name"><?php echo esc_html(get_the_title()); ?></h5>
                <p class="professor-bio"><?php echo wp_trim_words(get_the_content(), 30); ?></p>

                <?php
                $relatedPrograms = get_field('related_programs');
                if($relatedPrograms) { ?>
                    <p><?php echo esc_html(get_the_title()); ?> teaches <?php foreach($relatedPrograms as $key => $program) {
                        echo esc_html(get_the_title($program));
                        if($key + 1 < count($relatedPrograms)) {
                            echo ", ";
                        }
                    } ?>.</p>
                <?php }?>
                <p class="professor-subjects"></p>
                <a class="professor-callout__link" href="<?php echo esc_url(get_permalink()); ?>">Learn more about <?php echo esc_html(get_the_title()); ?></a>
            </div>
        </div><?php
        $html = ob_get_clean();
    }
    wp_reset_postdata();
    return $html;
}