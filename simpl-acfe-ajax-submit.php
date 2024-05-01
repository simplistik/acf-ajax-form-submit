<?php
/**
 * Plugin Name: ACF/ACFE AJAX Form Submit
 * Plugin URI:  https://github.com/simplistik/acf-ajax-form-submit
 * Description: Handles AJAX submissions using Advanced Custom Fields & Advanced Custom Fields Enhanced.
 * Version:     2.0.0
 * Author:      Lucas Williams
 * Author URI:  https://taproot.agency
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: simpl-acfe-ajax-submit
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly

class Simpl_ACFE_Ajax_Submit
{
  private static $instance = null;

  public function __construct() {
    add_action( 'wp_ajax_simpl_acfe_ajax_submit', [$this, 'submit'] );
    add_action( 'wp_ajax_nopriv_simpl_acfe_ajax_submit', [$this, 'submit'] );

    add_action( 'wp_enqueue_scripts', [$this, 'enqueue_scripts'] );

    add_filter( 'script_loader_tag', [$this, 'script_loader_tag'], 10, 2 );
    add_filter( 'acfe/module/register_field_groups/module=form', [$this, 'register_field_groups'], 20, 2 );

    add_action( 'acfe/form/load_form', [$this, 'form_render_before_form'], 20 );
  }

  /**
   * Enqueues scripts for the plugin.
   *
   * This method enqueues the 'simpl-acfe-ajax-submit.js' script file and adds 'defer' attribute to it.
   * The script file, directory URL, directory path, and dependencies can be modified using the 'simpl/acfe-ajax/file',
   * 'simpl/acfe-ajax/dir_url', 'simpl/acfe-ajax/dir_path', and 'simpl/acfe-ajax/dependencies' filters respectively.
   *
   * @return void
   */
  public function enqueue_scripts() {
    $file         = apply_filters( 'simpl/acfe-ajax/file', 'simpl-acfe-ajax-submit.js' );
    $dir_url      = apply_filters( 'simpl/acfe-ajax/dir_url', plugin_dir_url( __FILE__ ) );
    $dir_path     = apply_filters( 'simpl/acfe-ajax/dir_path', plugin_dir_path( __FILE__ ) );
    $dependencies = apply_filters( 'simpl/acfe-ajax/dependencies', ['jquery'] );

    wp_enqueue_script(
      'simpl-acfe-ajax-submit',
      $dir_url . $file,
      $dependencies,
      $this->file_modified_date( $dir_path . $file ),
      true
    );

    $args = wp_json_encode( apply_filters( 'simpl/acfe-ajax/js_config', [
      'jsClass' => $this->set_ajax_class(),
    ] ) );

    wp_add_inline_script( 'simpl-acfe-ajax-submit', sprintf( 'const simplAcfeAjaxConfig = %s', $args ), 'before' );
    wp_script_add_data( 'simpl-acfe-ajax-submit', 'simpl_script_execution', 'defer' );
  }

  /**
   * Modifies form attributes before rendering.
   *
   * This function checks if the 'simpl_ajax_submit' attribute is set in the form. If it is, it appends the AJAX class to the form's class attribute.
   *
   * @param array $form An associative array containing form attributes.
   *
   * @return array The modified form attributes.
   */
  public function form_render_before_form( $form ) {
    if ( empty( $form['attributes']['simpl_ajax_submit'] ) ) return $form;

    $form['attributes']['form']['class'] = $form['attributes']['form']['class'] . ' ' . $this->set_ajax_class();

    return $form;
  }

  /**
   * Retrieves the instance of the class.
   *
   * This method checks if an instance of the class already exists. If it does not, it creates a new instance.
   * It then returns the instance.
   *
   * @return self The instance of the class.
   */
  public static function get_instance() {
    if ( null === self::$instance ):
      self::$instance = new self();
    endif;

    return self::$instance;
  }

  /**
   * Registers field groups.
   *
   * This function adds a new field to the first field group if a field with key 'field_submit' is found.
   * The new field is of type 'true_false' and is used to determine whether to submit the form with AJAX.
   *
   * @param array $field_groups An array of field groups. Each field group is an associative array containing a 'fields' key, which is an array of fields.
   * @param mixed $module       The module associated with the field groups.
   *
   * @return array The modified field groups.
   */
  public function register_field_groups( $field_groups, $module ) {
    $default_fields = &$field_groups[0]['fields'];

    $new_field = [
      'key'               => 'field_simpl_ajax_submit',
      'label'             => __( 'Enable AJAX Submission', 'simpl' ),
      'name'              => 'simpl_ajax_submit',
      'type'              => 'true_false',
      'instructions'      => __( 'Whether or not to submit this form with AJAX. Defaults to false', 'simpl' ),
      'required'          => 0,
      'conditional_logic' => [
        [
          [
            'field'    => 'field_submit',
            'operator' => '==',
            'value'    => '1',
          ]
        ]
      ],
      'wrapper'           => [
        'width' => '',
        'class' => '',
        'id'    => '',
      ],
      'message'       => '',
      'default_value' => 0,
      'ui'            => 1,
      'ui_on_text'    => '',
      'ui_off_text'   => '',
      'group_with'    => 'attributes',
    ];

    foreach ( $default_fields as $index => $field ):
      if ( $field['key'] === 'field_submit' ):
        array_splice( $default_fields, $index + 1, 0, [$new_field] );

        break;
      endif;
    endforeach;

    return $field_groups;
  }

  /**
   * Modifies the script tag for async or defer loading.
   *
   * This method checks if the 'simpl_script_execution' data is set for the given handle.
   * If it is, and it's set to 'async' or 'defer', it modifies the script tag to include the respective attribute.
   * However, if the script has dependencies, it doesn't modify the tag.
   *
   * @param  string $tag    The `<script>` tag for the enqueued script.
   * @param  string $handle The script's registered handle.
   * @return string The modified `<script>` tag.
   */
  public function script_loader_tag( $tag, $handle ) {
    $script_execution = wp_scripts()->get_data( $handle, 'simpl_script_execution' );

    if ( !$script_execution ) return $tag;

    if ( 'async' !== $script_execution && 'defer' !== $script_execution ) return $tag;

    foreach ( wp_scripts()->registered as $script ):
      if ( in_array( $handle, $script->deps, true ) ) return $tag;
    endforeach;

    if ( !preg_match( ":\s$script_execution(=|>|\s):", $tag ) )
      $tag = preg_replace( ':(?=></script>):', " $script_execution", $tag, 1 );

    return $tag;
  }

  /**
   * Handles the AJAX form submission.
   *
   * This method checks if the 'acfe_form_front' or 'acfe_module_form_front' classes exist.
   * If they do, it creates an instance of the appropriate class and calls the 'save_post' method.
   * It then sends a JSON response with a success message.
   * If the classes do not exist, it sends a JSON response with an error message.
   *
   * @return void
   */
  public function submit() {
    if ( !wp_doing_ajax() ) return;

    // Check if either 'acfe_form_front' or 'acfe_module_form_front' classes exist
    if ( class_exists( 'acfe_form_front' ) || class_exists( 'acfe_module_form_front' ) ):

      // Get an instance of the appropriate class
      $form = class_exists( 'acfe_module_form_front' ) ? acf_get_instance( 'acfe_module_form_front' ) : acf_get_instance( 'acfe_form_front' );

      // Get the form submission data
      $form_data = $form->get_form_data();

      // Save the form post data
      $form->save_post();

      // Send a JSON response with the success message and form data, after applying the 'simpl/acfe-ajax/success' filter
      wp_send_json( [
        'success'   => true,
        'message'   => apply_filters( 'simpl/acfe-ajax/success', sprintf( $form_data['success']['wrapper'], $form_data['success']['message'] ), $form_data ),
        'hide_form' => $form_data['success']['hide_form']
      ] );
    else:

      // If the classes do not exist, send a JSON response with an error message, after applying the 'simpl/acfe-ajax/fail' filter
      wp_send_json( [
        'success' => false,
        'message' => apply_filters( 'simpl/acfe-ajax/fail', 'acfe_form_front class does not exist.' )
      ], 500 );
    endif;
  }

  /**
   * Gets the modified date of a file.
   *
   * This method checks if the given file exists. If it does, it returns the MD5 hash of the file's modified time.
   * If the file does not exist, it returns false.
   *
   * @param  string      $file The path to the file.
   * @return string|bool The MD5 hash of the file's modified time, or false if the file does not exist.
   */
  private function file_modified_date( $file ) {
    if ( file_exists( $file ) )
      return md5( filemtime( $file ) );

    return false;
  }

  /**
   * Sets the AJAX class.
   *
   * This function applies a filter to the 'simpl/acfe-ajax/class' hook and returns the class name for AJAX submission.
   *
   * @return string The class name for AJAX submission.
   */
  private function set_ajax_class() {
    return apply_filters( 'simpl/acfe-ajax/class', 'simpl-ajax-submit' );
  }
}

// Instantiate the class
Simpl_ACFE_Ajax_Submit::get_instance();