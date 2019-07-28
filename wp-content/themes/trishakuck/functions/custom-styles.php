<?php
/**
 * Custom Styles
 *
 * Functions for the customizer and theme options 
 * styles
 *
 * @package WordPress
 * @since 1.0
 */

/**
 * Theme customizer colors
 */
if (!function_exists('ag_color_customizer')) :
	function ag_color_customizer($wp_customize) {
	  $colors = array();
	  
	  $colors[] = array( 
	  	'slug'=>'highlight_color', 
		'default' => '#00a498',
	  	'label' => __( 'Theme Highlight Color', 'framework' ),
		'priority' => 20 
		);
	  
	  $colors[] = array( 
	  	'slug'=>'content_bg_color', 
		'default' => '#ffffff',
	  	'label' => __( 'Site Background Color', 'framework' ),
		'priority' => 30 
		);
	  
	  $colors[] = array( 
	  	'slug'=>'page_bg_color', 
		'default' => '#f3f3f3',
	  	'label' => __( 'Page Background Color', 'framework' ),
		'priority' => 40 
		);
	  
	  $colors[] = array( 
	  	'slug'=>'heading_color', 
	  	'default' => '#222222',
	  	'label' => __( 'Site Headings and Titles Color', 'framework' ),
	    'priority' => 50 
		);
	  
	  $colors[] = array( 
	  	'slug'=>'body_color', 
		'default' => '#555555',
	  	'label' => __( 'General Site Text Color', 'framework' ),
		'priority' => 60 
		);
	  
	  $colors[] = array( 
	  	'slug'=>'content_li_color', 
		'default' => '#555555',
	  	'label' => __( 'Dropdown Navigation Color', 'framework' ),
		'priority' => 70 
		); 

	  $colors[] = array( 
		'slug'=>'content_li_bg_color', 
		'default' => '#fff',
		'label' => __( 'Dropdown Navigation Background Color', 'framework' ),
		'priority' => 60 
		); 
	  
	  foreach($colors as $color)
	  {

	    // SETTINGS
	    $wp_customize->add_setting( $color['slug'], array( 'default' => $color['default'],
	    'type' => 'option', 'capability' => 'edit_theme_options' ));

	    // CONTROLS
	    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize,
	     $color['slug'], array( 'label' => $color['label'], 'section' => 'colors',
	     'settings' => $color['slug'] )));
	  }
	  

	}
	add_action('customize_register', 'ag_color_customizer');
endif;

/**
 * Background image
 */
if (!function_exists('ag_background_theme_customizer')) :
	function ag_background_theme_customizer( $wp_customize ) {
	    $wp_customize->add_section( 'ag_background', array(
	        'title' => 'Background Image', // The title of section
	        'description' => 'Background Image For Your Site', // The description of section
	    ) );
	 
	  // ADD BACKGROUND IMAGE UPLOAD
	  $wp_customize->add_setting( 'uploaded_image', array(
	    'type' => 'option',
	  ) );
	  
	  $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 
	  	'uploaded_image', array( 'label'   => 'Background Image', 'section' => 'ag_background',
	  )));
	  
	  // Remove the Title Tagline Section
	  $wp_customize->remove_section( 'title_tagline'); 
	}
	add_action( 'customize_register', 'ag_background_theme_customizer', 11 );
endif;

/**
 * Options text fonts
 */
if ( ! function_exists( 'tw_options_text' ) ) :
	function tw_options_text($arr) {
		$new_arr = array(
			'font-family' => '"' . $arr['face'] . '", arial, sans-serif;',
			'font-weight' => ($arr['style'] == 'bold italic' || $arr['style'] == 'bold') ? 'bold' : 'normal',
			'font-style' => ($arr['style'] == 'bold italic' || $arr['style'] == 'italic') ? 'italic' : 'normal',
			'text-transform' => ($arr['style2'] == 'Normal' || $arr['style2'] == 'none') ? 'none' : 'uppercase',
		);
		return $new_arr;
	}
endif;

/**
 * 
 */
