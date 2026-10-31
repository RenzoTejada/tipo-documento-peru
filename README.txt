=== Tipo Documento Perú ===
Contributors: renzotejada, huakotech
Tags: dni, ruc, documento, checkout, peru, woocommerce
Requires at least: 6.8
Tested up to: 7.1.4
Stable tag: 0.4.0
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Agrega un selector de tipo y número de documento en el checkout de WooCommerce.

== Description ==

Permite recopilar DNI, RUC, carné de extranjería, pasaporte u otros documentos de tus clientes. Los tipos se configuran desde WooCommerce > Tipo de Documento Perú y la información se conserva en cada pedido.

* Selector en checkout clásico y registro inicial mediante la API de campos adicionales en Checkout Blocks.
* Validación básica de formato: DNI de ocho dígitos y RUC de once dígitos en checkout clásico.
* Datos visibles en el pedido, página de gracias y correos electrónicos.
* Compatible a nivel de código con la API de pedidos HPOS y conserva las claves históricas `_documento` y `_nro`.
* Interfaz administrativa renovada con logo RT y enlace de soporte.

La función de validación de formato no consulta bases de datos de RENIEC ni SUNAT. La integración con Blocks requiere pruebas funcionales antes de su publicación definitiva.

== Installation ==

1. Sube el ZIP desde Plugins > Añadir plugin o instala el plugin desde WordPress.org.
2. Actívalo con WooCommerce instalado.
3. Configura las opciones en WooCommerce > Tipo de Documento Perú.
4. Comprueba la compra de prueba en tu checkout.

== Changelog ==

= 0.4.0 =
* Mejoras UX/UI en la pantalla de ajustes, con la identidad visual original de Renzo Tejada.
* Selector configurable de documentos en checkout clásico.
* Registro inicial de campos adicionales en Checkout Blocks.
* Guardado y lectura de pedidos mediante la API WooCommerce para HPOS.
* Corrección de validación y avisos del checkout clásico; retirada de alertas JavaScript.
* Mantiene claves históricas de pedido sin migraciones destructivas.
* Actualización del archivo readme.

= 0.3 =
* Versión anterior.
