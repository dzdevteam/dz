<?php
/**
 * The template index file
 *
 * @author DZTeam http://dezign.vn
 * @copyright Copyright (C) 2012 - 2013 DZ Creative Studio 
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
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
<title><?php echo $this->error->getCode(); ?> - <?php echo $this->title; ?></title>
<link rel="stylesheet" href="<?php echo JUri::root(true) ?>/media/cms/css/debug.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $dz->templateUrl; ?>/css-compiled/bootstrap.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="<?php echo $dz->templateUrl; ?>/css/mainstyle.css" type="text/css" media="screen,projection" />
<?php if ($dz->get('responsive', 0)) : ?>
    <link rel="stylesheet" href="<?php echo $dz->templateUrl; ?>/css-compiled/responsive.css" type="text/css" media="screen,projection" />
<?php endif; ?>
</head>

<body<?php $class = $dz->get('pageclass_sfx', ''); echo (!empty($class)) ? ' class="'. $class .'"' : '';?>>
<div id="block-dz">
    <div class="container">
        <?php $module = JModuleHelper::getModule( 'menu' );
        echo JModuleHelper::renderModule( $module); ?>
        <div class="hero-unit text-center">
            <h1>Oops...!&nbsp;<small><span style="color:red">#<?php echo $this->error->getCode() ;?></span></small></h1>
            
            <p><?php echo JText::_('JERROR_AN_ERROR_HAS_OCCURRED'); ?><p>
            <div class="alert alert-danger"><?php echo $this->error->getMessage(); ?></div>
            
            <?php if (!($this->error->getCode() >= 400 && $this->error->getCode() < 500)) { ?>
            <div class="well text-left">
            <p><strong><?php echo JText::_('JERROR_LAYOUT_NOT_ABLE_TO_VISIT'); ?></strong></p>
            <ol>
                <li><?php echo JText::_('JERROR_LAYOUT_AN_OUT_OF_DATE_BOOKMARK_FAVOURITE'); ?></li>
                <li><?php echo JText::_('JERROR_LAYOUT_SEARCH_ENGINE_OUT_OF_DATE_LISTING'); ?></li>
                <li><?php echo JText::_('JERROR_LAYOUT_MIS_TYPED_ADDRESS'); ?></li>
                <li><?php echo JText::_('JERROR_LAYOUT_YOU_HAVE_NO_ACCESS_TO_THIS_PAGE'); ?></li>
                <li><?php echo JText::_('JERROR_LAYOUT_REQUESTED_RESOURCE_WAS_NOT_FOUND'); ?></li>
                <li><?php echo JText::_('JERROR_LAYOUT_ERROR_HAS_OCCURRED_WHILE_PROCESSING_YOUR_REQUEST'); ?></li>
            </ol>
            </div>
            <?php } ?>
            
            <p><strong><?php echo JText::_('JERROR_LAYOUT_PLEASE_TRY_ONE_OF_THE_FOLLOWING_PAGES'); ?></strong></p>
            <a class="btn btn-large btn-info" href="<?php echo JUri::root(true) ?>/index.php" title="<?php echo JText::_('JERROR_LAYOUT_GO_TO_THE_HOME_PAGE'); ?>"><?php echo JText::_('JERROR_LAYOUT_HOME_PAGE'); ?></a>
            <a class="btn btn-large btn-info" href="<?php echo JUri::root(true) ?>/index.php?option=com_search" title="<?php echo JText::_('JERROR_LAYOUT_SEARCH_PAGE'); ?>"><?php echo JText::_('JERROR_LAYOUT_SEARCH_PAGE'); ?></a>
            <br /><br />
            <p><?php echo JText::_('JERROR_LAYOUT_PLEASE_CONTACT_THE_SYSTEM_ADMINISTRATOR'); ?></p>
        </div>
        
        <?php if (!($this->error->getCode() >= 400 && $this->error->getCode() < 500)) { ?>
        <pre>
        <?php if ($this->debug) :
            echo $this->renderBacktrace();
        endif; ?>
        </pre>
        <?php } ?>
    </div><!-- end container -->
</div>
</body>
</html>
<?php $dz->finalize();?>