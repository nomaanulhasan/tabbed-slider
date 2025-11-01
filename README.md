# Tabbed Slider for Elementor (Target: Mobile)

A professional, mobile-first WordPress plugin that adds a custom tabbed slider widget to Elementor page builder. Features single visible tab navigation with arrow controls, touch/swipe support, and flexible content options.

**Version:** 1.1.3
**Author:** Syed NomanulHasan
**Author URI:** https://nomaanulhasan.com

## Features

- ✅ **Mobile-First Design** - Optimized for mobile devices with responsive breakpoints
- ✅ **Single Visible Tab** - Only one tab visible at a time with arrow navigation
- ✅ **Elementor Integration** - Native Elementor widget with visual controls
- ✅ **Flexible Content** - Support for Elementor templates or WYSIWYG editor content
- ✅ **Touch/Swipe Support** - Native mobile swipe gestures
- ✅ **Keyboard Navigation** - Arrow keys, Home, End key support
- ✅ **Accessibility** - ARIA labels, focus states, keyboard navigation
- ✅ **Autoplay** - Optional autoplay with pause on hover/interaction
- ✅ **Dot Indicators** - Visual slide indicators at the bottom

## Requirements

- WordPress 5.0 or higher
- Elementor 3.0 or higher (for widget functionality)
- PHP 7.4 or higher

## Installation

### Standard Plugin Installation

1. Upload the `tabbed-slider` folder to `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. The "Tabbed Slider" widget will automatically appear in Elementor's widget panel

## File Structure

```
tabbed-slider/
├── tabbed-slider.php                    # Main plugin file
├── includes/
│   ├── assets/
│   │   ├── css/
│   │   │   └── tabbed-slider.css        # Stylesheet
│   │   └── js/
│   │       └── tabbed-slider.js         # JavaScript functionality
│   └── widget-tabbed-slider.php         # Elementor widget class
└── README.md                            # This file
```

## Usage

### Elementor Widget (Recommended)

1. **Open Elementor Editor** - Edit any page/post with Elementor
2. **Find Widget** - Search for "Tabbed Slider" in the widget panel
3. **Add Widget** - Drag and drop the widget onto your page
4. **Configure Tabs**:
   - Click the "+ Add Item" button to add tabs
   - For each tab:
     - Set the **Tab Title**
     - Choose **Content Type**:
       - **Elementor Template** - Select a saved Elementor template
       - **Text Editor** - Use WYSIWYG editor for custom HTML/content
     - Configure content based on your chosen type
5. **Configure Autoplay** (optional):
   - Enable "Enable Autoplay" toggle
   - Set "Autoplay Delay" in milliseconds (default: 6000ms)
6. **Style Customization**:
   - Use the Style tab to customize:
     - Tab background color
     - Content padding
     - Content background color

### PHP Function

You can also use the plugin programmatically:

```php
$items = array(
    array(
        'title' => 'Tab 1',
        'from_items' => array(
            array(
                'title' => 'Section Title',
                'points' => array(
                    'Point one',
                    'Point two'
                )
            )
        ),
        'to_items' => array(
            array(
                'title' => 'Section Title',
                'points' => array(
                    'Point one',
                    'Point two'
                )
            )
        )
    ),
);

// Autoplay disabled by default
wp_tabbed_slider($items, 'my-slider-id');

// Enable autoplay with custom delay
wp_tabbed_slider($items, 'my-slider-id', array(
    'autoplay' => true,
    'autoplay_delay' => 5000  // 5 seconds in milliseconds
));
```

**Note:** The PHP function uses the legacy From/To structure. For full flexibility, use the Elementor widget.

### Shortcode

```
[tabbed_slider id="custom-slider-id"]

// With autoplay enabled
[tabbed_slider id="custom-slider-id" autoplay="true" autoplay_delay="5000"]
```

**Note:** The shortcode uses demo data. For custom content, use the Elementor widget or PHP function.

## Elementor Widget Features

- **Repeater Controls** - Add unlimited tabs with visual repeater
- **Content Type Selection** - Choose between Elementor templates or WYSIWYG editor
- **Template Integration** - Select from saved Elementor templates
- **Style Controls** - Customize colors and spacing via Elementor controls
- **Autoplay Settings** - Enable/disable and configure autoplay delay

## Customization

### CSS Customization

You can customize the slider appearance by overriding CSS classes:

- **Tab styling**: `.ts-tab` (default background: `#EF7C00`)
- **Navigation arrows**: `.ts-nav-arrow`
- **Dot indicators**: `.ts-dot` (default: `#4F9CF9`, active: `#043873`)
- **Content slides**: `.ts-content-slide`
- **Legacy sections** (for PHP function):
  - `.ts-section-title` (default: `#003D7C`)
  - `.ts-card` (background with border)

### Autoplay Configuration

Autoplay is **disabled by default**. To enable:

**Elementor Widget:**

- Enable "Enable Autoplay" toggle
- Set "Autoplay Delay" in milliseconds

**PHP Function:**

```php
wp_tabbed_slider($items, 'slider-id', array(
    'autoplay' => true,
    'autoplay_delay' => 5000  // milliseconds
));
```

**Shortcode:**

```
[tabbed_slider autoplay="true" autoplay_delay="5000"]
```

**JavaScript (if initializing manually):**

```javascript
initTabbedSlider("#slider-id", {
  autoplay: true,
  autoplayDelay: 5000, // milliseconds
});
```

**HTML Data Attributes:**

```html
<div
  class="wp-tabbed-slider"
  data-autoplay="true"
  data-autoplay-delay="5000"
></div>
```

### Animation Speed

Edit `transition` duration in CSS:

- `.ts-tab-track` and `.ts-content-track` (default: `0.4s`)
- Located in `includes/assets/css/tabbed-slider.css`

## Browser Support

- iOS Safari 12+
- Chrome (Desktop & Android)
- Firefox (Desktop & Mobile)
- Edge (Modern versions)
- Modern desktop browsers with CSS Grid support

## Accessibility Features

- **ARIA Labels** - All interactive elements have proper ARIA labels
- **Keyboard Navigation** - Full keyboard support (Arrow keys, Home, End)
- **Focus States** - Visible focus indicators for keyboard users
- **Semantic HTML** - Proper button and role attributes
- **Screen Reader Support** - ARIA attributes for assistive technologies

## Keyboard Shortcuts

- **Arrow Left** - Previous slide
- **Arrow Right** - Next slide
- **Home** - Go to first slide
- **End** - Go to last slide

## Touch Gestures

- **Swipe Left** - Next slide
- **Swipe Right** - Previous slide
- **Tap Tab** - Jump to specific slide
- **Tap Dots** - Jump to specific slide

## License

Use freely in your WordPress projects.

## Support

For issues, feature requests, or questions, please contact the plugin author.
