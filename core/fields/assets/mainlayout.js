jQuery(document).ready(function() {
    var permuArray = [["12"],["2-10","3-9","4-8","5-7","6-6","7-5","8-4","9-3","10-2"],["2-2-8","2-3-7","2-4-6","2-5-5","2-6-4","2-7-3","2-8-2","3-2-7","3-3-6","3-4-5","3-5-4","3-6-3","3-7-2","4-2-6","4-3-5","4-4-4","4-5-3","4-6-2","5-2-5","5-3-4","5-4-3","5-5-2","6-2-4","6-3-3","6-4-2","7-2-3","7-3-2","8-2-2"],["2-2-2-6","2-2-3-5","2-2-4-4","2-2-5-3","2-2-6-2","2-3-2-5","2-3-3-4","2-3-4-3","2-3-5-2","2-4-2-4","2-4-3-3","2-4-4-2","2-5-2-3","2-5-3-2","2-6-2-2","3-2-2-5","3-2-3-4","3-2-4-3","3-2-5-2","3-3-2-4","3-3-3-3","3-3-4-2","3-4-2-3","3-4-3-2","3-5-2-2","4-2-2-4","4-2-3-3","4-2-4-2","4-3-2-3","4-3-3-2","4-4-2-2","5-2-2-3","5-2-3-2","5-3-2-2","6-2-2-2"],["2-2-2-2-4","2-2-2-3-3","2-2-2-4-2","2-2-3-2-3","2-2-3-3-2","2-2-4-2-2","2-3-2-2-3","2-3-2-3-2","2-3-3-2-2","2-4-2-2-2","3-2-2-2-3","3-2-2-3-2","3-2-3-2-2","3-3-2-2-2","4-2-2-2-2"],["2-2-2-2-2-2"]];
    
    // Enable visual-mini tooltips
    jQuery('.visual-inner > span').tooltip();
    
    jQuery(".visual-table.mainlayout").each(function(){
        var $table          = jQuery(this),
            $forceinput     = jQuery('input.forcebox-input', this),
            $expendinput    = jQuery('input.expandbox-input', this),
            $hiddeninput    = jQuery('input[type="hidden"]', this),
            $columnsselect  = jQuery('select.columns-select', this),
            $layoutinput    = jQuery('input.layout-input', this);
            
        /* ----- UTILITY ------ */
        // Update the hidden input function
        var update_hidden_input = function() {
            $hiddeninput.val($columnsselect.val() + ',' + $table.data('layout') + ',' + $table.data('force') + ',' + $table.data('expandmain'));
        };
        
        // Update visual function
        var update_visual_container = function() {
            var layout = String($table.data('layout')).split('-'), mainpos = null,
                visualminis = jQuery('span.visual-mini', $table), $mini = null;
            
            // Calculate the main position
            switch( parseInt($columnsselect.val()) ) {
                case jQuery.DZConfig.SIDEBAR_COMP_SIDEBAR_SIDEBAR:
                case jQuery.DZConfig.SIDEBAR_COMP_SIDEBAR:
                case jQuery.DZConfig.SIDEBAR_COMP:
                    mainpos = 1;
                    break;
                case jQuery.DZConfig.COMP_SIDEBAR_SIDEBAR:
                case jQuery.DZConfig.COMP_SIDEBAR:
                case jQuery.DZConfig.COMP:
                default:
                    mainpos = 0;
                    break;
            }
            
            // Recalculate classes for the minis
            for (var i = 0, j = 1; i < visualminis.length; i++) {
                $mini = jQuery(visualminis[i]);
                
                // Remove all class from this visual mini
                $mini.removeClass();
                
                if (i < layout.length) {
                    $mini.addClass('visual-mini grid-' + layout[i]);
                    if (i == mainpos) {
                        $mini.attr('title', 'main').tooltip('fixTitle');
                    } else {
                        $mini.attr('title', 'sidebar-' + j).tooltip('fixTitle');
                        j++;
                    }
                    $mini.text(layout[i]);
                } else {
                    $mini.addClass('visual-mini visual-hidden');
                }
            }
            
            // Add some special classes
            visualminis[0].addClass('visual-first');
            visualminis[mainpos].addClass('visual-main');
            visualminis[layout.length - 1].addClass('visual-last');
        }
        
        /* ----- EVENT HANDLERS ------ */
        // "Force" checkbox handler
        var chkbox_force_handler = function() {
            var force = jQuery(this).attr('checked') ? 1 : 0;
            $table.data('force', force);
            update_hidden_input();
        }
        $forceinput.on('change', chkbox_force_handler);
        
        // "Expand main" checkbox handler
        var chkbox_expmain_handler = function() {
            var expand = jQuery(this).attr('checked') ? 1 : 0;
            $table.data('expandmain', expand);
            update_hidden_input();
        }
        $expendinput.on('change', chkbox_expmain_handler);
        
        // "Columns" select handler
        var columns_select_handler = function() {
            if ($layoutinput.data('slider') != null) {
                var slider = $layoutinput.data('slider'),
                $slider = $layoutinput.parents('.slider'),
                $slidercontainer = $layoutinput.parents('.slider-container'),
                index  = (jQuery(this).val() % 10) - 1,
                value  = isNaN(slider.getValue()) ? 0 : slider.getValue(),
                max    = permuArray[index].length - 1;
                
                // Constraint value to max
                if (value > max) value = max;
            
                // Detach the input from the DOM
                $layoutinput.detach();
                $layoutinput.data('slider', null);
                
                // Remove the slider
                $slider.remove();
                
                // Reattach the input into the container
                $layoutinput.appendTo($slidercontainer);
                
                // Reconstruct the slider
                $layoutinput.slider({
                    min: 0,
                    max: max,
                    step: 1,
                    value: value,
                    tooltip: 'hide'
                }).on('slide', slider_slide_handler).data('index', index);
            
                // Update the table data
                $table.data('layout', permuArray[index][value]);
                
                // Update the input and the visual
                update_hidden_input();
                update_visual_container();
            } else {
                // When the slider is not constructed yet,
                // we just update the container with the default data
                update_visual_container();
            }
        }
        $columnsselect.on('change', columns_select_handler).trigger('change');
        
        /* ------- INITIALIZATION ------- */
        // Setup slider
        var layout = String($table.data('layout')),
            index = layout.split('-').length - 1,
            max = permuArray[index].length - 1,
            value = permuArray[index].indexOf(layout);
        
        var slider_slide_handler = function(event) {
            // Calculate the layout
            var value = (isNaN(event.value) ? 0 : event.value),
            index = jQuery(this).data('index'),
            newLayout = permuArray[index][value],
            oldLayout = $table.data('layout');
        
            if (newLayout != oldLayout) {
                // Only update the data when the new layout is different from our current layout
                $table.data('layout', newLayout);
                
                // Now update the hidden input and the visual
                update_hidden_input();
                update_visual_container();
            }
        }
        
        $layoutinput.slider({
            min: 0,
            max: max,
            step: 1,
            value: value,
            tooltip: 'hide'
        }).on('slide', slider_slide_handler).data('index', index);
    })
});