<?php 
/**
 * @package     DZ Joomla 2.5
 * @subpackage  mod_menu
 * @layout      default
 * @version     1.0
 * @date        10/2012
 */
defined('_JEXEC') or die; 
$lastdeeper = false;
$currentitemcount = 0;
?>
<div class="inner mod-menu">
    <ul class="nav"<?php
            $tag = '';
            if ($params->get('tag_id')!=NULL) {
                $tag = $params->get('tag_id').'';
                echo ' id="'.$tag.'"';
            } ?>>
                <?php
        foreach ($list as $i => &$item) :
            $class = '';
            if ($item->id == $active_id) {
                $class .= 'current ';
            }
        
            if (    $item->type == 'alias' &&
                    in_array($item->params->get('aliasoptions'),$path)
                ||  in_array($item->id, $path)) {
              $class .= 'active ';
            }
                $currentitemcount ++;
            if ($item->shallower or $currentitemcount == count($list)) {
            $class .= 'last ';
            }
            if ($lastdeeper or $currentitemcount == 1) {
            $class .= 'first ';
            } 
            if ($item->deeper) {
            $class .= 'deeper ';
            $lastdeeper = true;
            } else {
            $lastdeeper = false;
            }
            if ($item->parent) {
                $class .= 'parent ';
            }
            if ($item->anchor_css) {
                $class .= $item->anchor_css;
            }
            if (!empty($class)) {
                $class = ' class="'.trim($class) .'"';
            }
        
            echo '<li id="item'.$item->id.'"'.$class.'>';
        
            // Render the menu item.
        
            switch ($item->type) :
                case 'separator':
                case 'url':
                case 'component':
                    require JModuleHelper::getLayoutPath('mod_menu', 'default_'.$item->type);
                    break;
        
                default:
                    require JModuleHelper::getLayoutPath('mod_menu', 'default_url');
                    break;
            endswitch;
        
            // The next item is deeper.
            if ($item->deeper) {
                echo '<ul class="child">';
            }
            // The next item is shallower.
            else if ($item->shallower) {
                echo '</li>
                ';
                echo str_repeat('</ul></li>', $item->level_diff);
            }
            // The next item is on the same level.
            else {
                echo '</li>';}  
        endforeach; ?>
        </ul>
</div>