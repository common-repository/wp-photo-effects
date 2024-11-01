<?php
/**
* Plugin Name: WP Photo Effects
* Description: Apply Beautiful Effects on Images By Using Shortcode
* Version: 1.2.4
* Author: Muhammad Rehman
* Author URI: http://muhammadrehman.com/
* License: GPLv2 or later
*/

// Includes css files of libraries
function wppe_theme_name_scripts() {
    wp_enqueue_style( 'style-demo', plugins_url( '/css/demo.css' , __FILE__ ) );
    wp_enqueue_style( 'style-normalize', plugins_url( '/css/normalize.css' , __FILE__ ) );
    wp_enqueue_style( 'style-tilteffect', plugins_url( '/css/tilteffect.css' , __FILE__ ) );
}
add_action( 'wp_enqueue_scripts', 'wppe_theme_name_scripts' );

// Include tilt js
add_action('wp_footer','wppe_tiltjs');

function wppe_tiltjs() {
    wp_enqueue_script( 'script-tiltfx', plugins_url( '/js/tiltfx.js' , __FILE__ ) );
    wp_enqueue_script( 'wppe-script', plugins_url( '/js/script.js' , __FILE__ ) );
}

function wppe_effect( $atts ) {

    // shortcode default attributes
    $a = shortcode_atts( array(
        'url' => plugins_url( '/sample-image/default.jpg' , __FILE__ ),
        'effect' => 'one',
        'grayscale' => 'no',
        'move_x' => '-15',
        'move_y' => '-15',
        'move_z' => '20',
        'rotate_x' => '15',
        'rotate_y' => '15',
        'rotate_z'  => '10',
        'opacity'  => '0.5',
        'extra_imgs' => '4',
        'overflow'  => 'visible',
        'width' => '300px',
        'height' => '300px',
        'fitscreen' => 'no',
        'radius' => '',
        'link' => '#',
        'border' => '1px',
        'target' => '_self'
    ), $atts );

    // Default tilt class
    $class = 'tilt-effect';

    // Apply specific class for grayscale
    if($a['grayscale'] == 'yes' )
        $filter_effect = 'grid__img--example-3';
    else
        $filter_effect = 'grid__img--border';

    $move_x = $a['move_x'];
    $move_y = $a['move_y'];
    $move_z = $a['move_z'];
    $rotate_x = $a['rotate_x'];
    $rotate_y = $a['rotate_y'];
    $rotate_z = $a['rotate_z'];
    $opacity = $a['opacity'];
    $extraImgs = $a['extra_imgs'];
    $overflow = 'overflow:'.$a['overflow'].';';
    $width = 'width:'.$a['width'].';';
    $height = 'height:'.$a['height'];
    $fitscreen = $a['fitscreen'];
    $radius = $a['radius'];
    $link = $a['link'];
    $border = 'solid '.$a['border'].' #000 !important';
    $target = $a['target'];

    // Add random id for adjust fit screen to specific image
    $rand_id = 'rand-'.rand(100,99999);

    // Apply some style for fitscreen image
    if($fitscreen == 'yes') {
        ?>
        <style>
            <?php echo '.'.intval( $rand_id ).' ' ?>.tilt__back,
            <?php echo '.'.intval( $rand_id ).' ' ?>.tilt__front {
                background-size: 100% 100%;
            }
        </style>
        <?php
    }

    // Apply radius effect on specific image
    if($radius != '') {
        ?>
        <style>
            <?php echo '.'.intval( $rand_id ).' ' ?>.tilt__back,
            <?php echo '.'.intval( $rand_id ).' ' ?>.tilt__front {
                border-radius: <?php echo esc_html( $radius )?>;
            }
        </style>
        <?php
    }

    ?>
    <style>
        <?php echo '.'.intval( $rand_id ).' ' ?>.tilt__back,
        <?php echo '.'.intval( $rand_id ).' ' ?>.tilt__front {
            border: <?php echo esc_html( $border );?>
        }
    </style>
    <?php


    // Use different effects
    if($a['effect'] == 'three') {
        $data_tilt = "data-tilt-options='{  \"extraImgs\" : $extraImgs, \"bgfixed\" : false, \"movement\": { \"perspective\" : 1000, \"translateX\" : $move_x, \"translateY\" : $move_y, \"translateZ\" : $move_z, \"rotateX\" : $rotate_x, \"rotateY\" : $rotate_y, \"rotateZ\" : $rotate_z } }'";
    } else if($a['effect'] == 'two') {
        $data_tilt = "data-tilt-options='{ \"extraImgs\" : $extraImgs, \"opacity\" : $opacity, \"movement\": { \"perspective\" : 500, \"translateX\" : $move_x, \"translateY\" : $move_y, \"translateZ\" : $move_z, \"rotateX\" : $rotate_x, \"rotateY\" : $rotate_y } }'";
    } else if($a['effect'] == 'one') {
        $data_tilt = "data-tilt-options='{ \"extraImgs\" : $extraImgs, \"opacity\" : $opacity, \"movement\": { \"perspective\" : 500, \"translateX\" : $move_x, \"translateY\" : $move_y, \"translateZ\" : $move_z, \"rotateX\" : $rotate_x, \"rotateY\" : $rotate_y } }'";
    }

    $img_url = $a['url']; // Image URL given by attribute
    $img_class = $a['effect']; // Apply effect given by attribute

    // Create HTML for displaying image with effect on frontend
    $img = '<ul class="grid grid--xray">
			 <li class="grid__item '.$rand_id.'" style="'.$width.'">
			  <div class="grid__img '.$filter_effect.'" style="'.$overflow.$width.$height.'">';
    if($link != '#')
        $img .= '<a href="'.$link.'" target="'.$target.'"><img src="'.$img_url.'" class="'.$class.'" '.$data_tilt.' /></a>';
    else if($link == '#')
        $img .= '<img src="'.$img_url.'" class="'.$class.'" '.$data_tilt.' />';
    $img .= '</div>
             </li>
            </ul>';

    return $img;
}

// shortcode to display images with some effects
add_shortcode( 'wppe_effect', 'wppe_effect' );