if ( ! function_exists( 'themewich_custom_styles' ) ) :
	function themewich_custom_styles() {

		$output = '';

		// Top padding
		$styles[] = array(
			'selectors' => '.sitecontainer .container.top-nav',
			'styles' => array(
				'padding-top' => of_get_option('of_logo_top_padding') ? of_get_option('of_logo_top_padding') . 'px' : '20px',
				'padding-bottom' => of_get_option('of_logo_bottom_padding') ? of_get_option('of_logo_bottom_padding') . 'px' : '20px'
			)
		);

		// Navigation font
		if ( $sffont = of_get_option('of_nav_font') ) { 
			$styles[] = array(
				'selectors' => '.sf-menu a, .ajax-select ul.sf-menu li li a',
				'styles' => tw_options_text($sffont)
			);
		}

		// Slider Caption, Page Title, and Section Title Font 
		if ( $headingfont = of_get_option('of_heading_font') ) { 
			$styles[] = array(
				'selectors' => '.pagetitle h1, .homecaption h2, .section h2, #logo h1 a, #logo h2 a',
				'styles' => tw_options_text($headingfont)
			);
		} 

		// subtitle font
		if ( $subtitlefont = of_get_option('of_page_subtitle_font') ) { 
			$styles[] = array(
				'selectors' => '.pagetitle h2',
				'styles' => tw_options_text($subtitlefont)
			);
		} 

		// Blog and Portfolio Item Font
		if ( $secondaryfont = of_get_option('of_secondary_font') ) { 
			$styles[] = array(
				'selectors' => 'h2.title, h2.title a, .post .date h4.day',
				'styles' => tw_options_text($secondaryfont)
			);
		} 

		// Content Area Fonts
		if ( $contentfont = of_get_option('of_content_heading_font') ) { 
			$styles[] = array(
				'selectors' => 'h1, h1 a, h2, h2 a, h3, h3 a, h4, h4 a, h5, h5 a,h6, h6 a, .ag-pricing-cost',
				'styles' => tw_options_text($contentfont)
			);
		} 

		// Button Fonts
		if ($buttonfont = of_get_option('of_button_font') ) { 
			$styles[] = array(
				'selectors' => '.button, a.button, a.more-link, #submit, input[type="submit"]',
				'styles' => tw_options_text($buttonfont)
			);
		} 

		// Tiny Details Font
		if ( $tinyfont = of_get_option('of_tiny_font')  ) { 
			$styles[] = array(
				'selectors' => 'h5, h5 a, .widget h3, .widget h2, .widget h4, h4.widget-title, .ag-pricing-table .ag-pricing-header h5',
				'styles' => tw_options_text($tinyfont)
			);
		} 

		// Paragraph Font
		if ( $pfont = of_get_option('of_p_font') ) { 
			$styles[] = array(
				'selectors' => 'html, body, input, textarea, p, ul, ol, .button, .ui-tabs-vertical .ui-tabs-nav li a span.text,
					.footer p, .footer ul, .footer ol, .footer.button, .credits p,
					.credits ul, .credits ol, .credits.button, .footer textarea, .footer input, .testimonial p, 
					.contactsubmit label, .contactsubmit input[type=text], .contactsubmit textarea, h2 span.date, .articleinner h1,
					.articleinner h2, .articleinner h3, .articleinner h4, .articleinner h5, .articleinner h6, .nivo-caption h1,
					.nivo-caption h2, .nivo-caption h3, .nivo-caption h4, .nivo-caption h5, .nivo-caption h6, .nivo-caption h1 a,
					.nivo-caption h2 a, .nivo-caption h3 a, .nivo-caption h4 a, .nivo-caption h5 a, .nivo-caption h6 a,
					#cancel-comment-reply-link',
				'styles' => tw_options_text($pfont)
			);
		} 

				// Content Area Fonts
		if ( $contentfont = of_get_option('of_content_heading_font') ) { 
			$styles[] = array(
				'selectors' => 'h1, h1 a, h2, h2 a, h3, h3 a, h4, h4 a, h5, h5 a,h6, h6 a, .ag-pricing-cost',
				'styles' => tw_options_text($contentfont)
			);
		} 

		foreach($styles as $style) {
			$output .= tw_get_styles($style);
		}

		return $output;
	}
endif;

