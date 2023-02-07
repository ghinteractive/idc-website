<?php

use Roots\Assets;

/**
 * Animated Chart Block Template.
 *
 * @var   array $block The block settings and attributes.
 * @var   string $content The block inner HTML (empty).
 * @var   bool $is_preview True during AJAX preview.
 * @var   (int|string) $post_id The post ID this block is saved to.
 */

// SET DEFAULT CLASS & GET ADDITIONAL CLASS NAMES & ALIGN CLASSES FROM GUTENBERG
$className = 'c-animated-chart-block';

// Create id attribute allowing for custom "anchor" value.
$id = apply_filters('acf_gutenberg_block_id', 'animated-chart-', $block);

// Load values and assign defaults.
$color_theme = get_field('select_color_theme');
$chart_type = get_field('select_chart_type');
?>


<?php if(is_admin()): ?>
<section id="<?= esc_attr($id) ?>">
    <div class="<?= $className ?>__inner container container--padding-x">
        <div class="chartColor-<?= $color_theme ?>">
            <?php
            //admin specific previews for lottie since wp-admin isn't great for displaying these js-driven items
            switch ($chart_type) {
                case 'bar':
                    //svg - export to allow css tinting
                    echo '<div class="chart-bar"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256"><defs><clipPath id="a"><path style="fill:none" d="M0 0h256v256H0z"/></clipPath></defs><g style="clip-path:url(#a)"><path d="M34 162.29c0-8.84 7.16-16 16-16s16 7.16 16 16V254c0 1.1-.9 2-2 2H36c-1.1 0-2-.9-2-2v-91.71Zm52-36.57c0-8.84 7.16-16 16-16s16 7.16 16 16V254c0 1.1-.9 2-2 2H88c-1.1 0-2-.9-2-2V125.71Zm52-45.71c0-8.84 7.16-16 16-16s16 7.16 16 16V254c0 1.1-.9 2-2 2h-28c-1.1 0-2-.9-2-2V80Z" style="isolation:isolate;opacity:.2;fill:#771b61"/><path d="M190 21.62C190 12.99 197.16 6 206 6s16 7 16 15.62v232.43c0 1.08-.9 1.95-2 1.95h-28c-1.1 0-2-.87-2-1.95V21.62Z" style="fill:#771b61"/></g></svg></div>';
                    break;
                case 'circle':
                    //svg - export to allow css tinting
                    echo '<div class="chart-circle"><svg width="256" height="256" fill="none" xmlns="http://www.w3.org/2000/svg"><circle opacity=".15" cx="128" cy="128" r="112" stroke="#3CAFAB" stroke-width="32"/><path d="M128 16a111.999 111.999 0 0 1 79.196 191.196 112.004 112.004 0 0 1-172.32-16.972A111.999 111.999 0 0 1 16 128" stroke="#3CAFAB" stroke-width="32" stroke-linecap="round"/></svg></div>';
                    break;
                case 'line':
                    //svg - export to allow css tinting
                    echo '<div class="chart-line"><svg width="256" height="254" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#a)" stroke="#F1AE4B" stroke-width="32" stroke-linecap="round" stroke-linejoin="round"><path d="m183 95 57-78"/><path opacity=".8" d="M160.123 76.867 183 95"/><path opacity=".6" d="m85.5 171 74.623-94.133"/><path opacity=".4" d="m64.5 154 21 17"/><path opacity=".2" d="m16 214 48.5-60"/></g><defs><clipPath id="a"><path fill="#fff" d="M0 0h256v254H0z"/></clipPath></defs></svg></div>';
                    break;
            }?>
        </div>
    </div>
</section>
<?php else: ?>
<section id="<?= esc_attr($id) ?>">
    <div class="<?= $className ?>__inner container container--padding-x">
        <?php
            //generate a unique dom ID for this element each time the page is loaded
            $lottieUniqID = "lottie_".uniqid();
            //animateToFrame is only used for the circle graph
            $animateToFrame = "0";
            if($chart_type === "circle"){
                //get percentage of completion to animate the chart to 0-100
                $chart_value = get_field('number_chart_value');
                //there are 24 frames in the animation. percentage of completion can be set e.g. 50% is 24*.50 = 12 (stop at frame 12)
                $animateToFrame = round(24*($chart_value/100)) - .5; //tweak percentage value to help with landing on a partially rendered frame
            }
        ?>
        <div class="<?= $className ?> <?= $className ?>--<?= $chart_type ?> lottieAnim chartColor-<?= $color_theme ?>" id="<?= $lottieUniqID ?>" data-animation-end="<?= $animateToFrame ?>" data-animation-playing="false">
            <?php
                /*
                Values of note in parent div:
                    id - used to reference this element, also used to generate JS var names referenced with the window object
                    data-animation-end - js reference used to determine when to stop the animation
                    data-animation-playing - defaults to false, updated via js when the element is in the viewport
                */
                switch ($chart_type) {
                    case 'bar':
                        //generate the lottie object
                        echo    '<script>'.
                                    'var '.$lottieUniqID.' = lottie.loadAnimation({'.
                                        'container: document.getElementById(\''.$lottieUniqID.'\'),'.
                                        'renderer: \'svg\','.
                                        'loop: false,'.
                                        'autoplay: false,'.
                                        'path: \''.Assets\asset_path('lottie/animate_chart_bar.json').'\''.
                                    '});'.
                                '</script>';
                        break;
                    case 'circle':
                        //generate the lottie object
                        echo    '<script>'.
                                    'var '.$lottieUniqID.' = lottie.loadAnimation({'.
                                        'container: document.getElementById(\''.$lottieUniqID.'\'),'.
                                        'renderer: \'svg\','.
                                        'loop: false,'.
                                        'autoplay: false,'.
                                        'path: \''.Assets\asset_path('lottie/animate_chart_circle.json').'\''.
                                    '});'.
                                '</script>';
                        //onEnterFrame event listener to stop the animation on the animateToFrame value
                        echo    '<script>'.
                                     $lottieUniqID .'.addEventListener(\'enterFrame\', () => {'.
                                        'if('. $lottieUniqID .'.currentFrame >= '. $animateToFrame .'){'.
                                             $lottieUniqID .'.removeEventListener(\'enterFrame\');'.
                                             $lottieUniqID .'.pause();'.
                                        '}'.
                                    '});'.
                                '</script>';
                        break;
                    case 'line':
                        //generate the lottie object
                        echo    '<script>'.
                                    'var '.$lottieUniqID.' = lottie.loadAnimation({'.
                                        'container: document.getElementById(\''.$lottieUniqID.'\'),'.
                                        'renderer: \'svg\','.
                                        'loop: false,'.
                                        'autoplay: false,'.
                                        'path: \''.Assets\asset_path('lottie/animate_chart_line.json').'\''.
                                    '});'.
                                '</script>';
                        break;
                }
            ?>
        </div>
    </div>
</section>
<?php endif; ?>