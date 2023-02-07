<?php
/*
    Name:                     setup.php
    Description:              Core setup for functions.php for the theme.
    Version:                  1.0.0
    Author:                   Garrison Hughes
*/

namespace GHInt\Setup;

/**
 * Theme setup
 */

function setup()
{
    /*
     * Switches default core markup for search form, comment form,
     * and comments to output valid HTML5.
     */
    add_theme_support('html5', array(
        'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'script', 'style'
    ));

    /*
     * Enable theme
     */
    load_theme_textdomain('ghint', get_template_directory() . '/lang');

    /*
     * This theme supports all available post formats by default.
     * See https://codex.wordpress.org/Post_Formats
     */
    add_theme_support('post-formats', array(
        'aside', 'audio', 'chat', 'gallery', 'image', 'link', 'quote', 'status', 'video'
    ));

    /*
     * RSS Feed support
     */
    add_theme_support('automatic-feed-links');

    /*
     * This theme uses a custom image size for featured images, displayed on
     * "standard" posts and pages.
     */
    add_theme_support('post-thumbnails');

    add_theme_support('title-tag');

    /*
     * Add custom menu support
     */
    add_theme_support('menus');

    /*
     * Register custom navigation menus
     */
    register_nav_menus([
        'primary_navigation' => __('Primary Navigation', 'ghint'),
        'footer_navigation_one' => __('Footer Navigation: One', 'ghint'),
        'footer_navigation_two' => __('Footer Navigation: Two', 'ghint'),
        'footer_legal_navigation' => __('Footer Legal Navigation', 'ghint')
    ]);

    /*
     * Clean up the <head> default junk Wordpress inserts
     */
    // category feeds
    remove_action('wp_head', 'feed_links_extra', 3);
    // post and comment feeds
    remove_action('wp_head', 'feed_links', 2);
    // rsd link
    remove_action('wp_head', 'rsd_link');
    // windows live writer
    remove_action('wp_head', 'wlwmanifest_link');
    // previous link
    remove_action('wp_head', 'parent_post_rel_link', 10, 0);
    // start link
    remove_action('wp_head', 'start_post_rel_link', 10, 0);
    // links for adjacent posts
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
    // WP version
    remove_action('wp_head', 'wp_generator');
    // Remove emojis
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');

    /*
     * Enable BrowserSync injection/reload
     */

    //Comment out if you don't want to use BrowserSync!
    if (WP_ENV == 'development') {
        add_action('wp_head', function () { ?>
            <script type="text/javascript" id="__bs_script__">
                //<![CDATA[
                document.write("<script async src='http://HOST:3000/browser-sync/browser-sync-client.js'><\/script>".replace("HOST", location.hostname));
                //]]>
            </script>
<?php }, 999);
    }

    // Kill the WP Update nag for non-admins
    if (!current_user_can('edit_users')) {
        add_action('init', function () {
            remove_action('init', 'wp_version_check');
        }, 2);

        add_filter('pre_option_update_core', function ($a) {
            return null;
        });
    }

    //Hide the admin bar for non admins
    if (!current_user_can('manage_options')) {
        show_admin_bar(false);
    }

    add_theme_support('custom-spacing');
    add_theme_support('align-wide');
    add_theme_support('wp-block-styles');
    /**
     * Add editor styles
     */
    add_theme_support('editor-styles');
    add_editor_style('dist/static/styles/blocks.css');

    add_theme_support('editor-font-sizes', [
        [
            'name' => __('Micro', 'ghint'),
            'size' => 12,
            'slug' => 'text-micro',
        ],
        [
            'name' => __('Mini', 'ghint'),
            'size' => 14,
            'slug' => 'text-mini',
        ],
        [
            'name' => __('Small', 'ghint'),
            'size' => 16,
            'slug' => 'text-small',
            'isDefault' => true,
        ],
        [
            'name' => __('Medium', 'ghint'),
            'size' => 18,
            'slug' => 'text-md',
        ],
        [
            'name' => __('Big', 'ghint'),
            'size' => 20,
            'slug' => 'text-big',
        ],
        [
            'name' => __('Display Header', 'ghint'),
            'size' => 56,
            'slug' => 'text-display',
        ],
    ]);

    /**
     * Disable default colors and apply IDC colors
     */
    add_theme_support('disable-custom-colors');
    add_theme_support('editor-color-palette', [

        [
            'name' => __('Charcoal', 'ghint'),
            'slug' => 'charcoal',
            'color' => '#50505A',
        ],
        [
            'name' => __('Dark', 'ghint'),
            'slug' => 'dark',
            'color' => '#000000',
        ],
        [
            'name' => __('Gold', 'ghint'),
            'slug' => 'gold',
            'color' => '#F1AE4B',
        ],
        [
            'name' => __('Gray 1', 'ghint'),
            'slug' => 'gray-one',
            'color' => '#eaebec',
        ],
        [
            'name' => __('Gray 2', 'ghint'),
            'slug' => 'gray-two',
            'color' => '#dadbdc',
        ],
        [
            'name' => __('Gray 3', 'ghint'),
            'slug' => 'gray-three',
            'color' => '#cacbcc',
        ],
        [
            'name' => __('Purple', 'ghint'),
            'slug' => 'purple',
            'color' => '#771B61',
        ],
        [
            'name' => __('Red', 'ghint'),
            'slug' => 'red',
            'color' => '#FF0000',
        ],
        [
            'name' => __('Teal', 'ghint'),
            'slug' => 'teal',
            'color' => '#3CAFAB',
        ],
        [
            'name' => __('Teal Lite', 'ghint'),
            'slug' => 'teal-lite',
            'color' => '#339999',
        ],
        [
            'name' => __('Teal Medium', 'ghint'),
            'slug' => 'teal-medium',
            'color' => '#666699',
        ],
        [
            'name' => __('Teal Dark', 'ghint'),
            'slug' => 'teal-dark',
            'color' => '#663366',
        ],
        [
            'name' => __('White', 'ghint'),
            'slug' => 'white',
            'color' => '#fff',
        ],
    ]);

    // Disable HTML in Wordpress comments
    add_filter('pre_comment_content', 'esc_html');
}
add_action('after_setup_theme', __NAMESPACE__ . '\\setup');

