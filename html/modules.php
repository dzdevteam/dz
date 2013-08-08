<?php
defined('_JEXEC') or die;
function modChrome_dz ($module, &$params, &$attribs)
{	
	if (!empty ($module->content)) : ?>
<div id="m<?php echo $module->id;?>" class="module<?php echo htmlspecialchars($params->get('moduleclass_sfx'));?>">
	<div class="module-inner">
<?php if ($module->showtitle) :?><h3 class="module-header"><?php echo $module->title;?></h3><?php endif; ?>
	<div class="module-content"><?php echo $module->content; ?></div>
</div>   
</div>   
<?php endif;
}
?>