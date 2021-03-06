<?php
/**
 * DZ uses the Joomla Framework (http://www.joomla.org), a GNU/GPLv2 content management system
 * 
 * @version dzconfig.class.php 2012-12-05
 * @author DZTeam http://dezign.vn
 * @copyright Copyright (C) 2012 -2013 DZ Creative Studio
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 */
defined('JPATH_BASE') or die;

/**
 * This class contains configuration information
 * for using in DZ class
 * 
 * @author DZTeam
 * @package DZCore
 *
 */
abstract class DZConfig
{
    /** @name Main layout pre-configuration
     * Configuration constants for main layout
     */
    ///@{ 
    const SIDEBAR_COMP_SIDEBAR_SIDEBAR  = 4;
    const COMP_SIDEBAR_SIDEBAR          = 3;
    const SIDEBAR_COMP_SIDEBAR          = 13;
    const SIDEBAR_COMP                  = 2;
    const COMP_SIDEBAR                  = 12;
    const COMP                          = 1;
    ///@}
    
    /** @name Logo display configuration
     * Constants for logo display options
     */
    ///@{
    const LOGO_TEXT_ONLY            = 0;
    const LOGO_IMAGE_ONLY           = 1;
    const LOGO_TEXT_AND_SLOGAN      = 2;
    const LOGO_IMAGE_AND_TEXT       = 3;
    const LOGO_IMAGE_TEXT_SLOGAN    = 4;
    ///@}
}