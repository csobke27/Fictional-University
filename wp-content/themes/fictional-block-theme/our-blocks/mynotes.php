<?php
// check if user is logged in
if(!is_user_logged_in()){
    wp_redirect(esc_url(site_url('/')));
    exit;
}

pageBanner();?>
<div class="container container--narrow page-section">
    <div class="create-note">
        <h2 class="headline headline--medium">Create New Note</h2>
        <input placeholder="Title" id="new-note-title" class="new-note-title">
        <textarea placeholder="Your note here..." id="new-note-body" class="new-note-body"></textarea>
        <span class="submit-note btn btn--blue btn--large">Add Note</span>
        <span class="note-limit-message">Note limit reached. Please delete existing notes to add new ones.</span>
    </div>
    <ul class="min-list link-list" id="my-notes">
        <?php
            $userNotes = new WP_Query(array(
                'post_type' => 'note',
                'posts_per_page' => -1,
                'author' => get_current_user_id(),
                // 'orderby' => 'date',
                // 'order' => 'ASC'
            ));
            while($userNotes->have_posts()){
                $userNotes->the_post(); ?>
                <li data-id="<?php echo get_the_ID(); ?>" data-state="read">
                    <input readonly value="<?php echo str_replace('Private: ', '', esc_attr(get_the_title())); ?>" class="note-title-field">
                    <span class="edit-note"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</span>
                    <span class="delete-note"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</span>
                    <textarea readonly class="note-body-field"><?php echo esc_textarea(wp_strip_all_tags(get_the_content())); ?></textarea>
                    <span class="update-note btn btn--blue btn--small"><i class="fa fa-arrow-right" aria-hidden="true"></i> Save</span>
                </li>
            <?php }
            wp_reset_postdata();
        ?>
    </ul>
</div>