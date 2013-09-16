<?php 
/**
 * @package     DZ Core Template
 * @update      April 2013
 * @copyright   Copyright © 2013 DZ Creative Studio. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
global $dz;

// Add styles
$dz->addStyle($dz->templateUrl.'/css-compiled/bootstrap.css', true);
$dz->addStyle($dz->templateUrl.'/css/mainstyle.css', true);
$color = $dz->get('colorizeCSS', -1);
if ($color != -1)
    $dz->addStyle($dz->templateUrl.'/css/colors/'.$color, true);
if ($dz->get('responsive', 0))
    $dz->addStyle($dz->templateUrl.'/css-compiled/responsive.css', true);

// Add scripts
JHtml::_('bootstrap.framework');
?>
<div id="block-dz">
<div class="container">
    <?php echo $dz->displayModulesRow("fixedtop", "row");?>
    <?php echo $dz->displayModulesRow("top", "row");?>
    <?php echo $dz->displayModulesRow("header", "row");?>
    <div class="row"><?php echo $dz->displayModules("nav", 12);?></div>
    <?php echo $dz->displayModulesRow("showcase", "row");?>
    <?php echo $dz->displayModulesRow("maintop", "row");?>
    <?php if (!($dz->currentMenuItem == $dz->defaultMenuItem && $dz->get('compactHome', 0) == 1)) :?>
        <?php echo dz_displayMain();?>
    <?php endif;?>
    <?php echo $dz->displayModulesRow("mainbottom", "row");?>
    <?php echo $dz->displayModulesRow("bottom", "row");?>
    <?php echo $dz->displayModulesRow("footer", "row");?>
    <?php echo $dz->displayModulesRow("fixedbottom", "row");?>
</div>
</div>