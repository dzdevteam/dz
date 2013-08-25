jQuery(document).ready(function() {
    jQuery('.btn-compile').on('click', function() {
        // Data skeleton
        var data = {
            compile: {
                variables: {},
                imports: {}
            }
        },
            variables = ['baseFontSize', 'sansFontFamily', 'serifFontFamily', 'baseFontFamily', 'baseLineHeight', 'textColor', 'linkColor', 'linkColorHover'],
            imports = ['responsive-767px-max', 'responsive-768px-979px', 'responsive-1200px-min'],
            alert_tpl = '<div class="alert fade in"><a class="close" data-dismiss="alert" href="#">&times;</a></div>';
        
        // Prepare variables
        for (var i = 0; i < variables.length; i++) {
            data.compile.variables[variables[i]] = jQuery('#jform_params_' + variables[i]).val();
        }
        
        // Prepare imports
        for (var i = 0; i < imports.length; i++) {
            data.compile.imports[imports[i] + '.less'] = jQuery('input[name="jform[params][' + imports[i]+']"]:checked').val();
        }
        
        // Loading animation
        jQuery('#compile-result').html('<img src="../media/system/images/modal/spinner.gif" />');
        
        // Send the data
        jQuery.ajax({
            url: jQuery(this).data('compiler-href'),
            method: 'POST',
            data: data
        }).done(function(response) {
            var $alert = jQuery(alert_tpl).addClass('alert-success').append(response.message);
            jQuery('#compile-result').html($alert);
        }).error(function(jqXHR, textStatus, errorThrown) {
            var $alert = jQuery(alert_tpl).addClass('alert-danger').append(errorThrown);
            jQuery('#compile-result').html($alert);
        });
        
        // Prevent form submission
        return false;
    });
});