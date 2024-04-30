<?php
add_action( 'wp_ajax_simpl_ajax_submit_form', 'simpl_acf_ajax_submit_form' );
add_action( 'wp_ajax_nopriv_simpl_ajax_submit_form', 'simpl_acf_ajax_submit_form' );

function simpl_acf_ajax_submit_form() {
  if ( class_exists( 'acfe_form_front' ) || class_exists( 'acfe_module_form_front' ) ) :

    if ( class_exists( 'acfe_module_form_front' ) ): // 0.9 support
      $form = new acfe_module_form_front;
    elseif ( class_exists( 'acfe_form_front' ) ): // legacy support
      $form = new acfe_form_front;
    endif;
    
    $form->save_post();
    wp_send_json( ['success' => true, 'message' => 'Your form has been submitted.'] );
  else:
    wp_send_json( ['success' => false, 'message' => 'acfe_form_front class does not exist.'], 500 );
  endif;
}
