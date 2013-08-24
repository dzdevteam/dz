<?php

defined('JPATH_BASE') or die();

jimport('joomla.html.html');
jimport('joomla.form.formfield');

class JFormFieldRowLayout extends JFormField
{
    protected $type = "rowlayout";
    
    protected function getInput()
    {
        $doc = JFactory::getDocument();
        $template = $this->form->getValue('template');
        
        $tpl_path = str_replace('/administrator/', '/', JURI::base()).'templates/'.$template;
        $minify_path = $tpl_path.'/core/utilities/min';
        
        $tpl_rel_path = str_replace('/administrator', '/', JURI::base(true)).'templates/'.$template;
        $assets_rel_path = $tpl_rel_path.'/core/fields/assets/';
         
        $doc->addStyleSheet($minify_path.'?f='.$assets_rel_path.'rowlayout.css');
        $doc->addScript($minify_path.'?f='.$assets_rel_path.'rowlayout.js');
        $id = $this->_getIdbyName($this->name);
        list($value, $force) = explode(",", $this->value);
        $script = 'initRowConfig("'.$id.'", "'.$value.'", '.$force.');';
        $doc->addScriptDeclaration($script);
        
        $html = '';
        $html .= '<table class="visual-table"><tr><td>';
        $html .= '<div id="'.$id.'_visual" class="visual-container">
                    <div class="visual-mini grid-2">a</div>
                    <div class="visual-mini grid-2">b</div>
                    <div class="visual-mini grid-2">c</div>
                    <div class="visual-mini grid-2">d</div>
                    <div class="visual-mini grid-2">e</div>
                    <div class="visual-mini grid-2">f</div>
                </div>';
        $html .= '</td><td>';
        $html .= '<select id="'.$id.'_rowcolumns" onchange="updateSlider(\''.$id.'\', this.value)">
                    <option value="1">1 '.JText::_('DZ_FIELD_ROWLAYOUT_COLUMN').'</option>
                    <option value="2">2 '.JText::_('DZ_FIELD_ROWLAYOUT_COLUMNS').'</option>
                    <option value="3">3 '.JText::_('DZ_FIELD_ROWLAYOUT_COLUMNS').'</option>
                    <option value="4">4 '.JText::_('DZ_FIELD_ROWLAYOUT_COLUMNS').'</option>
                    <option value="5">5 '.JText::_('DZ_FIELD_ROWLAYOUT_COLUMNS').'</option>
                    <option value="6">6 '.JText::_('DZ_FIELD_ROWLAYOUT_COLUMNS').'</option>
                </select>';
        $html .= '<div class="rowlayout_forcebox"><label for="'.$id.'_force"><input type="checkbox" id="'.$id.'_force" onchange="updateInput(\''.$id.'\')" />&nbsp;Force Position</label></div>';
        $value = json_decode($this->value, true);
        $html .= '<div id="'.$id.'_slider" class="slider"><div class="knob"></div></div>';
        
        $html .= '<div id="'.$id.'_value" class="slider_value">'.$value['layout'].'</div>';
        $html .= '</td></table>';
        $html .= '<input type="hidden" id="'.$id.'_input" name="'.$this->name.'" value="'.$this->value.'" />';
        
        return $html;
    }
    
    private function _getIdbyName($formName)
    {
        $id = strrchr($formName, '[');
        $id = str_replace('[', "", $id); $id = str_replace(']', "", $id);
        
        return $id;
    }
}