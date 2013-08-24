<?php
/**
 * DZ uses the Joomla Framework (http://www.joomla.org), a GNU/GPLv2 content management system
 * 
 * @version dz.class.php 2012-12-05
 * @author DZTeam http://dezign.vn
 * @copyright Copyright (C) 2012 DZ Creative Studio
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 * @package DZCore
 */
defined('JPATH_BASE') or die();

dz_import('core.dzfilter');
dz_import('core.dzconfig');

/**
 * The class which has the responsibility to provide API for 
 * building template based on DZ Framework
 * 
 * @author DZTeam
 * @package DZCore
 *
 */
class DZ
{
    /** Default priority for newly added stylesheets or scripts */
    const DEFAULT_PRIORITY = 10;
    
    /** 
     * Template instances
     * 
     * Contain all the template instances created by this class
     * 
     * @type DZ[]  
     */
    static $instances = array();
    
    /**
     * Get a template instance specified by its name.
     * 
     * Automatically create a template instance if it didn't 
     * exists.
     * 
     * @static
     *
     * @param string $template_name
     *
     * @return DZ
    */
    public static function getInstance($template_name)
    {
        if (!array_key_exists($template_name, self::$instances)) {
            self::$instances[$template_name] = new DZ($template_name);
        }
        return self::$instances[$template_name];
    }
    
    /** @name Basic information
     * Information variables for template
     */
    ///@{
    public $basePath;
    public $baseUrl;
    public $templateName;
    public $templateUrl;
    public $templatePath;
    public $templateId;
    public $layoutPath;
    public $defaultMenuItem;
    public $currentMenuItem;
    public $currentMenuTree;
    public $template_prefix;
    public $positions = array();
    /**
     * @var JDocumentHTML
     */
    public $document;   
    public $language;
    public $session;
    public $currentUrl;
    ///@}

    /** @name Private variables
     * Store parameters, scripts and stylesheets of the template
     */
    ///@{
    private $_working_params;
    private $_preconfigs;
    public $_scripts = array();
    public $_domready_script = '';
    public $_styles = array();
    public $_styles_available = array();
    ///@}
    
    /**
     * Internal filter
     * @var DZFilter
     */
    protected $__filter;
    
    /**
     * Constructor
     * 
     * This will initialize the class when it is declared the first time.
     * Some helper variables will be set and an internal filter for this 
     * class will also be created.
     *
     * @param string $template_name
     *  (optional) The name of the template which we want to build by this class. DEFAULT: null
     *
     * @return DZ
     */
    public function __construct($template_name = null)
    {
        // set the base class vars
        $doc            = JFactory::getDocument();
        $this->document =& $doc;

        if ($template_name == null) {
            $this->templateName = $this->getCurrentTemplate();
        } else {
            $this->templateName = $template_name;
        }
        
        $this->basePath = $this->cleanPath(JPATH_ROOT);
        $this->templatePath        = $this->cleanPath(JPATH_ROOT . '/' . 'templates' . '/' . $this->templateName);
        $this->baseUrl             = JURI::root(true) . "/";
        $this->templateUrl         = $this->baseUrl . 'templates' . "/" . $this->templateName;

        $this->defaultMenuItem = $this->getDefaultMenuItem();
        $this->currentMenuItem = $this->defaultMenuItem;
        
        $reflector = new ReflectionClass('DZConfig');
        $this->_preconfigs = $reflector->getConstants();
        
        // Initialize filter
        $this->__filter = new DZFilter();
    }

    /**
     * INITIALIZER
     * 
     * Get and set important variables. The other functions depend 
     * heavily on this one. Do not use any other methods without first 
     * running of this function. 
     *
     * @return void
     */
    public function init()
    {
        if (defined('DZ_INIT')) 
        {
            return;
        }

        define('DZ_INIT', "DZ_INIT");

        //JHTML::_('behavior.mootools');
        $doc               = JFactory::getDocument();
        $this->document    =& $doc;
        $this->_working_params = $this->document->params;
        $this->language    = $doc->language;
        $this->session     = JFactory::getSession();
        $this->baseUrl     = JURI::root(true) . "/";
        $uri               = JURI::getInstance();
        $this->currentUrl  = $uri->toString();
        $this->templateUrl = $this->baseUrl . 'templates' . "/" . $this->templateName;
        
        $app = JFactory::getApplication();
        // use any menu item level overrides
        $menus                 = $app->getMenu();
        $menu                  = $menus->getActive();
        $this->_working_params->merge($menu->params);
        $this->currentMenuItem = ($menu != null && isset($menu->id) ) ? $menu->id : null;
        $this->currentMenuTree = ($menu != null && isset($menu->tree) ) ? $menu->tree : array();
    }
    
