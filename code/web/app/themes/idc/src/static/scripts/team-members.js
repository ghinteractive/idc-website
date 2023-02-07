(function ($) {
  function arrayRange(start, stop, step = 1) {
    return Array(stop - start + 1)
      .fill(start)
      .map((x, y) => x + y * step);
  }

  const renderFeaturedImage = (data) => {
    if (data.featured_media) {
      const img = data._embedded['wp:featuredmedia'][0];
      const thumb = 'media_details' in img && 'medium' in img.media_details.sizes ? img.media_details.sizes.medium.source_url : img.source_url;
      const alt = img.alt_text || '';

      return `<img src="${thumb}" class="attachment-thumbnail size-medium wp-post-image" alt="${alt}" />`;
    }
    return '';
  };

  const renderPermalink = (data) => {
    const link = data.link;
    return link;
  };

  const renderTitle = (data) => {
    const title = data.title.rendered;
    return title;
  };

  const renderPosition = (data) => {
    if (data.acf.title) {
      const position = data.acf.title;
      return `<h4 class="h6 text--teal">${position}</h4>`;
    }
    return '';
  };

  const renderTeamMember = (data) => {
    const className = 'c-team-member';
    return `
      <div class="${className} ${className}--post text--center">
        <a class="${className}__permalink" href="${renderPermalink(data)}" title="${renderTitle(data)}">
          <div class="${className}__feat-img">
            <div class="${className}__feat-img-wrapper">
              ${renderFeaturedImage(data)}
            </div>
          </div>
          <div class="${className}__title">
            <h3 class="h4 text--charcoal">${renderTitle(data)}</h3>
              ${renderPosition(data)}
          </div>
        </a>
      </div>
    `;
  };

  const renderPageLinks = (range, current) => {
    return range
      .map(
        (n) => `
      <div class="page-item${current === n ? ' active disabled' : ''} page-item--num">
        <a class="page-link" href="#${n}" title="Page ${n}">${n}</a>
      </div>
    `
      )
      .join('');
  };

  const pagination = (total, current) => {
    const max = Math.max(total, 1);
    let range = arrayRange(current - 2, current + 2);
    if (current < 5) {
      range = arrayRange(1, Math.min(5, max));
    } else if (current >= max - 2) {
      range = arrayRange(max - 4, max);
    }
    return `
      <div class="page-item page-item--icon first${current > 3 ? '' : ' disabled'}">
        <a class="page-link" href="#1" title="Go To First">
          <i class="text--teal fas fa-angle-double-left" aria-hidden="true"></i>
        </a>
      </div>
      <div class="page-item page-item--icon prev${current > 1 ? '' : ' disabled'}">
        <a class="page-link" href="#${current - 1}" title="Previous">
          <i class="text--teal fas fa-angle-left" aria-hidden="true"></i>
        </a>
      </div>
      ${renderPageLinks(range, current)}
      <div class="page-item page-item--icon next${current < max ? '' : ' disabled'}">
        <a class="page-link" href="#${current + 1}" title="Next">
          <i class="text--teal fas fa-angle-right" aria-hidden="true"></i>
        </a>
      </div>
      <div class="page-item page-item--icon last${max - current > 2 ? '' : ' disabled'}">
        <a class="page-link" href="#${max}" title="Go To Last">
          <i class="text--teal fas fa-angle-double-right" aria-hidden="true"></i>
        </a>
      </div>
    `;
  };

  const setPagination = (total, currentPage) => {
    const $pagination = $('#team-members-pagination');
    $pagination.html(pagination(total, parseInt(currentPage)));
  };

  $(function () {
    const $form = $('#team-member-search');
    const currentPage = $('.c-our-team__filter-container input[name=page]');

    const loadTeamMembers = () => {
      const $posts = $('#team-members');

      $.ajax({
        url: $form.attr('action'),
        data: $form
          .find(':input')
          .filter((i, el) => {
            return !!$(el).val();
          })
          .serialize(),
        type: $form.attr('method') || 'GET',
        beforeSend: function () {
          $('.c-modal-loading--post').fadeIn();
        },
        success: function (data, status, response) {
          if (data) {
            $.each(data, (i, v) => {
              $posts.append(renderTeamMember(v));
            });

            if (data.length < 1) {
              $posts.html('<h2>Sorry, no team member(s) found.</h2>');
            }

            $('.c-team-member--post', $posts).each(function (index) {
              const row = $(this);
              setTimeout(function () {
                row.addClass('c-team-member--show');
              }, 50 * index);
            });
          }
          setPagination(response.getResponseHeader('X-WP-TotalPages'), currentPage.val());
          $('.c-modal-loading--post').fadeOut();
        },
        error: function (jqXHR, textStatus, errorThrown) {
          console.log(jqXHR);
          console.log(textStatus);
          console.log(errorThrown);
        },
      });
    };

    const initialLoadTeamMembers = () => {
      $('#team-members').empty();
      loadTeamMembers();
    };

    const search = (page) => {
      currentPage.val(page);
      initialLoadTeamMembers();
      //   pagination(page, currentPage.val());
    };

    $('#team-dept-filter').on('change', (e) => {
      e.preventDefault();
      search(1);
    });

    $form.on('submit', (e) => {
      e.preventDefault();
      search(1);
    });

    $('.c-our-team__pagination').on('click', '.page-link', (e) => {
      const $self = $(e.currentTarget);
      // Disable pagination if class disabled exits
      if ($self.parent().hasClass('disabled')) {
        e.preventDefault();
        return;
      }
      e.preventDefault();

      const destination = $('.c-our-team');
      $('html, body').animate({ scrollTop: destination.offset().top }, 'slow');
      search(e.currentTarget.hash.substr(1));
    });

    initialLoadTeamMembers();
  });
})(jQuery);
