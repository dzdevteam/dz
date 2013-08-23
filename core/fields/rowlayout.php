<?php

defined('JPATH_BASE') or die();

jimport('joomla.html.html');
jimport('joomla.form.formfield');

class JFormFieldRowLayout extends JFormField
{
    protected $type = "rowlayout";
    
    /**
     * Method to get the field label markup.
     *
     * @return  string  The field label markup.
     *
     * @since   11.1
     */
    protected function getLabel()
    {
        // Initialise variables.
        $label = '';
    
        if ($this->hidden)
        {
            return $label;
        }
    
        // Get the label text from the XML element, defaulting to the element name.
        $text = $this->element['label'] ? (string) $this->element['label'] : (string) $this->element['name'];
        $text = $this->translateLabel ? JText::_($text) : $text;
    
        // Build the class for the label.
        $class = !empty($this->description) ? 'hasTip' : '';
        $class = $this->required == true ? $class . ' required' : $class;
        $class = !empty($this->labelClass) ? $class . ' ' . $this->labelClass : $class;
    
        // Inject specific class for this
        $class = $class . ' ' . 'rowlayout_label';
        
        // Add the opening label tag and main attributes attributes.
        $label .= '<label id="' . $this->id . '-lbl" for="' . $this->id . '" class="' . $class . '"';
    
        // If a description is specified, use it to build a tooltip.
        if (!empty($this->description))
        {
            $label .= ' title="'
                    . htmlspecialchars(
                            trim($text, ':') . '::' . ($this->translateDescription ? JText::_($this->description) : $this->description),
                            ENT_COMPAT, 'UTF-8'
                    ) . '"';
        }
    
        // Add the label text and closing tag.
        if ($this->required)
        {
            $label .= '>' . $text . '<span class="star">&#160;*</span></label>';
        }
        else
        {
            $label .= '>' . $text . '</label>';
        }
    
        return $label;
    }
    
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
        
        $html = '<div style="clear:both"></div>';
        $html .= '<table width="100%"><tr><td width="50%">';
        $html .= '<div id="'.$id.'_visual" class="visual-container">
                    <div class="visual-mini grid-2">a</div>
                    <div class="visual-mini grid-2">b</div>
                    <div class="visual-mini grid-2">c</div>
                    <div class="visual-mini grid-2">d</div>
                    <div class="visual-mini grid-2">e</div>
                    <div class="visual-mini grid-2">f</div>
                </div>';
        $html .= '</td><td width="50%">';
        $html .= '<select id="'.$id.'_rowcolumns" onchange="updateSlider(\''.$id.'\', this.value)">
                    <option value="1">1 '.JText::_('DZ_FIELD_ROWLAYOUT_COLUMN').'</option>
                    <option value="2">2 '.JText::_('DZ_FIELD_ROWLAYOUT_COLUMNS').'</option>
                    <option value="3">3 '.JText::_('DZ_FIELD_ROWLAYOUT_COLUMNS').'</option>
                    <option value="4">4 '.JText::_('DZ_FIELD_ROWLAYOUT_COLUMNS').'</option>
                    <option value="5">5 '.JText::_('DZ_FIELD_ROWLAYOUT_COLUMNS').'</option>
                    <option value="6">6 '.JText::_('DZ_FIELD_ROWLAYOUT_COLUMNS').'</option>
                </select>';
        $html .= '<div style="float:left;" class="rowlayout_forcebox"><input type="checkbox" id="'.$id.'_force" onchange="updateInput(\''.$id.'\')" /><label for="'.$id.'_force">Force Position</label></div>';
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