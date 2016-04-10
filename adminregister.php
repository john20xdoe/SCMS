<?php require("common/doctype-head.php"); ?>
<script type="text/javascript" src="js/register_proc.js"></script>
	<?php require("common/nav-and-title.php");
     if (!(isset($_SESSION['valid']) && $_SESSION['valid'])) {
   ?>

<h2>Register</h2>

<fieldset><legend>Administrator</legend>
<div id="register">
<form name="frmregister" action=""><span class="hold"></span>
<p>Fields with an asterisk (*) are required.</p>
<table class="tblForm">
<tr><td><b>Account info</b></td><td></td></tr>
<tr>
<td><label for="newuser">Desired username: </label></td>
<td><input type="text" maxlength="20" id="newuser" name="newuser" value="<?php echo $_POST['newuser']; ?>" /> *
<label for="newuser" class="error" id="newuser_error">Field is required</label></td>
</tr>
<tr><td><label for="newpass">Password: (6-15 characters)</label></td><td><input maxlength="20" type="password" id="newpass" name="newpass" value="<?php echo $_POST['newpass']; ?>" /> *
<label for="newpass" class="error" id="newpass_error">Field is required</label></td>
</tr>
<tr><td><label for="newpasscfm">Confirm Password: &nbsp;</label></td><td><input maxlength="20" type="password" id="newpasscfm" name="newpasscfm" value="<?php echo $_POST['newpasscfm']; ?>" /> *
<label for="newpasscfm" class="error" id="newpasscfm_error">Password confirmation does not match</label></td>
</tr>

<tr><td><b>Personal info</b></td><td></td></tr>
<tr><td><label for="fname">Your first name:</label>&nbsp;&nbsp;</td>
<td><input type="text" id="fname" maxlength="40" name="fname" value="<?php echo $_POST['fname']; ?>" /> *
<label for="fname" class="error" id="fname_error">Field is required</label></td></tr>

<tr><td><label for="mname">Your middle name:</label> </td>
<td><input maxlength="30" type="text" id="mname" name="mname" value="<?php echo $_POST['mname']; ?>" />
<label for="mname" class="error" id="mname_error">Field is required</label></td>
</tr>

<tr><td><label for="lname">Your last name:</label>&nbsp;&nbsp;</td>
<td><input type="text" maxlength="40" id="lname" name="lname" value="<?php echo $_POST['lname']; ?>" /> *
<label for="lname" class="error" id="lname_error">Field is required</label></td>
</tr>

<tr><td><label for="usertype">You are a:</label></td><td>
<?php
//populate usertypes
    $db = dbconnect(1);
     if ($db == -1){
           printf("Connect failed: %s\n\n", mysqli_connect_error());
           exit();
     }
        $query = "SELECT userTypeID,userTypeDesc FROM UserType";
        if ($result = mysqli_query($db, $query)) {
           /* fetch associative array */
           echo "<select name=\"usertype\" id=\"usertype\">";
          while ($row = mysqli_fetch_row($result)) {
            printf("<option value=\"%d\">%s</option>",$row[0],$row[1]);
            }
            echo "</select>";
            mysqli_free_result($result);
            dbconnect(0);
        } else $_SESSION['msg'] = 'Your MySQL server is not responding.';


?>
 *<label for="usertype" class="error" id="usertype_error">Field is required</label></td>
</tr>
<tr><td colspan="2"><b>Terms of Use</b> (can be viewed <a href="terms.php">here</a>)</td></tr>
<tr><td colspan="2"><div style="padding:10px;border:1px solid;background:#fff;width:95%;height:200px;overflow:auto;">
<?php
$filename = "terms_2.php";
$text ='';
$fp = fopen($filename, "r") or die("Can't load the Terms of Use.");
 while (!feof($fp)) {
     $text .= fgets($fp, 1024);
 }
fclose($fp);
$text =  strip_tags($text,"<p><b><blockquote><br />");
echo $text;
?>

</div></td></tr>
<tr><td><input type="checkbox" name="agree" id="agree" <?php if ($_POST['agree'] == 'on') echo "checked='checked'"; ?>  /><label for="agree"> I agree to the terms above. *</label> <label for="agree" class="error" id="agree_error">You have to agree to the Terms to be registered.</label></td>
<td></td></tr>
<tr><td colspan="2"><br /><input type="submit" name="submit" class="sendbtn" id="submit_btn" value="OK, register me now" /><a href="index.php">Nah, I don't want an account</a></td></tr>
<input type="hidden" name="what" id="what" value="admin" />
</table>
</form>
</div>
</fieldset>

<?php } else {header("Location: index.php");} ?>
 <?php include_once("common/footer.php"); ?>