if (!function_exists('tw_customize_css')) :
	function tw_customize_css() { 

		$output = '';

		$styles[] = array(
			'selectors' => 'body',
			'styles' => array(
				'background-color' => get_option('content_bg_color') ? get_option('content_bg_color') : '#ffffff',
				'background-image' => get_option('uploaded_image') ? 'url(' . get_option('uploaded_image') . ');' : 'none'
			)
		);

		$styles[] = array(
			'selectors' => 
				'h1, h1 a,
				h2, h2 a,
				h3, h3 a,
				h4, h4 a,
				h5, h5 a,
				h6, h6 a,
				.widget h1 a,
				.widget h2 a,
				.widget h3 a,
				.widget h4 a,
				.widget h5 a,
				.widget h6 a,
				.tabswrap .tabpost a,
				.more-posts a,
				ul li a.rsswidget',
			'styles' => array(
				'color' => get_option('heading_color') ? get_option('heading_color') : '#222222'
			)
		);


		$styles[] = array(
			'selectors' => 
				'.sf-menu li li li li a, 
				.sf-menu li li li a, 
				.sf-menu li li a, 
				.sf-menu li li a:visited,
				.sf-menu li li li a:visited, 
				.sf-menu li li li li a:visited,
				.sf-menu a, .sf-menu a:visited ',
			'styles' => array(
				'color' => get_option('content_li_color') ? get_option('content_li_color') : '#555555'
			)
		);

		$styles[] = array(
			'selectors' => 
				'.sf-menu ul.sub-menu,
				.sf-menu li li li li a, 
				.sf-menu li li li a, 
				.sf-menu li li a, 
				.sf-menu li li li li a:visited, 
				.sf-menu li li li a:visited, 
				.sf-menu li li a:visited',
			'styles' => array(
				'background' => get_option('content_li_bg_color') ? get_option('content_li_bg_color') : '#ffffff'
			)
		);

		$styles[] = array(
			'selectors' => 
				'.avatar-info .comment-counter,
				.categories a:hover, .tagcloud a, .widget .tagcloud a, .single .categories a, .single .sidebar .categories a:hover, 
				.tabswrap ul.tabs li a.active, .tabswrap ul.tabs li a:hover, #footer .tabswrap ul.tabs li a:hover, #footer .tabswrap ul.tabs li a.active, 
				.pagination a.button.share:hover, #commentsubmit #submit, #cancel-comment-reply-link, ul.filter li a.active, .categories a, .widget .categories a,
				ul.filter li a:hover, .button, a.button, .widget a.button, a.more-link, .widget a.more-link, #footer .button, #footer a.button, #footer a.more-link, .cancel-reply p a,
				#footer .button:hover, #footer a.button:hover, #footer a.more-link:hover, .ag-pricing-table .featured .ag-pricing-header',
			'styles' => array(
				'background-color' => get_option('highlight_color') ? get_option('highlight_color') : '#00a498',
				'color' => '#fff'
			)
		);

		$styles[] = array(
			'selectors' => 
				'p a, a, blockquote, blockquote p, .pagetitle h2, .tabswrap .tabpost a:hover, 
				.articleinner h2 a:hover, span.date a:hover, .highlight, h1 a:hover, h2 a:hover, 
				h3 a:hover, h4 a:hover, h5 a:hover, .post h2.title a:hover, #wp-calendar tbody td a,
				.author p a:hover, .date p a:hover, .widget a:hover, .widget.ag_twitter_widget span a, 
				#footer h1 a:hover, #footer h2 a:hover, #footer h3 a:hover, #footer h3 a:hover, 
				#footer h4 a:hover, #footer h5 a:hover, a:hover, #footer a:hover, .blogpost h2 a:hover, 
				.blogpost .smalldetails a:hover',
			'styles' => array(
				'color' => get_option('highlight_color') ? get_option('highlight_color') : '#00a498',
			)
		);

		$styles[] = array(
			'selectors' => 
				'.recent-project:hover,
				#footer .recent-project:hover',
			'styles' => array(
				'border-color' => get_option('highlight_color') ? get_option('highlight_color') : '#00a498',
			)
		);


		$styles[] = array(
			'selectors' => '.pagecontent',
			'styles' => array(
				'background-color' => get_option('page_bg_color') ?get_option('page_bg_color') : '#f3f3f3',
			)
		);

		if (get_option('page_bg_color') == '#fff' || get_option('page_bg_color') == '#ffffff') { 
			$styles[] = array(
				'selectors' => '.singlecomment',
				'styles' => array(
					'background' => '#f3f3f3',
					'background' => 'rgba(0,0,0,0.05)'
				)
			);
			$styles[] = array(
				'selectors' => '#wp-calendar tbody td',
				'styles' => array(
					'background' => '#f3f3f3',
					'border' => '1px solid #ffffff'
				)
			);
		}

		$styles[] = array(
			'selectors' => '
				body, p, ul, ol, ul.filter li a, 
				.author p a, .date p a, .widget a, 
				.widget_nav_menu a:hover, .widget_recent_entries a:hover,
				.sf-menu a, .sf-menu a:visited',
			'styles' => array(
				'color' => get_option('body_color') ? get_option('body_color') : '#555555'
			)
		);

		foreach($styles as $style) {
			$output .= tw_get_styles($style);
		}

		return $output;
	}
endif;