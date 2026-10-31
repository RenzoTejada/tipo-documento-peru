<?php
if (!defined('ABSPATH')) exit;
function rt_tipo_choices() {
 $choices=array();
 foreach (array('dni'=>array('tipo_checkbox_dni','DNI'),'ruc'=>array('tipo_checkbox_ruc','RUC'),'ce'=>array('tipo_checkbox_ce','CARNÉ DE EXTRANJERÍA'),'pass'=>array('tipo_checkbox_pass','PASAPORTE'),'otros'=>array('tipo_checkbox_otros','OTROS')) as $id=>$opt) {
  $default=in_array($id,array('dni','ruc'),true)?'on':'';
  if (get_option($opt[0],$default)==='on') $choices[$id]=$opt[1];
 }
 return $choices;
}
function rt_tipo_valid_number($type,$number) {
 if ($type==='dni') return (bool)preg_match('/^[0-9]{8}$/D',$number);
 if ($type==='ruc') return (bool)preg_match('/^[0-9]{11}$/D',$number);
 return (bool)preg_match('/^.{1,40}$/u',$number);
}
// Classic shortcode checkout.
add_action('woocommerce_before_checkout_billing_form',function($checkout){
 $docs=rt_tipo_choices();if (!$docs) return;
 woocommerce_form_field('billing_documento',array('type'=>'select','class'=>array('form-row-wide'),'label'=>'Tipo de documento','required'=>true,'options'=>array(''=>'Seleccionar documento')+$docs),$checkout->get_value('billing_documento'));
 woocommerce_form_field('billing_nro',array('type'=>'text','class'=>array('form-row-wide'),'label'=>'Número de documento','required'=>true),$checkout->get_value('billing_nro'));
});
add_action('woocommerce_checkout_process',function(){
 $docs=rt_tipo_choices();if (!$docs) return;
 $type=isset($_POST['billing_documento'])?sanitize_key(wp_unslash($_POST['billing_documento'])):'';
 $number=isset($_POST['billing_nro'])?trim(sanitize_text_field(wp_unslash($_POST['billing_nro']))):'';
 if (!isset($docs[$type])) wc_add_notice('Selecciona un tipo de documento válido.','error');
 elseif (!rt_tipo_valid_number($type,$number)) wc_add_notice('Revisa el número de documento. DNI requiere 8 dígitos y RUC 11 dígitos.','error');
});
add_action('woocommerce_checkout_create_order',function($order){
 if (isset($_POST['billing_documento'])) $order->update_meta_data('_documento',sanitize_key(wp_unslash($_POST['billing_documento'])));
 if (isset($_POST['billing_nro'])) $order->update_meta_data('_nro',sanitize_text_field(wp_unslash($_POST['billing_nro'])));
},20);
// Checkout Blocks: use official additional checkout fields API (WooCommerce 8.9+).
add_action('woocommerce_init',function(){
 if (!function_exists('woocommerce_register_additional_checkout_field')) return;
 $docs=rt_tipo_choices();if (!$docs) return;
 $options=array();foreach($docs as $id=>$label) $options[]=array('value'=>$id,'label'=>$label);
 woocommerce_register_additional_checkout_field(array('id'=>'rt-tipo-doc/tipo','label'=>'Tipo de documento','location'=>'order','type'=>'select','required'=>true,'options'=>$options));
 woocommerce_register_additional_checkout_field(array('id'=>'rt-tipo-doc/numero','label'=>'Número de documento','location'=>'order','type'=>'text','required'=>true));
});
// Sync Blocks additional fields with historical keys so existing order displays and integrations work.
add_action('woocommerce_store_api_checkout_order_processed',function($order){
 if(!($order instanceof WC_Order))return;
 $type=$order->get_meta('_wc_other/rt-tipo-doc/tipo',true);
 $number=$order->get_meta('_wc_other/rt-tipo-doc/numero',true);
 if ($type && isset(rt_tipo_choices()[$type])) $order->update_meta_data('_documento',sanitize_key($type));
 if ($number) $order->update_meta_data('_nro',sanitize_text_field($number));
 if ($type||$number) $order->save();
},20);
function rt_tipo_order_data($order){return array($order->get_meta('_documento',true)?:$order->get_meta('_wc_other/rt-tipo-doc/tipo',true),$order->get_meta('_nro',true)?:$order->get_meta('_wc_other/rt-tipo-doc/numero',true));}
function rt_tipo_render_order_data($order,$plain=false){
 list($type,$number)=rt_tipo_order_data($order);$docs=array('dni'=>'DNI','ruc'=>'RUC','ce'=>'CARNÉ DE EXTRANJERÍA','pass'=>'PASAPORTE','otros'=>'OTROS');
 if(!$type&&!$number)return;
 if($plain){if($type)echo 'Tipo de documento: '.esc_html($docs[$type]??strtoupper($type))."
";if($number)echo 'Número de documento: '.esc_html($number)."
";return;}
 if($type) echo '<p><strong>Tipo de documento:</strong> '.esc_html($docs[$type]??strtoupper($type)).'</p>';
 if($number) echo '<p><strong>Número de documento:</strong> '.esc_html($number).'</p>';
}
add_action('woocommerce_admin_order_data_after_billing_address','rt_tipo_render_order_data');
add_action('woocommerce_email_after_order_table',function($order,$sent_to_admin,$plain_text,$email){rt_tipo_render_order_data($order,$plain_text);},20,4);
add_action('woocommerce_thankyou',function($order_id){$order=wc_get_order($order_id);if($order)rt_tipo_render_order_data($order);},20);
add_filter('woocommerce_rest_prepare_shop_order_object',function($response,$order){
 if (is_wp_error($response)||!isset($response->data['billing']))return $response;
 list($type,$number)=rt_tipo_order_data($order);
 $response->data['billing']['tipo_documento']=$type;
 $response->data['billing']['nro']=$number;
 return $response;
},10,2);
