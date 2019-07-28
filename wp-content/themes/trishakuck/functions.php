<?php 
/**
 * District functions and definitions.
 *
 * Sets up the theme and provides some helper functions, which are used
 * in the theme. Others are attached to action and filter hooks in WordPress 
 * to change core functionality.
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * For more information on hooks, actions, and filters, see http://codex.wordpress.org/Plugin_API.
 *
 * @package WordPress
 * @subpackage District 
 * @since 1.0
 */

/**
 * Options Framework
 * @since  1.0
 */
if ( ! function_exists( 'optionsframework_init' ) ) :
	// Set the file path based on whether the Options Framework Theme is a parent theme or child theme
	if ( get_stylesheet_directory() == get_template_directory() ) {
		define('OPTIONS_FRAMEWORK_URL', get_template_directory() . '/admin/');
		define('OPTIONS_FRAMEWORK_DIRECTORY', get_template_directory_uri() . '/admin/');
	} else {
		define('OPTIONS_FRAMEWORK_URL', get_template_directory() . '/admin/');
		define('OPTIONS_FRAMEWORK_DIRECTORY', get_template_directory_uri() . '/admin/');
	}
	require_once (OPTIONS_FRAMEWORK_URL . 'options-framework.php');
endif;

/**
 * Add Visual Editor Style
 * @since 1.0
 */
add_editor_style();

/**
 * Include Updater script
 * @since 1.0
 */
include("functions/theme-updater.php");

/**
 * Get Username and API Key from Theme Options
 * @since 1.3
 */
if ( ! function_exists( 'themewich_get_update_info' ) ) :
  function themewich_get_update_info() {
    global $tw_options;

    $username = of_get_option('of_tf_username');
	$api = of_get_option('of_tf_api');

	if ($username && $username != '') {
	    define('THEMEFOREST_USERNAME',$username);
	}
	if ($api && $api != '') {
	    define('THEMEFOREST_APIKEY', $api);
	}
  }
  add_action('init', 'themewich_get_update_info'); 
endif;

/**
 * Admin Post Scripts only post.php pages
 * @since  1.3
 */
if (!function_exists('tw_load_post_scripts')) :
	function tw_load_post_scripts($hook) {
	    if ( ! ( 'post.php' == $hook || 'post-new.php' == $hook ) ) {
	        return;
	    }
	    wp_enqueue_script( 'tw-post-js', get_template_directory_uri() . '/functions/js/post-javascript.js', 'jquery' );
	    wp_enqueue_style('color-picker', OPTIONS_FRAMEWORK_DIRECTORY . 'css/colorpicker.css');
	}
	add_action( 'admin_enqueue_scripts', 'tw_load_post_scripts' );
endif;

/**
 * Add additional stylesheets to admin
 * @since  1.3
 */
if (!function_exists('ag_admin_css')) :
	function ag_admin_css() {
		$getposttype = '';
		if (isset($_GET['post_type'])) $getposttype = $_GET['post_type'];
		global $post_type; 
		
		if ($getposttype == 'section' || $post_type == 'section' || $getposttype == 'slide' || $post_type == 'slide') :	
			wp_enqueue_style('section-slide', get_template_directory_uri() . '/functions/css/section-slide.css');	
		endif;
	}
	// Add Css for Seciton of Slide
	add_action( 'admin_enqueue_scripts', 'ag_admin_css' );
endif;

/**
 * Add Embed Code to Footer
 */
if (!function_exists('ag_embed_code')) :
	function ag_embed_code() { 
		echo of_get_option('of_google_analytics'); 
	}
	add_action( 'wp_footer', 'ag_embed_code', 1000 );
endif;

/**
 * Add Contextual Help
 */
if (!function_exists('ag_contextual_help')) :
	function ag_contextual_help( $contextual_help, $screen_id, $screen ) {
	   // echo 'Screen ID = '.$screen_id.'<br />';
	    $screen = get_current_screen();
	    switch( $screen_id ) {
			
			/* #Section Help Options
			================================================== */		
	        case 'section' :
			
			$section_readme = wp_remote_get( get_template_directory_uri() . '/functions/help/section-general-readme.html' );

			if(!is_wp_error( $section_readme )) {
				$screen->add_help_tab( array(
					'id'      => 'ag_general_help_'.$screen_id,
					'title'   => __( 'What Are Sections', 'framework'),
					// Tab content
		   			'content' => $section_readme['body'],
				));
			}
			
			$section_readme = wp_remote_get( get_template_directory_uri() . '/functions/help/section-slides-readme.html' );

			if(!is_wp_error( $section_readme )) {
				$screen->add_help_tab( array(
					'id'      => 'ag_slides_help_'.$screen_id,
					'title'   => __( 'Adding Slides', 'framework' ),
					// Tab content
					'content' => $section_readme['body'],
				));
			}

			$section_readme = wp_remote_get( get_template_directory_uri() . '/functions/help/section-video-readme.html' );

			if(!is_wp_error( $section_readme )) {
				$screen->add_help_tab( array(
					'id'      => 'ag_video_help_'.$screen_id,
					'title'   => __( 'Adding Video', 'framework' ),
					// Tab content
					'content' => $section_readme['body'],
				));
			}

			$section_readme = wp_remote_get( get_template_directory_uri() . '/functions/help/section-layout-readme.html' );

			if(!is_wp_error( $section_readme )) {
			    $screen->add_help_tab( array(
					'id'      => 'ag_layout_help_'.$screen_id,
					'title'   => __( 'Customizing Layout', 'framework' ),
					// Tab content
					'content' => $section_readme['body'],
				));
			}

			$section_readme = wp_remote_get( get_template_directory_uri() . '/functions/help/section-colors-readme.html' );	

			if(!is_wp_error( $section_readme )) {	
				$screen->add_help_tab( array(
					'id'      => 'ag_colors_help_'.$screen_id,
					'title'   => __( 'Customizing Colors', 'framework' ),
					// Tab content
					'content' => $section_readme['body'],
				));
			}

			$section_readme = wp_remote_get( get_template_directory_uri() . '/functions/help/section-button-readme.html' );

			if(!is_wp_error( $section_readme )) {
				$screen->add_help_tab( array(
					'id'      => 'ag_button_help_'.$screen_id,
					'title'   => __( 'Customizing The Button', 'framework' ),
					// Tab content
					'content' => $section_readme['body'],
				));
			}
			
	        break;
			
			/* #Portfolio Help Options
			================================================== */
	        case 'portfolio' :
			
			$section_readme = wp_remote_get( get_template_directory_uri() . '/functions/help/portfolio-general-readme.html' );

			if(!is_wp_error( $section_readme )) {		
		        $screen->add_help_tab( array(
					'id'      => 'ag_general_help_'.$screen_id,
					'title'   => __( 'What is a Portfolio', 'framework' ),
					// Tab content
					'content' => $section_readme['body'],
				));
	    	}
			
			$section_readme = wp_remote_get( get_template_directory_uri() . '/functions/help/portfolio-slides-readme.html' );

			if(!is_wp_error( $section_readme )) {		
				$screen->add_help_tab( array(
					'id'      => 'ag_slides_help_'.$screen_id,
					'title'   => __( 'Portfolio Slides', 'framework' ),
					// Tab content
					'content' => $section_readme['body'],
				));
			}
			
			$section_readme = wp_remote_get( get_template_directory_uri() . '/functions/help/portfolio-options-readme.html' );

			if(!is_wp_error( $section_readme )) {		
				$screen->add_help_tab( array(
					'id'      => 'ag_options_help_'.$screen_id,
					'title'   => __( 'Portfolio Options', 'framework' ),
					// Tab content
					'content' => $section_readme['body'],
				));
			}

			$section_readme = wp_remote_get( get_template_directory_uri() . '/functions/help/page-sections-readme.html' );	

			if(!is_wp_error( $section_readme )) {	
				$screen->add_help_tab( array(
					'id'      => 'ag_sections_help_'.$screen_id,
					'title'   => __( 'Adding Sections', 'framework' ),
					// Tab content
					'content' => $section_readme['body'],
				));
			}
			
	        break;
			
			/* #Slide Help Options
			================================================== */
			case 'slide' :
			
			$section_readme = wp_remote_get( get_template_directory_uri() . '/functions/help/slide-general-readme.html' );

			if(!is_wp_error( $section_readme )) {		
		        $screen->add_help_tab( array(
					'id'      => 'ag_general_help_'.$screen_id,
					'title'   => __( 'What is a Slide', 'framework' ),
					// Tab content
					'content' => $section_readme['body'],
				));
		    }
			
			$section_readme = wp_remote_get( get_template_directory_uri() . '/functions/help/slide-options-readme.html' );	

			if(!is_wp_error( $section_readme )) {	
				$screen->add_help_tab( array(
					'id'      => 'ag_options_help_'.$screen_id,
					'title'   => __( 'Slide Display', 'framework' ),
					// Tab content
					'content' => $section_readme['body'],
				));
			}
			
			$section_readme = wp_remote_get( get_template_directory_uri() . '/functions/help/slide-colors-readme.html' );	

			if(!is_wp_error( $section_readme )) {	
				$screen->add_help_tab( array(
					'id'      => 'ag_colors_help_'.$screen_id,
					'title'   => __( 'Slide Colors', 'framework' ),
					// Tab content
					'content' => $section_readme['body'],
				));
			}
			
			$section_readme = wp_remote_get( get_template_directory_uri() . '/functions/help/slide-button-readme.html' );

			if(!is_wp_error( $section_readme )) {		
				$screen->add_help_tab( array(
					'id'      => 'ag_button_help_'.$screen_id,
					'title'   => __( 'Customizing The Button', 'framework' ),
					// Tab content
					'content' => $section_readme['body'],
				));
			}

	        break;
			
			/* #Page Help Options
			================================================== */		
			case 'page' :
			
			$section_readme = wp_remote_get( get_template_directory_uri() . '/functions/help/page-image-readme.html' );	

			if(!is_wp_error( $section_readme )) {	
		        $screen->add_help_tab( array(
					'id'      => 'ag_image_help_'.$screen_id,
					'title'   => __( 'Featured Image', 'framework' ),
					// Tab content
					'content' => $section_readme['body'],
				));
		    }

			
			$section_readme = wp_remote_get( get_template_directory_uri() . '/functions/help/page-sections-readme.html' );

			if(!is_wp_error( $section_readme )) {		
				$screen->add_help_tab( array(
					'id'      => 'ag_sections_help_'.$screen_id,
					'title'   => __( 'Adding Sections', 'framework' ),
					// Tab content
					'content' => $section_readme['body'],
				));
			}
			
			$section_readme = wp_remote_get( get_template_directory_uri() . '/functions/help/page-button-readme.html' );

			if(!is_wp_error( $section_readme )) {		
				$screen->add_help_tab( array(
					'id'      => 'ag_button_help_'.$screen_id,
					'title'   => __( 'Customizing The Button', 'framework' ),
					// Tab content
					'content' => $section_readme['body'],
				));
			}
			
			$section_readme = wp_remote_get( get_template_directory_uri() . '/functions/help/page-options-readme.html' );

			if(!is_wp_error( $section_readme )) {		
				$screen->add_help_tab( array(
					'id'      => 'ag_options_help_'.$screen_id,
					'title'   => __( 'Page Options', 'framework' ),
					// Tab content
					'content' => $section_readme['body'],
				));
			}
			
	        break;
	    }
	   // return $contextual_help;
	}
	add_filter('contextual_help', 'ag_contextual_help', 10, 3);