    /**
     * A function to make sure we have a valid path
     * 
     * @param string $path
     *  Path to be cleaned
     * 
     * @return string $path
     *  Cleaned path
     */
    public function cleanPath($path)
    {
        if (!preg_match('#^/$#', $path))
        {
            $path = preg_replace('#[/\\\\]+#', '/', $path);
            $path = preg_replace('#/$#','',$path);
        }
        return $path;
    }
    
    /** @name Parameter Helpers
     */
    ///@{   
    /**
     * Get the name of the current template
     * 
     * @return string
     */
    public function getCurrentTemplate()
    {
        $session = JFactory::getSession();
        
        $app      = JApplication::getInstance('site', array(), 'J');
        $template = $app->getTemplate();
        
        $session->set('dz-current-template', $template);
        return $template;
    }

    /**
     * Get the default menu item's ID
     * 
     * @return int
     */
    protected function getDefaultMenuItem()
    {
        $app          = &JFactory::getApplication();
        $language     = &JFactory::getLanguage();
        $menu         = $app->getMenu();
        $default_item = $menu->getDefault($language->getTag());
        return $default_item->id;
    }


    /**
     * Alternative way to retrieve a parameter
     * 
     * @param string $param
     *  Parameter's name
     * @param mixed $default
     *  (optional) Default value if the parameter is not set. DEFAULT: null
     *  
     * @return mixed
     *  The value of the parameter
     */
    public function get($param, $default = null)
    {
        $result = $this->_working_params->get($param, null);
        if ($result == null && isset($this->_preconfigs['PARAMS_'.strtoupper($param)]))
            $result = $this->_preconfigs['PARAMS_'.strtoupper($param)];
        
        return ($result !== null) ? $result : $default;
    }
    
    /**
     * Alternative way to set a parameter
     * 
     * @param string $param
     *  Parameter's name
     * @param mixed $value
     *  Parameter's value
     *  
     * @return mixed
     *  The parameter's value which just be set
     */
    public function set($param, $value)
    {
        return $this->_working_params->set($param, $value);
    }
    ///@}
    
    /** @name Layout Helpers
     */
    ///@{
    /**
     * Include the layout file specified by its name
     * 
     * @param string $layout
     *  Layout file's name (no '.php' at the end)
     * 
     * @return void
     */
    public function includeLayout($layout = 'default')
    {
        include_once(JPATH_THEMES.DS.$this->templateName.DS.'layouts'.DS.$layout.'.php');       
    }
    
    /**
     * Count number of modules for a position or expression
     * 
     * Refer to JDocumentHTML documentation for more details
     * @see http://docs.joomla.org/JDocumentHTML/countModules JDocumentHTML Documentation
     * 
     * @param string $condition
     *  Position name or Expression
     * 
     * @return int
     *  The number of modules available in that position
     */
    public function countModules($condition)
    {
        return $this->document->countModules($condition);
    }
    
    /**
     * Display individual module position
     * 
     * @param string $position 
     *  Position name
     * 
     * @param int $span
     *  (optional) The span width of div wrapper for current position. 
     *  If equals to 0, there will be no div wrapper around the position.
     *  DEFAULT: 0 
     *
     * @param bool $force
     *  (optional) Still creates the div wrapper if there's no module assigned 
     *  to current position (but does not when span width is 0). 
     *  DEFAULT: FALSE 
     * 
     * @param string $style
     *  (optional) Specific style for all modules in this position. 
     *  DEFAULT: "dz". 
     *  
     * @return string $html
     *  HTML code of the modules
     */
    public function displayModules($position, $span = 0, $force = false, $style = "dz")
    {
        $html = "";
        $content = "";
        
        if ($this->countModules($position))
            $content = '<div class="dz-'.$position.'"><jdoc:include type="modules" name="'.$position.'" style="'.$style.'" /></div>';
        
        $this->__filter->applyFilter($content, 'module_'.$position);
        
        if ($span > 0)
        {
            if ($force || !empty($content))
            {
                $html = '<div class="span'.$span.'">'.$content.'</div>';
            }
        } else {
            $html = $content;   
        }

        return $html;
    }
    
