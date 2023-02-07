(function ($) {
  function arrayRange(start, stop, step = 1) {
    return Array(stop - start + 1)
      .fill(start)
      .map((x, y) => x + y * step);
  }

  const renderFeaturedImage = (data) => {
    if (data.featured_media) {
      const img = data._embedded['wp:featuredmedia'][0];
      const thumb = 'media_details' in img && 'medium_large' in img.media_details.sizes ? img.media_details.sizes.medium_large.source_url : img.source_url;
      const alt = img.alt_text || '';

      return `<img src="${thumb}" class="size-medium-large" alt="${alt}" />`;
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

  const renderExcerpt = (data) => {
    const excerpt = data.excerpt.rendered;
    return excerpt;
  };

  const renderCat = (data) => {
    if (data.categories) {
      const cat = data._embedded['wp:term'][0][0];
      const cat_name = cat.name;
      const className = 'c-blog';

      return `<div class="${className}__tag-link"><i class="fa-light fa-tags text--purple"></i><p class="text--teal text--uppercase">${cat_name}</p></div>`;
    }
    return '';
  };

  const renderPost = (data) => {
    const className = 'c-blog';
    return `
      <div class="${className} ${className}--post">
        <a class="${className}__permalink" href="${renderPermalink(data)}" title="${renderTitle(data)}">
          <div class="${className}__feat-img">
              ${renderFeaturedImage(data)}
          </div>
        </a>
        <p class="${className}__title text--charcoal text--big container--padding-xl">
            ${renderTitle(data)}
        </p>
        <div class="${className}__tag-button container--padding-xl">
            ${renderCat(data)}
            <div class="is-style-idc-button-right">
                <a href="${renderPermalink(data)}" title="${renderTitle(data)}" class="wp-block-button__link"></a>
            </div>
         </div>
      </div>
    `;
  };
  const $currentPage = $('.c-blog__filter-container input[name=page]');
  const $loadPosts = $('#load-posts');

  const setPagination = (total, current) => {
    if (current < total) {
      $currentPage.val(parseInt(current) + 1);
    } else {
      $loadPosts.attr('disabled', 'disabled').text('No More Posts');
    }
  };

  $(function () {
    const $form = $('#blog-search');

    const loadPosts = () => {
      const $posts = $('#news-resources');

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
              $posts.append(renderPost(v));
            });

            if (data.length < 1) {
              $posts.html('<h2>Sorry, no results found.</h2>');
            }

            $('.c-blog--post', $posts).each(function (index) {
              const row = $(this);
              setTimeout(function () {
                row.addClass('c-blog--show');
              }, 50 * index);
            });
          }
          setPagination(response.getResponseHeader('X-WP-TotalPages'), $currentPage.val());
          $('.c-modal-loading--post').fadeOut();
        },
        error: function (jqXHR, textStatus, errorThrown) {
          console.log(jqXHR);
          console.log(textStatus);
          console.log(errorThrown);
        },
      });
    };

    const search = (page, empty) => {
      $currentPage.val(page);

      if (empty === true) {
        $('#news-resources').empty();
      }
      loadPosts();
    };

    $('#blog-category-filter').on('change', (e) => {
      e.preventDefault();
      search(1, true);
    });

    $('.c-blog__cat-list .blog-category-btn').on('click tap', (e) => {
      e.preventDefault();
      let button = e.target;
      $('#blog-category-filter').val(button.value);
      search(1, true);
    });

    $form.on('submit', (e) => {
      e.preventDefault();
      search(1, true);
    });

    $('#load-posts').on('click tap', (e) => {
      e.preventDefault();
      search($currentPage.val() || 1, false);
    });

    loadPosts();
  });
})(jQuery);
