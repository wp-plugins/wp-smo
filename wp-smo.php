<?php
/**
Plugin Name: WP SMO
Plugin URI: http://tonyarchambeau.com/
Description: Add some meta data to improve the Social Media Optimization (SMO) of your website/blog.
Version: 1.0.0
Author: Tony Archambeau
Author URI: http://tonyarchambeau.com/
Text Domain: wp-mo
Domain Path: /languages

Copyright 2013 Tony Archambeau
*/


load_plugin_textdomain( 'wp_smo', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );



/***************************************************************
 * Define
 ***************************************************************/

if ( !defined('WPSMO_USER_NAME') )       define('WPSMO_USER_NAME', basename(dirname(__FILE__)) );
if ( !defined('WPSMO_USER_PLUGIN_DIR') ) define('WPSMO_USER_PLUGIN_DIR', WP_PLUGIN_DIR .'/'. WPSMO_USER_NAME );
if ( !defined('WPSMO_USER_PLUGIN_URL') ) define('WPSMO_USER_PLUGIN_URL', WP_PLUGIN_URL .'/'. WPSMO_USER_NAME );



/***************************************************************
 * Install and uninstall
 ***************************************************************/


/**
 * Hooks for install
 */
if (function_exists('register_uninstall_hook')) {
  register_deactivation_hook(__FILE__, 'wpsmo_uninstall');
}


/**
 * Hooks for uninstall
 */
if( function_exists('register_activation_hook')){
  register_activation_hook(__FILE__, 'wpsmo_install');
}


/**
 * Install this plugin
 */
function wpsmo_install() {
  // nothing to do
}


/**
 * Uninstall this plugin
 */
function wpsmo_uninstall() {
  // nothing to do
  // do not delete the data to restore them if necessary
}



/***************************************************************
 * Menu + settings page
 ***************************************************************/

/**
 * Add menu on the Back-Office for the plugin
 */
function wpsmo_add_options_page() {
  $page_title = __('WP SMO', 'wp_smo');
  $menu_title = __('WP SMO', 'wp_smo');
  $capability = 'administrator';
  $menu_slug = 'wp_smo';
  $function = 'wpsmo_settings_page'; // function that contain the page
  add_options_page( $page_title, $menu_title, $capability, $menu_slug, $function );
}
add_action('admin_menu', 'wpsmo_add_options_page');


/**
 * Add the settings page
 * 
 * @return boolean
 */
function wpsmo_settings_page() {
  $path = trailingslashit(dirname(__FILE__));
  
  if (!file_exists( $path . 'settings.php')) {
    return false;
  }
  require_once($path . 'settings.php');
}



/**
 * Manage the option when we submit the form
 */
function wpsmo_save_settings() {
	register_setting( 'wp-smo', 'wpsmo_twitter_site' ); 
} 
add_action( 'admin_init', 'wpsmo_save_settings' );



/***************************************************************
 * Add the DONATE link
 ***************************************************************/


/**
 * Additional links on the plugin page
 *
 * @param array $links
 * @param str $file
 */
function wpsmo_plugin_row_meta($links, $file) {
  if ($file == plugin_basename(__FILE__)) {
    $settings_page = 'wp_smo';
    $links[] = '<a href="options-general.php?page=' . $settings_page .'">' . __('Settings','wp_smo') . '</a>';
    $links[] = '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=FQKK22PPR3EJE&lc=GB&item_name=WP%20Sitemap%20Page&item_number=wp%2dsitemap%2dpage&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donate_LG%2egif%3aNonHosted">'.__('Donate', 'wp_smo').'</a>';
  }
  return $links;
}
add_filter('plugin_row_meta', 'wpsmo_plugin_row_meta',10,2);



/***************************************************************
 * Global functions
 ***************************************************************/

/**
 * Truncate a string
 * 
 * @param str $text string to truncate
 * @param int $size_max limit size of the string
 */
function wpsmo_truncate( $text, $size_max=100 ) {
  if (strlen($text) > $size_max) {
    $text = substr($text, 0, $size_max);
    $last_space = strrpos($texte, ' ');
    $text = substr($text, 0, $last_space).'&hellip;';
  }
  return $texte;
}