endif;

/**
 * Add Theme Shortcodes
 */
include("functions/shortcodes.php");

/**
 * Add Multiple Thumbnail Support
 */
include("functions/multi-post-thumbnails.php");

if (class_exists('MultiPostThumbnails')) { 

   if ( $thumbnum = of_get_option('of_thumbnail_number') ) { $thumbnum = ($thumbnum + 1); } else { $thumbnum = 7;}
   $counter1 = 2;

	while ($counter1 < ($thumbnum)) {
	
	// Add Slides in Posts	
	new MultiPostThumbnails( 
		array( 
			'label' => 'Slide ' . $counter1, 
			'id' => $counter1 . '-slide', 
			'post_type' => 'post' 
		));
	
	// Add Slides in Sections	
	new MultiPostThumbnails( 
		array( 
			'label' => 'Slide ' . $counter1, 
			'id' => $counter1 . '-slide', 
			'post_type' => 'section' 
		));	
	
	// Add Slides in Portfolio Items
	new MultiPostThumbnails( 
		array( 
			'label' => 'Slide ' . $counter1, 
			'id' => $counter1 . '-slide', 
			'post_type' => 'portfolio' 
		));	
	
	$counter1++;
	
	}
}

/**
 * Add Widget Shortcode Support
 */
add_filter('widget_text', 'shortcode_unautop');
add_filter('widget_text', 'do_shortcode');

/**
 * Add the Custom Fields for Sections and Pages
 */
include("functions/customfields.php");

/**
 * Include Drag and Drop Slide Order Functionality
 */
include('functions/drag-drop-order.php');

/**
 * Register and Load JS
 */
if (!function_exists('tw_register_js')) :
	function tw_register_js() {
		if (!is_admin()) {

			// Get theme version info
			$themewich_theme_info = wp_get_theme();
			
			if ( ! wp_script_is('jquery') ) wp_enqueue_script('jquery'); // Load jquery if not already loaded
			wp_register_script('modernizer', get_template_directory_uri() . '/js/modernizer.min.js', 'jquery', '2.6.2', false);
			wp_register_script('validation', get_template_directory_uri() . '/js/jquery.validate.min.js', 'jquery', '1.13.1', false);
			wp_register_script('easing', get_template_directory_uri() . '/js/jquery.easing.1.3.js', 'jquery', '1.3', false);
			wp_register_script('fitvids', get_template_directory_uri() . '/js/jquery.fitvids.js', 'jquery', '1.0', true);
			wp_register_script('revolution-tools', get_template_directory_uri() . '/js/jquery.themepunch.tools.min.js', 'jquery', '1.0', true);
			wp_register_script('revolution', get_template_directory_uri() . '/js/jquery.themepunch.revolution.min.js', 'jquery', '4.6.4', true);
			wp_register_script('isotope', get_template_directory_uri() . '/js/jquery.isotope.min.js', 'jquery', '2.0.0', true);
			wp_register_script('infinite-scroll', get_template_directory_uri() . '/js/jquery.infinitescroll.min.js', 'jquery', '2.0b2.120519', true);
			wp_register_script('imagesloaded', get_template_directory_uri() . '/js/imagesloaded.pkgd.min.js', 'jquery', '3.1.8', true);
			wp_register_script('bxslider', get_template_directory_uri() . '/js/jquery.bxslider.min.js', 'jquery', '4.1.2', true);
			wp_register_script('prettyphoto', get_template_directory_uri() . '/js/jquery.prettyPhoto.js', 'jquery', '3.1.5', true);
			wp_register_script('superfish', get_template_directory_uri() . '/js/superfish.min.js', 'jquery', '1.7.4', false);
			wp_register_script('custom', get_template_directory_uri() . '/js/custom.js', 'jquery', $themewich_theme_info->Version, true);
			wp_register_script('trishakuckjs', get_template_directory_uri() . '/js/trishakuck.js', 'jquery');

			// Localize the ajax script
			$variables_array = array( 
				'ajaxurl' => admin_url( 'admin-ajax.php' ), 
				'get_template_directory_uri' => get_template_directory_uri(),
				'nonce' => wp_create_nonce('ajax-nonce') 
			);
			wp_localize_script('custom', 'twAjax', $variables_array);

			// Enqueue javascript and custom js file
			wp_enqueue_script('modernizer');
			wp_enqueue_script('validation');
			wp_enqueue_script('superfish');
			wp_enqueue_script('prettyphoto');
			wp_enqueue_script('easing');
			wp_enqueue_script('fitvids');
			wp_enqueue_script('revolution-tools');
			wp_enqueue_script('revolution');
			wp_enqueue_script('isotope');
			wp_enqueue_script('infinite-scroll');
			wp_enqueue_script('imagesloaded');
			wp_enqueue_script('bxslider');
			wp_enqueue_script('jquery');
			wp_enqueue_script('custom');
			wp_enqueue_script('trishakuckjs');
		}
	}
	add_action('wp_enqueue_scripts', 'tw_register_js');
endif;

/**
 * Custom Styles
 */
