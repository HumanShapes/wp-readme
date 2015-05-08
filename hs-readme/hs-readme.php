<?php
/*
Plugin Name: HS Readme
Description: A custom documentation widget by Human Shapes.
Version: 1.0
Author URI: http://humanshapes.co
*/

// Add to settings menu
add_action('admin_menu', 'hs_create_menu');

function hs_create_menu() {
  add_options_page(
    'HS Readme Widget Settings', // Page Title
    'HS Readme', // Menu Title
    'manage_options',
    'readme-settings', // Menu Slug
    'hs_readme_settings_page'
  );

  add_action('admin_init', 'hs_register_readme_settings');
}

// Register settings
function hs_register_readme_settings() {
  register_setting( 'readme-settings-group', 'readme_title' );
  register_setting( 'readme-settings-group', 'readme_url' );
  register_setting( 'readme-settings-group', 'readme_height' );
}

// Build settings page
function hs_readme_settings_page() {
?>
<div class="wrap">
  <h2>HS Readme Widget Settings</h2>
  <form method="post" action="options.php">
    <?php settings_fields( 'readme-settings-group' ); ?>
    <?php do_settings_sections( 'readme-settings-group' ); ?>
    <table class="form-table">
      <tr valign="top">
        <th scope="row">Widget Title</th>
        <td><input type="text" name="readme_title" value="<?php echo esc_attr( get_option('readme_title') ); ?>" /></td>
      </tr>
      <tr valign="top">
        <th scope="row">Site URL</th>
        <td><input type="text" name="readme_url" value="<?php echo esc_attr( get_option('readme_url') ); ?>" /></td>
      </tr>
      <tr valign="top">
        <th scope="row">Height (default: 500)</th>
        <td><input type="text" name="readme_height" value="<?php echo esc_attr( get_option('readme_height') ); ?>" /></td>
      </tr>
    </table>
    <?php submit_button(); ?>
  </form>
</div>
<?php }

// Create the function to output the contents of our Dashboard Widget
function hs_widget_content() {
  $url = esc_attr( get_option('readme_url') );
  $height = esc_attr( get_option('readme_height') );
  if (!$height) {
    $height = 500;
  }
  echo '<iframe src="'. $url .'" width="100%" height="'. $height .'" frameBorder="0">Browser not compatible. Please visit: '. $url .'</iframe>';
}

// Create the function use in the action hook
function hs_add_readme_widget() {
  $title = esc_attr( get_option('readme_title') );
  wp_add_dashboard_widget('hs_readme_widget', $title, 'hs_widget_content');
}

// Hook into the 'wp_dashboard_setup' action to register our other functions
add_action('wp_dashboard_setup', 'hs_add_readme_widget' );

// Force Dashboard widgets to display full width
add_action( 'admin_head-index.php', function()
{
?>
<style>
.postbox-container {
    min-width: 100% !important;
}
</style>
<?php
});
