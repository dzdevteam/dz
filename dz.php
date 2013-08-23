<?php
defined('JPATH_BASE') or die();

if (!defined('DZ_VERSION')) {
    /** $var $dz DZ */
    global $dz;
    
    /**
     * @name DZ_VERSION
     */
    define('DZ_VERSION', '1.3.0');

    if (!defined('DS')) {
        define('DS', DIRECTORY_SEPARATOR);
    }
    
    require_once (realpath(dirname(__FILE__)) . '/core/dzloader.class.php');
    
    /**
     * @param  string $path the gantry path to the class to import
     *
     * @return void
     */
    function dz_import($path)
    {
        return DZLoader::import($path);
    }
    
    /**
     *
     */
    function dz_setup()
    {
        dz_import('core.dz');
        jimport('joomla.html.parameter');
    
        /** @var $dz DZ */
        global $dz;
    
        $template      = dz_getTemplate();
        $template_name = $template->template;

        $dz = DZ::getInstance($template_name);
        
        $dz->init();
    }
    
    function dz_getTemplate()
    {
        $app = JFactory::getApplication();
        $template = $app->getTemplate(true);
        
        return $template;
    }
    
    function dz_displayMain()
    {
        /** @var $dz DZ */
        global $dz;
        
        dz_import('core.dzconfig');
        $html = '';
        
        list($maincfg, $mainlayout, $force, $expandMain) = explode(',',$dz->get('mainlayout', '4,2-6-3-3,0,0'));
        $mainlayout = explode('-', $mainlayout);
        switch ($maincfg)
        {
            case DZConfig::SIDEBAR_COMP_SIDEBAR_SIDEBAR:
                $sidebar1 = $dz->displayModules('sidebar-1', $mainlayout[0], $force);
                $sidebar2 = $dz->displayModules('sidebar-2', $mainlayout[2], $force);
                $sidebar3 = $dz->displayModules('sidebar-3', $mainlayout[3], $force);
                if ($expandMain)
                {
                    if (empty($sidebar1)) $mainlayout[1] += $mainlayout[0];
                    if (empty($sidebar2)) $mainlayout[1] += $mainlayout[2];
                    if (empty($sidebar3)) $mainlayout[1] += $mainlayout[3];
                }
                $main = dz_displayCompBlock($mainlayout[1]);
                $html .= $sidebar1.$main.$sidebar2.$sidebar3;
                break;
            case DZConfig::COMP_SIDEBAR_SIDEBAR:
                $sidebar1 = $dz->displayModules('sidebar-1', $mainlayout[1], $force);
                $sidebar2 = $dz->displayModules('sidebar-2', $mainlayout[2], $force);
                if ($expandMain)
                {
                    if (empty($sidebar1)) $mainlayout[0] += $mainlayout[1];
                    if (empty($sidebar2)) $mainlayout[0] += $mainlayout[2];
                }
                $main = dz_displayCompBlock($mainlayout[0]);
                $html .= $main.$sidebar1.$sidebar2;
                break;
            case DZConfig::SIDEBAR_COMP_SIDEBAR:
                $sidebar1 = $dz->displayModules('sidebar-1', $mainlayout[0], $force);
                $sidebar2 = $dz->displayModules('sidebar-2', $mainlayout[2], $force);
                if ($expandMain)
                {
                    if (empty($sidebar1)) $mainlayout[1] += $mainlayout[0];
                    if (empty($sidebar2)) $mainlayout[1] += $mainlayout[2];
                }
                $main = dz_displayCompBlock($mainlayout[1]);
                $html .= $sidebar1.$main.$sidebar2;
                break;
            case DZConfig::SIDEBAR_COMP:
                $sidebar1 = $dz->displayModules('sidebar-1', $mainlayout[0], $force);
                if ($expandMain)
                    if (empty($sidebar1))
                        $mainlayout[1] += $mainlayout[0];
                $main = dz_displayCompBlock($mainlayout[1]);
                $html .= $sidebar1.$main;
                break;
            case DZConfig::COMP_SIDEBAR:
                $sidebar1 = $dz->displayModules('sidebar-1', $mainlayout[1], $force);
                if ($expandMain)
                    if (empty($sidebar1))
                        $mainlayout[0] += $mainlayout[1];
                $main = dz_displayCompBlock($mainlayout[0]);
                $html .= $main.$sidebar1;
                break;
            case DZConfig::COMP:
                // Fall through
            default:
                $html .= dz_displayCompBlock(12);
                break;
        }
        
        if (!empty($html))
            $html = '<div class="row-fluid">'.$html.'</div>';
        
        return $html;
    }
    
    function dz_displayCompBlock($span, $class = "")
    {
        /** @var $dz DZ */
        global $dz;
        
        $html   =   '<div class="span'.$span.( !empty($class) ? ' '.$class : '').'"><div class="dz-main">';
        
        $before =   $dz->displayModules("before", 0);
        if (!empty($before))
            $html .= $before;
        
        if ( ($dz->get('modulesOverComp', 0) == 1 && $dz->countModules('component')))
            $html .=    '<div class = "dz-component">'.$dz->displayModules("component", 0).'</div>';
        else
            $html .= '<div class="dz-component"><jdoc:include type="component" /></div>';
        
        $after =    $dz->displayModules("after", 0);
        if (!empty($after))
            $html .= $after;
        
        $html   .= '</div></div>';
        
        return $html;
    }
    
    /**
     * Add logo html
     * @param string $content
     *  String to add the logo html to
     *
     * @return string
     *  new html string with the logo inside
     */
    function dz_logoFilter($content)
    {
        $doc = JFactory::getDocument();
        $params = $doc->params;
    
        if ($params->get('logoText')) {
            $sitename = $params->get('logoText');
        } else {
            $config = JFactory::getConfig();
            $sitename = $config->getValue('config.sitename');
        }
    
        switch ($params->get('logoDisplay')) {
            case 0:
                $logo = $sitename;
                break;
            case 1:
                $logo = '<img src='.$doc->baseurl.'/'.$params->get('logoImage').' alt="'.$sitename.'"/>';
                break;
            case 2:
                $logo = $sitename.'<small>'.$params->get('logoSlogan').'</small>';
                break;
            case 3:
                $logo = '<img src='.$params->get('logoImage').' alt="'.$sitename.'"/>'.$sitename;
                break;
            case 4:
                $logo = '<img src='.$params->get('logoImage').' alt="'.$sitename.'"/>'.$sitename.'<small>'.$params->get('logoSlogan').'</small>';
                break;
        }
    
        $html = '<h1 class="logo"><a href="'.$doc->baseurl.'" title="'.$sitename.'">'.$logo.'</a></h1>';
        
        // Ignore the module's content and replace it with the logo
        return $html;
    }
    
    function dz_menuFilter($content)
    {
        $html = "";
        $before = 
            '<div class="navbar">'
        .       '<div class="navbar-inner">'
        .           '<div class="container">'
        .               '<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">'
        .                   '<span class="icon-bar"></span>'
        .                   '<span class="icon-bar"></span>'
        .                   '<span class="icon-bar"></span>'
        .               '</a>'
        .               '<a class="brand" href="/">DZ Original</a>'
        .               '<div class="nav-collapse collapse">';
        $after = 
                        '</div>'
        .           '</div>'
        .       '</div>'
        .   '</div>';
        
        if (!empty($content))
            $html = $before.$content.$after;
    
        return $html;
    }
    
    function dz_gAnalytics()
    {
        global $dz;
        
        $code = $dz->get('analytics_code', '');
        $script = '';
        
        if (!empty($code))
        {
            $script = "<script type=\"text/javascript\">

                  var _gaq = _gaq || [];
                  _gaq.push(['_setAccount', '".$code."']);
                  _gaq.push(['_trackPageview']);
                
                  (function() {
                    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
                    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
                  })();
                
                </script>"  ;       
        }
        
        return $script;
    }
    // Run initialization
    dz_setup();
    $dz->addFilter('module_'.$dz->get('logoPosition'), 'dz_logoFilter');
    $dz->addFilter('module_'.'menu', 'dz_menuFilter');
}