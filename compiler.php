<?php 
/**
 * Using lessc to compile bootstrap
 *
 * @author DZTeam http://dezign.vn
 * @copyright Copyright (C) 2012 - 2013 DZ Creative Studio 
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
    // This script will return json
    header('Content-Type: application/json');
    
    // The lessc library
    include_once("core/includes/lessc.inc.php");
    
    // Use Joomla Platform
    chdir('../../libraries');
    define('JPATH_PLATFORM', getcwd());
    define('JPATH_LIBRARIES', getcwd());
    require_once 'import.legacy.php';
    chdir('..');
    define('JPATH_SITE', getcwd());  
    jimport('joomla.filesystem.file');

    // DEBUG
    if (isset($_GET['compile']))
        $data = $_GET['compile'];
    else if (isset($_POST['compile']))
        $data = $_POST['compile'];

    if (empty($data) || !isset($data['variables']) || !isset($data['responsive']) || !isset($data['components']))
    {
        echo json_encode(array('status' => 'nok', 'message' => 'Invalid Request!'));
        return;
    }
    
    // New lessc compiler object
    $less = new lessc;
    
    // Set custom variables into variables-override.less   
    $overridefile = dirname(__FILE__)."/less/variables-override.less";
    $override = '';
    foreach ($data['variables'] as $var => $value) {
        $override .= ("@" . $var . ": ".str_replace("\\", "", $value).";\n");
    }
    JFile::write($overridefile, $override);
    
    // Set responsive import declarations based on configuration    
    $configfile = dirname(__FILE__)."/less/responsive-config.less";
    $config = '';
    foreach ($data['responsive'] as $var => $value) {
        if ($value == 1)
           $config .= "@import \"".$var."\";\n";
    }
    JFile::write($configfile, $config);
    
    // Get extra components
    $extrafile = dirname(__FILE__)."/less/components-config.less";
    $extra = '';
    if (!empty($data['components']) && is_array($data['components'])) {
        foreach ($data['components'] as $value) {
            $extra .= "@import \"".$value."\";\n";
        }
        JFile::write($extrafile, $extra);
    }
    
    // Attempt to compile bootstrap
    try {
        $bootstrap =dirname(__FILE__)."/css-compiled/bootstrap.css";
        $responsive =dirname(__FILE__)."/css-compiled/responsive.css";
        if (JFile::exists($bootstrap))
            JFile::delete($bootstrap);
        if (JFile::exists($responsive))
            JFile::delete($responsive);
        
        $less->compileFile(dirname(__FILE__)."/less/bootstrap.less", $bootstrap);
        $less->compileFile(dirname(__FILE__)."/less/responsive.less", $responsive);

        echo json_encode(array('status' => 'ok', 'message' => 'Compile Successful!'));
    } catch (exception $e) {
        echo json_encode(array('status' => 'nok', 'message' => $e->getMessage()));
    }
?>
