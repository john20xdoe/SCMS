<?php
//error_reporting(E_ALL);
// Date in the past
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

// always modified
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");

// HTTP/1.1
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);

// HTTP/1.0
header("Pragma: no-cache");
require("login.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="author" content="Lee Alexis Bermejo" />
    <meta name="description" content="This is the Student Curriculum Management System for the College of Computer Studies." />
    <meta name="robots" content="no-follow" />
    <?php
 $pieces1 = explode("/", $_SERVER['PHP_SELF']);
        $last = sizeof($pieces1);
        $thisUrl = $pieces1[$last-1]; //get filename

        $pieces2 = explode(".",$thisUrl);
        $thistitle = $pieces2[0]; //get title
?>
	<title>SCMS - <?php echo $thistitle; ?></title>

	<link rel="stylesheet" type="text/css" href="css/style.css" />
    <link rel="shortcut icon" href="images/favicon.ico" type="image/ico"  />
    <noscript>
    <style type="text/css">
    /*<![CDATA[*/
    #inside-content { display:block; }
    #ajax-loader { display:none; }
    /*]]>*/
    </style>
    </noscript>
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/js.js"></script>
    <script type="text/javascript" src="js/printliminator.js"></script>
