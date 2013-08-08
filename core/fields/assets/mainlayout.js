function initMainConfig(prefix, mode, value, force, expandMain)
{
	window.addEvent("domready", function(){
		var valueStr = value; 
		var valueArr = valueStr.split("-");
		var index = valueArr.length-1;
		if (force)
				document.id(prefix+"_force").set("checked", true);
		if (expandMain)
			document.id(prefix+"_exMain").set("checked", true);
		document.id(prefix+'_rowcolumns').set("value", mode);
		sliders[prefix] = new Slider($(prefix+'_slider'), $(prefix+'_slider').getElement(".knob"), {
				snap: false,
				steps: permuObj[index].length + 1,
				min: 1,
				initialStep: permuObj[index].indexOf(value) + 1,
				onChange: function (step) {
					updateSliderValue(prefix, index, step - 1);
					updateMainInput(prefix);
					highlightMain(prefix, mode);
				}
		});
		highlightMain(prefix, mode);
	})
}

function updateMainSlider(prefix, value)
{
	var current_step = sliders[prefix].step;
	var index = value % 10 - 1;
	if (current_step > permuObj[index].length)
		current_step = permuObj[index].length - 1;
	
	// Clone the slider object without event
	var clone = $(sliders[prefix].element).clone(true, true);
	var next = sliders[prefix].element.nextElementSibling;
	
	// Replace old object by the new clone
	$(sliders[prefix].element).dispose();
	clone.inject(next, "before");
	
	// Reinitialize sliders
	sliders[prefix] = new Slider(clone, $(clone).getElement(".knob"), {
		snap: false,
		steps: permuObj[index].length + 1,
		initialStep: current_step,
		onChange: function (step) {
			updateSliderValue(prefix, index, step - 1); 
			updateMainInput(prefix);
			highlightMain(prefix, value);
		}
	})
	highlightMain(prefix, value);
}

function highlightMain(prefix, mode)
{
	var minis = document.id(prefix+'_visual').getElements('.visual-mini');
	switch (parseInt(mode)) {
	case 4:
	case 13:
	case 2:
		minis[1].addClass('visual-main');
		break;
	case 3:
	case 12:
	case 1:
		minis[0].addClass('visual-main');
		break;
	default:
			break;
	}
}

function updateMainInput(prefix)
{
	var mode = document.id(prefix+'_rowcolumns').get('value');
	var layout = document.id(prefix+'_value').get('html');
	var force = document.id(prefix+'_force').get('checked') ? 1 : 0;
	var expandMain = document.id(prefix+'_exMain').get('checked') ? 1 : 0;
	document.id(prefix + '_input').set('value', mode+','+layout + ',' + force+','+expandMain);
	updateVisual(prefix,layout,mode);
	highlightMain(prefix, mode);
}