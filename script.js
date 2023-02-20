/* eslint-disable */
( function( $ ) {
  if ( typeof acf !== 'undefined' ) {

    acf.addAction( 'validation_success', ( $el, json ) => {

      const $formEl = $( $el );

      /**
       * Custom class set on the form just to ensure I'm doing what I want 
       * on the correct form. You can validate this however you want, or 
       * even remove it. This is just personal preference.
       */

      if ( ! $formEl.hasClass( 'force-ajax-submission' ) ) return;

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

        const data = acf.serialize( $formEl );

        /**
         * Your custom action will do all the submission similar to
         * how ACF already does it, work smarter, not harder.
         */
        data.action = 'simpl_ajax_submit_form';

        $.ajax( {
          url: acf.get( 'ajaxurl' ),
          data: acf.prepareForAjax( data ),
          type: 'post',
          dataType: 'json',
          error( jqXHR, textStatus, errorThrown ) {
            const data = jqXHR.responseJSON;

            alert( `OH NO! ${acf.maybe_get( data, 'message' )}` );

            acf.unlockForm( $formEl ); // Reset the ACF form back to normal.
          },
          success( data, textStatus, jqXHR ) {
            if ( data.success === true ) {
              // Your custom success stuff will go here
              alert( `YAY! ${acf.maybe_get( data, 'message' )}` ); 
            } else {
              // Errors should be caught before this point, but you never know
              alert( `OH NO! ${acf.maybe_get( data, 'message' )}` ); 
            };
          },
          complete( jqXHR, textStatus ) {
            acf.unlockForm( $formEl ); // Reset the ACF form back to normal.
          }
        } ); // End $.ajax

      } ); // End submit listener

    } ); // End validation_success hook
  } // End acf check
})( jQuery );
