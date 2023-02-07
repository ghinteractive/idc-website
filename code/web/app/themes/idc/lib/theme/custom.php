<?php
/*
  Name:               custom.php
  Description:        Add any customization to the theme functions.php here.
  Version:            1.0.0
  Author:             Garrison Hughes
*/

namespace GHInt\Custom;

use GHInt\Gate\LoginException;
use GHInt\Gate\ProtectedUploads;
use GHInt\Gate\Token;
use GHInt\Gate\User;
use GuzzleHttp\Client;
use WP_Post;
use WP_REST_Request;

/**
 * Register custom post template for default WordPress post type
 */
function custom_post_template_init()
{
    $template = [
        ['core/paragraph', ['placeholder' => __('Add your post content here...', 'ghint')]],
        ['core/separator'],
        ['acf/author'],
        ['acf/news-resources']
    ];
    $post_type_object = get_post_type_object('post');
    $post_type_object->template = $template;
}
add_action('init', __NAMESPACE__ . '\\custom_post_template_init');

/**
 * Register custom WordPress objects
 */
function custom_wp_init()
{
    register_taxonomy('offices', array('job-postings', 'team-members'), array(
        'hierarchical'          => false,
        'public'                => true,
        'publicly_queryable'    => false,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'show_in_nav_menus'     => false,
        'show_in_rest'          => true,
        'show_admin_column'     => true,
        'query_var'             => false,
        'rewrite'               => false,
        'show_tagcloud'         => false,
        'labels'                => array(
            'name'                       => _x('Offices', 'taxonomy general name', 'ghint'),
            'singular_name'              => _x('Office', 'taxonomy singular name', 'ghint'),
            'search_items'               => __('Search Offices', 'ghint'),
            'popular_items'              => __('Popular Offices', 'ghint'),
            'all_items'                  => __('All Offices', 'ghint'),
            'parent_item'                => null,
            'parent_item_colon'          => null,
            'edit_item'                  => __('Edit Office', 'ghint'),
            'update_item'                => __('Update Office', 'ghint'),
            'add_new_item'               => __('Add New Office', 'ghint'),
            'new_item_name'              => __('New Office Name', 'ghint'),
            'separate_items_with_commas' => __('Separate offices with commas', 'ghint'),
            'add_or_remove_items'        => __('Add or remove offices', 'ghint'),
            'choose_from_most_used'      => __('Choose from the most used offices', 'ghint'),
            'not_found'                  => __('No offices found.', 'ghint'),
            'menu_name'                  => __('Offices', 'ghint'),
        ),
    ));
    register_taxonomy('job-categories', array('job-postings', 'team-members'), array(
        'hierarchical'          => false,
        'public'                => true,
        'publicly_queryable'    => false,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'show_in_nav_menus'     => false,
        'show_in_rest'          => true,
        'show_admin_column'     => true,
        'query_var'             => false,
        'rewrite'               => false,
        'show_tagcloud'         => false,
        'labels'                => array(
            'name'                       => _x('Categories', 'taxonomy general name', 'ghint'),
            'singular_name'              => _x('Category', 'taxonomy singular name', 'ghint'),
            'search_items'               => __('Search Categories', 'ghint'),
            'popular_items'              => __('Popular Categories', 'ghint'),
            'all_items'                  => __('All Categories', 'ghint'),
            'parent_item'                => null,
            'parent_item_colon'          => null,
            'edit_item'                  => __('Edit Category', 'ghint'),
            'update_item'                => __('Update Category', 'ghint'),
            'add_new_item'               => __('Add New Category', 'ghint'),
            'new_item_name'              => __('New Category Name', 'ghint'),
            'separate_items_with_commas' => __('Separate categories with commas', 'ghint'),
            'add_or_remove_items'        => __('Add or remove categories', 'ghint'),
            'choose_from_most_used'      => __('Choose from the most used categories', 'ghint'),
            'not_found'                  => __('No categories found.', 'ghint'),
            'menu_name'                  => __('Categories', 'ghint'),
        ),
    ));
    register_post_type('job-postings', array(
        'menu_icon'          => 'dashicons-building',
        'public'             => true,
        'publicly_queryable' => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_nav_menus'  => false,
        'show_in_rest'       => true,
        'query_var'          => false,
        'rewrite'            => false,
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'supports'           => array('title'),
        'taxonomies'         => array('offices', 'job-categories'),
        'labels'             => array(
            'name'                  => _x('Job Postings', 'Post type general name', 'ghint'),
            'singular_name'         => _x('Job Posting', 'Post type singular name', 'ghint'),
            'menu_name'             => _x('Job Postings', 'Admin Menu text', 'ghint'),
            'name_admin_bar'        => _x('Job Posting', 'Add New on Toolbar', 'ghint'),
            'add_new'               => __('Add New', 'ghint'),
            'add_new_item'          => __('Add New Job Posting', 'ghint'),
            'new_item'              => __('New Job Posting', 'ghint'),
            'edit_item'             => __('Edit Job Posting', 'ghint'),
            'view_item'             => __('View Job Posting', 'ghint'),
            'all_items'             => __('All Job Postings', 'ghint'),
            'search_items'          => __('Search Job Postings', 'ghint'),
            'parent_item_colon'     => __('Parent Job Postings:', 'ghint'),
            'not_found'             => __('No job postings found.', 'ghint'),
            'not_found_in_trash'    => __('No job postings found in Trash.', 'ghint'),
            'featured_image'        => _x('Job Posting Cover Image', 'Overrides the "Featured Image" phrase for job postings', 'ghint'),
            'set_featured_image'    => _x('Set cover image', 'Overrides the "Set featured image" phrase for job postings', 'ghint'),
            'remove_featured_image' => _x('Remove cover image', 'Overrides the "Remove featured image" phrase for job postings', 'ghint'),
            'use_featured_image'    => _x('Use as cover image', 'Overrides the "Use as featured image" phrase for job postings', 'ghint'),
            'archives'              => _x('Job Posting archives', 'The post type archive label used in nav menus. Default "Post Archives"', 'ghint'),
            'insert_into_item'      => _x('Insert into job posting', 'Overrides the "Insert into post"/"Insert into page" phrase (used when inserting media into a post)', 'ghint'),
            'uploaded_to_this_item' => _x('Uploaded to this job posting', 'Overrides the "Uploaded to this post"/"Uploaded to this page" phrase (used when viewing media attached to a post)', 'ghint'),
            'filter_items_list'     => _x('Filter job postings list', 'Screen reader text for the filter links heading on the post type listing screen. Default "Filter posts list"/"Filter pages list"', 'ghint'),
            'items_list_navigation' => _x('Job Postings list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default "Posts list navigation"/"Pages list navigation"', 'ghint'),
            'items_list'            => _x('Job Postings list', 'Screen reader text for the items list heading on the post type listing screen. Default "Posts list"/"Pages list"', 'ghint'),
        ),
    ));

    register_post_type('team-members', array(
        'menu_icon'          => 'dashicons-groups',
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_nav_menus'  => false,
        'show_in_rest'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'about/our-team'),
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => 5,
        'supports'           => array('title', 'editor', 'revisions', 'excerpt', 'thumbnail', 'custom-fields'),
        'taxonomies'         => array('offices', 'job-categories'),
        'labels'             => array(
            'name'                  => _x('Team Members', 'Post type general name', 'ghint'),
            'singular_name'         => _x('Team Member', 'Post type singular name', 'ghint'),
            'menu_name'             => _x('Team Members', 'Admin Menu text', 'ghint'),
            'name_admin_bar'        => _x('Team Member', 'Add New on Toolbar', 'ghint'),
            'add_new'               => __('Add New', 'ghint'),
            'add_new_item'          => __('Add New Team Member', 'ghint'),
            'new_item'              => __('New Team Member', 'ghint'),
            'edit_item'             => __('Edit Team Member', 'ghint'),
            'view_item'             => __('View Team Member', 'ghint'),
            'all_items'             => __('All Team Members', 'ghint'),
            'search_items'          => __('Search Team Members', 'ghint'),
            'parent_item_colon'     => __('Parent Team Members:', 'ghint'),
            'not_found'             => __('No team members found.', 'ghint'),
            'not_found_in_trash'    => __('No team members found in Trash.', 'ghint'),
            'featured_image'        => _x('Team Member Cover Image', 'Overrides the "Featured Image" phrase for team members', 'ghint'),
            'set_featured_image'    => _x('Set cover image', 'Overrides the "Set featured image" phrase for team members', 'ghint'),
            'remove_featured_image' => _x('Remove cover image', 'Overrides the "Remove featured image" phrase for team members', 'ghint'),
            'use_featured_image'    => _x('Use as cover image', 'Overrides the "Use as featured image" phrase for team members', 'ghint'),
            'archives'              => _x('Team Member archives', 'The post type archive label used in nav menus. Default "Post Archives"', 'ghint'),
            'insert_into_item'      => _x('Insert into team member', 'Overrides the "Insert into post"/"Insert into page" phrase (used when inserting media into a post)', 'ghint'),
            'uploaded_to_this_item' => _x('Uploaded to this team member', 'Overrides the "Uploaded to this post"/"Uploaded to this page" phrase (used when viewing media attached to a post)', 'ghint'),
            'filter_items_list'     => _x('Filter team members list', 'Screen reader text for the filter links heading on the post type listing screen. Default "Filter posts list"/"Filter pages list"', 'ghint'),
            'items_list_navigation' => _x('Team Members list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default "Posts list navigation"/"Pages list navigation"', 'ghint'),
            'items_list'            => _x('Team Members list', 'Screen reader text for the items list heading on the post type listing screen. Default "Posts list"/"Pages list"', 'ghint'),
        ),
    ));
}
add_action('init', __NAMESPACE__ . '\\custom_wp_init');

