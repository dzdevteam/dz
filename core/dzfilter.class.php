<?php
/**
 * DZ uses the Joomla Framework (http://www.joomla.org), a GNU/GPLv2 content management system
 * 
 * @version dzfilter.class.php 2012-12-05
 * @author DZTeam http://dezign.vn
 * @copyright Copyright (C) 2012 DZ Creative Studio 
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 * 
 */
defined('JPATH_BASE') or die;

/**
 * An internal class inspired by the filter model of wordpress.
 * 
 * @author DZTeam
 * @package DZCore
 * @subpackage Utilities
 *
 */
class DZFilter
{
	/**
	 * Contain all filter names and all functions name used for each filters.
	 * 
	 * Structure:
	 *  - filter_1
	 *    - function_1.1
	 *    - function_1.2
	 *  - filter_2
	 *    - function_2.1
	 *    
	 * @var array()
	 * 
	 */
	protected $__filters = array();

	/**
	 * Add a filter function into a filter group
	 * 
	 * If the group does not exists, it will be automatically
	 * created.
	 * 
	 * The function must only have one argument to accept the
	 * data passed through it by applyFilter() method and then return
	 * the modified content.
	 * 
	 * Example usage:
	 * <code>
	 *   $filter->addFilter("emoticon", "replaceEmoticon");
	 *   
	 *   function replaceEmoticon(&$content)
	 *   {
	 *   	// code to replace all :D, :^",etc into image
	 *      
	 *      return $content;
	 *   }
	 * </code>
	 * 
	 * @param string $filterName
	 *   Filter group's name
	 * @param string $filterFunc
	 *   Filter function's name
	 */
	public function addFilter($filterName, $filterFunc)
	{
		// Prevent duplication of filter functions
		if (key_exists($filterName, $this->__filters))
			if (key_exists($filterFunc, $this->__filters[$filterName]))
				return;
		
		// Add filter function
		$this->__filters[$filterName][] = $filterFunc;
	}
	
	/**
	 * Passthrough content into all filter functions of a group.
	 * 
	 * How the content is modified is based on the behavior of each
	 * filter function.
	 * 
	 * Example Usage:
	 * @code
	 *   // Replace all emoticon inside $content into image
	 *   $filter->applyFilter($content, "emoticon");
	 * @endcode
	 * 
	 * @param mixed $content
	 *   The content variable which needs to be filtered. Can be of any type.
	 * @param string $filterName
	 *   The filter group's name
	 */
	public function applyFilter(&$content, $filterName)
	{
		if (array_key_exists($filterName, $this->__filters))
		{
			foreach ($this->__filters[$filterName] as $filter)
			{
				$content = call_user_func($filter, $content);
			}
		}
	}
}