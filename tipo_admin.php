<?php
if (!defined('ABSPATH')) exit;
add_action('admin_menu', function() {
 add_submenu_page('woocommerce','Tipo de Documento Perú','Tipo de Documento Perú','manage_woocommerce','tipo_settings','rt_tipo_submenu_settings_callback');
});
add_action('admin_init', function() {
 foreach (array('tipo_checkbox_dni','tipo_checkbox_ruc','tipo_checkbox_ce','tipo_checkbox_pass','tipo_checkbox_otros') as $key) {
  register_setting('tipo_settings_group_docs',$key,array('sanitize_callback'=>function($v){return $v==='on'?'on':'';}));
 }
});
function rt_tipo_submenu_settings_callback() {
 if (!current_user_can('manage_woocommerce')) return;
 $tab = isset($_GET['tab']) ? sanitize_key(wp_unslash($_GET['tab'])) : 'docs';
 $logo = plugins_url('assets/logo-rt.png',__FILE__);
 ?>
 <div class="wrap rt-tipo-wrap">
  <header class="rt-tipo-head">
    <img src="<?php echo esc_url($logo); ?>" alt="Renzo Tejada Web Developer" />
    <div class="rt-tipo-head-title"><h1>Tipo de Documento Perú</h1><p>Gestiona qué documentos podrán seleccionar tus clientes durante el checkout.</p></div>
    <span class="rt-tipo-version">v<?php echo esc_html(Version_RT_Tipo_Documento); ?></span>
  </header>
  <nav class="nav-tab-wrapper">
   <a class="nav-tab <?php echo $tab==='docs'?'nav-tab-active':''; ?>" href="<?php echo esc_url(admin_url('admin.php?page=tipo_settings&tab=docs')); ?>">Configuración</a>
   <a class="nav-tab <?php echo $tab==='help'?'nav-tab-active':''; ?>" href="<?php echo esc_url(admin_url('admin.php?page=tipo_settings&tab=help')); ?>">Ayuda y contacto</a>
  </nav>
  <?php if ('help'===$tab): ?>
  <section class="rt-tipo-card"><h2>Ayuda y contacto</h2><p>El plugin incorpora un selector de tipo de documento y un campo para el número, asociados a cada pedido.</p><p>En el checkout clásico se muestran en la dirección de facturación. En Checkout Blocks se incorporan como información del pedido.</p><p>El plugin recopila los datos; no realiza consultas ni validaciones de identidad en RENIEC o SUNAT.</p><a class="button button-primary" href="https://renzotejada.com/contacto/" target="_blank" rel="noopener noreferrer">Contacto y soporte</a></section>
  <?php else: ?>
  <form method="post" action="options.php" class="rt-tipo-card">
  <?php settings_fields('tipo_settings_group_docs'); ?>
  <h2>Documentos disponibles</h2><p class="description">Activa los tipos que deseas mostrar en el selector. DNI y RUC están disponibles por defecto en instalaciones nuevas.</p>
  <?php
  $items=array('tipo_checkbox_dni'=>array('DNI','Documento nacional de identidad'),'tipo_checkbox_ruc'=>array('RUC','Registro Único de Contribuyentes'),'tipo_checkbox_ce'=>array('Carné de extranjería','Documento para residentes extranjeros'),'tipo_checkbox_pass'=>array('Pasaporte','Documento de viaje'),'tipo_checkbox_otros'=>array('Otros','Otros tipos de identificación'));
  foreach ($items as $key=>$item):
  $enabled=get_option($key, in_array($key,array('tipo_checkbox_dni','tipo_checkbox_ruc'),true)?'on':'');
  ?>
  <label class="rt-tipo-setting"><span><strong><?php echo esc_html($item[0]); ?></strong><small><?php echo esc_html($item[1]); ?></small></span><input type="checkbox" name="<?php echo esc_attr($key); ?>" value="on" <?php checked($enabled,'on'); ?> /></label>
  <?php endforeach; ?>
  <p class="rt-tipo-hint">Al desactivar una opción no se alteran los documentos guardados en pedidos anteriores.</p>
  <?php submit_button('Guardar cambios'); ?>
  </form>
  <?php endif; ?>
 </div>
 <?php
}
