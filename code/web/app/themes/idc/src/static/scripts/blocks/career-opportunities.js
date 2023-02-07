/*
	Name:				        career-opportunities.js
	Description:        Script for the Career Opportunities block
	Version:            1.0.0
	Author:             Garrison Hughes
*/

(($, blockName, animationTiming) => {
  $(() => {
    const currentFilters = [];

    /**
     * Builds the available filters for each block set
     */
    $(`.${blockName}`).each((i, el) => {

      const $filters = $(el).find(`.${blockName}__filter`);
      const $results = $(el).find(`.${blockName}__results`);

      currentFilters[i] = {};

      /**
       * Gets the initial value for each filter keyed by name
       */
      $filters.find('select').each((j, el) => {
        currentFilters[i][el.name] = $(el).val();
      });

      /**
       * Listener to trigger an update
       */
      $filters.on('change', 'select', (e) => {
        const $target = $(e.currentTarget);
        currentFilters[i][e.currentTarget.name] = parseInt($target.val() || 0, 10);
        $results.trigger('ghint:update', [currentFilters[i]]);
      });
    });

    /**
     * Each results block receives an update event when its filters are changed
     * Current filter values are passed to the event handler
     */
    $(`.${blockName}__results`).on('ghint:update', (e, filter) => {
      const $list = $(e.currentTarget);
      $list.find('.job-posting').each((i, el) => {
        const $posting = $(el);
        const terms = $posting.data('filter-terms');
        const isVisible = $posting.is(':visible');
        const isMatch = Object.keys(filter).every(
          (tax) => !!filter[tax] ? terms[tax].includes(filter[tax]) : true
        );

        if (isMatch && !isVisible) {
          $posting.slideDown(animationTiming);
        } else if (!isMatch && isVisible) {
          $posting.slideUp(animationTiming);
        }
      });

      /**
       * Determine if we should show the empty message after all animations finish
       */
      $list.find('.job-posting:animated').promise().done(function () {
        const direction = !!$list.find('.job-posting:visible').length ? 'Up' : 'Down';
        $list.find(`.${blockName}__results-empty`)[`slide${direction}`](animationTiming);
      });
    });
  });
})(jQuery, 'c-career-opportunities-block', 200);