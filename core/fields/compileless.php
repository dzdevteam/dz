<?php

defined('JPATH_BASE') or die();

jimport('joomla.html.html');
jimport('joomla.form.formfield');

class JFormFieldCompileLess extends JFormField
{
	protected $type = "compileless";
	
	protected function getInput()
	{
		$template = $this->form->getValue('template');
		$tpl_path = str_replace('/administrator/', '/', JURI::base()).'templates/'.$template;
		
		$doc = JFactory::getDocument();
		$doc->addScript($tpl_path.'/core/fields/assets/compileless.js');
		$html = '<input type="hidden" name="compile[variables][baseFontSize]" id="compile_baseFontSize" />';
		$html .= '<input type="hidden" name="compile[variables][sansFontFamily]" id="compile_sansFontFamily" />';
		$html .= '<input type="hidden" name="compile[variables][serifFontFamily]" id="compile_serifFontFamily" />';
		$html .= '<input type="hidden" name="compile[variables][baseFontFamily]" id="compile_baseFontFamily" />';
		$html .= '<input type="hidden" name="compile[variables][baseLineHeight]" id="compile_baseLineHeight" />';
		$html .= '<input type="hidden" name="compile[variables][textColor]" id="compile_textColor" />';
		$html .= '<input type="hidden" name="compile[variables][linkColor]" id="compile_linkColor" />';
		$html .= '<input type="hidden" name="compile[variables][linkColorHover]" id="compile_linkColorHover" />';
		$html .= '<input type="hidden" name="compile[imports][responsive-767px-max.less]" id="compile_responsive-767px-max" />';
		$html .= '<input type="hidden" name="compile[imports][responsive-768px-979px.less]" id="compile_responsive-768px-979px" />';
		$html .= '<input type="hidden" name="compile[imports][responsive-1200px-min.less]" id="compile_responsive-1200px-min" />';
		$html .= '<button type="button" onclick="sendCompileRequest(\''.$tpl_path.'/compiler.php\')">'.JText::_('DZ_BUTTON_COMPILE_LESS').'</button>';
		$html .= '<div class="fltlft" id="compile_result"></div>';
		
		return $html;
	}
}