/***************************************************************
 * META BOX
 ***************************************************************/

/**
 * Add some CSS in the header of the admin style without using a CSS file.
 */
function wpsmo_admin_head_func(){
  ?>
  <style type="text/css">
  input[type="text"].background_gray{
    background-color:#bbb;
  }
  .legend-count-characters{
    line-height:23px;
  }
  </style>
  <?php
}
add_action('admin_head', 'wpsmo_admin_head_func');


/**
 * Adds a box to the main column on the Post and Page edit screens.
 */
function wpsmo_add_meta_box_func() {
  
  $post_types = array( 'post', 'page' );
  
  foreach ( $post_types as $post_type ) {
    
    add_meta_box(
      'wpsmo_id',
     __('WP SMO', 'wp_smo'),
      'wpsmo_meta_box_func',
      $post_type
      );
  }
}
add_action( 'add_meta_boxes', 'wpsmo_add_meta_box_func' );


/**
 * Add meta box to edit the SMO data
 * 
 * @param object $post content the post data
 */
function wpsmo_meta_box_func( $post ) {
  
  // Add an nonce field so we can check for it later.
  wp_nonce_field( 'wpsmo_meta_box_field', 'wpsmo_meta_box_field_nonce' );

  /*
   * Use get_post_meta() to retrieve an existing value
   * from the database and use the value for the form.
   */
  $wpsmo_og_title            = get_post_meta( $post->ID, 'wpsmo_og_title', true );
  $wpsmo_og_description      = get_post_meta( $post->ID, 'wpsmo_og_description', true );
  $wpsmo_twitter_title       = get_post_meta( $post->ID, 'wpsmo_twitter_title', true );
  $wpsmo_twitter_description = get_post_meta( $post->ID, 'wpsmo_twitter_description', true );
  $wpsmo_twitter_author      = get_post_meta( $post->ID, 'wpsmo_twitter_author', true );
  ?>
  <script type="text/javascript">
  function compter(f1, f1_len) {
    var txt1 = f1.value;
    var nb1 = txt1.length;
    f1_len.value = nb1+1;
  }
  </script>
  
  <table class="form-table">
    <tbody>
      <tr valign="top">
        <th scope="row"><label for="wpsmo_og_title"><?php _e( 'OG title', 'wp_smo' ); ?></label></th>
        <td>
		  <input type="text" name="wpsmo_og_title" id="wpsmo_og_title" 
		    onkeypress="compter(this.form.wpsmo_og_title, this.form.wpsmo_og_title_len)" 
		    value="<?php echo esc_attr( $wpsmo_og_title ); ?>" size="50" /><br />
		  <input class="background_gray" type="text" name="wpsmo_og_title_len" size="3" />
		  <span class="legend-count-characters"><?php _e('characters.', 'wp_smo'); ?></span>
		</td>
      </tr>
      <tr valign="top">
        <th scope="row"><label for="wpsmo_og_description"><?php _e( 'OG description', 'wp_smo' ); ?></label></th>
        <td>
		  <textarea name="wpsmo_og_description" id="wpsmo_og_description" 
		    onkeypress="compter(this.form.wpsmo_og_description, this.form.wpsmo_og_description_len)" 
		    rows="2" cols="50" 
		    class="large-text code"><?php echo $wpsmo_og_description; ?></textarea><br />
		  <input class="background_gray" type="text" name="wpsmo_og_description_len" size="3" />
		  <span class="legend-count-characters"><?php _e('characters. Use a maximum of 200 characters for most of the social media websites.', 'wp_smo'); ?></span>
		</td>
      </tr>
      <tr valign="top">
        <th scope="row"><label for="wpsmo_twitter_title"><?php _e( 'Twitter title', 'wp_smo' ); ?></label></th>
        <td>
		  <input type="text" id="wpsmo_twitter_title" name="wpsmo_twitter_title" 
		    onkeypress="compter(this.form.wpsmo_twitter_title, this.form.wpsmo_twitter_title_len)" 
		    value="<?php echo esc_attr( $wpsmo_twitter_title ); ?>" size="50" /><br />
		  <input class="background_gray" type="text" name="wpsmo_twitter_title_len" size="3" />
		  <span class="legend-count-characters"><?php _e('characters.', 'wp_smo'); ?></span>
		</td>
      </tr>
      <tr valign="top">
        <th scope="row"><label for="wpsmo_twitter_description"><?php _e( 'Twitter description', 'wp_smo' ); ?></label></th>
        <td>
		  <textarea name="wpsmo_twitter_description" id="wpsmo_twitter_description" 
		    onkeypress="compter(this.form.wpsmo_twitter_description, this.form.wpsmo_twitter_description_len)" 
		    rows="2" cols="50"
            class="large-text code"><?php echo $wpsmo_twitter_description; ?></textarea><br />
		  <input class="background_gray" type="text" name="wpsmo_twitter_description_len" size="3" />
		  <span class="legend-count-characters"><?php _e('characters. Use a maximum of 200 characters for most of the social media websites.', 'wp_smo'); ?></span>
		</td>
      </tr>
      <tr valign="top">
        <th scope="row"><label for="wpsmo_twitter_author"><?php _e( 'Twitter author', 'wp_smo' ); ?></label></th>
        <td>
		  <input type="text" id="wpsmo_twitter_author" name="wpsmo_twitter_author" 
		    value="<?php echo esc_attr( $wpsmo_twitter_author ); ?>" 
		    placeholder="<?php _e('example: @twitter', 'wp_smo'); ?>" />
		</td>
      </tr>
    </tbody>
  </table>
  
  
  <?php
}


