permuObj = [["12"],["2-10","3-9","4-8","5-7","6-6","7-5","8-4","9-3","10-2"],["2-2-8","2-3-7","2-4-6","2-5-5","2-6-4","2-7-3","2-8-2","3-2-7","3-3-6","3-4-5","3-5-4","3-6-3","3-7-2","4-2-6","4-3-5","4-4-4","4-5-3","4-6-2","5-2-5","5-3-4","5-4-3","5-5-2","6-2-4","6-3-3","6-4-2","7-2-3","7-3-2","8-2-2"],["2-2-2-6","2-2-3-5","2-2-4-4","2-2-5-3","2-2-6-2","2-3-2-5","2-3-3-4","2-3-4-3","2-3-5-2","2-4-2-4","2-4-3-3","2-4-4-2","2-5-2-3","2-5-3-2","2-6-2-2","3-2-2-5","3-2-3-4","3-2-4-3","3-2-5-2","3-3-2-4","3-3-3-3","3-3-4-2","3-4-2-3","3-4-3-2","3-5-2-2","4-2-2-4","4-2-3-3","4-2-4-2","4-3-2-3","4-3-3-2","4-4-2-2","5-2-2-3","5-2-3-2","5-3-2-2","6-2-2-2"],["2-2-2-2-4","2-2-2-3-3","2-2-2-4-2","2-2-3-2-3","2-2-3-3-2","2-2-4-2-2","2-3-2-2-3","2-3-2-3-2","2-3-3-2-2","2-4-2-2-2","3-2-2-2-3","3-2-2-3-2","3-2-3-2-2","3-3-2-2-2","4-2-2-2-2"],["2-2-2-2-2-2"]];
sliders = new Array();

function updateSliderValue(prefix, permuIdx, permuIdx2)
{
	if (permuIdx2 >= permuObj[permuIdx].length)
		permuIdx2 = permuObj[permuIdx].length - 1;
	else if (permuIdx2 < 0)
		permuIdx2 = 0;
	document.id(prefix+'_value').set('html', permuObj[permuIdx][permuIdx2]);
	updateInput(prefix);
}

function updateSlider(prefix, value)
{
	var current_step = sliders[prefix].step;
	var index = value - 1;
	if (current_step > permuObj[index].length)
		current_step = permuObj[index].length - 1;
	var clone = $(sliders[prefix].element).clone(true, true);
	var next = sliders[prefix].element.nextElementSibling;
	$(sliders[prefix].element).dispose();
	clone.inject(next, "before");
	sliders[prefix] = new Slider(clone, $(clone).getElement(".knob"), {
		snap: false,
		steps: permuObj[index].length + 1,
		initialStep: current_step,
		onChange: function (step) {updateSliderValue(prefix, index, step - 1);}
	})
}

function updateInput(prefix)
{
	var layout = document.id(prefix+'_value').get('html');
	var force = document.id(prefix+'_force').get('checked') ? 1 : 0;
	document.id(prefix + '_input').set('value', layout + ',' + force);
	updateVisual(prefix, layout);
}

function updateVisual(prefix, value, mode)
{
	var valueArr = value.split("-");
	var visual = document.id(prefix+'_visual');
	visual.set('html', '');
	if (mode == null)
	{
		for (i = 0; i < valueArr.length; i++)
		{
			var visual_mini = new Element('span', {
				'class': 'visual-mini grid-'+valueArr[i], 
				html: valueArr[i], 
				title: prefix.replace('layout','-')+(i+1)
			});
			visual_mini.inject(visual);
			new Tips(visual_mini);
		}
	}
	else
	{
		for (i = 0; i < valueArr.length; i++)
		{
			var visual_mini = new Element('span', {
				'class': 'visual-mini grid-'+valueArr[i], 
				html: valueArr[i]
			});
			switch (parseInt(mode)) {
			case 4:
			case 13:
			case 2:
				if (i == 1)
					visual_mini.set('title', 'main');
				else
					visual_mini.set('title', 'sidebar-'+ ((i > 1) ? i : (i+1)));
				break;
			case 3:
			case 12:
			case 1:
				if (i == 0)
					visual_mini.set('title', 'main');
				else
					visual_mini.set('title',  'sidebar-'+ i);
				break;
			default:
					break;
			}
			visual_mini.inject(visual);
			new Tips(visual_mini);
		}
	}
}

function initRowConfig(prefix, value, force)
{
	window.addEvent("domready", function(){
		var valueStr = value; 
		var valueArr = valueStr.split("-");
		var index = valueArr.length-1;
		if (force)
				document.id(prefix+"_force").set("checked", true); 
		sliders[prefix] = new Slider($(prefix+'_slider'), $(prefix+'_slider').getElement(".knob"), {
				snap: false,
				steps: permuObj[index].length + 1,
				min: 1,
				initialStep: permuObj[index].indexOf(value) + 1,
				onChange: function (step) {updateSliderValue(prefix, index, step - 1);}
		});
		document.id(prefix+'_rowcolumns').set("value", valueArr.length);
		updateVisual(prefix, value);
	})
}