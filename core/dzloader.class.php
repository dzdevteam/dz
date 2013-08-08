<?php
/**
 * DZ uses the Joomla Framework (http://www.joomla.org), a GNU/GPLv2 content management system
 * 
 * @version dzloader.class.php 2012-12-05
 * @author DZTeam http://dezign.vn
 * @copyright Copyright (C) 2012 DZ Creative Studio
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 */
defined('JPATH_BASE') or die();

/**
 * This class has the responsibility to connect classes together
 * 
 * @author DZTeam
 * @package DZCore
 *
 */
class DZLoader
{
	/**
	 * Loads a class from specified directories.
	 *
	 * @param string $filePath    
	 *  The class name to look for ( dot notation ).
	 *
	 * @return void
	 */
	public static function import($filePath)
	{
		static $paths, $base;

		if (!isset($paths)) {
			$paths = array();
		}

		if (!isset($base)) {
			$base = realpath(dirname(__FILE__) . '/..');
		}

		if (!isset($paths[$filePath])) {
			$parts            = explode('.', $filePath);
			$classname        = array_pop($parts);
			$path             = str_replace('.', '/', $filePath);
			$rs               = include($base . '/' . $path . '.class.php');
			$paths[$filePath] = $rs;
		}
		return $paths[$filePath];
	}
}