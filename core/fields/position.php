<?php
defined('JPATH_BASE') or die();

jimport('joomla.html.html');
jimport('joomla.form.formfield');

class JFormFieldPosition extends JFormField {
    protected $type = "position";
    
    protected function getInput() {
        require_once JPATH_ADMINISTRATOR . '/components/com_modules/helpers/modules.php';
        JHtml::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_modules/helpers/html');
        $language = JFactory::getLanguage();
        $language->load('com_modules', JPATH_ADMINISTRATOR);
        
        $clientId       = 0;
        $state          = 1;
        $selectedPosition = $this->value;
        $positions = JHtml::_('modules.positions', $clientId, $state, $selectedPosition);


        // Add custom position to options
        $customGroupText = JText::_('COM_MODULES_CUSTOM_POSITION');

        // Build field
        $attr = array(
            'id'          => $this->id,
            'list.select' => $this->value,
            'list.attr'   => 'class="chzn-custom-value input-xlarge" '
            . 'data-custom_group_text="' . $customGroupText . '" '
            . 'data-no_results_text="' . JText::_('COM_MODULES_ADD_CUSTOM_POSITION') . '" '
            . 'data-placeholder="' . JText::_('COM_MODULES_TYPE_OR_SELECT_POSITION') . '" '
        );

        return JHtml::_('select.groupedlist', $positions, $this->name, $attr);
    }
}