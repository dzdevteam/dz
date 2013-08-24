<?php
defined('_JEXEC') or die;
require_once('dz.php');
/** @var $dz DZ */
$dz->init();
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $dz->language; ?>" lang="<?php echo $dz->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
<?php if ($dz->get('responsive') == 1) : ?>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<?php endif; ?>
<jdoc:include type="head" />
<?php echo dz_gAnalytics();?>
</head>

<body<?php $class = $dz->get('pageclass_sfx', ''); echo (!empty($class)) ? ' class="'. $class .'"' : '';?>>
<?php $dz->includeLayout("default");?>
</body>
</html>
<?php $dz->finalize();?>