    /**
     * Display a row of related module positions
     * 
     * @param string $prefix
     *  All positions with this prefix will be display.
     *  The number of positions will be determine from params.
     * 
     * @param string $class
     *  (optional) Custom class for the row. 
     *  DEFAULT: "".
     * 
     * @param boolean $forceoverride
     *  (optional) This will override the "Force Position" settings from template's params. 
     *  DEFAULT: NULL (not set)
     *  
     * @return string $html
     *  HTML code of the row
     */
    public function displayModulesRow($prefix, $class = "", $style = "dz", $forceoverride = null)
    {
        $html = ''; 
        list($rowlayout, $force) = explode(',', $this->get($prefix.'layout', '2-2-2-2-2-2,0'));
        $rowspans = explode('-', $rowlayout);
        foreach ($rowspans as $i => $span)
        {
            $html .= $this->displayModules($prefix.'-'.($i + 1), $span, ($forceoverride == null) ? $force : $forceoverride, $style);
        }
        
        if (!empty($class))
            $class = ' class = "'.$class.'"';
        if (!empty($html))
            $html = '<div'.$class.'>'.$html.'</div>';
        
        return $html;
    }
    
    /**
     * Check for any module-assigned positions in a row
     * 
     * @param string $rowName
     *  The prefix of all the module positions displayed in this row.
     *  
     * @return boolean
     *  TRUE if at least 1 position has a module assigned to it
     */
    public function rowExists($rowName)
    {
        // Search for existance of any module in the row specified
        for ($i = 1; $i <= 6; $i++)
            if ($this->countModules($rowName.'-'.$i))
                return true;
        
        // Check for logo existance
        $pos = explode('-', $this->get('logoPosition'));
        if ($pos[0] == $rowName)
            return true;
        
        // Default
        return false;
    }
    ///@}
    
    /** @name Stylesheets and Scripts Helpers
     */
    ///@{
    /**
     * Enqueue stylesheet file with its priority
     * 
     * @param string $file
     *  URL of the stylesheet
     * @param boolean $isRel
     *  True to indicate that the file path is relative to the root URL
     * @param int $priority
     *  (optional) Lower value mean higher priority, i.e The CSS file will be added first.
     *  DEFAULT: DZ::DEFAULT_PRIORITY
     *  
     * @return void
     */
    public function addStyle($file = '', $isRel = false, $priority = self::DEFAULT_PRIORITY)
    {
        if (is_array($file)) {
            $this->addStyles($file, $priority);
            return;
        }
        
        if ($isRel) {
            if ( (boolean) $this->get('autominify', 0) )
                $file = $this->templateUrl.'/core/utilities/min?f='.$file;
            else
                $file = $this->baseUrl.$file;
        }
        
        $addit = true;
        foreach ($this->_styles as $style_priority => $files) {
            $index = array_search($file, $files);
            if ($index !== false) {
                if ($priority < $style_priority) {
                    unset($this->_styles[$style_priority][$index]);
                } else {
                    $addit = false;
                }
            }
        }
        
        if ($addit) {
            if (!defined('DZ_FINALIZED')) {
                $this->_styles[$priority][] = $file;
            } else {
                $this->document->addStyleSheet($file);
            }
        }
        
        //clean up styles
        foreach ($this->_styles as $style_priority => $priority_links) {
            if (count($priority_links) == 0) {
                unset($this->_styles[$style_priority]);
            }
        }
    }
    
    /**
     * Auto minify a stylesheet file using PHP Minify library
     * 
     * Example usage:
     * @code
     *   // An instance of the core class
     *   global $dz;
     *   
     *   // Get the relative path of the template 
     *   $tplRelPath = JUri::base(true).'/templates/'.$dz->templateName;
     *   
     *   // Add style 
     *   $dz->addStyleMinify($tplRelPath.'/css-compiled/bootstrap.css');
     * @endcode
     * 
     * @param string $relPath
     *  Relative path (compare to root) of the file
     */
    public function addStyleMinify($relPath)
    {
        $minifyPath = $this->templateUrl.'/core/utilities/min';
        $this->addStyle($minifyPath.'?f='.$relPath);    
    }
    
    /**
     * Enqueue group of stylesheet files with the same priority
     * 
     * @param array $styles
     *  Array of stylesheet file's paths
     * 
     * @param int   $priority
     */
    public function addStyles($styles = array(), $priority = self::DEFAULT_PRIORITY)
    {
        if (defined('DZ_FINALIZED')) return;
        foreach ($styles as $style) 
            $this->addStyle($style, $priority);
    }
    
