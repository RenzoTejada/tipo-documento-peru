<?php

/**
 *
 * @link              https://renzotejada.com/
 * @package           Tipo Documento Perú
 *
 * @wordpress-plugin
 * Plugin Name:       Tipo Documento Perú
 * Plugin URI:        https://renzotejada.com/tipo-de-documento-peru/
 * Description:       Type of Peruvian Document where the option to choose DNI or RUC or others is added.
 * Version:           0.1.0
 * Author:            Renzo Tejada
 * Author URI:        https://renzotejada.com/
 * License:           GNU General Public License v3.0
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       rt-tipo-doc
 * Domain Path:       /language
 * WC tested up to:   8.7.0
 * WC requires at least: 2.6
 */
if (!defined('ABSPATH')) {
    exit;
}

$plugin_tipo_documento_version = get_file_data(__FILE__, array('Version' => 'Version'), false);

define('Version_RT_Tipo_Documento', $plugin_tipo_documento_version['Version']);

add_action( 'before_woocommerce_init', function() {
    if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
    }
} );

function rt_tipo_load_textdomain()
{
    load_plugin_textdomain('rt-tipo-doc', false, basename(dirname(__FILE__)) . '/language/');
}

add_action('init', 'rt_tipo_load_textdomain');

function rt_tipo_add_plugin_page_settings_link( $links )
{
    $links2[] = '<a href="' . admin_url( 'admin.php?page=tipo_settings' ) . '">' . __('Settings', 'rt-tipo-doc') . '</a>';
    $links = array_merge($links2,$links);
    return $links;
}

add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'rt_tipo_add_plugin_page_settings_link');

/*
 * ADMIN
 */
require dirname(__FILE__) . "/tipo_admin.php";

/*
 * CHECKOUT
 */
require dirname(__FILE__)."/tipo_checkout.php";