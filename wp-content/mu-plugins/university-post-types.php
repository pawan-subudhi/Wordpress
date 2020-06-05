<?php 
    //to register/create a post type
    //Event Post Type
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

        //Program Post Type
        register_post_type('program', array(
            'supports' => array('title', 'editor'),
            'rewrite' => array(
                'slug' => 'programs'
            ),
            'has_archive' => true,//so it could support archive mode
            'public' => true, //this make sthe post type visible to editors and viewers over the website               
            'labels' => array(
                'name' => 'Programs',//gives name in the dasboard events like any other sections
                'add_new_item' => 'Add New Program',//when clicked onto the events it will show at the top add new eventin this scene
                'edit_item' => 'Edit Program',
                'all_items' => 'All Programs',//whenever we hiver on the event ssection it will show this 
                'singular_name' => 'Program',//name for one object of this post type
            ),
            'menu_icon' => 'dashicons-awards'
        ));

        //Professor Post Type
        register_post_type('professor', array(
            'supports' => array('title', 'editor','thumbnail'),
            'public' => true, //this make sthe post type visible to editors and viewers over the website               
            'labels' => array(
                'name' => 'Professors',//gives name in the dasboard events like any other sections
                'add_new_item' => 'Add New Professor',//when clicked onto the events it will show at the top add new eventin this scene
                'edit_item' => 'Edit Professor',
                'all_items' => 'All Professors',//whenever we hiver on the event ssection it will show this 
                'singular_name' => 'Professor',//name for one object of this post type
            ),
            'menu_icon' => 'dashicons-welcome-learn-more'
        ));

        //Campus Post Type
        register_post_type('campus', array(
            'supports' => array('title', 'editor', 'excerpt'),
            // archive URL
            'rewrite' => array(
                'slug' => 'campuses'
            ),
            'has_archive' => true,//so it could support archive mode
            'public' => true, //this make sthe post type visible to editors and viewers over the website               
            'labels' => array(
                'name' => 'Campuses',//gives name in the dasboard events like any other sections
                'add_new_item' => 'Add New Campus',//when clicked onto the events it will show at the top add new eventin this scene
                'edit_item' => 'Edit Campus',
                'all_items' => 'All Campuses',//whenever we hiver on the event ssection it will show this 
                'singular_name' => 'Campus',//name for one object of this post type
            ),
            'menu_icon' => 'dashicons-location-alt'
        ));
        
    }
    add_action('init','university_post_types');