/**
 * ACF GUTENBERG BLOCK CLASSES
 */
function custom_acf_gutenberg_block_classes($className, $block)
{
    $classes = [];
    // GET ADDITIONAL BLOCK CLASSES FROM ADMIN
    if (!empty($block['className'])) {
        $classes[] = $block['className'] . ' ' . $className;
    }

    // GET ALIGN CLASSES FROM ADMIN
    if (!empty($block['align'])) {
        $classes[] .= ' align' . $block['align'];
    }
    return implode('', $classes);
}
add_filter('acf_gutenberg_block_classes', __NAMESPACE__ . '\\custom_acf_gutenberg_block_classes', 10, 2);

/**
 * ACF GUTENBERG BLOCK IDs
 */
function custom_acf_gutenberg_block_id($id, $block)
{
    $id .= $block['id'];
    if (!empty($block['anchor'])) {
        $id = $block['anchor'];
    }

    return $id;
}
add_filter('acf_gutenberg_block_id', __NAMESPACE__ . '\\custom_acf_gutenberg_block_id', 10, 2);

/**
 * ACF BLOCKS
 */
function custom_acf_init()
{
    /**
     * Add AFC Option pages
     */
    if (function_exists('acf_add_options_page')) {

        acf_add_options_page(array(
            'page_title' => 'Theme General Settings',
            'menu_title' => 'Theme Settings',
            'menu_slug' => 'theme-general-settings',
            'capability' => 'edit_posts',
            'redirect' => false
        ));

        acf_add_options_sub_page(array(
            'page_title' => 'Theme Sidebar Form Settings',
            'menu_title' => 'Sidebar Form Settings',
            'parent_slug' => 'theme-general-settings',
        ));

        acf_add_options_sub_page(array(
            'page_title' => 'Theme Gated Form Settings',
            'menu_title' => 'Gated Form Settings',
            'parent_slug' => 'theme-general-settings',
        ));
    }

    $idc_purple = '#771B61'; //Purple for pages
    $idc_teal = '#3CAFAB'; //Teal for posts

    // check function exists
    if (function_exists('acf_register_block')) {

        // register an accordion block
        acf_register_block_type(array(
            'name' => 'accordion',
            'title' => __('Accordion', 'ghint'),
            'description' => __('A custom block to display content within an accordion.', 'ghint'),
            'render_template' => 'templates/blocks/accordion.php',
            'enqueue_script' => get_template_directory_uri() . '/dist/static/scripts/blocks.js',
            'mode' => 'preview',
            'supports' => [
                'align' => ['wide', 'full'],
                'multiple' => true,
                'mode' => true,
                'jsx' => true
            ],
            'icon' => array(
                'background' => '#fff',
                'foreground' => $idc_teal,
                'src' => 'plus',
            ),
        ));

        // register an animated chart graphic
        acf_register_block_type(array(
            'name' => 'animated-chart',
            'title' => __('Animated Chart', 'ghint'),
            'description' => __('An animated chart', 'ghint'),
            'render_template' => 'templates/blocks/animated-chart.php',
            'category' => 'common',
            'mode' => 'preview',
            'supports' => [
                'align' => false,
                'anchor' => true,
                'customClassName' => true,
                'multiple' => true,
                'mode' => false,
                'jsx' => false
            ],
            'icon' => array(
                'background' => '#fff',
                'foreground' => $idc_purple,
                'src' => 'cover-image',
            ),
        ));

        // register an audience-image block
        acf_register_block_type(array(
            'name' => 'audience-image',
            'title' => __('Audience Image', 'ghint'),
            'description' => __('The Audience Image includes a half cicrle background with options to choose the color.', 'ghint'),
            'render_template' => 'templates/blocks/audience-image.php',
            'category' => 'common',
            'mode' => 'preview',
            'supports' => [
                'align'  => false,
                'anchor' => false,
                'customClassName' => false,
                'multiple' => true,
                'jsx' => true,
            ],

            'icon' => array(
                'background' => '#fff',
                'foreground' => $idc_purple,
                'src' => 'format-image',
            ),
        ));

        // register an author block
        acf_register_block_type(array(
            'name' => 'author',
            'title' => __('Author', 'ghint'),
            'description' => __('Display an IDC Team Member in an Author block, meant for use on a News & Resources post', 'ghint'),
            'render_template' => 'templates/blocks/author.php',
            'enqueue_script' => get_template_directory_uri() . '/dist/static/scripts/blocks.js',
            'category' => 'common',
            'mode' => 'preview',
            'supports' => [
                'align' => false,
                'anchor' => false,
                'customClassName' => false,
                'multiple' => false,
                'mode' => false,
                'jsx' => true,
            ],
            'icon' => array(
                'background' => '#fff',
                'foreground' => $idc_purple,
                'src' => 'businesswoman',
            ),
            'post_types' => array('post')
        ));

        // register a widget block for the GET START animated circle button
        acf_register_block_type(array(
            'name' => 'button_circle_animate',
            'title' => __('Widget: Button Circle Animated', 'ghint'),
            'description' => __('An animated circle button', 'ghint'),
            'render_template' => 'templates/widgets/button-circle-animate.php',
            'category' => 'widgets',
            'mode' => 'preview',
            'supports' => [
                'align' => ['wide', 'full'],
                'multiple' => false,
                'mode' => false,
                'jsx' => true
            ],
            'icon' => array(
                'background' => '#fff',
                'foreground' => $idc_purple,
                'src' => 'button',
            ),
        ));

        // register a career-opportunities block
        acf_register_block_type(array(
            'name' => 'career-opportunities',
            'title' => __('Career Opportunities', 'ghint'),
            'description' => __('Renders out Job Postings with filtering', 'ghint'),
            'render_template' => 'templates/blocks/career-opportunities.php',
            'enqueue_script' => get_template_directory_uri() . '/dist/static/scripts/blocks.js',
            'category' => 'common',
            'mode' => 'preview',
            'supports' => [
                'align' => false,
                'anchor' => false,
                'customClassName' => false,
                'multiple' => true,
                'mode' => false,
                'jsx' => true,
            ],
            'icon' => array(
                'background' => '#fff',
                'foreground' => $idc_purple,
                'src' => 'building',
            ),
        ));

        // register a feature-image block
        acf_register_block_type(array(
            'name' => 'feature-image',
            'title' => __('Feature Image', 'ghint'),
            'description' => __('Featured circular image with multiple options for styling', 'ghint'),
            'render_template' => 'templates/blocks/feature-image.php',
            'category' => 'common',
            'mode' => 'preview',
            'supports' => [
                'align' => false,
                'anchor' => false,
                'customClassName' => false,
                'multiple' => true,
                'jsx' => true,
            ],
            'icon' => array(
                'background' => '#fff',
                'foreground' => $idc_purple,
                'src' => 'format-image',
            ),
        ));

        // register a footer-form block
        acf_register_block_type(array(
            'name' => 'footer-form',
            'title' => __('Footer Form Widget', 'ghint'),
            'description' => __('The Footer Form Widget will appear on any page or posts when specified.', 'ghint'),
            'render_template' => 'templates/widgets/footer-form.php',
            'category' => 'common',
            'mode' => 'preview',
            'supports' => [
                'align' => false,
                'anchor' => false,
                'customClassName' => false,
                'multiple' => false,
                'jsx' => true,
            ],
            'icon' => array(
                'background' => $idc_teal,
                'foreground' => $idc_purple,
                'src' => 'forms',
            ),
        ));

        // register a masthead-blog block
        acf_register_block_type(array(
            'name' => 'masthead-blog',
            'title' => __('Masthead for News & Resources', 'ghint'),
            'description' => __('A masthead block for News & Resources Page', 'ghint'),
            'render_template' => 'templates/blocks/masthead-blog.php',
            'category' => 'common',
            'mode' => 'preview',
            'supports' => [
                'align' => false,
                'anchor' => true,
                'customClassName' => true,
                'multiple' => false,
                'mode' => true,
                'jsx' => true
            ],
            'icon' => array(
                'background' => '#fff',
                'foreground' => $idc_teal,
                'src' => 'cover-image',
            ),
            'post_types' => array('page'),
        ));

        // register a masthead-contact block
        acf_register_block_type(array(
            'name' => 'masthead-contact',
            'title' => __('Masthead for the Contact Page', 'ghint'),
            'description' => __('A masthead block for the Contact Page', 'ghint'),
            'render_template' => 'templates/blocks/masthead-contact.php',
            'category' => 'common',
            'mode' => 'preview',
            'supports' => [
                'align' => false,
                'anchor' => true,
                'customClassName' => true,
                'multiple' => false,
                'mode' => false,
                'jsx' => true
            ],
            'icon' => array(
                'background' => '#fff',
                'foreground' => $idc_purple,
                'src' => 'cover-image',
            ),
            'post_types' => array('page'),
        ));

        // register a masthead-page block
        acf_register_block_type(array(
            'name' => 'masthead-page',
            'title' => __('Masthead for Page', 'ghint'),
            'description' => __('A masthead block for pages', 'ghint'),
            'render_template' => 'templates/blocks/masthead-page.php',
            'category' => 'common',
            'mode' => 'preview',
            'supports' => [
                'align' => false,
                'anchor' => true,
                'customClassName' => true,
                'multiple' => false,
                'mode' => false,
                'jsx' => true
            ],
            'icon' => array(
                'background' => '#fff',
                'foreground' => $idc_purple,
                'src' => 'cover-image',
            ),
            'post_types' => array('page'), //Is there a way to keep this on all pages excpect 43 === News & Insights
        ));

        // register a news-resources block
        acf_register_block_type(array(
            'name' => 'news-resources',
            'title' => __('News & Resources', 'ghint'),
            'description' => __('A custom block to display 3 posts from News & Resources.', 'ghint'),
            'render_template' => 'templates/blocks/news-resources.php',
            'category' => 'formatting',
            'mode' => 'auto',
            'supports' => [
                'align' => array('full'),
                'anchor' => true,
                'customClassName' => true,
                'multiple' => false,
                'jsx' => true
            ],
            'icon' => array(
                'background' => '#fff',
                'foreground' => $idc_teal,
                'src' => 'admin-post',
            ),
        ));

        // register a partners block
        acf_register_block_type(array(
            'name' => 'partners',
            'title' => __('Partners', 'ghint'),
            'description' => __('A block for displaying Partners', 'ghint'),
            'render_template' => 'templates/blocks/partners.php',
            'category' => 'common',
            'mode' => 'preview',
            'supports' => [
                'align' => false,
                'anchor' => true,
                'customClassName' => true,
                'multiple' => false,
                'mode' => false,
                'jsx' => true
            ],
            'icon' => array(
                'background' => '#fff',
                'foreground' => $idc_purple,
                'src' => 'groups',
            ),
        ));

        // register a solutions block
        acf_register_block_type(array(
            'name' => 'solutions-block',
            'title' => __('Solutions', 'ghint'),
            'description' => __('A solutions block to highlight IDC\'s information about the company and services provided. This block provides a similar look to the masthead block.', 'ghint'),
            'render_template' => 'templates/blocks/solutions-block.php',
            'category' => 'common',
            'mode' => 'preview',
            'supports' => [
                'align' => false,
                'anchor' => true,
                'customClassName' => true,
                'multiple' => true,
                'mode' => true,
                'jsx' => true,
            ],
            'icon' => array(
                'background' => '#fff',
                'foreground' => $idc_purple,
                'src' => 'cover-image',
            ),
        ));

        // register a solutions-image block
        acf_register_block_type(array(
            'name' => 'solutions-image',
            'title' => __('Solutions Image', 'ghint'),
            'description' => __('Stylized circular image', 'ghint'),
            'render_template' => 'templates/blocks/solutions-image.php',
            'category' => 'common',
            'mode' => 'preview',
            'supports' => [
                'align' => false,
                'anchor' => false,
                'customClassName' => false,
                'multiple' => true,
                'mode' => false,
                'jsx' => false,
            ],
            'icon' => array(
                'background' => '#fff',
                'foreground' => $idc_purple,
                'src' => 'format-image',
            ),
        ));

        // register a team-slider block
        acf_register_block_type(array(
            'name' => 'team-slider',
            'title' => __('Team Slider', 'ghint'),
            'description' => __('A slider to showcase the team members.', 'ghint'),
            'render_template' => 'templates/blocks/team-slider.php',
            'category' => 'common',
            'mode' => 'preview',
            'supports' => [
                'align' => false,
                'anchor' => true,
                'customClassName' => true,
                'multiple' => true,
                'mode' => true,
                'jsx' => true
            ],
            'icon' => array(
                'background' => '#fff',
                'foreground' => $idc_purple,
                'src' => 'groups',
            ),
        ));

        // register a testimonials block
        acf_register_block_type(array(
            'name' => 'testimonials',
            'title' => __('Testimonials', 'ghint'),
            'description' => __('Testimonials block', 'ghint'),
            'render_template' => 'templates/blocks/testimonials.php',
            'category' => 'common',
            'mode' => 'auto',
            'supports' => [
                'align' => false,
                'anchor' => true,
                'customClassName' => true,
                'multiple' => true,
                'mode' => false,
                'jsx' => false
            ],
            'icon' => array(
                'background' => '#fff',
                'foreground' => $idc_purple,
                'src' => 'testimonial',
            ),
        ));

        // register a video block
        acf_register_block_type(array(
            'name' => 'video-block',
            'title' => __('Video Block', 'ghint'),
            'description' => __('Video block', 'ghint'),
            'render_template' => 'templates/blocks/video-block.php',
            'category' => 'formatting',
            'mode' => 'preview',
            'supports' => [
                'align' => false,
                'anchor' => true,
                'customClassName' => false,
                'multiple' => true,
                'mode' => true,
                'jsx' => true
            ],
            'icon' => array(
                'background' => $idc_purple,
                'foreground' => $idc_teal,
                'src' => 'video-alt3',
            ),
        ));

        // register a widget company info block
        acf_register_block(array(
            'name' => 'widget_company_information',
            'title' => __('Widget: Company Information'),
            'description' => __('A custom widget block for displaying company information in the footer. This widget may only be used once.'),
            'render_template' => get_template_directory() . '/templates/widgets/company-info.php',
            'enqueue_style' => get_template_directory_uri() . '/dist/static/styles/widgets.css',
            'category' => 'widgets',
            'supports' => [
                'align_text' => true,
                'multiple' => false,
            ],
            'mode' => 'preview',
            'icon' => array(
                'background' => '#fff',
                'foreground' => $idc_purple,
                'src' => 'screenoptions',
            ),
            'keywords' => array('company', 'widget', 'information'),
        ));
    }
}
add_action('acf/init', __NAMESPACE__ . '\\custom_acf_init');

