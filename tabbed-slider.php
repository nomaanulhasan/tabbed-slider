<?php
/**
 * Plugin Name: Tabbed Slider
 * Plugin URI: https://nomaanulhasan.com/wordpress/plugins/tabbed-slider
 * Description: A custom Elementor widget that adds a tabbed slider component.
 * Version: 1.2.0
 * Author: Syed NomanulHasan
 * Author URI: https://nomaanulhasan.com
 * WordPress Tabbed Slider Component - Mobile First
 * Elementor Compatible
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: tabbed-slider
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

/**
 * Admin: Intro Page (plain HTML & CSS)
 */
function tabbed_slider_register_intro_page() {
    add_menu_page(
        'Tabbed Slider',
        'Tabbed Slider',
        'manage_options',
        'tabbed-slider',
        'tabbed_slider_render_intro_page',
        'dashicons-slides',
        80
    );
}

add_action('admin_menu', 'tabbed_slider_register_intro_page');

/**
 * Render the plugin intro page with simple HTML and CSS
 */
function tabbed_slider_render_intro_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    ?>
    <div class="ts-admin-wrap">
        <style>
            .ts-admin-wrap { max-width: 960px; margin: 24px auto; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, "Fira Sans", "Droid Sans", "Helvetica Neue", Arial, sans-serif; color: #1d2327; }
            .ts-hero { background: #f6f7f7; border: 1px solid #dcdcde; border-radius: 8px; padding: 28px; display: flex; gap: 20px; align-items: center; }
            .ts-hero-icon { width: 56px; height: 56px; display: inline-flex; align-items: center; justify-content: center; background: #2271b1; color: #fff; border-radius: 8px; }
            .ts-hero h1 { margin: 0 0 6px; font-size: 22px; }
            .ts-hero p { margin: 0; color: #3c434a; }
            .ts-section { margin-top: 28px; background: #fff; border: 1px solid #dcdcde; border-radius: 8px; }
            .ts-section h2 { margin: 0; padding: 16px 20px; font-size: 18px; border-bottom: 1px solid #dcdcde; background: #fbfbfc; }
            .ts-section .ts-content { padding: 16px 20px; }
            .ts-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 16px; }
            .ts-card { border: 1px solid #e2e4e7; border-radius: 8px; padding: 16px; background: #fff; }
            .ts-card h3 { margin: 0 0 8px; font-size: 15px; }
            .ts-card p { margin: 0 0 10px; color: #50575e; }
            .ts-badge { display: inline-block; padding: 2px 8px; border-radius: 999px; background: #eef6ff; color: #1d4ed8; font-size: 12px; border: 1px solid #bfdbfe; }
            .ts-kbd { display: inline-block; padding: 2px 6px; border: 1px solid #c3c4c7; border-bottom-width: 2px; background: #f6f7f7; border-radius: 4px; font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace; font-size: 12px; }
            .ts-code { display: block; background: #0b1520; color: #d1e4ff; padding: 12px 14px; border-radius: 6px; overflow: auto; font-size: 13px; }
            .ts-list { margin: 0; padding-left: 18px; }
            .ts-list li { margin: 6px 0; }
            .ts-hint { color: #3c434a; font-size: 13px; }
            .ts-footer { margin-top: 16px; color: #6b7280; font-size: 12px; }
            .ts-highlight { color: #2271b1; }
        </style>

        <div class="ts-hero">
            <div class="ts-hero-icon">
                <span class="dashicons dashicons-slides" style="font-size:24px; line-height:56px; width:56px; height:56px;"></span>
            </div>
            <div>
                <h1>Tabbed Slider for WordPress</h1>
                <p>Create a mobile-first, accessible tabbed slider anywhere using a shortcode or the Elementor widget.</p>
            </div>
        </div>

        <div class="ts-section">
            <h2>Quick Start</h2>
            <div class="ts-content ts-grid">
                <div class="ts-card">
                    <h3>1) Insert via Shortcode <span class="ts-badge">Fastest</span></h3>
                    <p>Add this into any post, page, or widget area:</p>
                    <code class="ts-code">[tabbed_slider autoplay="false" autoplay_delay="6000"]</code>
                    <ul class="ts-list">
                        <li><strong>autoplay</strong>: <span class="ts-kbd">true</span> or <span class="ts-kbd">false</span></li>
                        <li><strong>autoplay_delay</strong>: milliseconds (e.g. <span class="ts-kbd">6000</span>)</li>
                    </ul>
                    <p class="ts-hint">Use block editor: add a Shortcode block and paste the code above.</p>
                </div>
                <div class="ts-card">
                    <h3>2) Use Elementor Widget</h3>
                    <p>Open Elementor, search for <span class="ts-highlight">Tabbed Slider</span>, drag it into your layout, and tune settings (autoplay, delay) in the widget panel.</p>
                </div>
                <div class="ts-card">
                    <h3>3) What it Renders</h3>
                    <p>The slider shows tab buttons with left/right arrows and slides content for “From” and “To” sections. It’s mobile‑first and keyboard accessible.</p>
                </div>
            </div>
        </div>

        <div class="ts-section">
            <h2>How It Works</h2>
            <div class="ts-content">
                <ul class="ts-list">
                    <li>Front-end CSS/JS are loaded from the plugin&apos;s <span class="ts-kbd">includes/assets</span> folder.</li>
                    <li>Shortcode <span class="ts-kbd">[tabbed_slider]</span> renders the component with sensible demo content by default.</li>
                    <li>Elementor users get a dedicated widget registered by the plugin.</li>
                </ul>
                <p class="ts-footer">Need help? See the plugin readme or contact the author.</p>
            </div>
        </div>
    </div>
    <?php
}

