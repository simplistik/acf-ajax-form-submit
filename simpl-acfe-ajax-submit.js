( function( $ ) {
  if ( typeof acf !== 'undefined' ) {
    acf.addAction( 'validation_success', ( $el, json ) => {

      const $formEl = $( $el );

      /**
       * Custom class set on the form just to ensure I'm doing what I want
       * on the correct form. You can validate this however you want, or
       * even remove it. This is just personal preference.
       */

      if ( ! $formEl.hasClass( simplAcfeAjaxConfig.jsClass ) ) return;


      /**
       * You could check for json.data.errors here if you're feelin froggy.
       */

      $formEl.on( 'submit', ( event ) => {
        event.preventDefault();

        /**
         * Forces the form to lock itself to prevent submissions. This happens automatically
         * during the first submission, but doesn't happen automatically after subsequent
         * submissions, which makes this redundant the first time, but doesn't impact performance.
         */
        acf.lockForm( $formEl );

        const data = new FormData( $formEl[0] );
        data.append( 'action', 'simpl_acfe_ajax_submit' );

        /**
         * Your custom action will do all the submission similar to
         * how ACF already does it, work smarter, not harder.
         */
        $.ajax( {
          url: acf.get( 'ajaxurl' ),
          data: data,
          type: 'post',
          dataType: 'json',
          processData: false,
          contentType: false,
          error( jqXHR, textStatus, errorThrown ) {
            const data = jqXHR.responseJSON;

            $( document ).trigger( 'simpl_ajax_submit_error', [ $formEl, data ] );

            acf.unlockForm( $formEl ); // Reset the ACF form back to normal.
          },
          success( data, textStatus, jqXHR ) {
            if ( data.success === true ) {

              $( document ).trigger( 'simpl_ajax_submit_success', [ $formEl, data ] );

            } else {

              $( document ).trigger( 'simpl_ajax_submit_failure', [ $formEl, data ] );

            };
          },
          complete( jqXHR, textStatus ) {
            acf.unlockForm( $formEl ); // Reset the ACF form back to normal.
          }
        } ); // End $.ajax

      } ); // End submit listener

    } ); // End validation_success hook
  } // End acf check

  // Example of how to use the simpl_ajax_submit_success action.
  // $( document ).on( 'simpl_ajax_submit_success', ( event, $formEl, data, options ) => {
  //   $formEl.prev('.simpl-ajax-success').remove();
  //   $formEl.before( `<div class="simpl-ajax-success">${data.message}</div>` );

  //   if ( data.hide_form ) $formEl.remove();
  // } );

  // Example of how to use the simpl_acfe_ajax_submit action.
  // $( document ).on( 'simpl_ajax_submit_error', ( event, $formEl, data, options ) => {
  //   console.log( 'simpl_ajax_submit_error', event, $formEl, data );
  // } );

  // Example of how to use the simpl_ajax_submit_failure action.
  // $( document ).on( 'simpl_ajax_submit_failure', ( event, $formEl, data, options ) => {
  //   console.log( 'simpl_ajax_submit_failure', event, $formEl, data );
  // } );
})( jQuery );
