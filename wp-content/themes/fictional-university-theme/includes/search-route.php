<?php

add_action('rest_api_init', 'universityRegisterSearch');

function universityRegisterSearch() {
    register_rest_route('university/v1', 'search', array(
        'methods' => WP_REST_SERVER::READABLE,
        'callback' => 'universitySearchResults'
    ));
}

function universitySearchResults() {
    $mainQuery = new WP_Query(array(
        'post_type' => array('post', 'page', 'program', 'professor', 'event', 'campus'),
        's' => sanitize_text_field($_GET['term'])
    ));

    $results = array(
        'generalInfo' => array(),
        'programs' => array(),
        'professors' => array(),
        'events' => array(),
        'campuses' => array()
    );

    while($mainQuery->have_posts()) {
        $mainQuery->the_post();

        switch(get_post_type()) {
            case 'post':
            case 'page':
                $results['generalInfo'][] = array(
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink(),
                    'type' => get_post_type(),
                    'authorName' => get_the_author()
                );
                break;
            case 'program':
                $relatedCampuses = get_field('related_campus');
                if($relatedCampuses) {
                    foreach($relatedCampuses as $campus) {
                        $results['campuses'][] = array(
                            'title' => get_the_title($campus),
                            'permalink' => get_the_permalink($campus)
                        );
                    }
                }
                $results['programs'][] = array(
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink(),
                    'id' => get_the_ID()
                );
                break;
            case 'event':
                $eventDate = new DateTime(get_field('event_date'));
                $results['events'][] = array(
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink(),
                    'month' => $eventDate->format('M'),
                    'day' => $eventDate->format('d'),
                    'description' => has_excerpt() ? get_the_excerpt() : wp_trim_words(get_the_content(), 18)
                );
                break;
            case 'campus':
                $results['campuses'][] = array(
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink()
                );
                break;
            case 'professor':
                $results['professors'][] = array(
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink(),
                    'landscapeImage' => get_the_post_thumbnail_url(get_the_ID(), 'professorLandscape')
                );
                break;
        }
    }

    if(!empty($results['programs'])) {
        $programRelationshipMetaQuery = array('relation' => 'OR');

        foreach($results['programs'] as $program) {
            $programRelationshipMetaQuery[] = array(
                'key' => 'related_programs',
                'compare' => 'LIKE',
                'value' => '"'. $program['id'] .'"'
            );
        }

        $programRelationshipQuery = new WP_Query(array(
            'post_type' => array('professor', 'event'),
            'meta_query' => $programRelationshipMetaQuery
            )
        );

        while($programRelationshipQuery->have_posts()) {
            $programRelationshipQuery->the_post();
            switch(get_post_type()) {
                case 'professor':
                    $results['professors'][] = array(
                        'title' => get_the_title(),
                        'permalink' => get_the_permalink(),
                        'landscapeImage' => get_the_post_thumbnail_url(get_the_ID(), 'professorLandscape')
                    );
                    break;
                case 'event':
                    $eventDate = new DateTime(get_field('event_date'));
                    $results['events'][] = array(
                        'title' => get_the_title(),
                        'permalink' => get_the_permalink(),
                        'month' => $eventDate->format('M'),
                        'day' => $eventDate->format('d'),
                        'description' => has_excerpt() ? get_the_excerpt() : wp_trim_words(get_the_content(), 18)
                    );
                    break;
            }
        }

        // remove duplicate entries
        foreach($results as $resultType => $data) {
            $results[$resultType] = array_values(array_unique($results[$resultType], SORT_REGULAR));
        }
        
        // $results['professors'] = array_values(array_unique($results['professors'], SORT_REGULAR));
        // $results['events'] = array_values(array_unique($results['events'], SORT_REGULAR));
    }

    return $results;
}