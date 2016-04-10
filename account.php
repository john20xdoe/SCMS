<?php require("common/doctype-head.php"); ?>
	<?php require("common/nav-and-title.php");
    require("common/logcheck.php");?>

<h2>Your Info</h2>
<?php if ($_SESSION['msg']) { echo $_SESSION['msg'];}
    //then clear the message variable
    $_SESSION['msg'] ='';

//populate users info into variables
    $db = dbconnect(1);
     if ($db == -1){
           printf("Connect failed: %s\n\n", mysqli_connect_error());
           exit();
     }
     $query = "SELECT * FROM ScmsAdmin";
        if ($result = mysqli_query($db, $query)) {
           /* fetch associative array */
           while ($row = mysqli_fetch_row($result)) {
             if ($row[0] == $_SESSION['user']){
               $fname = $row[2];
               $mname = ($row[3] == 'NULL') ? '' : $row[3];
               $lname = $row[4];
             }
            }
           mysqli_free_result($result);
            dbconnect(0);
        } else $_SESSION['msg'] = $warn1.'Your MySQL server is not responding.'.$warn2;
?>
<fieldset>
<legend><?php echo $_SESSION['user']; ?></legend>
<form action="save.php" method="post">
<span>You are a
<?php
//populate usertype based on session
    $db = dbconnect(1);
     if ($db == -1){
           printf("Connect failed: %s\n\n", mysqli_connect_error());
           exit();
     }
     $usertypeno = $_SESSION['usertype'];
        $query = "SELECT * FROM UserType WHERE userTypeID = ".$usertypeno;
        if ($result = mysqli_query($db, $query)) {
           /* fetch associative array */
           while ($row = mysqli_fetch_row($result)) {
            printf("<b>%s</b>.",$row[1]);
            }
           mysqli_free_result($result);
            dbconnect(0);
        } else $_SESSION['msg'] = $warn1.'Your MySQL server is not responding.'.$warn2;
?>
</span>
<br /><br /><b>Personal Information</b><br />
<span><label for="fname">Your first name:&nbsp;&nbsp;&nbsp;&nbsp;</label>
<input type="text" id="fname" name="fname" value="<?php echo $fname; ?>" /></span><br />
<span><label for="mname">Your middle name:</label>
<input type="text" id="mname" name="mname" value="<?php echo $mname; ?>" /></span><br />
<span><label for="lname">Your last name:&nbsp;&nbsp;&nbsp;&nbsp;</label>
<input type="text" id="lname" name="lname" value="<?php echo $lname; ?>" /></span><br />
<br /><b>Change Password</b> (leave blank if you do not want to change this)<br />
<span><label for="editpass">New Password: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label><input type="password" id="editpass" name="editpass" value="" /></span><br />
<span><label for="editpasscfm">Confirm Password: </label><input type="password" id="editpasscfm" name="editpasscfm" value="" /></span>
<br /><input type="submit" value="Save changes" />
<input type="hidden" name="what" id="what" value="admin" />
<input type="hidden" name="action" id="action" value="edit" />
</form>
<br /><span><a href="deleteaccount.php">Deactivate</a> my account.</span>
</fieldset>

 <?php include_once("common/footer.php"); ?>