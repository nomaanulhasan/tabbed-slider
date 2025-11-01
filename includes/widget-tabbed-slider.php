<?php
/**
 * Elementor Tabbed Slider Widget
 *
 * @package TabbedSlider
 */

if (!defined('ABSPATH')) {
    exit;
}

class Tabbed_Slider_Elementor_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'tabbed_slider';
    }

    public function get_title() {
        return __('Tabbed Slider', 'tabbed-slider');
    }

    public function get_icon() {
        return 'eicon-slider-album';
    }

    public function get_categories() {
        return ['general'];
    }

    public function get_script_depends() {
        return ['tabbed-slider-js'];
    }

    public function get_style_depends() {
        return ['tabbed-slider-css'];
    }

    // Backwards compatibility for Elementor < 3.5
    protected function _register_controls() {
        $this->register_controls();
    }

    protected function register_controls() {
        // Items Repeater
        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'title',
            [
                'label' => __('Tab Title', 'tabbed-slider'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Tab', 'tabbed-slider'),
                'label_block' => true,
                'placeholder' => __('Tab Title', 'tabbed-slider'),
            ]
        );

        $repeater->add_control(
            'content_type',
            [
                'label' => __('Content Type', 'tabbed-slider'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'template',
                'options' => [
                    'template' => __('Elementor Template', 'tabbed-slider'),
                    'editor' => __('Text Editor', 'tabbed-slider'),
                ],
            ]
        );

        $repeater->add_control(
            'template_id',
            [
                'label' => __('Choose Template', 'tabbed-slider'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => $this->get_elementor_templates(),
                'condition' => [
                    'content_type' => 'template',
                ],
                'description' => __('Select an Elementor template to display as tab content. You can create templates in Templates > Saved Templates.', 'tabbed-slider'),
            ]
        );

        $repeater->add_control(
            'content',
            [
                'label' => __('Content', 'tabbed-slider'),
                'type' => \Elementor\Controls_Manager::WYSIWYG,
                'default' => __('Add your content here...', 'tabbed-slider'),
                'condition' => [
                    'content_type' => 'editor',
                ],
                'description' => __('Add any HTML content, widgets, or shortcodes here.', 'tabbed-slider'),
            ]
        );

        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'tabbed-slider'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'items',
            [
                'label' => __('Tabs', 'tabbed-slider'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'title' => __('Tab #1', 'tabbed-slider'),
                        'content_type' => 'editor',
                        'content' => __('Add your content here...', 'tabbed-slider'),
                    ],
                ],
                'title_field' => '{{{ title }}}',
            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label' => __('Enable Autoplay', 'tabbed-slider'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'tabbed-slider'),
                'label_off' => __('No', 'tabbed-slider'),
                'return_value' => 'yes',
                'default' => 'no',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'autoplay_delay',
            [
                'label' => __('Autoplay Delay (ms)', 'tabbed-slider'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 6000,
                'min' => 1000,
                'max' => 30000,
                'step' => 500,
                'condition' => [
                    'autoplay' => 'yes',
                ],
                'description' => __('Time between slides in milliseconds', 'tabbed-slider'),
            ]
        );

        $this->end_controls_section();

        // Style Section
        $this->start_controls_section(
            'style_section',
            [
                'label' => __('Style', 'tabbed-slider'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'tab_color',
            [
                'label' => __('Tab Background Color', 'tabbed-slider'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#FF6B35',
                'selectors' => [
                    '{{WRAPPER}} .ts-tab' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'content_padding',
            [
                'label' => __('Content Padding', 'tabbed-slider'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .ts-content-slide' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'content_bg_color',
            [
                'label' => __('Content Background Color', 'tabbed-slider'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ts-content-slide' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Get Elementor templates list
     */
    private function get_elementor_templates() {
        $templates = [];

        if (class_exists('\Elementor\Plugin')) {
            $posts = get_posts([
                'post_type' => 'elementor_library',
                'posts_per_page' => -1,
                'post_status' => 'publish',
            ]);

            $templates[''] = __('— Select —', 'tabbed-slider');

            foreach ($posts as $post) {
                $templates[$post->ID] = $post->post_title;
            }
        }

        return $templates;
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $items = $settings['items'] ?? [];

        $slider_id = 'tabbed-slider-' . $this->get_id();

        // Assets are automatically enqueued via get_script_depends() and get_style_depends()
        // But ensure they're registered (fallback)
        if (!wp_style_is('tabbed-slider-css', 'registered')) {
            tabbed_slider_register_assets();
        }
        wp_enqueue_style('tabbed-slider-css');
        wp_enqueue_script('tabbed-slider-js');

        // Get autoplay settings
        $settings = $this->get_settings_for_display();
        $autoplay = !empty($settings['autoplay']) && $settings['autoplay'] === 'yes';
        $autoplay_delay = !empty($settings['autoplay_delay']) ? absint($settings['autoplay_delay']) : 6000;

        $options = array(
            'autoplay' => $autoplay,
            'autoplay_delay' => $autoplay_delay
        );

        // Render slider
        if (empty($items)) {
            echo '<p>' . __('Please add at least one tab.', 'tabbed-slider') . '</p>';
            return;
        }

        $default_options = array(
            'autoplay' => false,
            'autoplay_delay' => 6000
        );

        $options = wp_parse_args($options, $default_options);
        $autoplay = filter_var($options['autoplay'], FILTER_VALIDATE_BOOLEAN);
        $autoplay_delay = absint($options['autoplay_delay']);

        ?>
        <div class="wp-tabbed-slider elementor-widget-tabbed-slider"
             id="<?php echo esc_attr($slider_id); ?>"
             data-autoplay="<?php echo $autoplay ? 'true' : 'false'; ?>"
             data-autoplay-delay="<?php echo esc_attr($autoplay_delay); ?>">
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
                                    type="button">
                                <?php echo esc_html($item['title'] ?? sprintf(__('Tab #%d', 'tabbed-slider'), $index + 1)); ?>
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

            <div class="ts-content-container">
                <div class="ts-content-track">
                    <?php foreach ($items as $index => $item): ?>
                        <div class="ts-content-slide <?php echo $index === 0 ? 'active' : ''; ?>"
                             data-index="<?php echo esc_attr($index); ?>">
                            <?php
                            $content_type = $item['content_type'] ?? 'editor';

                            if ($content_type === 'template' && !empty($item['template_id'])) {
                                // Render Elementor template
                                $template_id = (int)$item['template_id'];

                                // Check if we're in Elementor editor mode
                                if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                                    // In editor, show template content
                                    $template_content = \Elementor\Plugin::instance()->frontend->get_builder_content_for_display($template_id, true);
                                    if (!empty($template_content)) {
                                        echo $template_content;
                                    } else {
                                        echo '<div class="ts-content-inner"><p>' . sprintf(__('Template #%d not found or empty.', 'tabbed-slider'), $template_id) . '</p></div>';
                                    }
                                } else {
                                    // On frontend, render normally
                                    echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display($template_id);
                                }
                            } else {
                                // Render WYSIWYG editor content
                                $content = $item['content'] ?? '';
                                echo '<div class="ts-content-inner">' . wp_kses_post($content) . '</div>';
                            }
                            ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="ts-dots" role="tablist">
                <?php foreach ($items as $index => $item): ?>
                    <button class="ts-dot <?php echo $index === 0 ? 'active' : ''; ?>"
                            data-index="<?php echo esc_attr($index); ?>"
                            type="button"
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
}

