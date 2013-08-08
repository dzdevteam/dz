function sendCompileRequest(compileUrl)
{
		// Update the request form;
	var compilerForm = new Element('form'); 
	var fields = ['baseFontSize', 'sansFontFamily', 'serifFontFamily', 'baseFontFamily', 'baseLineHeight', 'textColor', 'linkColor', 'linkColorHover'];
	
	for (var i = 0; i < fields.length; i++ )
	{
		$('compile_'+fields[i]).set('value', $('jform_params_'+fields[i]).get('value'));
		$('compile_'+fields[i]).clone().inject(compilerForm);
	}
	
	var imports = ['responsive-767px-max', 'responsive-768px-979px', 'responsive-1200px-min'];
	for (var i = 0; i < imports.length; i++)
	{
		$('compile_' + imports[i]).set('value', isYes(imports[i]));
		$('compile_' + imports[i]).clone().inject(compilerForm);
	}
	
	var request = new Request({
		url: compileUrl,
		onRequest: function() {
			$('compile_result').set('text', 'compiling....');
		},
		onSuccess: function(responseText) {
			$('compile_result').set('html', responseText);
	    },
	    onFailure: function(){
	       $('compile_result').set('text', 'Sorry, your request failed :(');
	    }
	});

	request.post(compilerForm);
}

function isYes(field)
{
	field = field.replace(/-/g, '_');
	field = field.replace(/\./g, '_');
	
	if ($('jform_params_' + field + '0').get('checked') == true)
		return 0;
	else
		return 1;
}