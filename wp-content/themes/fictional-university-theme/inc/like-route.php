<?php 
add_action('rest_api_init', 'universityLikeRoutes');//used whenever we want to add new custom route or new field to the route

function universityLikeRoutes(){
    //register 2 new routes
    //this is for post method i.e this is responsible for POST https type request
    register_rest_route('university/v1', 'manageLike', array(
        'methods' => 'POST',
        'callback' => 'createLike'
    ));

    //this is for delete method i.e this is responsible for DELETE https type request
    register_rest_route('university/v1', 'manageLike', array(
        'methods' => 'DELETE',
        'callback' => 'deleteLike' 
    ));
}

function createLike($data){
    if(is_user_logged_in()){
        $professor = sanitize_text_field( $data['professorID'] );

        $existQuery = new WP_Query(array(
            'author' => get_current_user_id(),
            'post_type' => 'like',
            'meta_query' => array(
                array(
                    'key' => 'liked_professor_id',//name of custom field
                    'compare' => '=',
                    'value' => $professor
                )
            ),
        ));
        
        if($existQuery->found_posts == 0 AND get_post_type($professor) == 'professor'){
            //wp_insert_post returns a id of newly created post id if it successfull
            return wp_insert_post(array(
                'post_type' => 'like',
                'post_status' => 'publish',
                'post_title' => 'Our PHP Create Post Test',
                'meta_input' => array(
                    'liked_professor_id' => $professor
                ),
            ));//will let us programatically create a new post right from our php code
        } else {
            die("Invalid professor id");
        }
    } else {
        die("Only logged in users can create a like.");
    }
}

function deleteLike($data){
    $likeId = sanitize_text_field( $data['like'] );
    if(get_current_user_id() == get_post_field('post_author', $likeId) AND get_post_type($likeId) == 'like') {
        wp_delete_post($likeId, true);//takes 2 args .. 1st is the id and 2nd is wether we want to move it to the trash or skip the trash and completely delete. if true then we are trying to skip the trash
        return"Congrats, like deleted.";
    } else {
        die("You do not have the permission to delete that.");
    }
}