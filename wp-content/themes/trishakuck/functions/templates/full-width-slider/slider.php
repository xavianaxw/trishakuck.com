<?php
/*-----------------------------------------------------------------------------------*/
/* Full Width Slider Template
/*-----------------------------------------------------------------------------------*/

/* Set Variables
================================================== */

$ag_slide['data-items'] = '';
$ag_slide['data'] = array();

// If autoplay is set 
if ( of_get_option('of_home_autoplay') != 'true') {
	// get autoplay delay
	if ( $ag_slide['data']['delay'] = of_get_option('of_home_autoplay_delay') ) {
		$ag_slide['data']['delay'] = $ag_slide['data']['delay'] . '000';
	} else {
		// set default if none is set
		$ag_slide['data']['delay'] = 9000;
	}
} else {
	$ag_slide['data']['delay'] = false;
}

// if starting height is set
if ( ! $ag_slide['data']['height'] = of_get_option('of_home_slider_height') ) {
	// set default if none is set
	$ag_slide['data']['height'] = 575;
}

if ( isset( $ag_slide['data'] ) ) {
	foreach ($ag_slide['data'] as $key => $data) {
		if ($data) {
			$ag_slide['data-items'] .= ' data-' . $key . '="' . $data . '"';
		}
	}
}


/* Query Posts and Order
================================================== */
$query = new WP_Query( array( 
				'post_type' => 'slide', 
				'orderby' => 'menu_order', 
				'order' => 'ASC',
				'posts_per_page'=> -1 
				) 
			);
if ( $query->have_posts() ) : ?>

<div class="fullwidthbanner-container" <?php echo $ag_slide['data-items']; ?>>
    <div class="fullwidthbanner">
        <ul>
			<?php while ( $query->have_posts() ) : $query->the_post(); 
                
				/* Get Slide Layout and Use Correct Template
				================================================== */
				$ag_slide['layout'] = get_post_meta(get_the_ID(), 'ag_slide_layout', true);
				
				switch ($ag_slide['layout']) {
					case 'Center':
						echo get_template_part('functions/templates/full-width-slider/slide-center'); 
					break;
				
					case 'Right':
						echo get_template_part('functions/templates/full-width-slider/slide-right'); 
					break;
					
					default :
						echo get_template_part('functions/templates/full-width-slider/slide-left'); 
					break;	
				}
            
			// End Query           
            endwhile; wp_reset_postdata(); ?>
        </ul>
    </div>
</div>
<div class="clear"></div>

<?php 
// end if have_posts();
endif; ?>