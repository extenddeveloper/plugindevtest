(function ($) {
  "use strict";

  /**
   * All of the code for your public-facing JavaScript source
   * should reside in this file.
   *
   * Note: It has been assumed you will write jQuery code here, so the
   * $ function reference has been prepared for usage within the scope
   * of this function.
   *
   * This enables you to define handlers, for when the DOM is ready:
   *
   * $(function() {
   *
   * });
   *
   * When the window is loaded:
   *
   * $( window ).load(function() {
   *
   * });
   *
   * ...and/or other possibilities.
   *
   * Ideally, it is not considered best practise to attach more than a
   * single DOM-ready or window-load handler for a particular page.
   * Although scripts in the WordPress core, Plugins and Themes may be
   * practising this, we should strive to set a better example in our own work.
   */

jQuery(document).ready(function ($) {
  $('#load-more').on('click', function () {
      var button = $(this);
      var page = button.data('page');
      var maxPages = button.data('max');

      $.ajax({
          url: tf_service.ajax_url,
          type: 'POST',
          data: {
              action: 'load_more_tfservices',
              page: page
          },
          success: function (response) {
              if (response != 0) {
                // check #main last article tag and push as next element
                var lastArticle = $('#main article:last');
                lastArticle.after(response);

                button.data('page', page + 1);

                if (page + 1 >= maxPages) {
                    button.remove();
                }
              } else {
                  button.remove();
              }
          }
      });
  });
});


jQuery(document).ready(function ($) {
    // Open the modal when clicking the search button
    $('#open-search').on('click', function () {
        $('#search-modal').fadeIn();
    });

    // Close modal
    $('.close-modal').on('click', function () {
        $('#search-modal').fadeOut();
    });

    // AJAX search functionality
    $('#tfservices-search-input').on('keyup', function () {
        var keyword = $(this).val();
        $.ajax({
            url: tfservicesAjax.ajaxurl,
            type: 'POST',
            data: {
                action: 'tfservices_search',
                keyword: keyword,
                paged: 1,
            },
            success: function (response) {
                $('#tfservices-search-results').html(response);
            },
        });
    });

    // Redirect on "View More" button click to full page search
    $(document).on('click', '#view-more', function () {
        var query = $(this).data('query');
        window.location.href = '/tf_services?search=' + query;
    });
});







})(jQuery);
