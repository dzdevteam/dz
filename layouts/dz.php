<?php 
global $dz;

// Add styles
$dz->addStyle($dz->templateUrl.'/css-compiled/bootstrap.css', true);
$dz->addStyle($dz->templateUrl.'/css/mainstyle.css', true);
$color = $dz->get('colorizeCSS', -1);
if ($color != -1)
    $dz->addStyle($dz->templateUrl.'/css/colors/'.$color, true);
if ($dz->get('responsive', 1))
    $dz->addStyle($dz->templateUrl.'/css-compiled/responsive.css', true);


// Add scripts
JHtml::_('bootstrap.framework');
?>
<div class="container">
    <?php echo $dz->displayModulesRow("fixedtop", "row");?>
    <?php echo $dz->displayModulesRow("top", "row");?>
    <?php echo $dz->displayModulesRow("header", "row");?>
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

