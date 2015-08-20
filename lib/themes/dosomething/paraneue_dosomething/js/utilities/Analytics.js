/**
 * We use the Google Analytics custom events API to fire events for client-side
 * events that we want to track, such as opening or closing modals, share buttons,
 * or form validation events.
 */

import $ from 'jquery';
import Validation from 'dosomething-validation';
import Modal from 'dosomething-modal';

// Only fire GA Custom Events if the GA object exists.
if (typeof ga !== 'undefined' && ga !== null) {
  // Validation
  Validation.Events.subscribe('Validation:InlineError', function(topic, args) {
    ga('send', 'event', 'Form', 'Inline Validation Error', args);
  });

  Validation.Events.subscribe('Validation:Suggestion', function(topic, args) {
    ga('send', 'event', 'Form', 'Suggestion', args);
  });

  Validation.Events.subscribe('Validation:SuggestionUsed', function(topic, args) {
    ga('send', 'event', 'Form', 'Suggestion Used', args);
  });

  Validation.Events.subscribe('Validation:Submitted', function(topic, args) {
    ga('send', 'event', 'Form', 'Submitted', args);
  });

  Validation.Events.subscribe('Validation:SubmitError', function(topic, args) {
    ga('send', 'event', 'Form', 'Validation Error on submit', args);
  });

  // Modals
  Modal.Events.subscribe('Modal:Open', function(topic, args) {
    const label = args !== null ? '#' + args.attr('id') : '';
    ga('send', 'event', 'Modal', 'Open', label);
  });

  Modal.Events.subscribe('Modal:Close', function(topic, args) {
    const label = args !== null ? '#' + args.attr('id') : '';
    ga('send', 'event', 'Modal', 'Close', label);
  });

  /******
   * Google Analytics Event Tracking
   * Requires an element with the 'ga-event' class on it
   * and the required data attributes:
   *   data-category
   *   data-action
   *   data-label
   *
   * TODO - Should we have default values for category, action, label
   * so anytime we want to track a click we can just add the .ga-click class to it with nothing else?
  ********/
  if ($('.ga-click').length) {
    $('.ga-click').on('click', function() {
      const category = (typeof ($(this).data('category')) !== 'undefined') ? $(this).data('category') : null;
      const action = (typeof ($(this).data('action')) !== 'undefined') ? $(this).data('action') : null;
      const label = (typeof ($(this).data('label')) !== 'undefined') ? $(this).data('label') : null;

      ga('send', 'event', category, action, label);
    });
  }
}
