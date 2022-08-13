# ACF/ACFE AJAX Form Submit
Submit a WordPress ACF front end form via AJAX, instead of it's standard POST/page refresh method.

"Disables" the refresh logic that ACF front end forms uses in favor of a custom AJAX submission. This allows you to utilize all the checks and validation that already come with ACF & ACFE and just handle the front end user interactions.

## Requirements
1. Advanced Custom Fields for WordPress (ACF): https://www.advancedcustomfields.com/
2. ACF Extended is also being used. If you're using ACF there's no reason to not use this, it's a straight enhancement: https://www.acf-extended.com/
3. jQuery is queued; ACF already does this so it shouldn't be an issue.
    1. Your script should queued after jQuery, ACF and ACFE. [I recommended defering your script](https://core.trac.wordpress.org/ticket/12009#comment:57).
5. You know how to do an AJAX hook in WP: https://codex.wordpress.org/AJAX_in_Plugins/ 

## FAQ
**Q:** Can this be done w/o jQuery? 

**A:** Yes, this could also be done easily with out jQuery, but it's already there so use it.
##

**Q:** Does this work w/o JavaScript enabled? 

**A:** ... It is an AJAX method (JavaScript is in the acronym). But it will fallback to ACFs default state.
##

**Q:** What version of ACF does this work with? 

**A:** Tested with the most current version as of 08/12/22: ACF 5.12.3 and ACFE 0.8.8.7. 
