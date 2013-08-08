<?php 
include_once("core/includes/lessc.inc.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<title>DZ LESS Compiler</title>
</head>
<body>
<?php
	// DEBUG
	echo '<pre>';
	if (isset($_GET['compile']))
		$method = $_GET['compile'];
	else if (isset($_POST['compile']))
		$method = $_POST['compile'];

	if (empty($method) || ( count($method['variables']) != 8 && count($method['imports']) != 3))
	{
		echo "Invalid Request!";
		return;
	}
	
	$less = new lessc;
	
	// Set custom variables into variables-override.less
	$file = fopen(dirname(__FILE__)."/less/variables-override.less", "w");
	foreach ($method['variables'] as $var => $value)
	{
		fputs($file, "@".$var.":\t\t".str_replace("\\", "", $value).";\n");
	}
	fclose($file);

	// Set responsive import declarations based on configuration
	$file = fopen(dirname(__FILE__)."/less/responsive-config.less", "w");
	foreach ($method['imports'] as $var => $value)
	{
		if ($value == 1)
			fputs($file, "@import \"".$var."\";\n");
	}
	fclose($file);
	
	try {
		$bootstrap =dirname(__FILE__)."/css-compiled/bootstrap.css";
		$responsive =dirname(__FILE__)."/css-compiled/responsive.css";
		if (file_exists($bootstrap))
			unlink($bootstrap);
		if (file_exists($responsive))
			unlink($responsive);
		
		$less->compileFile(dirname(__FILE__)."/less/bootstrap.less", $bootstrap);
		$less->compileFile(dirname(__FILE__)."/less/responsive.less", $responsive);

		echo "Compile Successful!";
	} catch (exception $e) {
		echo "Fatal Error: ".$e->getMessage();
	}
	echo '</pre>';
?>
</body>
</html>