require_once( dirname( __FILE__ ) . '/functions/custom-styles.php' );

/**
 * Stylesheets
 */
if ( !function_exists( 'tw_register_theme_styles' ) ) :
	function tw_register_theme_styles() {
		if (!is_admin()) {

			global $wp_styles;

			$ag_theme = wp_get_theme();

			// set defaults
			$options_css = $customizer_css = $custom_css = '';

			// get styles from options panel (fonts, etc.)
			$options_css = themewich_custom_styles();
			// get wordpress customizer css
			$customizer_css = tw_customize_css();
			// get custom css box css
			$custom_css = of_get_option('of_custom_css');

			// enqueue stylesheets
			wp_enqueue_style( 'style', get_stylesheet_uri(), false, $ag_theme->Version );
			wp_enqueue_style(  "ie7",  get_template_directory_uri() . "/css/ie7.css", false, 'ie7', "all");
			wp_enqueue_style(  "ie8",  get_template_directory_uri() . "/css/ie8.css", false, 'ie8', "all");
			wp_enqueue_style( "trishakuckcss", get_template_directory_uri() . "/css/trishakuck.css", false);

			$wp_styles->add_data( "ie7", 'conditional', 'IE 7' );
			$wp_styles->add_data( "ie8", 'conditional', 'IE 8' );

			// Add inline styles after main stylesheet
			wp_add_inline_style( 'style', $options_css);
			wp_add_inline_style( 'style', $customizer_css );
			wp_add_inline_style( 'style', $custom_css );
		}
	}
	add_action('wp_enqueue_scripts', 'tw_register_theme_styles');
endif;

/**
 * Register Navigation
 */
add_theme_support('menus');
if ( function_exists( 'register_nav_menus' ) ) {
    register_nav_menus(
        array(
          'main_nav_menu' => 'Main Navigation Menu'
        )
    );
    // remove menu container div
    function my_wp_nav_menu_args( $args = '' ) {
        $args['container'] = false;
        return $args;
    } 
    add_filter( 'wp_nav_menu_args', 'my_wp_nav_menu_args' );
}

/**
 * Automatic Feed Links
 */
if(function_exists('add_theme_support')) {
    add_theme_support('automatic-feed-links'); //WP Auto Feed Links
}

/**
 * Configure Excerpt String, Remove Automatic Periods
 */
if (!function_exists('ag_excerpt_more')) :
	function ag_excerpt_more($excerpt) {
		return str_replace('[...]', '...', $excerpt); 
	}
	add_filter('wp_trim_excerpt', 'ag_excerpt_more');
endif;

/**
 * Browser detection body class
 * @since  1.3
 */
if ( ! function_exists('tw_browser_body_class') ) :
    function tw_browser_body_class($classes) {
    	global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;
            if($is_lynx) $classes[] = 'lynx';
            elseif($is_gecko) $classes[] = 'gecko';
            elseif($is_opera) $classes[] = 'opera';
            elseif($is_NS4) $classes[] = 'ns4';
            elseif($is_safari) $classes[] = 'safari';
            elseif($is_chrome) $classes[] = 'chrome';
            elseif($is_IE) {
                    $classes[] = 'ie';
                    if(preg_match('/MSIE ([0-9]+)([a-zA-Z0-9.]+)/', $_SERVER['HTTP_USER_AGENT'], $browser_version))
                    $classes[] = 'ie'.$browser_version[1];
            } else $classes[] = 'unknown';
            if($is_iphone) $classes[] = 'iphone';
            if ( stristr( $_SERVER['HTTP_USER_AGENT'],"mac") ) {
                     $classes[] = 'osx';
               } elseif ( stristr( $_SERVER['HTTP_USER_AGENT'],"linux") ) {
                     $classes[] = 'linux';
               } elseif ( stristr( $_SERVER['HTTP_USER_AGENT'],"windows") ) {
                     $classes[] = 'windows';
               }

            $classes[] = wp_get_theme(); // add theme name 
            $classes[] = wp_is_mobile() ? 'mobile-device' : 'desktop-device'; // add mobile device class

            return $classes;
    }
    add_filter('body_class','tw_browser_body_class');
endif;

/**
 * Favicon function
 * @since  1.3
 */
if ( ! function_exists('tw_fav_icon') ) :
	function tw_fav_icon() {
		if ( $favicon = of_get_option('of_custom_favicon') ) {
			echo '<link rel="shortcut icon" href="'. $favicon.'"/>';
		}
	}
	add_action('wp_head', 'tw_fav_icon');
endif;

/**
 * Configure Thumbnails
 * @since 1.3
 */
if ( ! function_exists('tw_register_thumbnails') ) :
	function tw_register_thumbnails() {
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 56, 56, true ); // Normal post thumbnails tinyfeatured
		add_image_size( 'tinyfeatured', 56, 56, true ); // Tiny Square thumbnail
		add_image_size( 'sectionsmall', 514, '', true ); // Small Section thumbnail
		add_image_size( 'sectionlarge', 820, '', true ); // Large Section thumbnail
		add_image_size( 'homeslideshow', 1500, 600, true); // Homepage Slideshow
		add_image_size( 'homeslideshowfixed', 940, 545, true); // Homepage Slideshow
		add_image_size( 'homefeatured', 350, '', false); // Homepage Featured Image
		add_image_size( 'postsidebar', 420, 260, true); // Post Image Cropped
		add_image_size( 'post', 640, 375, true); // Post Image Cropped
		add_image_size( 'postnc', 640, '', false);  // Post Image No Crop
		add_image_size( 'postfull', 940, 475, true); // Post Full Cropped
		add_image_size( 'postfullnc', 940, '', false);	// Post Full No Crop
		add_image_size( 'portfolio-three', 426, 351, true);	// Portfolio Three Column
		add_image_size( 'portfolio-three-nc', 426, '', false);	// Portfolio Three Column No Crop
		add_image_size( 'portfolio-single', 640, 425, true); // Single Portfolio 
		add_image_size( 'portfolio-single-nc', 640, '', false); // Single Portfolio No Crop
	}
	add_action( 'after_setup_theme', 'tw_register_thumbnails' ); 
endif;

/**
 * Add PrettyPhoto to WordPress Galleries
 */
if (!function_exists('ag_prettyadd')) :
	function ag_prettyadd($content) {
		$content = preg_replace("/<a/","<a rel='prettyPhoto[slides]'",$content,1);
		return $content;
	}
	add_filter( 'wp_get_attachment_link', 'ag_prettyadd');
endif;

/**
 * Comment Reply Javascript Action
 */
if (!function_exists('ag_enqueue_comment_reply')) :
	function ag_enqueue_comment_reply() {
	    // on single blog post pages with comments open and threaded comments
	    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) { 
	        // enqueue the javascript that performs in-link comment reply fanciness
	        wp_enqueue_script( 'comment-reply' ); 
	    }
	}
	add_action( 'wp_enqueue_scripts', 'ag_enqueue_comment_reply' );
endif;

/**
 * Add Widgets
 */
// Add the News Custom Widget
include("functions/widgets/widget-news.php");
// Add the Contact Custom Widget
include("functions/widgets/widget-contact.php");
// Add the Social Counter Tabs Widget
include("functions/widgets/widget-tab.php");
// Add the Recent Projects Widget
include("functions/widgets/widget-recent-projects.php");

/**
 * Register Widget Sidebars
 */
