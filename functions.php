<?php

// Defines
define( 'FL_CHILD_THEME_DIR', get_stylesheet_directory() );
define( 'FL_CHILD_THEME_URL', get_stylesheet_directory_uri() );

// Classes
require_once 'classes/class-fl-child-theme.php';

// Actions
add_action( 'wp_enqueue_scripts', 'FLChildTheme::enqueue_scripts', 1000 );

//* Enqueue scripts and styles
add_action( 'wp_enqueue_scripts', 'parallax_enqueue_scripts_styles', 1000 );
function parallax_enqueue_scripts_styles() {
// Styles
wp_enqueue_style( 'custom-style', get_stylesheet_directory_uri() . '/style.css', array() );
wp_enqueue_style( 'pathway-google-fonts', 'https://fonts.googleapis.com/css2?family=Pathway+Gothic+One&display=swap', array() );
wp_enqueue_style( 'rubik-google-fonts', 'https://fonts.googleapis.com/css2?family=Rubik&display=swap', array() );

// Scripts
wp_enqueue_script( 'custom-script', get_stylesheet_directory_uri() . '/js/scripts.js', array() );
}

// Removes Query Strings from scripts and styles
function remove_script_version( $src ){
    if ( strpos( $src, 'uploads/bb-plugin' ) !== false || strpos( $src, 'uploads/bb-theme' ) !== false ) {
      return $src;
    }
    else {
      $parts = explode( '?ver', $src );
      return $parts[0];
    }
  }
  add_filter( 'script_loader_src', 'remove_script_version', 15, 1 );
  add_filter( 'style_loader_src', 'remove_script_version', 15, 1 );
  
  // Add Additional Image Sizes
  add_image_size( 'news-thumb', 260, 150, false );
  add_image_size( 'news-full', 800, 300, false );
  add_image_size( 'mailchimp', 564, 9999, false );
  add_image_size( 'amp', 600, 9999, false );
  add_image_size( 'home-news', 350, 171, true );
  add_image_size( 'subpage-header', 536, 221, true );
  add_image_size( 'service-full', 473, 444, true );
  add_image_size( 'woo-single-product', 700, 700, true );
  add_image_size( 'woo-thumb-product', 225, 225, true );
  
  // Gravity Forms confirmation anchor on all forms
  add_filter( 'gform_confirmation_anchor', '__return_true' );
  
  //Sets the number of revisions for all post types
  add_filter( 'wp_revisions_to_keep', 'revisions_count', 10, 2 );
  function revisions_count( $num, $post ) {
      $num = 3;
      return $num;
  }

  
  // Enable Featured Images in RSS Feed and apply Custom image size so it doesn't generate large images in emails
  function featuredtoRSS($content) {
  global $post;
  if ( has_post_thumbnail( $post->ID ) ){
  $content = '<div>' . get_the_post_thumbnail( $post->ID, 'mailchimp', array( 'style' => 'margin-bottom: 15px;' ) ) . '</div>' . $content;
  }
  return $content;
  }
   
  add_filter('the_excerpt_rss', 'featuredtoRSS');
  add_filter('the_content_feed', 'featuredtoRSS');



// Remove WooCommerce breadcrumbs 
add_action( 'init', 'remove_breadcrumbs' );
 
function remove_breadcrumbs() {
    remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
}


//Display full product description after price on single product page
function display_full_product_description_after_price() {
    // Check if it's a single product page
    if (is_product()) {
        global $product;

        // Get the product description
        $product_description = $product->get_description();

        // Display the full product description
        echo '<div class="full-description">' . wpautop($product_description) . '</div>';
    }
}

// Hook to display the full product description after the price
add_action('woocommerce_single_product_summary', 'display_full_product_description_after_price', 25);


//Remove product meta on single product page
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );


//Remove Product Tabs
add_filter( 'woocommerce_product_tabs', 'woo_remove_tabs', 98 );
function woo_remove_tabs( $tabs ){
    if(is_product()){
      unset( $tabs['description'] ); // Remove the description tab
      unset( $tabs['reviews'] ); // Remove the reviews tab
      unset( $tabs['additional_information'] ); // Remove the additional information tab
      }
  return $tabs;
}


//Show plus minus buttons to quantity field
add_action( 'woocommerce_after_quantity_input_field', 'woo_display_quantity_plus' );
  
function woo_display_quantity_plus() {
   echo '<button type="button" class="plus">+</button>';
}
  
add_action( 'woocommerce_before_quantity_input_field', 'woo_display_quantity_minus' );
  
function woo_display_quantity_minus() {
   echo '<button type="button" class="minus">-</button>';
}
  
//Trigger update quantity script
add_action( 'wp_footer', 'woo_add_cart_quantity_plus_minus' );
  
