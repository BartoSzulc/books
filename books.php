<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/BartoSzulc/books
 * @since             1.0.0
 * @package           Books
 *
 * @wordpress-plugin
 * Plugin Name:       Books
 * Plugin URI:        https://github.com/BartoSzulc/books
 * Description:       This plugin automates the creation of a custom post type called "Books" in WordPress. It seamlessly integrates Advanced Custom Fields (ACF) to capture essential book details like author, publication year, and genre.
 * Version:           1.0.0
 * Author:            Bartosz Szulc
 * Author URI:        https://github.com/BartoSzulc
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       books
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'BOOKS_VERSION', '1.0.0' );

define( 'MY_ACF_PATH', plugin_dir_path(__FILE__) . '/includes/acf/' );
define( 'MY_ACF_URL', plugin_dir_path(__FILE__) . '/includes/acf/' );

// Include the ACF plugin.
include_once( MY_ACF_PATH . 'acf.php' );

// Customize the URL setting to fix incorrect asset URLs.
add_filter('acf/settings/url', 'my_acf_settings_url');
function my_acf_settings_url( $url ) {
    return MY_ACF_URL;
}
// Hide the ACF admin menu item.
add_filter('acf/settings/show_admin', '__return_false');

// Custom Post Type "Books"
function books_register_post_type() {

    $args = array (
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'labels' => array(
            'name' => __( 'Książki' ),
            'singular_name' => __( 'Książka' )
        ),
        'menu_icon' => 'dashicons-book',
    );
    register_post_type('books', $args);

}
add_action('init', 'books_register_post_type');

function books_acf_fields() {
    if( function_exists('acf_add_local_field_group') ) {
        acf_add_local_field_group(array(
            'key' => 'group_books',
            'title' => 'Pola dla książki',
            'fields' => array(
                array(
                    'key' => 'field_author',
                    'label' => 'Autor',
                    'name' => 'author',
                    'type' => 'text',
                ),
                array(
                    'key' => 'field_year',
                    'label' => 'Rok wydania',
                    'name' => 'year',
                    'type' => 'text',
                ),
                array(
                    'key' => 'field_genre',
                    'label' => 'Gatunek',
                    'name' => 'genre',
                    'type' => 'text',
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'books',
                    ),
                ),
            ),
        ));
    }
}
add_action('acf/init', 'books_acf_fields');

function books_template_include($template) {
    if (is_post_type_archive('books')) {
        $new_template = plugin_dir_path(__FILE__) . '/src/templates/archive-books.php';
        if ('' !== $new_template) {
            return $new_template;
        }
    } elseif (is_singular('books')) {
        $new_template = plugin_dir_path(__FILE__) . '/src/templates/single-book.php';
        if ('' !== $new_template) {
            return $new_template;
        }
    }
    return $template;
}
add_filter('template_include', 'books_template_include');

function books_check_acf() {
    if ( ! class_exists('acf') && is_admin() ) {
        deactivate_plugins(plugin_basename( __FILE__ ) );
        add_action('admin_notices', 'books_display_acf_missing_notice');
    }
}
add_action('admin_init', 'books_check_acf');

function books_display_acf_missing_notice() {
    echo '<div class="error"><p>Przepraszamy, ale ta wtyczka wymaga zainstalowania i aktywowania Advanced Custom Fields (ACF).</p></div>';
}

// Enqueue app.css app.js
function books_enqueue_scripts() {
    if (is_post_type_archive('books') || is_singular('books')) {
        $plugin_dir_url = plugin_dir_url(__FILE__);
        $read = fn ($endpoint) => file_get_contents(plugin_dir_path(__FILE__) . 'dist/' . $endpoint);
      
        $entrypoints = json_decode($read('entrypoints.json'));
      
        foreach ($entrypoints->app->js as $js_file) {
            $js_file_url = $js_file;
            wp_enqueue_script(
                'books-' . $js_file,
                $js_file_url,
                [],
                null,
                true
            );
        }
        if (isset($entrypoints->app->css) && is_array($entrypoints->app->css)) {
            foreach ($entrypoints->app->css as $css_file) {
                $css_file_url = $css_file;
                wp_enqueue_style(
                    'books-' . basename($css_file, '.css'), // Unique style handle
                    $css_file_url,
                    [],
                    null
                );
            }
        }
    }
}
add_action('wp_enqueue_scripts', 'books_enqueue_scripts');