if ( function_exists('register_sidebar') ) {
 register_sidebar(array(
  'name' => 'Blog Sidebar',
  'before_widget' => '<div id="%1$s" class="widget %2$s">',
  'after_widget' => '</div><div class="clear"></div>',
  'before_title' => '<h4 class="widget-title">',
  'after_title' => '</h4>',
 ));
 register_sidebar(array(
  'name' => 'Single Post Sidebar',
  'before_widget' => '<div id="%1$s" class="widget %2$s">',
  'after_widget' => '</div><div class="clear"></div>',
  'before_title' => '<h4 class="widget-title">',
  'after_title' => '</h4>',
 ));
 register_sidebar(array(
  'name' => 'Page Sidebar',
  'before_widget' => '<div id="%1$s" class="widget %2$s">',
  'after_widget' => '</div><div class="clear"></div>',
  'before_title' => '<h4 class="widget-title">',
  'after_title' => '</h4>',
 ));
 register_sidebar(array(
  'name' => 'Contact Sidebar',
  'before_widget' => '<div id="%1$s" class="widget %2$s">',
  'after_widget' => '</div><div class="clear"></div>',
  'before_title' => '<h4 class="widget-title">',
  'after_title' => '</h4>',
 ));
 register_sidebar(array( 
  'name' => 'Footer Left',
  'before_widget' => '<div id="%1$s" class="widget %2$s">',
  'after_widget' => '</div><div class="clear"></div>',
  'before_title' => '<h3 class="widget-title">',
  'after_title' => '</h3>',
 ));
 register_sidebar(array( 
  'name' => 'Footer Left Center',
  'before_widget' => '<div id="%1$s" class="widget %2$s">',
  'after_widget' => '</div><div class="clear"></div>',
  'before_title' => '<h3 class="widget-title">',
  'after_title' => '</h3>',
 ));
 register_sidebar(array( 
  'name' => 'Footer Right Center',
  'before_widget' => '<div id="%1$s" class="widget %2$s">',
  'after_widget' => '</div><div class="clear"></div>',
  'before_title' => '<h3 class="widget-title">',
  'after_title' => '</h3>',
 ));
 register_sidebar(array( 
  'name' => 'Footer Right',
  'before_widget' => '<div id="%1$s" class="widget %2$s">',
  'after_widget' => '</div><div class="clear"></div>',
  'before_title' => '<h3 class="widget-title">',
  'after_title' => '</h3>',
 ));
}

/**
 * Comments Template
 */
if (!function_exists('ag_comment')) :
	function ag_comment($comment, $args, $depth) {

	    $isByAuthor = false;

	    if($comment->comment_author_email == get_the_author_meta('email')) {
	        $isByAuthor = true;
	    }

	    $GLOBALS['comment'] = $comment; ?>
	   <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
	   <div id="comment-<?php comment_ID(); ?>" class="singlecomment">
	        <p class="commentsmetadata">
	        	<cite><?php comment_date('F j, Y'); ?></cite>
	        </p>
	    	<div class="author">
	            <div class="reply"><?php echo comment_reply_link(array('depth' => $depth, 'max_depth' => $args['max_depth'])); ?></div>
	            <div class="name"><?php comment_author_link() ?></div>
	        </div>
	      <?php if ($comment->comment_approved == '0') : ?>
	         <p class="moderation"><?php _e('Your comment is awaiting moderation.', 'framework') ?></p>
	      <?php endif; ?>
	        <div class="commenttext">
	            <?php comment_text() ?>
	        </div>
		</div>
	<?php
	}
endif;

/**
 * Load Text Domain
 */
if (!function_exists('theme_init')) :
	function theme_init(){
	    load_theme_textdomain('framework', get_template_directory() . '/lang');
	}
	add_action ('init', 'theme_init');
endif;

/**
 * Add deconstructed URI as <body> classes in Admin
 */
if (!function_exists('add_to_admin_body_class')) :
	function add_to_admin_body_class($classes) {
		// get the global post variable
		global $post;
		// instantiate, should be overwritten
		$mode = '';
		// get the current page's URI (the part /after/ your domain name)
		$uri = $_SERVER["REQUEST_URI"];
		// get the post type from WP
		$post_type = get_post_type($post->ID);
		// set the $mode variable to reflect the editorial /list/ page...
		if (strstr($uri,'edit.php')) {
			$mode = 'edit-list-';
		}
		// or the actual editor page
		if (strstr($uri,'post.php')) {
			$mode = 'edit-page-';
		}
		// append our new mode/post_type class to any existing classes
		$classes .= $mode . $post_type;
		// and send them back to WP
		return $classes;
	}
	// add this filter to the admin_body_class hook
	add_filter('admin_body_class', 'add_to_admin_body_class');
endif;

/**
 * Create Section Post Type
 */
if (!function_exists('create_section_post_types')) :
	function create_section_post_types() {
		register_post_type( 'section',
			array(
				  'labels' => array(
				  'name' => __( 'Section', 'framework'),
				  'singular_name' => __( 'Section', 'framework'),
				  'add_new' => __( 'Add New', 'framework' ),
			   	  'add_new_item' => __( 'Add Section', 'framework'),
				  'edit' => __( 'Edit', 'framework' ),
		  		  'edit_item' => __( 'Edit Section', 'framework'),
		          'new_item' => __( 'New Section', 'framework'),
				  'view' => __( 'View Section', 'framework'),
				  'view_item' => __( 'View Section', 'framework'),
				  'search_items' => __( 'Search Sections', 'framework'),
		  		  'not_found' => __( 'No Sections found', 'framework'),
		  		  'not_found_in_trash' => __( 'No Section Items found in Trash', 'framework'),
				  'parent' => __( 'Parent Section', 'framework'),
				),
				'menu_icon' => 'dashicons-editor-insertmore',
				'public' => true,
				'exclude_from_search' => true, // we don't want sections to show up in search
				'rewrite' => array( 'slug' => 'section'), //  Change this to change the url of your "portfolio".
				'supports' => array( 
					'title', 
					'editor',  
					'thumbnail',
					'revisions'),
			)
		);
	}
	add_action( 'init', 'create_section_post_types' );
endif;

/**
 * Create Slide Post Type
 */
if (!function_exists('create_slide_post_types')) :
	function create_slide_post_types() {
		register_post_type( 'slide',
			array(
				  'labels' => array(
				  'name' => __( 'Slide', 'framework'),
				  'singular_name' => __( 'Slide', 'framework'),
				  'add_new' => __( 'Add New', 'framework' ),
			   	  'add_new_item' => __( 'Add Slide', 'framework'),
				  'edit' => __( 'Edit', 'framework' ),
		  		  'edit_item' => __( 'Edit Slide', 'framework'),
		          'new_item' => __( 'New Slide', 'framework'),
				  'view' => __( 'View Slide', 'framework'),
				  'view_item' => __( 'View Slide', 'framework'),
				  'search_items' => __( 'Search Slides', 'framework'),
		  		  'not_found' => __( 'No Slides found', 'framework'),
		  		  'not_found_in_trash' => __( 'No Slide Items found in Trash', 'framework'),
				  'parent' => __( 'Parent Slide', 'framework'),
				),
				'menu_icon' => 'dashicons-format-video',
				'public' => true,
				'exclude_from_search' => true, // we don't want Slides to show up in search
				'rewrite' => array( 'slug' => 'slide'), //  Change this to change the url of your "portfolio".
				'supports' => array( 
					'title',   
					'thumbnail',
					'editor', // Need this for qtranslate, hiding with custom css
					'revisions'),
			)
		);
	}
	add_action( 'init', 'create_slide_post_types' );
endif;

/**
 * Add Custom Portfolio Post Type
 */
if (!function_exists('create_portfolio_post_types')) :
	function create_portfolio_post_types() {
		register_post_type( 'portfolio',
			array(
				  'labels' => array(
				  'name' => __( 'Portfolio', 'framework'),
				  'singular_name' => __( 'Portfolio Item', 'framework'),
				  'add_new' => __( 'Add New', 'framework' ),
			   	  'add_new_item' => __( 'Add New Portfolio Item', 'framework'),
				  'edit' => __( 'Edit', 'framework' ),
		  		  'edit_item' => __( 'Edit Portfolio Item', 'framework'),
		          'new_item' => __( 'New Portfolio Item', 'framework'),
				  'view' => __( 'View Portfolio', 'framework'),
				  'view_item' => __( 'View Portfolio Item', 'framework'),
				  'search_items' => __( 'Search Portfolio Items', 'framework'),
		  		  'not_found' => __( 'No Portfolios found', 'framework'),
		  		  'not_found_in_trash' => __( 'No Portfolio Items found in Trash', 'framework'),
				  'parent' => __( 'Parent Portfolio', 'framework'),
				),
				'menu_icon'=> 'dashicons-portfolio',
				'public' => true,
				'rewrite' => array( 'slug' => 'portfolio'), //  Change this to change the url of your "portfolio".
				'supports' => array( 
					'title', 
					'editor',  
					'thumbnail',
					'revisions'),
			)
		);
	}
	add_action( 'init', 'create_portfolio_post_types' );
endif;

/**
 * Create the taxonomies function
 */
