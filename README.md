# ACF Front-End Form AJAX Submit
Submit a WordPress ACF front end form via AJAX, instead of it's standard POST/page refresh method.

"Disables" the refresh logic that ACF front end forms uses in favor of a custom AJAX submission. This allows you to utilize all the checks and validation that already come with ACF & ACFE and just handle the front end user interactions.

## Requirements
1. Advanced Custom Fields for WordPress (ACF): https://www.advancedcustomfields.com/
2. ACF Extended is also being used. If you're using ACF there's no reason to not use this, it's a straight enhancement: https://www.acf-extended.com/
3. jQuery is queued; ACF already does this so it shouldn't be an issue.
    1. Your script should queued after jQuery, ACF and ACFE. [I recommended defering your script](https://core.trac.wordpress.org/ticket/12009#comment:57).
5. You know how to do an AJAX hook in WP: https://codex.wordpress.org/AJAX_in_Plugins

## Usage (very generic)
**\*\*This is not plug-n-play. A small amount of work will need to be done to make this work.****

*Since almost all themes are unique, I can only give basic and general instructions.*

1. Copy the function and hooks from my `functions.php` into your theme's `functions.php`.

The current function is very basic and all it does it trigger ACF's form logic, which is the beauty of this implementation. Nearly everything is evaluated by ACF and it will validate and error out as if it was a normal form. The only thing that you might be responsible for is updating line 10, with whatever success message you want.

2. Copy the `scripts.js` into your theme's script and queue it up. 

This can be done any number of ways so I can't really explain how to do this to cover all bases. However, what is important is that your script is queued up after jQuery, ACF and ACFE. With jQuery it's easy, you can [just mark it as a dependency for your script](https://developer.wordpress.org/reference/functions/wp_enqueue_script/) the third argument can be as basic as `array( 'jquery' )`. However, ACF is not as easy because it queue's up mulitple scripts and we want to try to not interfere with anything else it's doing. This is where the `defer` comes into play, this will ensure that your script gets called after all the other ACF magic happens.

## FAQ
**Q:** Can this be done w/o jQuery? 

**A:** Yes, this could also be done easily with out jQuery, but it's already there so use it.
##

**Q:** Does this work w/o JavaScript enabled? 

**A:** ... It is an AJAX method (JavaScript is in the acronym). But it will fallback to ACFs default state.
##

**Q:** What version of ACF does this work with? 

**A:** Tested with the most current version as of 08/12/22: ACF 5.12.3 and ACFE 0.8.8.7. 

## Updates
* 02/20/2023 - Tested with ACF 6.0.7 and ACFE 0.8.9.2 🚀
