<?php 
// create shortcode with parameters so that the user can define what's queried - default is to list all blog posts
add_shortcode( 'eazy-css-slider', 'eazy_css_slider_shortcode' );
function eazy_css_slider_shortcode( $atts ) {
    ob_start();
 
    // define attributes and their defaults
    extract( shortcode_atts( array ('type' => 'eazy_slide', 'order' => '', 'orderby' => 'post_date', 'posts' => -1, 'slider' => '', ), $atts ) );

    // define query parameters based on attributes
    $eazyoptions = array( 'post_type' => $type, 'order' => $order, 'orderby' => $orderby, 'posts_per_page' => $posts, 'slider' => $slider, );
    $eazyquery = new WP_Query( $eazyoptions );
        

    if ( $eazyquery->have_posts() ) { $slideid = 0;  
        //Check if slider is named and add slider name to id
        if($slider): echo '<form class="eazy-css-slider" id="'. $slider .'">';
        else: echo '<form class="eazy-css-slider" id="all-slides">';
        endif;  
  
    while ( $eazyquery->have_posts() ) : $eazyquery->the_post(); /* Count slides with $slideid - assign total count to $last */ $slideid++; $last = $eazyquery->found_posts; 
        //Add input / radio button
        if ( $slideid == 1 ): echo '<input type="radio" name="radio-btn" id="'.$slider.'-eazy-css-slide-'.$slideid.'" checked="checked">';   
        else: echo '<input type="radio" name="radio-btn" id="'.$slider.'-eazy-css-slide-'.$slideid.'" >';
        endif; ?>
                         
                            
                                <?php 
                                if($slideid == 1): /* First Slide */?>
                                <div class="eazy-css-slide firstslide <?php echo $slider ."-eazy-css-slide-". $slideid; ?>"> 
                                <? the_post_thumbnail('full' ); ?>
                                    <div class="nav">
                                            <label for="<?php echo $slider; ?>-eazy-css-slide-<?php echo $last; ?>" class="prev">&#x2039;</label>
                                            <label for="<?php echo $slider; ?>-eazy-css-slide-2" class="next">&#x203a;</label>
                                    </div>
                                <?php 
                                elseif($slideid == $last): /* Last Slide */ ?>
                                <div class="eazy-css-slide lastslide <?php echo $slider ."-eazy-css-slide-". $slideid; ?>">
                                <? the_post_thumbnail('full' );  ?>
                                    <div class="nav">
                                        <label for="<?php echo $slider; ?>-eazy-css-slide-<?php print($slideid-1); ?>" class="prev">&#x2039;</label>
                                        <label for="<?php echo $slider; ?>-eazy-css-slide-1" class="next">&#x203a;</label>
                                    </div>
                                <?php 
                                else: /* Other Slides */?>
                                <div class="eazy-css-slide otherslide <?php echo $slider ."-eazy-css-slide-". $slideid; ?>">
                                <? the_post_thumbnail('full' );  ?>
                                    <div class="nav">
                                        <label for="<?php echo $slider; ?>-eazy-css-slide-<?php print($slideid-1); ?>" class="prev">&#x2039;</label>
                                        <label for="<?php echo $slider; ?>-eazy-css-slide-<?php print($slideid+1); ?>" class="next">&#x203a;</label>
                                    </div>
                                <?php endif; ?>
                        </div>

                <?php endwhile; ?>
            </form>
   
        
    <?php

       wp_reset_postdata();
        $myvariable = ob_get_clean();
        return $myvariable;
    }
}
