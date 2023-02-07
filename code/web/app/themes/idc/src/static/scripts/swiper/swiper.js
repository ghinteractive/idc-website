// init Swiper:
const teamSlider = new Swiper('.c-team-slider__swiper', {
  slidesPerView: 1,
  slidesPerGroup: 1,
  effect: 'slide',
  grid: {
    fill: 'column',
  },

  breakpoints: {
    // when window width is >= 769px
    769: {
      slidesPerView: 2,
      slidesPerGroup: 2,
      allowTouchMove: false,
    },

    // when window width is >= 993px
    993: {
      slidesPerView: 3,
      slidesPerGroup: 3,
      allowTouchMove: false,
    },
  },

  // Navigation arrows
  navigation: {
    nextEl: '.swiper-button-next',
    prevEl: '.swiper-button-prev',
  },
  a11y: {
    prevSlideMessage: 'Previous slide',
    nextSlideMessage: 'Next slide',
  },
});

let resizeTimer;

/** When window is load run functions */
(function ($) {
  $(window).on('resize', function (e) {
    /** reset timer */
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(function () {
      teamSlider.init();
    }, 250);
  });
})(jQuery);
