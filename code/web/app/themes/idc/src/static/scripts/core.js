/*
  Name:               Core.js
  Description:        Core script for the theme.
  Version:            1.0.0
  Author:             Garrison Hughes
*/

/**
 * utility function to check element position in viewport
 */
function isInViewport(elem, scrollOffset, useBottom) {
  var rect = elem.getBoundingClientRect();
  if (useBottom) {
    return (
      (rect.height > 0 || rect.width > 0) &&
      rect.bottom - scrollOffset <=
        (window.innerHeight || document.documentElement.clientHeight) &&
      rect.left <=
        (window.innerWidth || document.documentElement.clientWidth) &&
      rect.right >= 0
    );
  } else {
    return (
      (rect.height > 0 || rect.width > 0) &&
      rect.bottom >= 0 &&
      rect.right >= 0 &&
      rect.top + scrollOffset <=
        (window.innerHeight || document.documentElement.clientHeight) &&
      rect.left <= (window.innerWidth || document.documentElement.clientWidth)
    );
  }
}

(($) => {
  $(() => {
    /**
     * Onload get window size
     */
    let windowsize;
    const sizeCheck = () => {
      windowsize = $(window).outerWidth();
      mobileNavSize(windowsize);
      return windowsize;
    };
    const scrollCheck = () => {
      const addRemove = $(window).scrollTop() >= 50 ? 'add' : 'remove';
      const $lottie = $('.lottieAnim');
      $('.c-header')[`${addRemove}Class`]('c-header--scroll');

      /*
      lottie
      many of the in-page js scripts, vars and setup are created in templates/blocks/animated-chart.php
      loop through each element with the .lottieAnim class, check if it is visible, if so animate it
      */
      $lottie.each(function () {
        if (
          isInViewport(document.getElementById($(this).attr('id')), 150, true)
        ) {
          //get the ID of this element
          const animID = $(this).attr('id');
          const $target = $(`#${animID}`);
          //check if playing - if not, query the animation via the js window object
          if ($target.attr('data-animation-playing') !== 'true') {
            $target.attr('data-animation-playing', 'true');
            window[animID].play();
          }
        }
      });
    };

    /**
     * body variable
     */
    const $body = $('body');

    $(window)
      .on('resize', throttleDebounce.throttle(750, sizeCheck))
      .on('scroll', throttleDebounce.throttle(250, scrollCheck));

    /**
     * Hamburger toggle
     */
    const $mobileNav = $('.c-header__nav');
    $('.controls__nav button')
      .on('click touch', () => {
        $mobileNav.toggleClass('c-header__nav--open');
      })
      .on('keydown', (e) => {
        if (e.key === 'Escape') {
          $mobileNav.removeClass('c-header__nav--open');
        }
      });

    /**
     * If window is less than 992px
     */
    const mobileNavSize = (windowsize) => {
      if (windowsize > 992) {
        return false;
      }

      /**
       * Make first menu item click show submenu before navigating to new page.
       */
      $('.menu li.menu-item-even.menu-item-has-children > a').on(
        'click touch',
        function (e) {
          const self = $(this);
          const href = self.attr('href');
          const doToggle = !href || href === '#';

          if (!self.is('.active') || doToggle) {
            e.preventDefault();
            self.toggleClass('active');

            self.siblings('.sub-menu').slideToggle(function () {
              $(this).toggleClass('sub-menu--open').removeAttr('style');
            });

            self
              .children('.sub-menu-control')
              .toggleClass('sub-menu-control--open');
          }
        }
      );
    };

    /**
     * Carrot Down to activate submenu
     */
    $('.sub-menu-control').on('click touch', function (e) {
      // if screensize is lg than do nothing
      if (windowsize > 992) {
        return false;
      }
      // if smaller than run the below
      e.preventDefault();
      e.stopPropagation();
      const item = $(e.currentTarget).parent();

      $(this)
        .parent()
        .siblings('.sub-menu')
        .slideToggle(function () {
          $(this).toggleClass('sub-menu--open');
          $(this).removeAttr('style');
        });
      $(this).toggleClass('sub-menu-control--open');
      item.toggleClass('active');
    });
    /**
     * Open search overlay on search icon click
     */
    const $sidebarSearch = $('.c-sidebar-search');
    $('.c-nav__icon--search, .btn--search-form').on('click tap', (e) => {
      /** Prevent jump to top of page */
      e.preventDefault();
      e.stopPropagation();
      $sidebarSearch.toggleClass('c-sidebar-search--open');

      //if active then prevent body from scrolling
      $body.addClass('overflow-hidden');
    });

    /**
     * Open sidebar form on click of get-started button
     */
    const $sidebarForm = $('.c-sidebar-form');
    $('.btn--sidebar-form').on('click tap', (e) => {
      /** Prevent jump to top of page */
      e.preventDefault();
      e.stopPropagation();
      $sidebarForm.toggleClass('c-sidebar-form--open');

      //if active then prevent body from scrolling
      $body.addClass('overflow-hidden');
    });

    /**
     * Close Items
     */
    $(document).on('click touch keyup keydown', (e) => {
      /**
       * Close sidebar form if active and user clicks elsewhere or esc key is hit
       */
      if (
        e.target.matches('.c-sidebar-form--open') ||
        e.target.matches('.c-sidebar-form__controls--close i') ||
        e.key === 'Escape'
      ) {
        $sidebarForm.removeClass('c-sidebar-form--open').delay(150);
        $body.removeClass('overflow-hidden');
      }

      /**
       * close sidebar search
       */

      if (
        e.target.matches('.c-sidebar-search--open') ||
        e.target.matches('.c-sidebar-search__button--close i') ||
        e.key === 'Escape'
      ) {
        $sidebarSearch.removeClass('c-sidebar-search--open').delay(150);
        $body.removeClass('overflow-hidden');
      }
    });

    /**
     * General Magnific Popup
     */
    $('.c-popup-link').magnificPopup();

    /**
     * initial page load function calls
     */
    //window size check
    windowsize = sizeCheck();

    //scroll check
    scrollCheck();
  });
})(jQuery);
