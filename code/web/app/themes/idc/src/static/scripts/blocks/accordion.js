/*
	Name:				        Accordion.js
	Description:        Accordion Block script for the theme.
	Version:            1.0.0
	Author:             Garrison Hughes
*/
(function ($) {
  $(function () {
    $('.c-accordion-block__accordion > .c-accordion-block__panel').hide();

    $('.c-accordion-block__accordion > .c-accordion-block__headline').click(function () {
      const siblings = $(this).parents('.c-accordion-block').siblings('.c-accordion-block');

      $(this).toggleClass('c-accordion-block__headline--show').next().slideToggle();
      $(this).parent().toggleClass('c-accordion-block__accordion--active');

      siblings.find('.c-accordion-block__headline').removeClass('c-accordion-block__headline--show');
      siblings.find('.c-accordion-block__accordion').removeClass('c-accordion-block__accordion--active');
      siblings.find('.c-accordion-block__panel').slideUp();

      return false;
    });
  });
})(jQuery);