if (!function_exists('ag_create_taxonomies')) :
	function ag_create_taxonomies() {
	  // Add new taxonomy, make it hierarchical (like categories)
	  $labels = array(
	    'name' => _x( 'Filter', 'taxonomy general name', 'framework'),
	    'singular_name' => _x( 'Filter', 'taxonomy singular name', 'framework'),
	    'search_items' =>  __( 'Search Filters', 'framework'),
	    'all_items' => __( 'All Filters', 'framework'),
	    'parent_item' => __( 'Parent Filter', 'framework'),
	    'parent_item_colon' => __( 'Parent Filter:', 'framework'),
	    'edit_item' => __( 'Edit Filter', 'framework'), 
	    'update_item' => __( 'Update Filter', 'framework'),
	    'add_new_item' => __( 'Add New Filter', 'framework'),
	    'new_item_name' => __( 'New Filter Name', 'framework'),
	    'menu_name' => __( 'Filters', 'framework'),
	  ); 	

	  register_taxonomy('filter',array('portfolio'), array(
	    'hierarchical' => false,
	    'labels' => $labels,
	    'show_ui' => true,
	    'query_var' => true,
	    'rewrite' => array( 'slug' => 'filter' ), // This is the url slug
	  ));

	}
	//hook into the init action and call the taxonomy when it fires
	add_action( 'init', 'ag_create_taxonomies', 0 );
endif;

/**
 * Add additional functionality for Qtranslate
 */
if (function_exists('qtrans_modifyTermFormFor')) {
	add_action('filter_add_form', 'qtrans_modifyTermFormFor');
	add_action('filter_edit_form', 'qtrans_modifyTermFormFor');
}

if (!function_exists('tw_section_nofollow')) :
	function tw_section_nofollow() {
		// Disable indexing section preview pages
		global $post;
		if( $post && $post->post_type == 'section' ) {
			echo '<meta name="robots" content="noindex, nofollow" />';
		} 
    }
    add_action('wp_head', 'tw_section_nofollow');
endif;

/**
 * Get Post Slides
 */
if (!function_exists('ag_post_slideshow')) :
	function ag_post_slideshow($image_size, $id, $thumbnum, $arrows_outside, $slidelink='false') {
		
		// Add one to the thumbnail number for the loop
		$thumbnum++; 
		// Set the slideshow variable	
		$slideshow = '';
		
		// Get The Post Type
		$posttype = get_post_type( $id );
		
		// Check whether the slide should link
		if ($slidelink == 'true') {
			$permalink = get_permalink($id);
			$title = get_the_title($id);
			$permalink = '<a href="'.$permalink.'" title="'.$title.'">';
			$permalinkend = '</a>';
		} else {
			$permalink = '';
			$permalinkend = '';
		}
		
		$counter = 2; //start counter at 2			  
		
		$full = get_post_meta($id,'_thumbnail_id',false); // Get Image ID 
		
		
		/* If there's a featured image
		================================================== */
		if($full) {
		  
			$caption = get_post($full[0])->post_excerpt; 
			
			$alt = get_post_meta($full[0], '_wp_attachment_image_alt', true); // Alt text of image
			$full = wp_get_attachment_image_src($full[0], 'full', false);  // URL of Featured Full Image
					  
			$thumb = get_post_meta($id,'_thumbnail_id',false); 
			$thumb = wp_get_attachment_image_src($thumb[0], $image_size, false);  // URL of Featured first slide
			
			
			// Get all slides
			while ($counter < ($thumbnum)) {
				
				${"full" . $counter} = MultiPostThumbnails::get_post_thumbnail_id($posttype, $counter . '-slide', $id); // Get Image ID
				// The thumbnail caption:
				${"caption" . $counter} = get_post(${"full" . $counter})->post_excerpt;
				${"alt" . $counter} = get_post_meta(${"full" . $counter} , '_wp_attachment_image_alt', true); // Alt text of image			 
				${"full" . $counter} = wp_get_attachment_image_src(${"full" . $counter}, false); // URL of Second Slide Full Image
				
				${"thumb" . $counter} = MultiPostThumbnails::get_post_thumbnail_id($posttype, $counter . '-slide', $id); 
				${"thumb" . $counter} = wp_get_attachment_image_src(${"thumb" . $counter}, $image_size, false); // URL of next Slide 
			 
			$counter++;
			
			}
				
			// If there's a thumbnail set
				$slideshow .= '<div class="featured-image ';
				
				$slideshow .= (isset($thumb2[0]) && $thumb2[0] != '' && $arrows_outside == true) ? ' outsidearrows' : '';
				
				$slideshow .=  '">';
			
			// If there's a slide 2
			$slideshow .= (isset($thumb2[0]) && $thumb2[0] != '') ? '<ul class="bxslider"><li>' : '';
			
			// If there's a slide 2 and outside arrows are set to true
			$slideshow .= $permalink . '<img src="' . $thumb[0] .'" alt="';
			// If there's an image alt info, set it
			$slideshow .= ($alt) ? str_replace('"', "", $alt) : get_the_title();
			$slideshow .= '"';
			// If there's a caption, add it.
			$slideshow .= ($caption && $caption != '') ? ' title="' . strip_tags (apply_filters('the_content', $caption)) .'"' : ''; 
			
			$slideshow .= ' class="scale-with-grid"/>' .$permalinkend;
			
			$slideshow .= (isset($thumb2[0]) && $thumb2[0] != '') ? '</li>' : '';
			
			// Loop through thumbnails and set them
			if (isset($thumb2[0]) && $thumb2[0] != '') {	
				$tcounter = 2;
				while ($tcounter < ($thumbnum)) :
					if ( ${'thumb' . $tcounter}) : 
					   $slideshow .= '<li>' . $permalink . '<img src="' . ${'thumb' . $tcounter}[0] .'" alt="';
					   $slideshow .= (${'alt' . $tcounter}) ? str_replace('"', "", ${'alt' . $tcounter}) : get_the_title();
					   $slideshow .= '" ';
					   if (${'caption' . $tcounter} &&  ${'caption' . $tcounter} != '') { $slideshow .= ' title="' . strip_tags (apply_filters('the_content', ${'caption' . $tcounter}))  .'"'; }
					   $slideshow .= ' class="scale-with-grid" data-thumb="' . ${'thumb' . $tcounter}[0] . '"/>'. $permalinkend . '</li>';
					endif; $tcounter++;
				endwhile; 
			}
			
			// Add caption if there's no slideshow
			if (!(isset($thumb2[0]) && $thumb2[0] != '') && $caption) $slideshow .= '<div class="bx-caption"><span>' . strip_tags (apply_filters('the_content', $caption)) . '</span></div>';
			$slideshow .= (isset($thumb2[0]) && $thumb2[0] != '') ? '</ul>' : '';
			// Close slideshow divs
			$slideshow .= '</div>';
			
		} // End if $full
		  
		return $slideshow;

	} 
endif;

/**
 * Display Post Video Function
 */
if (!function_exists('ag_post_video')) :
	function ag_post_video($postvideo) {
		
		  // Get Video URL that was entered
		  if ($postvideo != '') : $vendor = parse_url($postvideo); 
			  $video = '';
			  $video .= '<div class="featured-image"><div class="videocontainer">';
			
			 // If it's a legitimate url
			 if (isset($vendor['host'])) {	
				 if ($vendor['host'] == 'www.youtube.com' || $vendor['host'] == 'youtu.be' || $vendor['host'] == 'www.youtu.be' || $vendor['host'] == 'youtube.com'){ // If from Youtube.com 
					 if ($vendor['host'] == 'www.youtube.com') { parse_str( parse_url( $postvideo, PHP_URL_QUERY ), $my_array_of_vars );
						$video .= '<iframe width="620" height="350" src="http://www.youtube.com/embed/' . $my_array_of_vars['v']. '?modestbranding=1;rel=0;showinfo=0;autoplay=0;autohide=1;yt:stretch=16:9;wmode=transparent;" frameborder="0" allowfullscreen></iframe>';
					 } else { 
						$video .= '<iframe width="620" height="350" src="http://www.youtube.com/embed' . parse_url($postvideo, PHP_URL_PATH) . '?modestbranding=1;rel=0;showinfo=0;autoplay=0;autohide=1;yt:stretch=16:9;wmode=transparent;" frameborder="0" allowfullscreen></iframe>';
					 } 
				 } else 
				if ($vendor['host'] == 'vimeo.com'){ // If from Vimeo.com 
					$video .= '<iframe src="http://player.vimeo.com/video' . parse_url($postvideo, PHP_URL_PATH) . '?title=0&amp;byline=0&amp;portrait=0&amp;color=ffffff" width="620" height="350" frameborder="0"></iframe>';
				} else {
					$video .= do_shortcode($postvideo);	
				}
			
			 // Otherwise echo shortcode content	
			 } else {
				$video .= do_shortcode($postvideo);
			 }
				
				$video .= '</div></div>';
			endif;
	return $video;
	}
