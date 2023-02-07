<?php

namespace Roots\Assets;

use GHInt\Gate\ProtectedUploads;

/**
 * Get paths for assets
 */
class JsonManifest
{
    private $manifest;

    public function __construct($manifest_path)
    {
        if (file_exists($manifest_path)) {
            $this->manifest = json_decode(file_get_contents($manifest_path), true);
        } else {
            $this->manifest = [];
        }
    }

    public function get()
    {
        return $this->manifest;
    }

    public function getPath($key = '', $default = null)
    {
        $collection = $this->manifest;
        if (is_null($key)) {
            return $collection;
        }
        if (isset($collection[$key])) {
            return $collection[$key];
        }
        foreach (explode('.', $key) as $segment) {
            if (!isset($collection[$segment])) {
                return $default;
            } else {
                $collection = $collection[$segment];
            }
        }
        return $collection;
    }
}

function asset_path($filename)
{
    $dist_path = get_template_directory_uri() . '/dist/static/';
    $directory = dirname($filename) . '/';
    $file = basename($filename);
    static $manifest;

    if (empty($manifest)) {
        $manifest_path = get_template_directory() . '/dist/' . 'assets.json';
        $manifest = new JsonManifest($manifest_path);
    }

    if (array_key_exists($file, $manifest->get())) {
        return $dist_path . $directory . $manifest->get()[$file];
    } else {
        return $dist_path . $directory . $file;
    }
}

function register_styles_scripts()
{
    global $post;

    /* Core Styles */
    wp_enqueue_style('normalize-styles', asset_path('styles/normalize.css'));
    wp_enqueue_style('urbanist-fonts', 'https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;800;900&display=swap');
    wp_enqueue_style('magnific-popup', 'https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css');
    wp_enqueue_style('idc-styles', asset_path('styles/styles.css'));
    wp_enqueue_style('idc-block-styles', asset_path('styles/blocks.css'));

    /* Core Scripts */
    wp_enqueue_script('jquery-cookie', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js', array('jquery'));
    wp_enqueue_script('throttle-debounce', 'https://cdn.jsdelivr.net/npm/throttle-debounce@5.0.0/umd/index.min.js');
    wp_enqueue_script('magnific-popup', 'https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js', array('jquery'));
    wp_enqueue_script('jquery-validate', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js', array('jquery'));
    wp_enqueue_script('google-recaptcha', 'https://www.google.com/recaptcha/api.js?render=6Lf_18cgAAAAAB_u1-_wsDFfPEHOZdOtGMLwqijB');
    wp_enqueue_script('lottie', asset_path('lottie/lottie.min.js'));
    wp_enqueue_script('idc-script', asset_path('scripts/scripts.js'), array('jquery', 'lottie', 'throttle-debounce', 'jquery-cookie', 'jquery-validate', 'google-recaptcha'), '1.0.0', true);
    wp_localize_script('idc-script', 'serverVars', [
        'redirectToMedia' => rawurldecode($_GET['gated_form_auth'] ?? ''),
        'userCanView' => ProtectedUploads::userCanView($post),
        'loginUrl' => rest_url('/ghint/v1/gate/login'),
        'cookieExpiry' => 30,
        'recaptchaSiteKey' => '6Lf_18cgAAAAAB_u1-_wsDFfPEHOZdOtGMLwqijB',
        'validationMessages' => [
            'required' => __('This field is required', 'ghint'),
            'email' => __('Please enter a valid email', 'ghint'),
        ],
    ]);

    /* Deferred Styles */
    wp_register_style('post-styles', asset_path('styles/posts.css'), array(), '1.0.0');
    wp_register_style('swiper-styles', asset_path('styles/swiper.css'), array(), false);

    /* Deferred Scripts */
    wp_register_script(
        'team-members-js',
        asset_path('scripts/team-members.js'),
        array('jquery'),
        '1.0.0',
        true
    );
    wp_register_script(
        'blog-js',
        asset_path('scripts/blog.js'),
        array('jquery'),
        '1.0.0',
        true
    );
    wp_register_script(
        'testimonials-js',
        asset_path('scripts/testimonials.js'),
        array('jquery'),
        '1.0.0',
        true
    );
    wp_register_script(
        'swiper-bundle-js',
        asset_path('scripts/swiper-bundle.min.js'),
        array(),
        '8.2.4',
        true
    );
    wp_register_script(
        'swiper-js',
        asset_path('scripts/swiper.js'),
        array('swiper-bundle-js'),
        '1.0.1',
        true
    );

    // Posts
    if (is_singular() || has_block('acf/news-resources') || has_block('acf/team-slider')) {
        wp_enqueue_style('post-styles');
    }

    // Team Members
    if (is_page('our-team')) {
        wp_enqueue_script('team-members-js');
    }

    // Team Slider Block
    if (has_block('acf/team-slider')) {
        wp_enqueue_script('swiper-bundle-js');
        wp_enqueue_script('swiper-js');
        wp_enqueue_style('swiper-styles');
    }

    // News & Insights
    if (is_page('news-resources')) {
        wp_enqueue_script('blog-js');
    }

    //Testimonials
    if (has_block('acf/testimonials') || has_block('acf/video-block')) {
        wp_enqueue_script('testimonials-js');
        wp_enqueue_script('vimeo', 'https://player.vimeo.com/api/player.js');
        wp_enqueue_script('youtube', 'https://www.youtube.com/iframe_api', '', '5.9.2', true);
    }
}
add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\register_styles_scripts');

/**
 * Enqueue Extend Blocks Styles
 */
function extend_blocks()
{
    wp_enqueue_script(
        'extend-blocks',
        asset_path('scripts/extend-blocks.js'),
        array('wp-blocks', 'wp-dom-ready'),
        '1.0.0',
        true
    );
    wp_enqueue_script(
        'register-custom-icons',
        asset_path('scripts/register-custom-icons.js'),
        array('wp-i18n', 'wp-hooks', 'wp-dom'),
        wp_get_theme()->get('Version'),
        true
    );
    wp_enqueue_style('extend-blocks-styles', asset_path('styles/extend-blocks.css'));
}
add_action('enqueue_block_editor_assets', __NAMESPACE__ . '\\extend_blocks');