/**
 * Widgets
 */
function custom_widgets()
{
    /* Footer Col 1 : Widget Company Info */
    register_sidebar([
        'id' => 'footer-col-one',
        'name' => __('Footer Column 1: Company Information', 'ghint'),
        'description' => __('Used within the footer column 1 of the website.', 'ghint'),
        'before_widget' => '<div id="%1$s" class="col footer-widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<p class="%1$s h6">',
        'after_title' => '</p>',
    ]);

    /* Footer Col 2 : Menu Items*/
    register_sidebar([
        'id' => 'footer-col-two',
        'name' => __('Footer Column 2: Menu Links', 'ghint'),
        'description' => __('Used within the footer column 2 of the website. This area is reserved for Footer Menu 1 and 2.', 'ghint'),
        'before_widget' => '<nav id="%1$s" class="col c-footer__nav c-footer__nav--widget %2$s">',
        'after_widget' => '</nav>',
        'before_title' => '<h6 class="widget-title">',
        'after_title' => '</h6>',
    ]);
    /* Footer Col 3 : CTA*/
    register_sidebar([
        'id' => 'footer-col-three',
        'name' => __('Footer Column 3: CTA Button', 'ghint'),
        'description' => __('Used within the footer column 3 of the website. This area is reserved for the "Get Started" button and will appear as a circle with an animated border.', 'ghint'),
        'before_widget' => '<div id="%1$s" class="col w-btn-circle-container  %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h6 class="widget-title">',
        'after_title' => '</h6>',
    ]);

    /* Footer Form Block Template Part : Get Started */
    register_sidebar([
        'id' => 'footer-form',
        'name' => __('Footer Form Template Block: Get Started', 'ghint'),
        'description' => __('Used within the upper footer area of pages and posts..', 'ghint'),
        'before_widget' => '<div id="%1$s" class="w-footer-form %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h6 class="widget-title">',
        'after_title' => '</h6>',
    ]);
    /* Teams Template Part : Join Our Team */
    register_sidebar([
        'id' => 'join-our-team',
        'name' => __('Team Template Block: Join Our Team', 'ghint'),
        'description' => __('Used within the upper footer area of the Our Team and Single Team Member pages.', 'ghint'),
        'before_widget' => '<div id="%1$s" class="bg-color--teal-light w-join-our-team %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h6 class="widget-title">',
        'after_title' => '</h6>',
    ]);

    /* Search Results Template Part : Sidebar */
    register_sidebar([
        'id' => 'search-results',
        'name' => __('Search Results CTA Items Template Block.', 'ghint'),
        'description' => __('This area displays in the sidebar of the Search Results page.', 'ghint'),
        'before_widget' => '<div id="%1$s" class="w-search-results %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h6 class="widget-title">',
        'after_title' => '</h6>',
    ]);

    /* Search Results Template Part : Footer News & Resrouces */
    register_sidebar([
        'id' => 'search-results-news-resources',
        'name' => __('Search Results News & Resources Template Block.', 'ghint'),
        'description' => __('This area displays in the footer area of the Search Results page.', 'ghint'),
        'before_widget' => '<div id="%1$s" class="w-search-results %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h6 class="widget-title">',
        'after_title' => '</h6>',
    ]);
}
add_action('widgets_init', __NAMESPACE__ . '\\custom_widgets');