    /**
     * Directly add some stylesheet code into the head of the document.
     * 
     * If the document is FINALIZED, this function will no longer have any effect.
     * 
     * @param string $css
     *  (optional) Some stylesheet code 
     *
     * @return JDocument|null
     */
    public function addInlineStyle($css = '')
    {
        if (defined('DZ_FINALIZED')) 
            return $this->document;
        return $this->document->addStyleDeclaration($css);
    }
    
    /**
     * Enqueue javascript file with its priority
     * 
     * @param string $file
     *  Full URL of the javascript file
     *  
     * @param int $priority
     *  (optional) Lower value means higher priority. i.e The file will be added first.
     *  DEFAULT: DZ::DEFAULT_PRIORITY
     *  
     * @return void
     */
    public function addScript($file = '', $priority = self::DEFAULT_PRIORITY)
    {
        if (is_array($file)) {
            $this->addScripts($file);
            return;
        }
        
        $addit = true;
        foreach ($this->_scripts as $script_priority => $scripts)
        {
            $index = array_search($file, $scripts);
            if ($index !== false) {
                if ($priority < $script_priority) {
                    unset($this->_styles[$script_priority][$index]);
                } else {
                    $addit = false;
                }
            }
        }
        
        if ($addit) {
            if (!defined('DZ_FINALIZED')) {
                $this->_scripts[$priority][] = $file;
            } else {
                $this->document->addScript($file);
            }
        }
    }
    
    /**
     * Enqueue groups of script file with the same priority
     * 
     * @param array $scripts
     *  Array of script files' paths
     * @param int $priority
     *  Priority of the group
     *  
     * @return void
     */
    public function addScripts($scripts = array(), $priority = self::DEFAULT_PRIORITY)
    {
        if (defined('DZ_FINALIZED')) return;
        foreach ($scripts as $script) 
            $this->addScript($script, $priority);
    }
    
    /**
     * Directly add some javascript code into the head of the document
     * 
     * @param string $js
     *  Some JS code
     * @return JDocument|null
     */
    public function addInlineScript($js = '')
    {
        if (defined('DZ_FINALIZED')) 
            return $this->document;
        
        return $this->document->addScriptDeclaration($js);
    }
    
    /**
     * Add script into document head and auto connect it with domready event of the document.
     * 
     * @param string $js
     *  Some javascript code
     *  
     * @return void
     */
    public function addDomReadyScript($js = '')
    {
        if (defined('DZ_FINALIZED')) return;
        if (!isset($this->_domready_script)) {
            $this->_domready_script = $js;
        } else {
            $this->_domready_script .= chr(13) . $js;
        }
    }
    ///@}
    
    /**
     * FINALIZER
     * 
     * This method will do the final work for the stylesheet and javascript functions, i.e actually put
     * js and css into the document head.
     * 
     * The document will be in finalized state after this step, i.e all stylesheet and javascript functions
     * will no longer have effect after running this methods.
     */
    public function finalize()
    {
        if (!defined('DZ_FINALIZED')) {
            define('DZ_FINALIZED', "DZ_FINALIZED");
            ksort($this->_styles);
            foreach ($this->_styles as $priorities) {
                foreach ($priorities as $css_file) {
                    $this->document->addStyleSheet($css_file);
                }
            }
            
            ksort($this->_scripts);
            foreach ($this->_scripts as $priorities) {
                foreach ($priorities as $script_file) {
                    $this->document->addScript($script_file);
                }
            }
            
            $lnEnd   = "\12";
            $domreadyStr = '';
            // Generate domready script
            if (isset($this->_domready_script) && !empty($this->_domready_script)) {
                $domreadyStr .= 'window.addEvent(\'domready\', function() {' . $this->_domready_script . $lnEnd . '});' . $lnEnd;
            }
            $this->document->addScriptDeclaration($domreadyStr);
        }       
    }
    
    /** @name Filter Helpers
     */
    //@{
    /** Shortcut for filtering methods of the variable __filter
     * @see DZFilter::addFilter()
     * @see DZFilter::applyFilter()
     */
    public function addFilter($filterName, $filterFunc)
    {
        $this->__filter->addFilter($filterName, $filterFunc);
    }
    
    /** Shortcut for filtering methods of the variable __filter
     * @see DZFilter::addFilter()
     * @see DZFilter::applyFilter()
     */
    public function applyFilter($content, $filterName)
    {
        $this->__filter->applyFilter($content, $filterName);
    }
    //@}
}

