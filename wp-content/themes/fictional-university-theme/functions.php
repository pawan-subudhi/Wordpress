<?php

require get_theme_file_path('/inc/search-route.php');

//to add a custom field to the raw json data that wp sends back to us while rest api we call for live search i.e add author name to the posts data we fetch
function university_custom_rest(){
  //takes 3 args. 1st is the post type we wan to cutomize, 2nd is the name to field u want to add, 3rd is array how we want to manage this field
  //here the return value of get_callback is assigned to the authorName
  register_rest_field('post', 'authorName', array(
    'get_callback' => function(){return get_the_author();}  
  ));
}
//takes 2 args. 1st is wp event we want to hook onto and 2nd is the func we want to call during this event hook
add_action('rest_api_init','university_custom_rest');

function pageBanner($args = NULL) {
  //php logic
  if (!$args['title']) {
    $args['title'] = get_the_title();
  }

  if (!$args['subtitle']) {
    $args['subtitle'] = get_field('page_banner_subtitle');
  }

  if (!$args['photo']) {
    if (get_field('page_banner_background_image')) {
      $args['photo'] = get_field('page_banner_background_image')['sizes']['pageBanner'];
    } else {
      $args['photo'] = get_theme_file_uri('/images/ocean.jpg');
    }
  }

  ?>
  <div class="page-banner">
    <div class="page-banner__bg-image" style="background-image: url(<?php echo $args['photo']; ?>);"></div>
    <div class="page-banner__content container container--narrow">
      <h1 class="page-banner__title"><?php echo $args['title'] ?></h1>
      <div class="page-banner__intro">
        <p><?php echo $args['subtitle']; ?></p>
      </div>
    </div>  
  </div>
<?php }

function university_files() {
  // alias name, file path, this script/file is dependednt on any other script, version number can give any or to avoid caching use a little trick of microtime which gives uniques value evrytime loaded, do u want to load this file right before the closing body tag
  wp_enqueue_script('main-university-js', get_theme_file_uri('/js/scripts-bundled.js'), NULL, microtime(), true);
  wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
  wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');

  wp_enqueue_style('university_main_styles', get_stylesheet_uri(), NULL, microtime());

  //take 3 args for 1st include name/handle js file which u want to make flexible
  //2nd arg is make u a variable name
  //3rd is array of data we want to make it available in js file
  wp_localize_script('main-university-js','universityData',array(
    'root_url' => get_site_url()
  ));
}

add_action('wp_enqueue_scripts', 'university_files');

function university_features() {
  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');
  add_image_size('professorLandscape', 400, 260, true);//nickname, width, height, do u want to crop the image or not
  //if set to true the cropping will be done towards the center
  //if want more control provide array with direction 1st parameter either left,right and center , 2nd param will be top, center, bottom
  
  add_image_size('professorPortrait', 480, 650, true);
  add_image_size('pageBanner', 1500, 350, true);
}

add_action('after_setup_theme', 'university_features');

function university_adjust_queries($query) {
  if (!is_admin() AND is_post_type_archive('campus') AND $query->is_main_query()) {
    $query->set('posts_per_page', -1);
  }

  if (!is_admin() AND is_post_type_archive('program') AND $query->is_main_query()) {
    $query->set('orderby', 'title');
    $query->set('order', 'ASC');
    $query->set('posts_per_page', -1);
  }

  if (!is_admin() AND is_post_type_archive('event') AND $query->is_main_query()) {
    $today = date('Ymd');
    $query->set('meta_key', 'event_date');
    $query->set('orderby', 'meta_value_num');
    $query->set('order', 'ASC');
    $query->set('meta_query', array(
              array(
                'key' => 'event_date',
                'compare' => '>=',
                'value' => $today,
                'type' => 'numeric'
              )
            ));
  }
}

add_action('pre_get_posts', 'university_adjust_queries');

//Reedirect Subuscriber account out of admin and onto homepage
add_action('admin_init','redirectSubsToFrontend');

function redirectSubsToFrontend(){
  $ourCurrentUser = wp_get_current_user();
  if(count($ourCurrentUser->roles) == 1 AND $ourCurrentUser->roles[0] == 'subscriber'){
    wp_redirect(site_url('/'));
    exit;
  }
}

add_action('wp_loaded','noSubsAdminBar');

function noSubsAdminBar(){
  $ourCurrentUser = wp_get_current_user();
  if(count($ourCurrentUser->roles) == 1 AND $ourCurrentUser->roles[0] == 'subscriber'){
    show_admin_bar(false);
  }
}

//Customizing login screen
add_filter('login_headerurl', 'ourHeaderUrl');

function ourHeaderUrl(){
  return esc_url(site_url('/'));
}

add_action('login_enqueue_scripts', 'ourLoginCSS');

function ourLoginCSS(){
  wp_enqueue_style('university_main_styles', get_stylesheet_uri(), NULL, microtime());
  wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
}

add_filter('login_headertitle', 'ourLoginTitle');

function ourLoginTitle(){
  return get_bloginfo('name');
}