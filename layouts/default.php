<?php 
/**
 * @package     DZ Core Template
 * @update      April 2013
 * @copyright   Copyright © 2013 DZ Creative Studio. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
global $dz;

// Add styles
$tplRelPath = JUri::base(true).'/templates/'.$dz->templateName;
$dz->addStyleMinify($tplRelPath.'/css-compiled/bootstrap.css');
$dz->addStyleMinify($tplRelPath.'/css/mainstyle.css');
$color = $dz->get('colorizeCSS', -1);
if ($color != -1)
    $dz->addStyleMinify($tplRelPath.'/css/colors/'.$color);
if ($dz->get('responsive', 1))
    $dz->addStyleMinify($tplRelPath.'/css-compiled/responsive.css');

// Add scripts
$dz->addScript($dz->templateUrl.'/js/jquery-1.8.2.min.js');
$dz->addInlineScript("jQuery.noConflict();");
$dz->addScript($dz->templateUrl.'/js/bootstrap.min.js');
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