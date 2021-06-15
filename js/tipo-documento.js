jQuery("#billing_documento").select2();


jQuery('#billing_nro').blur(function (event) {

    if (jQuery('#billing_documento option:selected').val() == "dni") {
        var dni = jQuery('#billing_nro').val();
        rt_documento_validar_dni(dni);
    } else if (jQuery('#billing_documento option:selected').val() == "ruc") {
        jQuery('#billing_nro').Rut({
            on_error: function () {
                alert('El ruc ingresado es incorrecto');
                jQuery('#billing_nro').val('');
                jQuery('#billing_nro').focus();
            },
            format_on: 'keyup'
        });
    }

});

function rt_documento_validar_dni(dni) {

    if (dni.length > 8) {
        jQuery('#billing_nro').val('');
        alert('El dni ingresado es incorrecto');
    }
}

(function ($) {
    jQuery.fn.Rut = function (options) {
        var defaults = {
            digito_verificador: null,
            on_error: function () {
            },
            on_success: function () {
            },
            validation: true,
        };

        var opts = $.extend(defaults, options);

        return this.each(function () {
            if (defaults.validation) {
                if (defaults.digito_verificador == null) {
                    jQuery(this).bind('blur', function () {
                        var rut = jQuery(this).val();
                        if (jQuery(this).val() != "" && !jQuery.Rut.validar(rut)) {
                            defaults.on_error();
                        } else if (jQuery(this).val() != "") {
                            defaults.on_success();
                        }
                    });
                } else {
                    var id = jQuery(this).attr("id");
                    jQuery(defaults.digito_verificador).bind('blur', function () {
                        var rut = jQuery("#" + id).val() + "-" + jQuery(this).val();
                        if (jQuery(this).val() != "" && !jQuery.Rut.validar(rut)) {
                            defaults.on_error();
                        } else if (jQuery(this).val() != "") {
                            defaults.on_success();
                        }
                    });
                }
            }
        });
    }
})(jQuery);

jQuery.Rut = {

    validar: function (ruc) {
        //11 dígitos y empieza en 10,15,16,17 o 20
        if (!(ruc >= 1e10 && ruc < 11e9
            || ruc >= 15e9 && ruc < 18e9
            || ruc >= 2e10 && ruc < 21e9))
            return false;

        for (var suma = -(ruc % 10 < 2), i = 0; i < 11; i++, ruc = ruc / 10 | 0)
            suma += (ruc % 10) * (i % 7 + (i / 7 | 0) + 1);
        return suma % 11 === 0;
    }
};