add_action('ghint/store_pardot_data', function (string $endpoint, WP_REST_Request $request) {
    $client = new Client(['base_uri' => 'https://go.pardot.com/l/756273/']);
    $client->post($endpoint, ['form_params' => $request->get_body_params()]);
}, 10, 2);

add_filter('ghint/recaptcha_validate', function (bool $valid, string $action, WP_REST_Request $request) {

    $curlData = array(
        'secret' => '6Lf_18cgAAAAAOEMBJr-Tu0hjRcZCHWdffNpJc7C',
        'response' => $request->get_param('hiddenRecaptcha')
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($curlData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $curLResponse = curl_exec($ch);

    $captchaResponse = json_decode($curLResponse, true);
    return $captchaResponse['success'] == '1' && $captchaResponse['score'] >= .5 && $captchaResponse['action'] === $action;
}, 10, 3);

add_filter('ghint/protected-attachment/is_gated', function ($isGated, $post) {
    return $post ? get_field('gated', $post->ID) : false;
}, 10, 2);

add_filter('ghint/protected-attachment/is_viewable', function ($canView) {
    $auth = $_SERVER['HTTP_X_MEDIA_GATE_AUTHORIZATION'] ?? false;
    if (!$canView && $auth) {
        $decoded = urldecode($auth);
        [$email, $key] = explode(':', base64_decode(explode(' ', $decoded)[1]));
        try {
            $user = new User($email, new Token($key));
            return $user->login();
        } catch (LoginException $e) {
            return false;
        }
    }
    return $canView;
});

add_action('ghint/protected-attachment/override-unauthorized-handler', function ($attachment) {
    $redirect = parse_url(wp_get_referer() ?: home_url());
    $path = $redirect['path'] ?? '/';
    parse_str($redirect['query'] ?? '', $query);
    $query['gated_form_auth'] = rawurlencode(wp_get_attachment_url($attachment->ID));

    wp_redirect($path . '?' . http_build_query($query));
    exit;
}, 10);

add_action('ghint/component-render-c-footer', function () {
    get_template_part('templates/forms/form', 'gated-media');
});

add_filter('ghint/protected-content/hide-gated-content', function (string $content, WP_Post $post) {
    if (!ProtectedUploads::userCanView($post)) {
        $contentParts = get_extended(apply_filters('the_content', $post->post_content));
        $truncated = $contentParts['extended'] ? $contentParts['main'] : get_the_excerpt($post);
        $readMore = $contentParts['more_text'] ?: _x('Read More', 'Gated Post Content CTA Text', 'ghint');
        return implode('', [
            apply_filters('ghint/protected-content/truncated-content', $truncated, $post),
            apply_filters('ghint/protected-content/read-more-cta', $readMore, $post),
        ]);
    }
    return apply_filters('the_content', $content);
}, 10, 2);

add_filter('ghint/protected-content/read-more-cta', function (string $readMore) {
    return sprintf('<div class="container--padding-bottom-64"><a class="c-popup-link mfp-inline wp-block-button__link has-teal-background-color has-background" href="%s">%s</a></div>', '#gated-media-form', $readMore);
});

/**
 * Update Excerpt Length
 */
add_filter('excerpt_length', fn ($len) => 15, 999);

/**
 * Update Post Navigation
 */
add_filter('previous_posts_link_attributes', function () {
    return 'class="wp-block-button__link has-teal-background-color has-background"';
});
add_filter('next_posts_link_attributes', function () {
    return 'class="wp-block-button__link has-teal-background-color has-background"';
});

/**
 * Add menu_order filter to team members for Pluging Post-Types Order
 */
add_filter("rest_team-members_collection_params", function ($query) {
    $query['orderby']['enum'][] = 'menu_order';
    return $query;
});

/**
 * Add the duplicate link to action list for post_row_actions
 * for "post" and custom post types
 */
add_filter('post_row_actions', __NAMESPACE__ . '\\idc_duplicate_post_link', 10, 2);

function idc_duplicate_post_link($actions, $post)
{
    if (!current_user_can('edit_posts')) {
        return $actions;
    }

    $url = wp_nonce_url(
        add_query_arg(
            array(
                'action' => 'idc_duplicate_post_as_draft',
                'post' => $post->ID,
            ),
            'admin.php'
        ),
        basename(__FILE__),
        'duplicate_nonce'
    );

    $actions['duplicate'] = '<a href="' . $url . '" title="Duplicate this item" rel="permalink">Duplicate</a>';

    return $actions;
}

/**
 * Function creates post duplicate as a draft and redirects then to the edit post screen
 */
add_action('admin_action_idc_duplicate_post_as_draft', __NAMESPACE__ . '\\idc_duplicate_post_as_draft');

function idc_duplicate_post_as_draft()
{

    // check if post ID has been provided and action
    if (empty($_GET['post'])) {
        wp_die('No post to duplicate has been provided!');
    }

    // Nonce verification
    if (!isset($_GET['duplicate_nonce']) || !wp_verify_nonce($_GET['duplicate_nonce'], basename(__FILE__))) {
        return;
    }

    // Get the original post id
    $post_id = absint($_GET['post']);

    // And all the original post data then
    $post = get_post($post_id);

    /**
     * if you don't want current user to be the new post author,
     * then change next couple of lines to this: $new_post_author = $post->post_author;
     */
    $current_user = wp_get_current_user();
    $new_post_author = $current_user->ID;

    // if post data exists (I am sure it is, but just in a case), create the post duplicate
    if ($post) {

        // new post data array
        $args = array(
            'comment_status' => $post->comment_status,
            'ping_status'    => $post->ping_status,
            'post_author'    => $new_post_author,
            'post_content'   => $post->post_content,
            'post_excerpt'   => $post->post_excerpt,
            'post_name'      => $post->post_name,
            'post_parent'    => $post->post_parent,
            'post_password'  => $post->post_password,
            'post_status'    => 'draft',
            'post_title'     => $post->post_title,
            'post_type'      => $post->post_type,
            'to_ping'        => $post->to_ping,
            'menu_order'     => $post->menu_order
        );

        // insert the post by wp_insert_post() function
        $new_post_id = wp_insert_post($args);

        /**
         * get all current post terms ad set them to the new post draft
         */
        $taxonomies = get_object_taxonomies(get_post_type($post)); // returns array of taxonomy names for post type, ex array("category", "post_tag");
        if ($taxonomies) {
            foreach ($taxonomies as $taxonomy) {
                $post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
                wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
            }
        }

        // duplicate all post meta
        $post_meta = get_post_meta($post_id);
        if ($post_meta) {

            foreach ($post_meta as $meta_key => $meta_values) {

                if ('_wp_old_slug' == $meta_key) { // do nothing for this meta key
                    continue;
                }

                foreach ($meta_values as $meta_value) {
                    add_post_meta($new_post_id, $meta_key, $meta_value);
                }
            }
        }

        // redirect to all posts with a message
        wp_safe_redirect(
            add_query_arg(
                array(
                    'post_type' => ('post' !== get_post_type($post) ? get_post_type($post) : false),
                    'saved' => 'post_duplication_created' // just a custom slug here
                ),
                admin_url('edit.php')
            )
        );
        exit;
    } else {
        wp_die('Post creation failed, could not find original post.');
    }
}

/**
 * In case we decided to add admin notices
 */
add_action('admin_notices', __NAMESPACE__ . '\\rudr_duplication_admin_notice');

function rudr_duplication_admin_notice()
{
    // Get the current screen
    $screen = get_current_screen();

    if ('edit' !== $screen->base) {
        return;
    }

    //Checks if settings updated
    if (isset($_GET['saved']) && 'post_duplication_created' == $_GET['saved']) {
        echo '<div class="notice notice-success is-dismissible"><p>Post copy created.</p></div>';
    }
}