endif;

/**
 * Display Slide Video Function
 */
if (!function_exists('ag_slide_video')) :
	function ag_slide_video($slidevideo) {
		  if ($slidevideo != '') : $vendor = parse_url($slidevideo); 
			  $video = '';
			  $video .= '<div class="videocontainer">';
				
				 if ($vendor['host'] == 'www.youtube.com' || $vendor['host'] == 'youtu.be' || $vendor['host'] == 'www.youtu.be' || $vendor['host'] == 'youtube.com'){ // If from Youtube.com 
					 if ($vendor['host'] == 'www.youtube.com') { parse_str( parse_url( $slidevideo, PHP_URL_QUERY ), $my_array_of_vars );
						$video .= '<iframe width="620" height="350" src="http://www.youtube.com/embed/' . $my_array_of_vars['v']. '?modestbranding=1;rel=0;showinfo=0;autoplay=0;autohide=1;yt:stretch=16:9;wmode=transparent;" frameborder="0" allowfullscreen></iframe>';
					 } else { 
						$video .= '<iframe width="620" height="350" src="http://www.youtube.com/embed' . parse_url($slidevideo, PHP_URL_PATH) . '?modestbranding=1;rel=0;showinfo=0;autoplay=0;autohide=1;yt:stretch=16:9;wmode=transparent;" frameborder="0" allowfullscreen></iframe>';
					 } 
				 }
			
				if ($vendor['host'] == 'vimeo.com'){ // If from Vimeo.com 
					$video .= '<iframe src="http://player.vimeo.com/video' . parse_url($slidevideo, PHP_URL_PATH) . '?title=0&amp;byline=0&amp;portrait=0&amp;color=ffffff" width="620" height="350" frameborder="0"></iframe>';
				} 
				
				$video .= '</div>';
			endif;
	return $video;
	}
endif;

/**
 * Creates style attributes from multi-dimensional array
 */
if ( !function_exists( 'tw_get_styles' ) ) :
	function tw_get_styles($args) {
		$output = $styles = '';

		// if no styles are set, return
		if ( ! $args || ! isset($args['styles']) || ! is_array($args['styles']) ) {
			return false;
		}

		// defaults
		$defaults = array(
			'selectors' => false,
			'styles' => false
		);

		// Parse incoming $args into an array and merge it with $defaults
		$args = wp_parse_args( $args, $defaults );

		if ($args['selectors']) {
			$output .= $args['selectors'] . '{';

				foreach($args['styles'] as $style => $value) {
					if ($value) {
						$styles .= $style . ':' . $value . '; ';
					}	
				}

			$output = $output . $styles . '}';

			return $output; 
		}
	}
endif;

/**
 * Convert Hex to RGBA Function
 */

if ( ! function_exists('ag_hex2rgba') ) :
	function ag_hex2rgba($hex, $opacity) {
		$ohex = $hex;
	   $hex = str_replace("#", "", $hex);

	   if(strlen($hex) == 3) {
	      $r = hexdec(substr($hex,0,1).substr($hex,0,1));
	      $g = hexdec(substr($hex,1,1).substr($hex,1,1));
	      $b = hexdec(substr($hex,2,1).substr($hex,2,1));
	   } else {
	      $r = hexdec(substr($hex,0,2));
	      $g = hexdec(substr($hex,2,2));
	      $b = hexdec(substr($hex,4,2));
	   }
	   $rgb = array($r, $g, $b);
	   
	   $output = 'style="background:'.$ohex .'; background: rgba('.$rgb[0].','.$rgb[1].','.$rgb[2].','.$opacity.'); box-shadow: 20px 0 0 rgba('.$rgb[0].','.$rgb[1].','.$rgb[2].','.$opacity.'), -20px 0 0 rgba('.$rgb[0].','.$rgb[1].','.$rgb[2].','.$opacity.'); "';
	   //return implode(",", $rgb); // returns the rgb values separated by commas
	   return $output; // returns an array with the rgb values
	}
endif;

/**
 * Add HTTP to links function
 */
if ( ! function_exists('ag_addhttp') ) :
	function ag_addhttp($url) {
	    if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
	        $url = "http://" . $url;
	    }
	    return $url;
	}
endif;

/**
 * Get a Specific Amount of Categories
 */
if ( ! function_exists('ag_get_cats') ) :
	function ag_get_cats($num){
		
	    $t=get_the_category();
	    $count=count($t); 
		
		if ($count < $num) $num = $count;
		
		$cat_string = '';
	    for($i=0; $i<$num; $i++){
	        $cat_string.= '<a href="'.get_category_link( $t[$i]->cat_ID  ).'">'.$t[$i]->cat_name.'</a>';
	    }
		
		$cat_string .= '<div class="clear"></div>';
		
		if ($cat_string) return $cat_string;
	}
endif;

/**
 * Load Google Fonts
 */