/*
 * Prevent Wordpress from "guessing" URL's
 */
function stop_guessing($url)
{
    if (is_404()) {
        return false;
    }
    return $url;
}
add_filter('redirect_canonical', __NAMESPACE__ . '\\stop_guessing');

/*
 * Prevent detailed log in information from being displayed
 */
function no_wordpress_errors()
{
    return 'Incorrect username or password';
}
add_filter('login_errors', __NAMESPACE__ . '\\no_wordpress_errors');

/*
 * Add support for custom query variables
 */
function add_query_vars($vars)
{
    // $vars[] = 'query';
    return $vars;
}
add_filter('query_vars', __NAMESPACE__ . '\\add_query_vars');

/*
 * Change the log in logo link to URL of this site
 */
function set_login_logo_url()
{
    return home_url();
}
add_filter('login_headerurl', __NAMESPACE__ . '\\set_login_logo_url');

/*
 * Change the alt text on the logo to the site name
 */
function set_login_title()
{
    return get_option('blogname');
}
add_filter('login_headertext', __NAMESPACE__ . '\\set_login_title');

/*
 * Set default log in page styles to our custom one
 */
function set_login_styles()
{
    wp_enqueue_style('ghinter_login_css', get_template_directory_uri() . '/dist/static/styles/admin/login.css', false);
}
add_action('login_enqueue_scripts', __NAMESPACE__ . '\\set_login_styles', 10);

/*
 * Remove the p from around imgs
 * (http://css-tricks.com/snippets/wordpress/remove-paragraph-tags-from-around-images/)
 */
function filter_p_tags_around_images($content)
{
    return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
}
add_filter('the_content', __NAMESPACE__ . '\\filter_p_tags_around_images');

/*
 * Enable threaded comments
 */
function enable_threaded_comments()
{
    if (!is_admin()) {
        if (is_singular() and comments_open() and (get_option('thread_comments') == 1))
            wp_enqueue_script('comment-reply');
    }
}
add_action('get_header', __NAMESPACE__ . '\\enable_threaded_comments');

?>