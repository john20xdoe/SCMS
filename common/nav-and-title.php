</head>
<body>

<div id="page-wrap">

    <div id="navbar-top">
        <div class="login">
        <?php echo $data; //login panel ?>
        </div>
           <?php

function create_highlighted_sections($secarray,$activsec) {
    // Loop over the entire array, making a basic menu:
    echo "<ul id='main-nav'>\n";
    foreach ($secarray as $section => $pageurl) {
        // Echo out a link for this section, highlighting if currently in it
        $class = '';

        if (strncmp($section, $activsec, strlen($activsec)) == 0) {
            $class = ' class="here"';
            echo "<li{$class}><a href=\"{$pageurl}\">{$section}</a></li>\n";}
	    else {
		echo "<li><a href=\"{$pageurl}\">{$section}</a></li>\n";
		}
    }
    echo "</ul>\n";
}

$pieces = explode("/", $_SERVER['PHP_SELF']);
        $last = sizeof($pieces);
        $thisUrl = $pieces[$last-1]; //get filename

if (isset($_SESSION['valid']) && $_SESSION['valid']){
// An array with all section names and the filename
$topmenu = array(
    'Welcome' => 'index.php',
    'Records' => 'records.php',
    'Reports' => 'reports.php',
    'Account' => 'account.php',
    'Help' => 'help.php'
    );
}
else{
$topmenu = array(
    'Welcome' => 'index.php',
    'Register' => 'adminregister.php',
    'Help' => 'help.php'
    );
}

//find url, active section and corresponding sidemenu

    if (($thisUrl == "records.php") || ($thisUrl == "courses.php")|| ($thisUrl == "curriculums.php") || ($thisUrl == "subjects.php") || ($thisUrl == "instructors.php") || ($thisUrl == "students.php")){
       $sidemenu = array(
       'Courses' => 'courses.php',
       'Curriculums' => 'curriculums.php',
       'Subjects'=> 'subjects.php',
       'Instructors' => 'instructors.php',
       'Students' => 'students.php');
       $activsection='Records';
       }

    elseif ($thisUrl == "reports.php" || ($thisUrl == "templates.php")){
       $sidemenu = array(
       'Reports' => 'reports.php',
       'Templates' => 'templates.php'
       );
       $activsection='Reports';
       }
    elseif (($thisUrl == "account.php") || ($thisUrl == "deleteaccount.php") || ($thisUrl == "database.php")){
       $sidemenu = array (
       'Your Info' => 'account.php',
       'Database' => 'database.php'
       );
       $activsection='Account';
       }
    elseif (($thisUrl == "help.php") || ($thisUrl == "terms.php") || ($thisUrl == "documentation.php") || ($thisUrl == "about.php") ){
       $sidemenu = array (
       'Documentation' => 'documentation.php',
       'Terms of Use' => 'terms.php',
       'About' => 'about.php'
       );
       $activsection='Help';
       }
    elseif ($thisUrl == "adminregister.php"){
             $sidemenu = array(
       'Welcome' => 'index.php',
       'Register' => 'adminregister.php',
       'Search' => 'search.php');
       $activsection='Register';
    }
    else //    default side menu if ($thisUrl == "index.php")
       {
       $sidemenu = array(
       'Welcome' => 'index.php',
       'Search' => 'search.php');
       $activsection='Welcome';
       }

// And call the function
create_highlighted_sections($topmenu,$activsection);
?>
<br /><br />

    </div>

    <div id="navbar-left">
        <div id="logo"><a href="index.php">
        <span class="title">SCMS</span>
        <span class="description">Student Curriculum <br />Management System</span>
        </a>
        </div>
<?php
function create_sidemenu($secarray,$url) {
    // Loop over the entire array, making a basic menu:
    echo "<ul id='side-nav'>\n";
    foreach ($secarray as $section => $pageurl) {
        // Echo out a link for this section, highlighting if currently in it
        $class = '';
        if ($pageurl == $url){
            $class = ' class="active"';
            echo "<li{$class}><a href=\"{$pageurl}\"><img src=\"images/ajax-loader2.gif\" id=\"ajax-loader\" /> {$section}</a></li>\n";}
	    else {
		echo "<li><a href=\"{$pageurl}\">{$section}</a></li>\n";
		}
    }
    echo "</ul>\n";
}

create_sidemenu($sidemenu,$thisUrl);
?>
<br /><br />

    <div id="fixedbox">
    <fieldset>
    <legend>Page options</legend>
      <a href="javascript:(function(){function%20loadScript(a,b){var%20c=document.createElement('script');c.type='text/javascript';c.src=a;var%20d=document.getElementsByTagName('head')[0],done=false;c.onload=c.onreadystatechange=function(){if(!done&amp;&amp;(!this.readyState||this.readyState=='loaded'||this.readyState=='complete')){done=true;b()}};d.appendChild(c)}loadScript('js/jquery.js',function(){loadScript('js/printliminator.js',function(){printlimator()})})})()" class="bookmarklet" title="Print this page!"><img src="images/prtreport.png" width="20" height="20" title="Print this page!" alt="Printliminator" /></a>
      <a href="#" title="Back to Top"><img src="images/totop.png" width="20" height="20" alt="Top" title="Back to top of page" /></a>
    </fieldset>
    </div>

</div>
    <div id="main-content">
    <div id="inside-content">