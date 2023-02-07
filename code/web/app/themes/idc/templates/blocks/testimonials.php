<?php

use Roots\Assets;

/**
 * Testimonials Block Template.
 *
 * @var   array $block The block settings and attributes.
 * @var   string $content The block inner HTML (empty).
 * @var   bool $is_preview True during AJAX preview.
 * @var   (int|string) $post_id The post ID this block is saved to.
 */

// SET DEFAULT CLASS & GET ADDITIONAL CLASS NAMES & ALIGN CLASSES FROM GUTENBERG
$className = 'c-testimonials-block';

// Create id attribute allowing for custom "anchor" value.
$id = apply_filters('acf_gutenberg_block_id', 'testimonials', $block);

// Load values and assign defaults.
$color_theme = get_field('select_color_theme');
$testimonial_type = get_field('select_testimonial_type');
$show_cta = get_field('show_call_to_action_button');
$testimonialID = "testimonial_" . uniqid();
$testimonialCount = 0;
?>


<?php if (is_admin()) : ?>
    <img src="<?php echo Assets\asset_path('images/tetimonials-admin.jpg'); ?>" alt="Click to edit settings" class="<?= $className ?>--admin">
<?php else :
    if (have_rows('testimonials')) {
        while (have_rows('testimonials')) : the_row();
            $testimonialCount++;
        endwhile;
    }
?>
    <section id="<?= esc_attr($id) ?>">
        <div class="<?= $className ?>__inner">
            <?php if ($testimonial_type === "video") : ?>
                <div class="<?= $className ?> <?= $className ?>--<?= $testimonial_type ?> container--padding-y-96" data-testimonial-id="<?= $testimonialID ?>">
                <?php else : ?>
                    <div class="<?= $className ?> <?= $className ?>--<?= $testimonial_type ?> testimonialColor-<?= $color_theme ?> container--padding-y-96" data-testimonial-id="<?= $testimonialID ?>">
                    <?php endif; ?>

                    <div class="container container--padding-x">
                        <?php

                        if ($testimonial_type === "video") :
                            $args = array('id' => $testimonialID, 'width' => 'wide');
                            get_template_part('partials/component', 'video-block', $args);
                        else :
                        ?>
                            <div class="<?= $className ?>--headerWrapper">
                                <div class="<?= $className ?>--headline">Customer Results</div>
                                <?php
                                if ($testimonialCount > 1 && $testimonial_type === "simple") : ?>
                                    <button class="simpleButtonUp desktop" data-testimonial-id="<?= $testimonialID ?>"><i class="fa-solid fa-chevrons-up"></i></button>
                                <?php
                                endif;
                                ?>
                            </div>
                            <div class="wrapper">
                                <?php
                                switch ($testimonial_type) {
                                    case 'primary': ?>
                                        <div class="testimonial">
                                            <figure>
                                                <?php
                                                echo '<blockquote>' . get_field('quote') . '</blockquote>';
                                                ?>
                                                <div class="caption-group">
                                                    <div class="img-container mobile">
                                                        <div class="c-feature-image-block c-feature-image-block--background-color-gold">
                                                            <div class="c-feature-image-block__background c-feature-image-block__background--simple"></div>
                                                            <div class="c-feature-image-block__foreground c-feature-image-block__foreground--clipped">
                                                                <div class="viewport">
                                                                    <?php $image = get_field('image');
                                                                    if (!empty($image)) : ?>
                                                                        <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />

                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                            <div class="c-feature-image-block__border" style="transform: rotate(-45deg);"></div>
                                                        </div>
                                                    </div>
                                                    <?php
                                                    echo '<figcaption>' . get_field('name_title');
                                                    if (get_field('company')) {
                                                        echo '<cite>' . get_field('company') . '</cite>';
                                                    }
                                                    echo '</figcaption>';
                                                    echo '</div>';
                                                    ?>
                                            </figure>
                                            <div class="img-container">
                                                <div class="c-feature-image-block c-feature-image-block--background-color-gold">
                                                    <div class="c-feature-image-block__background c-feature-image-block__background--simple"></div>
                                                    <div class="c-feature-image-block__foreground c-feature-image-block__foreground--clipped">
                                                        <div class="viewport">
                                                            <?php $image = get_field('image');
                                                            if (!empty($image)) : ?>
                                                                <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                    <div class="c-feature-image-block__border" style="transform: rotate(-45deg);"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php break;
                                    case 'simple':
                                        if (have_rows('testimonials')) :
                                            $dataIndex = 10;
                                            $dataInverseIndex = 1;
                                            while (have_rows('testimonials')) : the_row(); ?>
                                                <div class="testimonial" data-index="<?= $dataIndex; ?>" data-inverse-index="<?= $dataInverseIndex; ?>">
                                                    <figure>
                                                        <?php
                                                        echo '<blockquote>' . get_sub_field('quote') . '</blockquote>';
                                                        echo '<figcaption>' . get_sub_field('name_title');
                                                        if (get_sub_field('company')) {
                                                            echo '<cite>' . get_sub_field('company') . '</cite>';
                                                        }
                                                        echo '</figcaption>';
                                                        ?>
                                                    </figure>
                                                </div>
                                            <?php
                                                $dataIndex--;
                                                $dataInverseIndex++;
                                            endwhile;
                                            ?>
                                <?php
                                        endif;
                                        break;
                                }
                                ?>
                            </div>
                            <?php
                            $footerClass = "";
                            if ($show_cta === true && get_field('call_to_action_link')) {
                                $footerClass = "hasCTA";
                            } else {
                                $footerClass = "noCTA";
                            }
                            ?>
                            <div class="<?= $className ?>--footerWrapper <?= $footerClass ?>">
                                <?php if ($show_cta === true && get_field('call_to_action_link')) :
                                    $link = get_field('call_to_action_link');
                                    $link_url = $link['url'];
                                    $link_title = $link['title'] ? $link['title'] : 'More Customer Results';
                                    $link_target = $link['target'] ? $link['target'] : '_self';
                                ?>
                                    <a href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>" class="cta"><?= $link_title ?></a>
                                <?php endif;
                                if ($testimonialCount > 1 && $testimonial_type === "simple") : ?>
                                    <div class="buttons">
                                        <button class="simpleButtonDown" data-testimonial-id="<?= $testimonialID ?>"><i class="fa-solid fa-chevrons-down"></i></button>
                                        <button class="simpleButtonUp mobile" data-testimonial-id="<?= $testimonialID ?>"><i class="fa-solid fa-chevrons-up"></i></button>
                                    </div>
                                <?php
                                endif;
                                ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    </div>
                </div>
    </section>
<?php endif; ?>