/**
 * When the post is saved, saves our custom data.
 * 
 * @param int $post_id The ID of the post being saved.
 */
function wpsmo_meta_box_save_func( $post_id ) {
  
  /*
   * We need to verify this came from the our screen and with proper authorization,
   * because save_post can be triggered at other times.
   */
  
  // Check if our nonce is set.
  if ( !isset($_POST['wpsmo_meta_box_field_nonce']) ) {
    return $post_id;
  }
  
  $nonce = $_POST['wpsmo_meta_box_field_nonce'];

  // Verify that the nonce is valid.
  if ( !wp_verify_nonce( $nonce, 'wpsmo_meta_box_field' ) ) {
    return $post_id;
  }
  
  // If this is an autosave, our form has not been submitted, so we don't want to do anything.
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
    return $post_id;
  }
  
  // Check the user's permissions.
  if ( 'page' == $_POST['post_type'] ) {
    if ( !current_user_can( 'edit_page', $post_id ) ) {
      return $post_id;
    }
  } else {
    if ( !current_user_can( 'edit_post', $post_id ) ) {
      return $post_id;
    }
  }
  
  /* OK, its safe for us to save the data now. */
  
  // Sanitize user input.
  $wpsmo_og_title            = sanitize_text_field( $_POST['wpsmo_og_title'] );
  $wpsmo_og_description      = sanitize_text_field( $_POST['wpsmo_og_description'] );
  $wpsmo_twitter_title       = sanitize_text_field( $_POST['wpsmo_twitter_title'] );
  $wpsmo_twitter_description = sanitize_text_field( $_POST['wpsmo_twitter_description'] );
  $wpsmo_twitter_author      = sanitize_text_field( $_POST['wpsmo_twitter_author'] );

  // Update the meta field in the database.
  update_post_meta( $post_id, 'wpsmo_og_title',            $wpsmo_og_title );
  update_post_meta( $post_id, 'wpsmo_og_description',      $wpsmo_og_description );
  update_post_meta( $post_id, 'wpsmo_twitter_title',       $wpsmo_twitter_title );
  update_post_meta( $post_id, 'wpsmo_twitter_description', $wpsmo_twitter_description );
  update_post_meta( $post_id, 'wpsmo_twitter_author',      $wpsmo_twitter_author );
  
}
add_action( 'save_post', 'wpsmo_meta_box_save_func' );



/***************************************************************
 * Add the social data easily
 ***************************************************************/


/**
 * Add the social data in the HTML head
 * 
 */