if ( ! function_exists('ag_load_fonts') ) :
	function ag_load_fonts() {
		
		$cyrillic = of_get_option('of_cyrillic_chars');

		// Initialize Variables
		$fonts = '';
		$font_faces = array();
		$cyrillic_chars = '';
		
		// Get All Font Options
		$option_fonts = array(
			of_get_option('of_nav_font'),
			of_get_option('of_heading_font'),
			of_get_option('of_page_subtitle_font'),
			of_get_option('of_secondary_font'),
			of_get_option('of_content_heading_font'),
			of_get_option('of_button_font'),
			of_get_option('of_tiny_font'),
			of_get_option('of_p_font')
			);

		foreach ($option_fonts as $option) {
			 // Make sure the font face isn't a non-google font.
			 if (!ag_is_default($option['face'])){
				// Store all font typefaces in an array
			 	array_push($font_faces, $option['face']); 
			 };
		}
		
	  // Remove duplicate values
	  $font_faces = array_unique($font_faces); 

	  // Check for cyrillic character option
	  if ($cyrillic == 'Yes') $cyrillic_chars = '::cyrillic,latin'; 
	  
	  $fonts .= "
	    <!-- Embed Google Web Fonts Via API -->
	    <script type='text/javascript'>
	          WebFontConfig = {
	            google: { families: [ ";
					// Store the font list.
					$fontlist = '';
					foreach ($font_faces as $font) {
						$fontlist .= ($font) ? "'" . $font . $cyrillic_chars . "', " : "'" . 'Source Sans Pro' . $cyrillic_chars . "', ";
					}
					// Trim the last comma and space for IE and store in fonts
					$fonts .= rtrim($fontlist, ', ');
	    $fonts .=  " ] }   };
	          (function() {
	            var wf = document.createElement('script');
	            wf.src = ('https:' == document.location.protocol ? 'https' : 'http') +
	                '://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
	            wf.type = 'text/javascript';
	            wf.async = 'true';
	            var s = document.getElementsByTagName('script')[0];
	            s.parentNode.insertBefore(wf, s);
	          })();
	    </script>";
		
		echo $fonts;
	}
endif;

/**
 * Get Popular Posts
 */
if ( ! function_exists('getPostViews') ) :
	function getPostViews($postID){
	    $count_key = 'post_views_count';
	    $count = get_post_meta($postID, $count_key, true);
	    if($count==''){
	        delete_post_meta($postID, $count_key);
	        add_post_meta($postID, $count_key, '0');
	        return "<span>0</span> Views";
	    }
	    return '<span>'. $count.'</span> '. __('Views', 'framework');
	}
endif;

if ( ! function_exists('setPostViews') ) :
	function setPostViews($postID) {
	    $count_key = 'post_views_count';
	    $count = get_post_meta($postID, $count_key, true);
	    if($count==''){
	        $count = 0;
	        delete_post_meta($postID, $count_key);
	        add_post_meta($postID, $count_key, '0');
	    }else{
	        $count++;
	        update_post_meta($postID, $count_key, $count);
	    }
	}
endif;

/**
 * New category walker for portfolio filter
 */
if (!class_exists('Walker_Portfolio_Filter')) :
	class Walker_Portfolio_Filter extends Walker_Category {
	   function start_el(&$output, $category, $depth = 0, $args = array(), $current_object_id = 0) {

	      extract($args);
	      $cat_name = esc_attr( $category->name);
	      $cat_slug = $category->slug;
	      $cat_name = apply_filters( 'list_cats', $cat_name, $category );
	      $link = '<a href="#" data-filter=".'.strtolower(preg_replace('/\s+/', '-', $cat_slug)).'" ';
	      if ( $use_desc_for_title == 0 || empty($category->description) )
	         $link .= 'title="' . sprintf(__( 'View all projects filed under %s', 'framework'), $cat_name) . '"';
	      else
	         $link .= 'title="' . esc_attr( strip_tags( apply_filters( 'category_description', $category->description, $category ) ) ) . '"';
	      $link .= '>';
	      $link .= strip_tags (apply_filters('the_content', $cat_name));
	      $link .= '</a>';
	      if ( (! empty($feed_image)) || (! empty($feed)) ) {
	         $link .= ' ';
	         if ( empty($feed_image) )
	            $link .= '(';
	         $link .= '<a href="' . get_category_feed_link($category->term_id, $feed_type) . '"';
	         if ( empty($feed) )
	            $alt = ' alt="' . sprintf(__( 'Feed for all posts filed under %s', 'framework'), $cat_name ) . '"';
	         else {
	            $title = ' title="' . $feed . '"';
	            $alt = ' alt="' . $feed . '"';
	            $name = $feed;
	            $link .= $title;
	         }
	         $link .= '>';
	         if ( empty($feed_image) )
	            $link .= $name;
	         else
	            $link .= "<img src='$feed_image'$alt$title" . ' />';
	         $link .= '</a>';
	         if ( empty($feed_image) )
	            $link .= ')';
	      }
	      if ( isset($show_count) && $show_count )
	         $link .= ' (' . intval($category->count) . ')';
	      if ( isset($show_date) && $show_date ) {
	         $link .= ' ' . gmdate('Y-m-d', $category->last_update_timestamp);
	      }
	      if ( isset($current_category) && $current_category )
	         $_current_category = get_category( $current_category );
	      if ( 'list' == $args['style'] ) {
	          $output .= '<li class="segment-2"';
	          $class = 'cat-item cat-item-'.$category->term_id;
	          if ( isset($current_category) && $current_category && ($category->term_id == $current_category) )
	             $class .=  ' current-cat';
	          elseif ( isset($_current_category) && $_current_category && ($category->term_id == $_current_category->parent) )
	             $class .=  ' current-cat-parent';
	          $output .=  '';
	          $output .= ">$link\n";
	       } else {
	          $output .= "\t$link<br />\n";
	       }
	   }
	}
endif;

/**
 * Remove Dimensions from Thumbnails (for responsivity) and Gallery
 */
if ( ! function_exists('remove_thumbnail_dimensions') ) :
	function remove_thumbnail_dimensions( $html ) {
	    $html = preg_replace( '/(width|height)=\"\d*\"\s/', "", $html );
	    return $html;
	}
	add_filter( 'post_thumbnail_html', 'remove_thumbnail_dimensions', 10, 3 );
	add_filter( 'wp_get_attachment_link', 'remove_thumbnail_dimensions', 10, 1 );
endif;

/**
 * Remove More Link Jump
 */
if ( ! function_exists('ag_remove_more_jump_link') ) :
	function ag_remove_more_jump_link($link) { 
		$offset = strpos($link, '#more-');
		if ($offset) { $end = strpos($link, '"',$offset); }
		if ($end) { $link = substr_replace($link, '', $offset, $end-$offset); }
		return $link;
	}
	add_filter('the_content_more_link', 'ag_remove_more_jump_link');
endif;

/**
 * Get Attachment ID from the source
 */
if ( ! function_exists('get_attachment_id_from_src') ) :
	function get_attachment_id_from_src($image_src) {
		global $wpdb;
		$query = "SELECT ID FROM {$wpdb->posts} WHERE guid='$image_src'";
		$id = $wpdb->get_var($query);
		return $id;
	}
endif;
	
/**
 * Wrap All Read More Tags In A Span
 */	
if ( ! function_exists('wrap_readmore') ) :
	function wrap_readmore($more_link) {
		return '<span class="more-link">'.$more_link.'</span>';
	}
	add_filter('the_content_more_link', 'wrap_readmore', 10, 1);
endif;

/**
 * Check for a Default Font
 */
if ( ! function_exists('ag_is_default') ) :
	function ag_is_default($font) {
	  if ($font == 'Arial' || $font == 'Georgia' || $font == 'Tahoma' || $font == 'Verdana' || $font == 'Helvetica') {
	    $font = true;
	  } else {
		$font = false;  
	  }
	  return $font;
	}
endif;

/**
 * Function to Get Slide Options
 */
if ( ! function_exists('ag_get_slide_variables') ) :
	function ag_get_slide_variables($id, $fixed) {
		// Get Slide Variables
		$ag_slide = array();
		
		// Slide Background Image
		$ag_slide['image_id'] = get_post_meta($id, 'ag_slide_background_image', true);
		
		if ($fixed == true) {
			$ag_slide['image'] = wp_get_attachment_image_src( $ag_slide['image_id'], 'homeslideshowfixed');
		} else {
			$ag_slide['image'] = wp_get_attachment_image_src( $ag_slide['image_id'], 'homeslideshow');
		}
		$ag_slide['image_src'] = $ag_slide['image'][0];
		
		// Slide Text
		$ag_slide['caption_show'] = get_post_meta($id, 'ag_slide_text_show', true);
		$ag_slide['caption_color'] = get_post_meta($id, 'ag_slide_text_color', true);
		$ag_slide['caption_bg_color'] = get_post_meta($id, 'ag_slide_text_bg_color', true);
		
		
		// Slide Button
		$ag_slide['button_show'] = get_post_meta($id, 'ag_slide_button_show', true);
		$ag_slide['button_color'] = get_post_meta($id, 'ag_slide_button_color', true);
		$ag_slide['button_text_color'] = get_post_meta($id, 'ag_slide_button_text_color', true);
		$ag_slide['button_text'] = get_post_meta($id, 'ag_slide_button_text', true);
		$ag_slide['button_link'] = get_post_meta($id, 'ag_slide_button_link', true); 
			// Apply Content Filters for Translation
			$ag_slide['button_link'] = apply_filters('the_content', $ag_slide['button_link']);
			$ag_slide['button_link'] = str_replace('<p>', '', $ag_slide['button_link']);
			$ag_slide['button_link'] = str_replace('</p>', '', $ag_slide['button_link']);
		
		// Slide Transition
		$ag_slide['transition'] = get_post_meta($id, 'ag_slide_transition', true);
		
		// Set Button Style
		$ag_slide['button_style'] = 'style="';
		$ag_slide['button_style'] .= ($ag_slide['button_color']) ? 'background-color: ' . $ag_slide['button_color'] . ';  ' : '';
		$ag_slide['button_style'] .= ($ag_slide['button_text_color']) ? 'color: ' . $ag_slide['button_text_color'] . ';  ' : '';
		$ag_slide['button_style'] .= '"';
		
		// Slide Link
		$ag_slide['slide_link'] = get_post_meta($id, 'ag_slide_link', true);
		
		// Video URL
		$ag_slide['video'] = get_post_meta($id, 'ag_slide_video', true);
		
		// Slider Height
		if (!($ag_slide['caption_height'] = (of_get_option('of_home_slider_height'))/2)) $ag_slide['caption_height'] = '275';
		
		return $ag_slide;
	}
endif;

/**
 * Function to Get Section Options
 */
if ( ! function_exists('ag_get_section_variables') ) :
	function ag_get_section_variables($id) {
		
			$sq = "'";
			$ag_section = array();
			
			// Get Section Options
			$ag_section['section_layout'] = get_post_meta($id, 'ag_section_layout', true);
			$ag_section['background_color'] = get_post_meta($id, 'ag_background_color', true);
			$ag_section['background_image'] = get_post_meta($id, 'ag_background_image', true); $ag_section['background_image'] = wp_get_attachment_image_src( $ag_section['background_image'], 'full');
			$ag_section['section_text'] = get_post_meta($id, 'ag_text_color', true);
			$ag_section['section_button_show'] = get_post_meta($id, 'ag_section_button_show', true); 
			$ag_section['background_repeat'] = get_post_meta($id, 'ag_background_repeat', true);
			
			// Create Background Style
			$ag_section['backgroundstyle'] = 'style="';
			$ag_section['backgroundstyle'] .= ($ag_section['background_color']) ? 'background-color: ' . $ag_section['background_color'] . ';  ' : '';
			$ag_section['backgroundstyle'] .= ($ag_section['background_image']) ? 'background-image: url(' . $ag_section['background_image'][0] . ');  background-position:center;' : '';
			$ag_section['backgroundstyle'] .= '"';
			
			//Get Button Options
			if ($ag_section['section_button_show'] == 'Yes') {
				$ag_section['section_button_color'] = get_post_meta($id, 'ag_section_button_color', true);
					$ag_section['section_button_color'] = ($ag_section['section_button_color']) ? 'background:' . $ag_section['section_button_color'] .';' : '';
				$ag_section['section_button_text'] = get_post_meta($id, 'ag_section_button_text', true);
				$ag_section['section_text_color'] = get_post_meta($id, 'ag_section_text_color', true);
				$ag_section['section_button_link'] = get_post_meta($id, 'ag_section_button_link', true);
				
				$ag_section['section_button'] = '<a href="' . $ag_section['section_button_link'] . '" class="button" style="' . $ag_section['section_button_color'] .' color: ' . $ag_section['section_text_color'] . ';">' . $ag_section['section_button_text'] . '</a>';
			} else {
				$ag_section['section_button'] = '';	
			}
			
			
			$ag_section['sectionvideo'] = get_post_meta($id, 'ag_section_video', true);
			
			$ag_section['sectionpadding'] = get_post_meta($id, 'ag_bottom_padding', true);
			
			$ag_section['background_repeat'] = get_post_meta($id, 'ag_background_repeat', true);
			
			
			return $ag_section;
		
	}
endif;

/**
 * Function to Get Page Options
 */
if ( ! function_exists('ag_get_page_variables') ) :
	function ag_get_page_variables($pageID) {
		
		$ag_page = array();
		
		$ag_page['button_show'] =  get_post_meta($pageID, 'ag_page_button_show', true);
		$ag_page['button_text'] = get_post_meta($pageID, 'ag_page_button_text', true);
		
		// Get Page Description
		$ag_page['page_desc'] = get_post_meta($pageID, 'ag_page_desc', true);
		$ag_page['page_desc_color'] = get_post_meta($pageID, 'ag_page_desc_color', true);
		
		//Get Button Options
		if ($ag_page['button_show'] == 'Yes' && $ag_page['button_text'] != '') {
			$ag_page['button_color'] = get_post_meta($pageID, 'ag_page_button_color', true);
			$ag_page['button_text_color'] = get_post_meta($pageID, 'ag_page_button_text_color', true);
			$ag_page['button_link'] = get_post_meta($pageID, 'ag_page_button_link', true);
			
			$ag_page['button'] = '<a href="' . $ag_page['button_link'] . '" class="button huge alignright" style="';
			$ag_page['button'] .= ($ag_page['button_color']) ? 'background:' . $ag_page['button_color'] .'; ' : '';
			$ag_page['button'] .= ($ag_page['button_text_color']) ? 'color: ' . $ag_page['button_text_color'] .'; ' : '';
			$ag_page['button'] .= '">' . strip_tags (apply_filters('the_content', $ag_page['button_text'])) . '</a>';
		} else {
			$ag_page['button'] = '';	
		}
		
		// Get Page Content Color
		$ag_page['page_title_bg_color'] = get_post_meta($pageID, 'ag_page_title_bg_color', true); 
		$ag_page['page_title_color'] = get_post_meta($pageID, 'ag_page_title_color', true);
		$ag_page['page_content_color'] = get_post_meta($pageID, 'ag_page_content_bg_color', true);
		$ag_page['thumb'] = wp_get_attachment_image_src( get_post_thumbnail_id($pageID), 'homeslideshow' );
		$ag_page['thumburl'] = $ag_page['thumb']['0'];
		
		// Create Background Style
		if ($ag_page['thumburl'] || $ag_page['page_title_bg_color']){
		$ag_page['background_style'] = 'style="';
		$ag_page['background_style'] .= ($ag_page['page_title_bg_color']) ? 'background-color: ' . $ag_page['page_title_bg_color'] . '; padding-top:35px;' : '';
		$ag_page['background_style'] .= ($ag_page['thumburl']) ? 'background-image: url(' . $ag_page['thumburl'] . ');  background-position:center; padding-top:35px;' : '';
		$ag_page['background_style'] .= '"';
		} else {
			$ag_page['background_style'] = '';	
		}
		
		return $ag_page;

	}
endif;

/**
 * Function to Get Portfolio Options
 */
if ( ! function_exists('ag_get_portfolio_variables') ) :
	function ag_get_portfolio_variables($pageID, $portfolio_page_id) {
		
		// Set Up Array
		$ag_portfolio = array();
		
		// Get Page Description
		$ag_portfolio['portfolio_desc'] = get_post_meta($pageID, 'ag_portfolio_desc', true);
		$ag_portfolio['portfolio_desc_color'] =  get_post_meta($portfolio_page_id, 'ag_page_desc_color', true);
		
		// Get Page Content Color
		$ag_portfolio['page_title_bg_color'] = get_post_meta($portfolio_page_id, 'ag_page_title_bg_color', true); 
		$ag_portfolio['page_title_color'] = get_post_meta($portfolio_page_id, 'ag_page_title_color', true);
		$ag_portfolio['page_content_color'] = get_post_meta($portfolio_page_id, 'ag_page_content_bg_color', true);
		$ag_portfolio['thumb'] = wp_get_attachment_image_src( get_post_thumbnail_id($portfolio_page_id), 'homeslideshow' );
		$ag_portfolio['thumburl'] = $ag_portfolio['thumb']['0'];
		
		// Create Background Style
		if ($ag_portfolio['thumburl'] || $ag_portfolio['page_title_bg_color']){
			$ag_portfolio['background_style'] = 'style="';
			$ag_portfolio['background_style'] .= ($ag_portfolio['page_title_bg_color']) ? 'background-color: ' . $ag_portfolio['page_title_bg_color'] . '; padding-top:35px;' : '';
			$ag_portfolio['background_style'] .= ($ag_portfolio['thumburl']) ? 'background-image: url(' . $ag_portfolio['thumburl'] . ');  background-position:center; padding-top:35px;' : '';
			$ag_portfolio['background_style'] .= '"';
		} else {
			$ag_portfolio['background_style'] = '';	
		}
		
		// Get Portfolio Page Button Options
		$ag_portfolio['button_color'] = get_post_meta($portfolio_page_id, 'ag_page_button_color', true);
		$ag_portfolio['button_text_color'] = get_post_meta($portfolio_page_id, 'ag_page_button_text_color', true);
		
		// Get Page Content Color
		$ag_portfolio['portfolio_content_color'] = get_post_meta($portfolio_page_id, 'ag_page_content_bg_color', true);
		$ag_portfolio['video'] = get_post_meta($pageID, 'ag_portfolio_video', true);
		$ag_portfolio['content_title'] = get_post_meta($pageID, 'ag_portfolio_content_title', true);
		
		$ag_portfolio['project_button'] = (of_get_option('of_project_button')) ? of_get_option('of_project_button') : 'on';
		$ag_portfolio['project_button_text'] = (of_get_option('of_project_button_text')) ? of_get_option('of_project_button_text') : 'Back To Projects';
		
		$ag_portfolio['thumbsize'] = (of_get_option('of_portfolio_crop') == 'crop') ? 'portfolio-single' : 'portfolio-single-nc';
		$ag_portfolio['slide_number'] = (of_get_option('of_thumbnail_number')) ? of_get_option('of_thumbnail_number') : '6';
		
		return $ag_portfolio;
		
	}
endif;

