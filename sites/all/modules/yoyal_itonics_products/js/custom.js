(function ($) {
  Drupal.behaviors.mymoduleChosenInit = {
    attach: function (context, settings) {
      $('.chosen-select', context).chosen({
        // Add Chosen options as needed.
        // For example:
        width: '100%',
        // Any other Chosen options...
      });
    }
  };
})(jQuery);


