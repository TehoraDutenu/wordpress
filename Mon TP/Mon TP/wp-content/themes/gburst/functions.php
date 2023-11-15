<?php
// --- MENU DE NAVIGATION
// -- Enregistrement
function register_menu()
{
    register_nav_menus(
        array(
            'menu-sup' => __('Main menu'),
            'menu-footer' => __('Footer menu')
        )
    );
}

// -- Initialisation
add_action('init', 'register_menu');

// -- Design du menu
class Simple_menu extends Walker_Nav_Menu
{
    public function start_el(&$output, $data_object, $depth = 0, $args = null, $current_object_id = 0)
    {
        $title = $data_object->title;
        $permalink = $data_object->url;

        $output .= "<div class='nav-item'>";
        $output .= "<a class='nav-link border m-1 custom_a' href='$permalink'>$title</a>";
    }
    public function end_el(&$output, $data_object, $depth = 0, $args = null)
    {
        $output .= "</div>";
    }
}

// -- Design des sous-menus
class Depth_menu extends Walker_Nav_Menu
{
    public function start_lvl(&$output, $depth = 0, $args = null)
    {
        $output .= "<ul class='sub-menu'>";
    }

    public function start_el(&$output, $data_object, $depth = 0, $args = null, $current_object_id = 0)
    {
        $title = $data_object->title;
        $permalink = $data_object->url;
        $indendation = str_repeat("\t", $depth);
        $classes = empty($data_object->classes) ? array() : (array) $data_object->classes;
        $class_name = join(' ', apply_filters('nav_menu_css_array', array_filter($classes), $data_object));

        if ($depth > 0) {
            $output .= $indendation . '<li class="' . esc_attr($class_name) . '">';
        } else {
            $output .= '<li class="' . esc_attr($class_name) . '">';
        }
        $output .= '<a class="custom_a" href="' . $permalink . '">' . $title . '</a>';
    }

    public function end_el(&$output, $data_object, $depth = 0, $args = null)
    {
        $output .= "</li>";
    }

    public function end_lvl(&$output, $depth = 0, $args = null)
    {
        $output .= "</ul>";
    }
}

class Footer_menu extends Walker_Nav_Menu
{
    public function start_lvl(&$output, $depth = 0, $args = null)
    {
        $output .= "<ul class='sub-menu'>";
    }

    public function start_el(&$output, $data_object, $depth = 0, $args = null, $current_object_id = 0)
    {
        $title = $data_object->title;
        $permalink = $data_object->url;
        $indendation = str_repeat("\t", $depth);
        $classes = empty($data_object->classes) ? array() : (array) $data_object->classes;
        $class_name = join(' ', apply_filters('nav_menu_css_array', array_filter($classes), $data_object));

        if ($depth > 0) {
            $output .= $indendation . '<li class="' . esc_attr($class_name) . '">';
        } else {
            $output .= '<li class="' . esc_attr($class_name) . '">';
        }
        $output .= '<a class="custom_a" href="' . $permalink . '">' . $title . '</a>';
    }

    public function end_el(&$output, $data_object, $depth = 0, $args = null)
    {
        $output .= "</li>";
    }

    public function end_lvl(&$output, $depth = 0, $args = null)
    {
        $output .= "</ul>";
    }
}

// -- SHORTCODES
// -  Sans paramètres
function monShortCode()
{
    return "<div>Mon shortcode sans paramètres</div>";
}
add_shortcode('monShortCode', 'monShortCode');

// -  Avec paramètre
function shortcodePromo($atts)
{
    $a = shortcode_atts(array('percent' => 10,), $atts);
    return "<div>Promo de {$a['percent']}%</div>";
}
add_shortcode('promo', 'shortcodePromo');


// --- WIDGET
function register_custom_widget_area()
{
    register_sidebar(
        array(
            'id' => 'new-widget-area',
            'name' => __('New Widget Area'),
            'description' => __('Widget area for the sidebar'),
            'before_widget' => '<div class="widget-content">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>'
        )
    );
}

add_action('widgets_init', 'register_custom_widget_area');


// --- STYLISER CSS BACK OFFICE
/* function admin_css()
{
    $admin_handle = 'admin_css';
    $admin_stylesheet = get_template_directory_uri() . '/css/admin.css';

    wp_enqueue_style($admin_handle, $admin_stylesheet);
}
add_action('admin_print_styles', 'admin_css', 11);
 */