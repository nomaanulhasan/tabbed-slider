<?php
/**
 * Plugin Name: Tabbed Slider for Elementor
 * Plugin URI: https://nomaanulhasan.com/wordpress/plugins/tabbed-slider_v1.1.3.zip
 * Description: A custom Elementor widget that adds a tabbed slider component.
 * Version: 1.1.3
 * Author: Syed NomanulHasan
 * Author URI: https://nomaanulhasan.com
 * WordPress Tabbed Slider Component - Mobile First
 * Elementor Compatible
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: tabbed-slider-elementor
 *
 * @package TabbedSlider
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Render mobile-first tabbed slider
 *
 * @param array $items Array of items with 'title', 'from_items', 'to_items'
 * @param string $slider_id Unique ID for slider instance
 * @param array $options Configuration options: 'autoplay' (bool), 'autoplay_delay' (int in ms)
 * @return void
 */
function wp_tabbed_slider($items = array(), $slider_id = 'tabbed-slider-1', $options = array()) {
    if (empty($items)) {
        // Demo data matching the design
        $items = array(
            array(
                'title' => 'All Funds Budgeting',
                'from_items' => array(
                    array(
                        'title' => 'Data version control issue',
                        'points' => array(
                            'Teams manage Excel budget templates in SharePoint with the ability to update budget amounts as needed',
                            'No easy, systemic way to track versions'
                        )
                    ),
                    array(
                        'title' => 'Limited reporting capability',
                        'points' => array(
                            'Limited built-in analytical capability with considerable turnaround time for reporting cycle since effort is largely manual'
                        )
                    )
                ),
                'to_items' => array(
                    array(
                        'title' => 'Improved version tracking with centralised data repository',
                        'points' => array(
                            'Teams can manage write access and lock approved budget versions to prevent accidental edits',
                            'Ability to plan by WBS and reconcile to department results'
                        )
                    )
                )
            ),
        );
    }

    // Enqueue assets
    $plugin_dir = plugin_dir_path(__FILE__);
    $plugin_url = plugin_dir_url(__FILE__);

    $css_url = $plugin_url . 'includes/assets/css/tabbed-slider.css';
    $js_url = $plugin_url . 'includes/assets/js/tabbed-slider.js';

    // Fallback to theme if plugin context
    if (strpos(__FILE__, WP_PLUGIN_DIR) === false) {
        $theme_dir = get_template_directory_uri();
        $css_url = $theme_dir . '/inc/tabbed-slider.css';
        $js_url = $theme_dir . '/inc/tabbed-slider.js';
    }

    wp_enqueue_style('tabbed-slider-css', $css_url, array(), '1.0.0');
    wp_enqueue_script('tabbed-slider-js', $js_url, array('jquery'), '1.0.0', true);

    // Default options
    $default_options = array(
        'autoplay' => false,
        'autoplay_delay' => 6000
    );

    $options = wp_parse_args($options, $default_options);
    $autoplay = filter_var($options['autoplay'], FILTER_VALIDATE_BOOLEAN);
    $autoplay_delay = absint($options['autoplay_delay']);

    ?>
    <div class="wp-tabbed-slider"
         id="<?php echo esc_attr($slider_id); ?>"
         data-autoplay="<?php echo $autoplay ? 'true' : 'false'; ?>"
         data-autoplay-delay="<?php echo esc_attr($autoplay_delay); ?>">
        <!-- Tab Navigation with Arrows -->
        <div class="ts-tab-navigation">
            <button class="ts-nav-arrow ts-prev" aria-label="Previous" type="button">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>

            <div class="ts-tab-wrapper">
                <div class="ts-tab-track">
                    <?php foreach ($items as $index => $item): ?>
                        <button class="ts-tab <?php echo $index === 0 ? 'active' : ''; ?>"
                                data-index="<?php echo esc_attr($index); ?>"
                                type="button"
                                aria-label="<?php echo esc_attr($item['title']); ?>">
                            <?php echo esc_html($item['title']); ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>

            <button class="ts-nav-arrow ts-next" aria-label="Next" type="button">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
        </div>

        <!-- Content Slider -->
        <div class="ts-content-container">
            <div class="ts-content-track">
                <?php foreach ($items as $index => $item): ?>
                    <div class="ts-content-slide <?php echo $index === 0 ? 'active' : ''; ?>"
                         data-index="<?php echo esc_attr($index); ?>">

                        <!-- From Section -->
                        <div class="ts-section ts-from-section">
                            <h3 class="ts-section-title">From</h3>
                            <div class="ts-card">
                                <?php if (!empty($item['from_items'])): ?>
                                    <?php foreach ($item['from_items'] as $from_item): ?>
                                        <div class="ts-item">
                                            <h4 class="ts-item-title"><?php echo esc_html($from_item['title']); ?></h4>
                                            <?php if (!empty($from_item['points'])): ?>
                                                <ul class="ts-item-list">
                                                    <?php foreach ($from_item['points'] as $point): ?>
                                                        <li><?php echo esc_html($point); ?></li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- To Section -->
                        <div class="ts-section ts-to-section">
                            <h3 class="ts-section-title">To</h3>
                            <div class="ts-card">
                                <?php if (!empty($item['to_items'])): ?>
                                    <?php foreach ($item['to_items'] as $to_item): ?>
                                        <div class="ts-item">
                                            <h4 class="ts-item-title"><?php echo esc_html($to_item['title']); ?></h4>
                                            <?php if (!empty($to_item['points'])): ?>
                                                <ul class="ts-item-list">
                                                    <?php foreach ($to_item['points'] as $point): ?>
                                                        <li><?php echo esc_html($point); ?></li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Dots Indicator -->
        <div class="ts-dots" role="tablist" aria-label="Slide indicators">
            <?php foreach ($items as $index => $item): ?>
                <button class="ts-dot <?php echo $index === 0 ? 'active' : ''; ?>"
                        data-index="<?php echo esc_attr($index); ?>"
                        type="button"
                        role="tab"
                        aria-label="Go to slide <?php echo esc_attr($index + 1); ?>"
                        aria-selected="<?php echo $index === 0 ? 'true' : 'false'; ?>">
                </button>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        jQuery(document).ready(function($) {
            if (typeof initTabbedSlider === 'function') {
                initTabbedSlider('#<?php echo esc_js($slider_id); ?>', {
                    autoplay: <?php echo $autoplay ? 'true' : 'false'; ?>,
                    autoplayDelay: <?php echo esc_js($autoplay_delay); ?>
                });
            }
        });
    </script>
    <?php
}

