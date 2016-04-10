<?php
// Date in the past
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

// always modified
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");

// HTTP/1.1
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);

// HTTP/1.0
header("Pragma: no-cache");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="author" content="ccs 3 2009-2010 students" />
    <meta name="description" content="This is the Student Curriculum Management System for the College of Computer Studies." />
    <meta name="robots" content="no-follow" />

	<title>SCMS - <?php echo $title; ?></title>

	<link rel="stylesheet" type="text/css" href="css/reports.css" />
    <link rel="shortcut icon" href="images/favicon.ico" type="image/ico"  />
    <noscript>
    <style type="text/css">
    /*<![CDATA[*/

    /*]]>*/
    </style>
    </noscript>
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/reports_proc.js"></script>
    <script type="text/javascript" src="js/printliminator.js"></script>
</head>
<body>
<div id="wrap">
