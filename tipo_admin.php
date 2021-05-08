<?php
if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

/************************* ADMIN PAGE **********************************
 ***********************************************************************/

add_action('admin_menu', 'rt_tipo_register_admin_page');

function rt_tipo_register_admin_page()
{
    add_submenu_page('woocommerce', 'Configuraciones', __('Document Type', 'rt-tipo-doc'), 'manage_options', 'tipo_settings', 'rt_tipo_submenu_settings_callback');
    add_action('admin_init', 'rt_tipo_register_comprobante_settings');
}

function rt_tipo_success_notice()
{
    ?>
    <div class="updated notice">
        <p><?php _e('Was saved successfully', 'rt-tipo-doc') ?></p>
    </div>
    <?php
}

function rt_tipo_submenu_settings_callback()
{
    if (isset($_REQUEST["settings-updated"]) && sanitize_text_field($_REQUEST["settings-updated"] == true)) {
        rt_tipo_success_notice();
    }
    ?>
    <style>
        input[type=text], select {
            width: 400px;
            margin: 0;
            padding: 6px !important;
            box-sizing: border-box;
            vertical-align: top;
            height: auto;
            line-height: 2;
            min-height: 30px;
        }
        input[type="checkbox"][readonly] {
            pointer-events: none;
        }
        .form-table th {
            width: 300px !important;
        }
    </style>
    <div class="wrap woocommerce" >
        <h1><?php _e('Peru document type | DNI and RUC integration in Woocommerce checkout', 'rt-tipo-doc') ?></h1>
        <hr>
        <h2 class="nav-tab-wrapper">
            <a href="?page=tipo_settings&tab=docs" class="nav-tab <?php
            if ((!isset($_REQUEST['tab'])) || ($_REQUEST['tab'] == "docs")) {
                print " nav-tab-active";
            }
            ?>"><?php _e('Documents', 'rt-tipo-doc') ?></a>
            <a href="?page=tipo_settings&tab=help" class="nav-tab <?php
            if ($_REQUEST['tab'] == "help") {
                print " nav-tab-active";
            } ?>"><?php _e('Help', 'rt-tipo-doc') ?></a>
        </h2>

        <?php
        if ((!isset($_REQUEST['tab'])) || ($_REQUEST['tab'] == "docs")) {
           rt_tipo_submenu_settings_docs();
        } elseif ($_REQUEST['tab'] == "help") {
            rt_tipo_submenu_settings_help();
        }
        ?>
    </div>
    <?php
}

function rt_tipo_submenu_settings_docs()
{
    ?>
    <form method="post" action="options.php" id="tipo_formulario">
        <?php settings_fields('tipo_settings_group_docs'); ?>
        <?php do_settings_sections('tipo_settings_group_docs'); ?>

        <h2><?php _e('Type of document enabled in your store', 'rt-tipo-doc') ?></h2>

        <table class="form-table">
            <tbody>
            <tr valign="top">
                <th scope="row" class="titledesc">
                    <label ><?php _e('NATIONAL IDENTITY CARD (DNI)', 'rt-tipo-doc') ?></label></th>
                <td class="forminp forminp-checkbox">
                    <input type="checkbox" name="tipo_checkbox_dni" id="tipo_checkbox_dni" value="on" checked readonly  />
                </td>
            </tr>
            <tr>
            </tr>
            <tr valign="top">
                <th scope="row" class="titledesc">
                    <label ><?php _e('SINGLE TAXPAYER REGISTRY (RUC)', 'rt-tipo-doc') ?></label></th>
                <td class="forminp forminp-checkbox">
                    <input type="checkbox" name="tipo_checkbox_ruc" id="tipo_checkbox_ruc" value="on" checked readonly  />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row" class="titledesc">
                    <label><?php _e('FOREIGNER\'S CARD (CE)', 'rt-tipo-doc') ?></label></th>
                <td class="forminp forminp-checkbox">
                    <input type="checkbox" name="tipo_checkbox_ce" id="tipo_checkbox_ce" value="on"
                        <?php if (esc_attr(get_option('tipo_checkbox_ce')) == "on") echo "checked"; ?> />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row" class="titledesc">
                    <label><?php _e('PASSPORT', 'rt-tipo-doc') ?></label></th>
                <td class="forminp forminp-checkbox">
                    <input type="checkbox" name="tipo_checkbox_pass" id="tipo_checkbox_pass" value="on"
                        <?php if (esc_attr(get_option('tipo_checkbox_pass')) == "on") echo "checked"; ?> />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row" class="titledesc">
                    <label><?php _e('OTHER', 'rt-tipo-doc') ?></label></th>
                <td class="forminp forminp-checkbox">
                    <input type="checkbox" name="tipo_checkbox_otros" id="tipo_checkbox_otros" value="on"
                        <?php if (esc_attr(get_option('tipo_checkbox_otros')) == "on") echo "checked"; ?> />
                </td>
            </tr>
            </tbody>
        </table>
        <?php submit_button(__( 'Save Changes', 'rt-tipo-doc' )); ?>
    </form>
    <?php
}

function rt_tipo_register_comprobante_settings()
{
    register_setting('tipo_settings_group_docs', 'tipo_checkbox_dni');
    register_setting('tipo_settings_group_docs', 'tipo_checkbox_ruc');
    register_setting('tipo_settings_group_docs', 'tipo_checkbox_ce');
    register_setting('tipo_settings_group_docs', 'tipo_checkbox_pass');
    register_setting('tipo_settings_group_docs', 'tipo_checkbox_otros');

    if (!class_exists('woocommerce')) {
        add_action('admin_notices', 'rt_tipo_error_no_woocommerce');
    }
}

function rt_tipo_error_no_woocommerce()
{
    ?>
    <div class="error notice">
        <p><?php _e("Peru document type for WooCommerce: The module needs to have WooCommerce installed to operate correctly.", 'rt-tipo-doc'); ?></p>
    </div>
    <?php
}

function rt_tipo_submenu_settings_help()
{
    ?>
    <h2><?php _e('Help', 'rt-tipo-doc'); ?></h2>

    <h3><?php _e('What does this module do?', 'rt-tipo-doc'); ?></h3>

    <p><?php _e('It allows you to integrate your Woocommerce the type of document of the customers in the checkout.', 'rt-tipo-doc'); ?></p>

    <h3><?php _e('What is the cost of the module?', 'rt-tipo-doc'); ?></h3>

    <p><?php _e('This plugin is totally free.', 'rt-tipo-doc'); ?></p>

    <h3><?php _e('I have other questions', 'rt-tipo-doc'); ?></h3>

    <p><?php _e('Go to', 'rt-tipo-doc'); ?> <a href="https://renzotejada.com/contacto?url=dashboard-wodpress" target="_blank"><?php _e('RT - Contact', 'rt-tipo-doc'); ?></a></p>
    <?php
}