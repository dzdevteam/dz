<?php

defined('JPATH_BASE') or die();

jimport('joomla.html.html');
jimport('joomla.form.formfield');
require_once JPATH_SITE.'/templates/dz/core/dzconfig.class.php';


class JFormFieldMainLayout extends JFormField
{
    protected $type = "mainlayout";
    
    protected function getInput()
    {
        $doc = JFactory::getDocument();
        
        $doc->addStyleSheet(JUri::root().'templates/dz/core/fields/assets/mainlayout.css');
        
        // Inject our config constants into global jquery
        $script = '
            jQuery.DZConfig = {
                SIDEBAR_COMP_SIDEBAR_SIDEBAR: ' . DZConfig::SIDEBAR_COMP_SIDEBAR_SIDEBAR . ',
                COMP_SIDEBAR_SIDEBAR: ' . DZConfig::COMP_SIDEBAR_SIDEBAR .',
                SIDEBAR_COMP_SIDEBAR: ' . DZConfig::SIDEBAR_COMP_SIDEBAR .',
                SIDEBAR_COMP: ' . DZConfig::SIDEBAR_COMP .',
                COMP_SIDEBAR: ' . DZConfig::COMP_SIDEBAR . ',
                COMP: ' . DZConfig::COMP . '
            }';
        $doc->addScriptDeclaration($script);
        $doc->addScript(JUri::root().'templates/dz/core/fields/assets/mainlayout.js');
        
        $id = $this->_getIdbyName($this->name);
        $prefix = str_replace('layout', '', $id);
        list($mode, $value, $force, $expandMain) = explode(",", $this->value);
        $html = '';
        $html .=    '<div class="visual-table mainlayout row-fluid" data-prefix="' . $id . '" data-layout="' . $value . '" data-force="' . $force . '" data-expandmain="' . $expandMain . '">';
        $html .=    '<div id="'.$id.'_visual" class="visual-container span6">
                        <div class="visual-inner">
                            <span class="visual-mini visual-sidebar visual-first" data-title="sidebar-1" data-container="#' . $id . '_visual">2</span>
                            <span class="visual-mini visual-main" data-title="main" data-container="#' . $id . '_visual">4</span>
                            <span class="visual-mini visual-sidebar" data-title="sidebar-2" data-container="#' . $id . '_visual">2</span>
                            <span class="visual-mini visual-sidebar visual-last" data-title="sidebar-3" data-container="#' . $id . '_visual">2</span>
                        </div>
                    </div>';
        $html .=    '<div class="span6">';
        $html .=    '<label class="forcebox-label pull-left hasTooltip" data-title="' . JText::_('DZ_FIELD_DESC_FORCEBOX') . '">
                        <input type="checkbox" class="forcebox-input" ' . ($force ? 'checked' : '') . '/>&nbsp;' . JText::_('DZ_FIELD_LBL_FORCEBOX') . '
                    </label>';
        $html .=    '<select class="columns-select pull-left">
                        <option value="' . DZConfig::SIDEBAR_COMP_SIDEBAR_SIDEBAR .'"' . (($mode == DZConfig::SIDEBAR_COMP_SIDEBAR_SIDEBAR) ? ' selected' : '') .'>'.JText::_('DZ_FIELD_MAINLAYOUT_SCSS').'</option>
                        <option value="' . DZConfig::COMP_SIDEBAR_SIDEBAR . '"' . (($mode == DZConfig::COMP_SIDEBAR_SIDEBAR) ? ' selected' : '') .'>'.JText::_('DZ_FIELD_MAINLAYOUT_CSS').'</option>
                        <option value="' . DZConfig::SIDEBAR_COMP_SIDEBAR . '"' . (($mode == DZConfig::SIDEBAR_COMP_SIDEBAR) ? ' selected' : '') .'>'.JText::_('DZ_FIELD_MAINLAYOUT_SCS').'</option>
                        <option value="' . DZConfig::SIDEBAR_COMP . '"' . (($mode == DZConfig::SIDEBAR_COMP) ? ' selected' : '') .'>'.JText::_('DZ_FIELD_MAINLAYOUT_SC').'</option>
                        <option value="' . DZConfig::COMP_SIDEBAR . '"' . (($mode == DZConfig::COMP_SIDEBAR) ? ' selected' : '') .'>'.JText::_('DZ_FIELD_MAINLAYOUT_CS').'</option>
                        <option value="' . DZConfig::COMP . '"' . (($mode == DZConfig::COMP) ? ' selected' : '') .'>'.JText::_('DZ_FIELD_MAINLAYOUT_C').'</option>
                    </select>';
        $html .=    '<div class="clearfix"></div>';
        $html .=    '<label class="expandbox-label pull-left hasTooltip" data-title="' . JText::_('DZ_FIELD_DESC_EXPANDBOX') . '">
                        <input type="checkbox" class="expandbox-input" ' . ($expandMain ? 'checked' : '') . '/>&nbsp;' . JText::_('DZ_FIELD_LBL_EXPANDBOX') . '
                    </label><div class="clearfix"></div>';
        $html .=    '<div class="slider-container">
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