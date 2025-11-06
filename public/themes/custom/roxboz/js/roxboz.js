/**
 * @file
 * Custom theme JavaScript.
 */

(function (Drupal) {
  'use strict';

  /**
   * Theme behavior.
   */
  Drupal.behaviors.customTheme = {
    attach: function (context, settings) {
      // Add a simple console log to verify JS is working
      console.log('Custom theme JavaScript loaded successfully!');
      
      // Example: Add click event to custom buttons
      const buttons = context.querySelectorAll('.btn');
      buttons.forEach(function(button) {
        button.addEventListener('click', function(e) {
          console.log('Button clicked:', this.textContent);
        });
      });
    }
  };

})(Drupal);