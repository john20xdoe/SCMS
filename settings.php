<?php include_once("common/doctype-and-head.php"); ?>

	<?php include_once("common/nav-and-title.php"); ?>

	<h2>Settings (Admin)</h2>

<?php
// Include our file:
@include 'lib/config.php';

// If we were asked to make changes, then do so:
if (count($_POST)) {
    // Just update/add the value to the 'data' array
    $dbsettings = array(
            "scmsDbHost" => $_POST["dbhost"],
            "scmsDbUser" => $_POST["dbusername"],
            "scmsDbPassword" => $_POST["dbpassword"],
            "scmsDb" => $_POST["db"]
            );


    // Now save it to disk, create the 'full' file wrapping it in valid PHP
    $file = "<?php\n\$dbsettings = " . var_export($dbsettings, true) . ";\n?>\n";
    file_put_contents('lib/config.php', $file, LOCK_EX);
}

// Echo out the current data diagnostic)
echo "<pre>Current Data is:\n\n";
print_r($dbsettings);
echo "</pre>\n";
//end diagnostic echo
?>

<h4>Database Settings</h4>
<?php
$mysqli = new mysqli($dbsettings['scmsDbHost'], $dbsettings['scmsDbUser'], $dbsettings['scmsDbPassword']);

 if (!mysqli_connect_errno()) {
    printf("<p class=\"calloutinfo\">Connected to server! <br/>Host information: %s\n</p>", mysqli_get_host_info($mysqli));
	mysqli_close($mysqli);
 } else {
    printf("<p class=\"calloutwarn\">Cannot connect to server: %s\n<br/><br/>Check your MySQL server.<br/>Check your MySQL connection settings.</p>  ", mysqli_connect_error());
    }
echo '
<form action="settings.php" method="POST">
<p><strong>MySQL hostname:</strong><br/>
<input type="text" name="dbhost" value="'.$dbsettings['scmsDbHost'].'"></p>
<p><strong>MySQL username:</strong><br/>
<input type="text" name="dbusername" value="'.$dbsettings['scmsDbUser'].'"></input></p>
<p><strong>MySQL password</strong><br/>
<input type="password" name="dbpassword" value="'.$dbsettings['scmsDbPassword'].'"></input></p>
<p><input type="submit" value="Save"/></p>
</form>
'
?>



<?php include_once("common/footer.php"); ?>