function wpsmo_header_func() {
  
  // make sure the page is one of these: a post / a page / the front page / the home of the blog
  if ( !is_single() && !is_page() && !is_front_page() && !is_home() ) {
	return '';
  }
  
  // get the thumbnail
  $size = array(50,50);
  $image_url = wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) );
  
  // get the excerpt + content
  $post = get_post();
  
  // Here are the limits :
  // - Google+ og meta description : 200 characters
  // - Linkedin og meta description : 225 characters
  // - Facebook og meta description : 300 characters
  // - Twitter description : 200 characters
  $excerpt = apply_filters( 'get_the_excerpt', $post->post_excerpt );
  $content = apply_filters( 'get_the_content', $post->post_content );
  
  // clean the data
  $excerpt = htmlentities(strip_shortcodes(strip_tags($excerpt)));
  $content = htmlentities(strip_shortcodes(strip_tags($content)));
  
  // decide what should be the description
  $normal_description = (!empty($excerpt) ? $excerpt : (!empty($content) ? $content : get_bloginfo('description')));
  
  // get the twitter account of this website
  $wpsmo_twitter_site        = get_option('wpsmo_twitter_site');
  
  // by priority : try to use the customed data, if it does not exist, take the data of this page/post/CPT
  $wpsmo_og_title            = trim(get_post_meta( $post->ID, 'wpsmo_og_title', true ));
  $wpsmo_og_description      = trim(get_post_meta( $post->ID, 'wpsmo_og_description', true ));
  $wpsmo_twitter_title       = trim(get_post_meta( $post->ID, 'wpsmo_twitter_title', true ));
  $wpsmo_twitter_description = trim(get_post_meta( $post->ID, 'wpsmo_twitter_description', true ));
  $wpsmo_twitter_author      = trim(get_post_meta( $post->ID, 'wpsmo_twitter_author', true ));
  
  // if the data are empty : use the default value
  $wpsmo_og_title            = esc_attr(!empty($wpsmo_og_title)            ? $wpsmo_og_title            : get_the_title() );
  $wpsmo_og_description      = esc_attr(!empty($wpsmo_og_description)      ? $wpsmo_og_description      : $normal_description);
  $wpsmo_twitter_title       = esc_attr(!empty($wpsmo_twitter_title)       ? $wpsmo_twitter_title       : get_the_title() );
  $wpsmo_twitter_description = esc_attr(!empty($wpsmo_twitter_description) ? $wpsmo_twitter_description : $normal_description);
  $wpsmo_twitter_author      = esc_attr($wpsmo_twitter_author);
  
  // OG meta data
  echo '<meta property="og:title" content="' . $wpsmo_og_title . '" />' . "\n";
  
  if ( is_front_page() && is_home() ) {
    // Type for default homepage : website
    echo '<meta property="og:type" content="website" />' . "\n";
  } elseif ( is_front_page()) {
    // Type for static homepage : website
    echo '<meta property="og:type" content="website" />' . "\n";
  } elseif ( is_home()) {
    // Type for blog page : blog
    echo '<meta property="og:type" content="blog" />' . "\n";
  } else {
    // Type for everything else : article
    echo '<meta property="og:type" content="article" />' . "\n";
  }
  
  echo '<meta property="og:url" content="' . get_permalink() . '" />' . "\n";
  echo '<meta property="og:description" content="' . $wpsmo_og_description . '" />' . "\n";
  echo '<meta property="og:site_name" content="' . get_bloginfo('name') . '" />' . "\n";
  echo '<meta property="og:locale" content="' . WPLANG . '" />' . "\n";
  if (!empty($image_url)) {
    echo '<meta property="og:image" content="' . $image_url . '" />' . "\n";
  }
  
  // Twitter meta data
  echo '<meta name="twitter:card" content="summary" />' . "\n";
  if (!empty($wpsmo_twitter_site)) {
    echo '<meta name="twitter:site" content="'.$wpsmo_twitter_site.'" />' . "\n";
  }
  // @TODO : evolve in including this : echo '<meta name="twitter:creator" content="@twitter" />' . "\n";
  echo '<meta name="twitter:title" content="' . $wpsmo_twitter_title . '" />' . "\n";
  echo '<meta name="twitter:description" content="' . $wpsmo_twitter_description . '" />' . "\n";
  if (!empty($image_url)) {
    echo '<meta name="twitter:image" content="' . $image_url . '" />' . "\n";
  }
}
add_action('wp_head', 'wpsmo_header_func');


