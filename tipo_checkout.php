<?php
if (!defined('ABSPATH'))
    exit;

function rt_tipo_add_checkout_field( $checkout )
{
    $docs = array();

    if ((get_option('tipo_checkbox_dni') == "on") && (get_option('tipo_checkbox_dni') != "dni")) {
        $docs['dni'] = __('DNI', 'rt-tipo-doc');
    }
    if ((get_option('tipo_checkbox_ruc') == "on") && (get_option('tipo_checkbox_ruc') != "ruc")) {
        $docs['ruc'] = __('RUC', 'rt-tipo-doc');
    }
    if ((get_option('tipo_checkbox_ce') == "on") && (get_option('tipo_checkbox_ce') != "ce")) {
        $docs['ce'] = __('FOREIGNER\'S CARD', 'rt-tipo-doc');
    }
    if ((get_option('tipo_checkbox_pass') == "on") && (get_option('tipo_checkbox_pass') != "pass")) {
        $docs['pass'] = __('PASSPORT', 'rt-tipo-doc');
    }
    if ((get_option('tipo_checkbox_otros') == "on") && (get_option('tipo_checkbox_otros') != "otros")) {
        $docs['otros'] = __('OTHER', 'rt-tipo-doc');
    }

    woocommerce_form_field( 'billing_documento', array(
        'type' => 'select',
        'class' => array( 'form-row-wide' ),
        'label' => __('Document Type', 'rt-tipo-doc'),
        'required' => true,
        'clear' => true,
        'options' => $docs,
        'priority' => 1
    ), $checkout->get_value( 'billing_documento' ) );


    woocommerce_form_field( 'billing_nro', array(
        'type' => 'number',
        'class' => array( 'form-row-wide' ),
        'label' => __('Document No.', 'rt-tipo-doc'),
        'required' => true,
    ), $checkout->get_value( 'billing_nro' ) );

}
add_action( 'woocommerce_before_checkout_billing_form', 'rt_tipo_add_checkout_field' );

function rt_tipo_able_woocommerce_loading_css_js()
{
    if( function_exists( 'is_woocommerce' ) ) {
        if (is_checkout()) {
            wp_register_script('tipo_documento_script', plugins_url('js/tipo-documento.js', __FILE__), array(), Version_RT_Tipo_Documento, true);
            wp_enqueue_script('tipo_documento_script');
        }
    }
}
add_action( 'wp_enqueue_scripts', 'rt_tipo_able_woocommerce_loading_css_js', 99 );

function rt_tipo_validate_checkout_field( $fields )
{
    if ( ! $_POST['billing_documento'] ) {
        wc_add_notice( '<b>'. __('Document Type', 'rt-tipo-doc') .'</b> es un campo requerido.', 'error' );
    }
    if ( ! $_POST['billing_nro'] ) {
        wc_add_notice( '<b>'. __('Document No.', 'rt-tipo-doc') .'</b> es un campo requerido.', 'error' );
    }
    if (  $_POST['billing_documento'] == 'dni') {
        if ($_POST['billing_nro']) {
            if (strlen($_POST['billing_nro']) < 8) {
                wc_add_notice('<b>' . __('Please enter 8 digits of your DNI', 'rt-tipo-doc') . '</b> is a required field.', 'error');
            }
        }
    }
    if (  $_POST['billing_documento'] == 'ruc') {
        if ($_POST['billing_nro']) {
            if (strlen($_POST['billing_nro']) < 11) {
                wc_add_notice('<b>' . __('Please enter 11 digits of your RUC', 'rt-tipo-doc') . '</b> is a required field.', 'error');
            }
        }
    }
}
add_action( 'woocommerce_checkout_process', 'rt_tipo_validate_checkout_field' );

function rt_tipo_save_checkout_field( $order_id )
{
    if ( $_POST['billing_documento'] ) update_post_meta( $order_id, '_documento', sanitize_text_field( $_POST['billing_documento'] ) );
    if ( $_POST['billing_nro'] ) update_post_meta( $order_id, '_nro', sanitize_text_field($_POST['billing_nro'] ) );
}
add_action( 'woocommerce_checkout_update_order_meta', 'rt_tipo_save_checkout_field' );

function rt_tipo_show_checkout_field_order( $order )
{
    $order_id = $order->get_id();
    if ( get_post_meta( $order_id, '_documento', true ) ) echo '<p><strong>'.__('Document Type', 'rt-tipo-doc').':</strong> ' . strtoupper(get_post_meta( $order_id, '_documento', true )) . '</p>';
    if ( get_post_meta( $order_id, '_nro', true ) ) echo '<p><strong>'.__('Document No.', 'rt-tipo-doc').':</strong> ' . get_post_meta( $order_id, '_nro', true ) . '</p>';
}
add_action( 'woocommerce_admin_order_data_after_billing_address', 'rt_tipo_show_checkout_field_order', 1, 1 );

function rt_tipo_show_checkout_field_emails( $order, $sent_to_admin, $plain_text, $email )
{
    if ( get_post_meta( $order->get_id(), '_documento', true ) ) echo '<p><strong>'.__('Document Type', 'rt-tipo-doc').':</strong> ' . strtoupper(get_post_meta( $order->get_id(), '_documento', true )) . '</p>';
    if ( get_post_meta( $order->get_id(), '_nro', true ) ) echo '<p><strong>'.__('Document No.', 'rt-tipo-doc').':</strong> ' . get_post_meta( $order->get_id(), '_nro', true ) . '</p>';
}
add_action( 'woocommerce_email_after_order_table', 'rt_tipo_show_checkout_field_emails', 20, 4 );


function rt_tipo_show_custom_fields_thankyou($order_id)
{
    if ( get_post_meta( $order_id, '_documento', true ) ) echo '<p><strong>'.__('Document Type', 'rt-tipo-doc').':</strong> ' . strtoupper(get_post_meta( $order_id, '_documento', true )) . '</p>';
    if ( get_post_meta( $order_id, '_nro', true ) ) echo '<p><strong>'.__('Document No.', 'rt-tipo-doc').':</strong> ' . get_post_meta( $order_id, '_nro', true ) . '</p>';
}
add_action('woocommerce_thankyou', 'rt_tipo_show_custom_fields_thankyou', 20);


function rt_tipo_get_product_order($response, $object, $request)
{
    if (empty($response->data))
        return $response;

    $response->data['billing']['tipo_documento'] = get_post_meta($response->data['id'], '_documento', true);
    $response->data['billing']['nro'] = get_post_meta($response->data['id'], '_nro', true);
    return $response;
}

add_filter("woocommerce_rest_prepare_shop_order_object", "rt_tipo_get_product_order", 10, 3);



