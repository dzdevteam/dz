<?php

defined('JPATH_BASE') or die();

jimport('joomla.html.html');
jimport('joomla.form.formfield');

class JFormFieldCompileLess extends JFormField
{
    protected $type = "compileless";
    
    protected function getInput()
    {        
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::root().'/templates/dz/core/fields/assets/compileless.js');
        
        $html = '<button type="button" class="btn btn-primary btn-compile" data-compiler-href="'.JUri::root().'/templates/dz/compiler.php">'.JText::_('DZ_BUTTON_COMPILE_LESS').'</button>';
        $html .= '<br /><br /><div id="compile-result"></div>';
        
        return $html;
    }
}