/**
 * Shortcode: [tabbed_slider]
 */
function wp_tabbed_slider_shortcode($atts) {
    $atts = shortcode_atts(array(
        'id' => 'tabbed-slider-' . uniqid(),
        'autoplay' => 'false',
        'autoplay_delay' => '6000'
    ), $atts);

    $options = array(
        'autoplay' => filter_var($atts['autoplay'], FILTER_VALIDATE_BOOLEAN),
        'autoplay_delay' => absint($atts['autoplay_delay'])
    );

    ob_start();
    wp_tabbed_slider(array(), $atts['id'], $options);
    return ob_get_clean();
}
add_shortcode('tabbed_slider', 'wp_tabbed_slider_shortcode');

/**
 * Register Assets
 */
function tabbed_slider_register_assets() {
    $plugin_url = plugin_dir_url(__FILE__);

    wp_register_style(
        'tabbed-slider-css',
        $plugin_url . 'includes/assets/css/tabbed-slider.css',
        array(),
        '1.0.0'
    );

    wp_register_script(
        'tabbed-slider-js',
        $plugin_url . 'includes/assets/js/tabbed-slider.js',
        array('jquery'),
        '1.0.0',
        true
    );
}

// Register assets for frontend
add_action('wp_enqueue_scripts', 'tabbed_slider_register_assets');

// Register assets for Elementor editor and frontend
add_action('elementor/frontend/before_enqueue_scripts', function() {
    tabbed_slider_register_assets();
});

/**
 * Elementor Widget Registration
 */
function tabbed_slider_register_elementor_widgets($widgets_manager) {
    require_once __DIR__ . '/includes/widget-tabbed-slider.php';
    $widgets_manager->register(new \Tabbed_Slider_Elementor_Widget());
}

add_action('elementor/widgets/register', 'tabbed_slider_register_elementor_widgets');
