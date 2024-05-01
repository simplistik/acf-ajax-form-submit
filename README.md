# ACF/ACFE Front-End Form AJAX Submit
Enhances the submission process of Advanced Custom Fields (ACF) and ACF Extended (ACFE) front-end forms by replacing the standard page refresh method with a seamless AJAX submission. This plugin streamlines the user experience by eliminating the need for full page reloads after form submission, while still leveraging ACF's built-in validation and processing capabilities.

## Version 2
Version 2 introduces a comprehensive refactoring while preserving the core functionality of seamless AJAX form submissions for ACF and ACFE front-end forms. This update seamlessly integrates with the ACFE options, allowing you to configure most settings directly within the familiar ACFE interface. The only manual customization required is [tailoring the success message](#javascript-success-message) to your specific needs, ensuring a personalized experience for your users.

## Requirements
- Advanced Custom Fields (ACF): https://www.advancedcustomfields.com/
  - https://www.advancedcustomfields.com/resources/create-a-front-end-form/
- ACF Extended (ACFE): https://www.acf-extended.com/
  - https://www.acf-extended.com/features/modules/forms

## Usage
1. Install the plugin via the plugin installer by uploading a .zip file.
2. Activate the plugin.
3. Create your ACFE form.
4. Click the "Attributes" tab and turn on "Enable AJAX Submission".

## Hooks

`simpl/acfe-ajax/success`

- This filter allows you to modify the success message returned by the AJAX form submission.
  - The default message is controlled in the form settings click on the "Success" tab → "Success message".

#### Parameters:

- `$message` (string): The success message. By default, this is a formatted string that includes the success message from the form data.
- `$form_data` (array): An array containing the form data.

#### Usage:

You can use this filter in your theme or plugin to customize the success message. Here's an example:

```php
add_filter( 'simpl/acfe-ajax/success', function( $message, $form_data ) {
    // Customize the success message
    $message = 'Form submitted successfully. Thank you, ' . $form_data['name'] . '!';

    return $message;
}, 10, 2 );
```
#

`simpl/acfe-ajax/fail`

This filter allows you to modify the error message returned by the AJAX form submission when the `acfe_form_front` class does not exist.

#### Parameters:

- `$message` (string): The error message. By default, this is a string that says 'acfe_form_front class does not exist.'

#### Usage:

You can use this filter in your theme or plugin to customize the error message. Here's an example:

```php
add_filter( 'simpl/acfe-ajax/fail', function( $message ) {
  // Customize the error message
  $message = 'There was an error with the form submission. Please try again later.';

  return $message;
}, 10, 1 );
```
#

`simpl/acfe-ajax/class`

This filter allows you to modify the class name for AJAX submission.

#### Parameters:

- `$class_name` (string): The class name for AJAX submission. By default, this is 'simpl-ajax-submit'.

#### Usage:

You can use this filter in your theme or plugin to customize the class name. Here's an example:

```php
add_filter( 'simpl/acfe-ajax/class', function( $class_name ) {
  // Customize the class name
  $class_name = 'my-custom-ajax-class';

  return $class_name;
}, 10, 1 );
```

## JavaScript Success Message

`simpl_ajax_submit_success`

This event is triggered when an AJAX form submission is successful.

#### Parameters:

- `event` (Event): The event object.
- `$formEl` (jQuery object): The form element that was submitted.
- `data` (object): An object containing the response data from the server. This includes a `message` property with the success message and a `hide_form` property that indicates whether the form should be hidden after submission.
- `options` (object): An object containing options for the AJAX submission.

#### Usage:

You can listen for this event in your JavaScript code to perform actions after a successful form submission.

Here is the minimum that you should include in your scripts:

```javascript
  $( document ).on( 'simpl_ajax_submit_success', ( event, $formEl, data, options ) => {
    $formEl.prev( '.simpl-ajax-success' ).remove();
    $formEl.before( `<div class="simpl-ajax-success">${data.message}</div>` );

    if ( data.hide_form ) $formEl.remove();
  } );
```
** *This emulates the native ACF success message.*

## FAQ
**Q:** Can this be done w/o jQuery?

**A:** Yes, this could also be done with out jQuery. However, ACF and ACFE already have it queued up as a depenedency so I also use it for simplicity. When the day comes that they make the change, so will I.

## Change Log
### 2.0.1
- 05/01/2024 - Replace serialization with FormData.

### 2.0.0
- 05/01/2024 - Refactor. Tested with ACF 6.2.9 and ACFE 0.9.0.2.

### 1.0
- 04/30/2024 - Updated for compatibility with ACFE 0.9+. Tested with ACF 6.2.9 and ACFE 0.9.0.2.
- 02/20/2023 - Tested with ACF 6.0.7 and ACFE 0.8.9.2 🚀
