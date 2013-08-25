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
         
        $doc->addStyleSheet(JUri::root().'templates/dz/core/fields/assets/rowlayout.css');
        $doc->addScript(JUri::root().'templates/dz/core/fields/assets/bootstrap-slider.js');
        $doc->addScript(JUri::root().'templates/dz/core/fields/assets/rowlayout.js');
        
        $id = $this->_getIdbyName($this->name);
        $prefix = str_replace('layout', '', $id);
        list($value, $force) = explode(",", $this->value);
        $columnsnum = count(explode('-', $value));
        
        $html = '';
        $html .=    '<div class="visual-table rowlayout row-fluid" data-prefix="' . $id . '" data-layout="' . $value . '" data-force="' . $force . '">';
        $html .=    '<div id="'.$id.'_visual" class="visual-container span6">
                        <div class="visual-inner">
                            <span class="visual-mini visual-first" data-title="' . $prefix . '-1" data-container="#' . $id . '_visual">2</span>
                            <span class="visual-mini" data-title="' . $prefix . '-2" data-container="#' . $id . '_visual">2</span>
                            <span class="visual-mini" data-title="' . $prefix . '-3" data-container="#' . $id . '_visual">2</span>
                            <span class="visual-mini" data-title="' . $prefix . '-4" data-container="#' . $id . '_visual">2</span>
                            <span class="visual-mini" data-title="' . $prefix . '-5" data-container="#' . $id . '_visual">2</span>
                            <span class="visual-mini visual-last" data-title="' . $prefix . '-6" data-container="#' . $id . '_visual">2</span>
                        </div>
                    </div>';
        $html .=    '<div class="span6">';
        $html .=    '<select class="columns-select pull-left">
                        <option value="1"' . (($columnsnum == 1) ? ' selected' : '') .'>1 '.JText::_('DZ_FIELD_ROWLAYOUT_COLUMN').'</option>
                        <option value="2"' . (($columnsnum == 2) ? ' selected' : '') .'>2 '.JText::_('DZ_FIELD_ROWLAYOUT_COLUMNS').'</option>
                        <option value="3"' . (($columnsnum == 3) ? ' selected' : '') .'>3 '.JText::_('DZ_FIELD_ROWLAYOUT_COLUMNS').'</option>
                        <option value="4"' . (($columnsnum == 4) ? ' selected' : '') .'>4 '.JText::_('DZ_FIELD_ROWLAYOUT_COLUMNS').'</option>
                        <option value="5"' . (($columnsnum == 5) ? ' selected' : '') .'>5 '.JText::_('DZ_FIELD_ROWLAYOUT_COLUMNS').'</option>
                        <option value="6"' . (($columnsnum == 6) ? ' selected' : '') .'>6 '.JText::_('DZ_FIELD_ROWLAYOUT_COLUMNS').'</option>
                    </select>';
        $html .=    '<label class="forcebox-label pull-left hasTooltip" data-title="' . JText::_('DZ_FIELD_DESC_FORCEBOX') . '"> 
                        <input type="checkbox" class="forcebox-input" ' . ($force ? 'checked' : '') . '/>&nbsp;' . JText::_('DZ_FIELD_LBL_FORCEBOX') . '
                    </label>';
        $html .=    '<div class="clearfix"></div>
                    <div class="slider-container">
                        <input class="layout-input" type="text"/>
                    </div>';
        $html .=    '</div>';
        $html .=    '<input type="hidden" id="'.$id.'_input" name="'.$this->name.'" value="'.$this->value.'" />';
        $html .=    '</div>';
        
        return $html;
    }
    
    private function _getIdbyName($formName)
    {
        $id = strrchr($formName, '[');
        $id = str_replace('[', "", $id); $id = str_replace(']', "", $id);
        
        return $id;
    }
}