function woo_add_cart_quantity_plus_minus() {
 
   if ( ! is_product() && ! is_cart() ) return;
    
   wc_enqueue_js( "   
           
      $(document).on( 'click', 'button.plus, button.minus', function() {
  
         var qty = $( this ).parent( '.quantity' ).find( '.qty' );
         var val = parseFloat(qty.val());
         var max = parseFloat(qty.attr( 'max' ));
         var min = parseFloat(qty.attr( 'min' ));
         var step = parseFloat(qty.attr( 'step' ));
 
         if ( $( this ).is( '.plus' ) ) {
            if ( max && ( max <= val ) ) {
               qty.val( max ).change();
            } else {
               qty.val( val + step ).change();
            }
         } else {
            if ( min && ( min >= val ) ) {
               qty.val( min ).change();
            } else if ( val > 1 ) {
               qty.val( val - step ).change();
            }
         }
 
      });
        
   " );
}

//Change the number of related products
function woo_related_products_limit() {
    global $product;
    
    $args['posts_per_page'] = 3;
    return $args;
    }
    add_filter( 'woocommerce_output_related_products_args', 'woo_related_products_args', 20 );
    function woo_related_products_args( $args ) {
    $args['posts_per_page'] = 3; // 4 related products
    $args['columns'] = 3; // arranged in 2 columns
    return $args;
    }

// Change the WooCommerce "Related products" title
add_filter('gettext', 'change_relatedproducts_text', 10, 3);

function change_relatedproducts_text($new_text, $related_text, $source)
{
     if ($related_text === 'Related products' && $source === 'woocommerce') {
         $new_text = esc_html__('Other Cool Stuff', $source);
     }
     return $new_text;
}


//Remove Gutenberg Block Library CSS from loading on the frontend
function smartwp_remove_wp_block_library_css(){
    wp_dequeue_style( 'wp-block-library' );
    wp_dequeue_style( 'wp-block-library-theme' );
    wp_dequeue_style( 'wc-block-style' ); // Remove WooCommerce block CSS
} 
add_action( 'wp_enqueue_scripts', 'smartwp_remove_wp_block_library_css', 100 );

add_action( 'wp_enqueue_scripts', function() {
    wp_dequeue_style( 'font-awesome' ); // FontAwesome 4
    wp_enqueue_style( 'font-awesome-5' ); // FontAwesome 5

    //wp_dequeue_style( 'jquery-magnificpopup' );
    //wp_dequeue_script( 'jquery-magnificpopup' );

    wp_dequeue_script( 'bootstrap' );
//    wp_dequeue_script( 'imagesloaded' ); //Commented by Saqib on 11/16/21
    wp_dequeue_script( 'jquery-fitvids' );
//    wp_dequeue_script( 'jquery-throttle' ); //Commented by Saqib on 11/16/21
    wp_dequeue_script( 'jquery-waypoints' );
}, 9999 );

/* Site Optimization - Removing several assets from Home page that we dont need */

// Remove Assets from HOME page only
function remove_home_assets() {
    if (is_front_page()) {
        
    wp_dequeue_style('addtoany');
    wp_dequeue_style('yoast-seo-adminbar');
    wp_dequeue_style('font-awesome');
    wp_dequeue_style('jquery-magnificpopup');
    wp_dequeue_style('pp-animate');
    
    wp_dequeue_script('addtoany-core');
    wp_dequeue_script('addtoany-jquery');
    wp_dequeue_script('addtoany-core');
    wp_dequeue_script('jquery-magnificpopup');
    }
    
  };
  add_action( 'wp_enqueue_scripts', 'remove_home_assets', 9999 );
  
  
  //Removing unused Default Wordpress Emoji Script - Performance Enhancer
  function disable_emoji_dequeue_script() {
      wp_dequeue_script( 'emoji' );
  }
  add_action( 'wp_print_scripts', 'disable_emoji_dequeue_script', 100 );
  remove_action( 'wp_head', 'print_emoji_detection_script', 7 ); 
  remove_action( 'wp_print_styles', 'print_emoji_styles' );
  
  // Removes Emoji Scripts 
  add_action('init', 'remheadlink');
  function remheadlink() {
      remove_action('wp_head', 'rsd_link');
      remove_action('wp_head', 'wp_generator');
      remove_action('wp_head', 'index_rel_link');
      remove_action('wp_head', 'wlwmanifest_link');
      remove_action('wp_head', 'feed_links', 2);
      remove_action('wp_head', 'feed_links_extra', 3);
      remove_action('wp_head', 'parent_post_rel_link', 10, 0);
      remove_action('wp_head', 'start_post_rel_link', 10, 0);
      remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
      remove_action('wp_head', 'wp_shortlink_header', 10, 0);
      remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
  }