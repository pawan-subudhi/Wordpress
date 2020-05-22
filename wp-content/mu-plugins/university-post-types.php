<?php 
    //to register/create a post type
    function university_post_types(){
        register_post_type('event', array(
            'supports' => array('title', 'editor', 'excerpt'),
            'rewrite' => array(
                'slug' => 'events'
            ),
            'has_archive' => true,//so it could support archive mode
            'public' => true, //this make sthe post type visible to editors and viewers over the website               
            'labels' => array(
                'name' => 'Events',//gives name in the dasboard events like any other sections
                'add_new_item' => 'Add New Event',//when clicked onto the events it will show at the top add new eventin this scene
                'edit_item' => 'Edit Event',
                'all_items' => 'All Events',//whenever we hiver on the event ssection it will show this 
                'singular_name' => 'Event',//name for one object of this post type
            ),
            'menu_icon' => 'dashicons-calendar'
        ));
    }
    add_action('init','university_post_types');
