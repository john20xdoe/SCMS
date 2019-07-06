﻿    <?php
@include 'lib/config.php';

// If we were asked to make changes, then do so:
if (count($_POST)) {
    // Just update/add the value to the 'data' array
    $dbsettings = array(
      "scmsDbHost" => $_POST["dbhost"],
      "scmsDbUser" => $_POST["dbusername"],
      "scmsDbPassword" => $_POST["dbpassword"],
      "scmsDb" => $_POST["db"],
      );


    // Now save it to disk, create the 'full' file wrapping it in valid PHP
    $file = "<?php \n/*Please do NOT EDIT this file unless you know what you are doing!*/\n \n\$dbsettings = " . var_export($dbsettings, true) . ";\n?>\n";
    file_put_contents('lib/config.php', $file, LOCK_EX);
}

$mysqli = new mysqli($dbsettings['scmsDbHost'], $dbsettings['scmsDbUser'], $dbsettings['scmsDbPassword'], $dbsettings['scmsDB']);
 if (!mysqli_connect_errno()) {
    printf("<p class=\"calloutinfo\">Connected <br/>Host information: %s\n</p>", mysqli_get_host_info($mysqli));
    echo "<button onclick=\"function(){\$('.popup').fadeOut().hide();}\">OK</button><br /><br />";
	mysqli_close($mysqli);
 } else {
    printf("<p class=\"calloutwarn\">Cannot connect to server. Something might be wrong with your MySQL login info.</p>");
    echo '<br /><button onclick="window.location = window.location.href;">Try again.</button><br /><br />';
    }
?>