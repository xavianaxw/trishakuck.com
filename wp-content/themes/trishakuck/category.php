<?php get_header(); 

$pageID = get_option('page_for_posts');

/* Get Page Options Defined in functions.php
================================================== */
$ag_page = ag_get_page_variables($pageID); ?>

<!-- Page Title -->
<div class="pagetitle">
    <div class="container verticalcenter">
        <div class="container_row">
        	<div class="verticalcenter cell">
                <div class="ten columns title">
                    <h1 <?php echo ($ag_page['page_title_color']) ? 'style="color: ' . $ag_page['page_title_color'] . ';"' : '' ?>><?php wp_title("",true); ?></h1>
                <?php if (category_description()) { ?> 
                    <h2 <?php echo ($ag_page['page_desc_color']) ? 'class="colored" style="color: ' . $ag_page['page_desc_color'] . ';"' : '' ?>><?php echo strip_tags (category_description()); ?> </h2>
                <?php } ?>			
                </div>
            </div>
            <div class="verticalcenter cell">
            	<div class="six columns">
				   <?php echo $ag_page['button']; ?>
           		</div>
            </div>
            <div class="clear"></div>
        </div>
      </div>
</div>
<!-- END Page Title -->

<div class="clear"></div>
<div class="pagecontent">
    <!-- Thumbnail Area -->
    <div class="container isowrap">
        <div class="isocontainer">
            <div id="isotope" class="isotopecontainer" data-value="3">
            
                <?php 
                
                if (!($ag_post['slide_number'] = of_get_option('of_thumbnail_number'))) $ag_post['slide_number'] = '6'; 
                
                /* #Loop through sticky posts
                ======================================================*/
                if ( have_posts() ) : while ( have_posts() ) : the_post(); 

                $ag_post['video'] = get_post_meta($post->ID, 'ag_post_video', true);
                $ag_post['author'] = of_get_option('of_author_style');
                $ag_post['thumbsize'] = (of_get_option('of_post_crop')) ? of_get_option('of_post_crop') : 'post';

                $terms = get_the_terms( get_the_ID(), 'filter' ); ?>
                
                <!-- Portfolio Item -->
                <div class="isobrick thirds border-box <?php if ($terms) { foreach ($terms as $term) { echo strtolower(preg_replace('/\s+/', '-', $term->slug)). ' '; } } ?>">
                    <div <?php post_class(); ?>>
                        <!-- Featured Image -->
                        <?php if (has_post_thumbnail()) { ?>
                        <div class="featured-image">
                            <a class="thumblink" title="<?php printf(__('Permanent Link to %s', 'framework'), get_the_title()); ?>" href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail($ag_portfolio['thumbsize'], array('class' => 'scale-with-grid')); /* post thumbnail settings configured in functions.php */ ?>
                            </a>
                            <div class="date">
                                <h4 class="day">
                                    <?php the_time('d'); ?>
                                    <span>
                                     <?php the_time('M'); ?>
                                     </span>
                                </h4>
                                <div class="clear"></div>
                            </div>
                        </div>
                        <?php } ?>
                        <!-- END Featured Image -->
                        
                        <!-- Portfolio Content -->
                        <div class="wrapper">
                            <h2 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                            <div class="mobiledate">
                                <p>
                                    Posted on <?php the_time(get_option('date_format')); 
                                    if( get_the_author() != 'root' ) :
                                    echo ' by '; the_author_posts_link();
                                    endif; ?>
                                </p>
                            </div>
                            <div class="morecontent">
                                <?php the_excerpt(); the_shortlink(__('Read More')); ?> 
                            </div>
                        </div>
                        <!-- END Portfolio Content -->
                    </div>
                    
                </div>
                <!-- END Portfolio Item -->
                          
                <?php endwhile; endif; ?>
            </div>
        </div>
        
        <div class="sixteen columns">
            
            <!-- Pagination
            ================================================== -->
            <p class="more-posts"><?php next_posts_link(__('Load More Posts', 'framework')); ?></p>
            <div class="clear"></div>
            
            <?php wp_reset_query(); ?>
            
            <!-- END pagination --> 
        </div>
    </div>
    <!-- END Thumbnail Area -->
</div>

<?php 
/* Get Footer
================================================== */
get_footer(); ?>