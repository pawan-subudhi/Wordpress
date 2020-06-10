<?php 

add_action('rest_api_init', 'universityRegisterSearch');

function universityRegisterSearch(){
    //takes 3 args. 1st is the namespace we want to use, 2nd is route, 3rd is array which defines what shoulf happen when someone visits this url
    register_rest_route('university/v1', 'search', array(
        'methods' => WP_REST_SERVER::READABLE,//this constant replaces by 'GET' 
        'callback' => 'universitySearchResults'//the value which this func returns will be the JSON data
    ));
}

function universitySearchResults($data){
    $mainQuery = new WP_Query(array(
        'post_type' => array('post','page','professor','program','campus','event'),
        's' => sanitize_text_field($data['term'])//s stands for search in wp_query of laravel
    ));
    $results = array(
        'generalInfo' => array(),//will have blog posts and pages
        'professors' => array(),
        'programs' => array(),
        'events' => array(),
        'campuses' => array(),
    );

    while($mainQuery->have_posts()){
        $mainQuery->the_post();//this statement gets all of the relevant data for the query post ready and accessible
        if(get_post_type() == 'post' OR get_post_type() == 'page'){
            array_push($results['generalInfo'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'postType' => get_post_type(),
                'authorName' => get_the_author(),
            ));
        }
        if(get_post_type() == 'professor'){
            array_push($results['professors'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'image' => get_the_post_thumbnail_url(0, 'professorLandscape'),
            ));
        }
        if(get_post_type() == 'program'){
            array_push($results['programs'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
            ));
        } 
        if(get_post_type() == 'campus'){
            array_push($results['campuses'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
            ));
        } 
        if(get_post_type() == 'event'){
            $eventDate = new DateTime(get_field('event_date', false, false));
            $description = null;
            if (has_excerpt()) {
                $description =  get_the_excerpt();
            } else {
                $description = wp_trim_words(get_the_content(), 18);
            }
            array_push($results['events'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'month' => $eventDate->format('M'),
                'day' => $eventDate->format('d'),
                'description' => $description,
            ));
        } 
    }  
    
    $programRelationshipQuery = new WP_Query(array(
        'post_type' => 'professor',
        'meta_query' => array(
            array(
                'key' => 'related_programs',//name of advanced sustom fields that we want to look within
                'compare' => 'LIKE',
                'value' => '"90"'
            )
        )//meta_query enables us to search based on custom fields
    ));
    
    while($programRelationshipQuery->have_posts){
        $programRelationshipQuery->the_post();

        if(get_post_type() == 'professor'){
            array_push($results['professors'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'image' => get_the_post_thumbnail_url(0, 'professorLandscape'),
            ));
        }
    }
    return $results;
}

