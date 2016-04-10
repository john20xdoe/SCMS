<?php require("common/doctype-head.php"); ?>
	<?php require("common/nav-and-title.php");
    require("common/logcheck.php");?>

    <h2>Instructors</h2>

    <?php
    if ($_SESSION['msg']) { echo $_SESSION['msg'];}
    //then clear the message variable
    $_SESSION['msg'] ='';

    if (isset($_GET['instructorid'])){   //viewing a specific instructor
         $getinstructorid = $_GET['instructorid'];
       $db = dbconnect(1);
        if ($db == -1){
         printf("Connect failed: %s\n\n ", mysqli_connect_error());
          exit();
        }
     $query = "SELECT * FROM Instructors JOIN Department WHERE deptID = departmentID AND instructorID = ".$getinstructorid;
     if ($result = mysqli_query($db, $query)) {
       echo '<fieldset>';
        while ($row = mysqli_fetch_row($result)) {
           printf("<legend>Instructor Info</legend>");
           echo '<form id="editinst" action="save.php" method="post">';
           echo '<label for="editifname">First name: </label>&nbsp;&nbsp;&nbsp;<input name="editifname" id="editifname" type="text" maxlength="50" value="'.$row[2].'" /><br />';
           echo '<label for="editimname">Middle name: </label><input name="editimname" id="editimname" type="text" maxlength="40" value="'.$row[3].'" /><br />';
           echo '<label for="editilname">Last name: </label>&nbsp;&nbsp;&nbsp;<input name="editilname" id="editilname" type="text" maxlength="50" value="'.$row[1].'" /><br /><br />';
           echo '<label for="editemployeeid">Employee ID (part-timers don\'t have this): </label><input name="editemployeeid" id="editemployeeid" type="text" maxlength="20" size="10" value="'.$row[4].'" /><br />';
           echo '<label for="editdepartment">Department: </label>&nbsp;&nbsp;&nbsp;&nbsp;<select name="editdepartment" id="editdepartment">';
           $query2 = "SELECT * FROM Department";
           if ($result2 = mysqli_query($db, $query2)) {
             while ($row2 = mysqli_fetch_row($result2)) {
               printf("<option %s value='%d'>%s</option>",(($row[5] == $row2[0])?"selected=\"selected\"":""),$row2[0],$row2[1]);
             }
             mysqli_free_result($result2);
           }
           echo '</select><br />';
           echo '<label for="editcontracttype">Contract Type: </label><select name="editcontracttype" id="editcontracttype"><option '.(($row[6]== 'part-timer')? 'selected="selected"': "").' value="part-timer">Part-timer</option><option '.(($row[6] == 'regular') ? 'selected="selected"': "" ).' value="regular">Regular</option><br />';
           echo '<input type="hidden" name="instructorid" value="'.$getinstructorid.'" /><input type="hidden" name="what" value="editinst" /><input value="Update" name="update" id="update" type="submit" />';
           echo '</form>';
        }
        echo '</fieldset>';
        mysqli_free_result($result);
     }
    }
    else {
       //show all instructors
       //query the database for registered instructors
        $db = dbconnect(1);
        if ($db == -1){
         printf("Connect failed: %s\n\n ", mysqli_connect_error());
          exit();
        }
     $query = "SELECT * FROM Instructors ORDER BY lastName ASC, departmentID ASC";
        if ($result = mysqli_query($db, $query)) {
          echo "<fieldset>";
          echo "<legend>Instructor List</legend>";
          echo "<p>The following are instructors teaching subjects under the CCS curriculums.</p>";
           echo "<table>";
           echo "<tr><td><b>Name</b></td><td><b>Type</b></td><td><b>Department</b></td></tr>";
           /* fetch associative array */
           while ($row = mysqli_fetch_row($result)) {
             $query2 = "SELECT deptDesc FROM Department where deptID = ".$row[5];
             if ($result2 = mysqli_query($db, $query2)){
             while ($row2 = mysqli_fetch_row($result2)){
             printf("<tr><td><a href=\"instructors.php?instructorid=%d\"><b>%s</b>, %s %s</a></td><td>%s</td><td>%s</td></tr>", $row[0],$row[1],$row[2],$row[3],$row[6],$row2[0]);
             }
             mysqli_free_result($result2);
            }
           }
        // free result set
        mysqli_free_result($result);
        echo "</table>";
        }
       echo "</fieldset>";

         echo '<fieldset>';
         echo '<legend>+ Add an Instructor</legend>';
         echo '<p>You can add a new instructor here. Fields with an asterisk (*) are required.</p><form method="post" action="save.php">';
         echo '<label for="ifname">First name:* </label><input type="text" maxlength="50" name="ifname" id="ifname" value="" />';
         echo '&nbsp;&nbsp;&nbsp;&nbsp;<label for="imname">Middle name: </label><input type="text" maxlength="40" name="imname" id="imname" value="" />';
         echo '<br /><label for="ilname">Last name:* </label><input type="text" maxlength="50" name="ilname" id="ilname" value="" />';
         echo '<br /><label for="employeeid">Employee ID: </label><input type="text" name="employeeid" maxlength="20"  id="employeeid" value="" />';
         echo '&nbsp;&nbsp;&nbsp;&nbsp;<label for="contracttype">Type:* </label><select id="contracttype" name="contracttype">';
         echo '<option value="regular">Regular</option><option value="part-timer">Part-timer</option></select>';
         echo '<br /><label for="department">Under department:* </label><select id="department" name="department">*';
         $query2 = "SELECT deptID,deptDesc FROM Department";
             if ($result2 = mysqli_query($db, $query2)){
              while ($row2 = mysqli_fetch_row($result2)){
              printf("<option value=\"%d\">%s</option>",$row2[0],$row2[1]);
              } }
             mysqli_free_result($result2);
         echo '</select>';
         echo '<input type="hidden" id="what" name="what" value="inst" />';
         echo '<input type="submit" value="Save instructor" /></form></fieldset>';
       dbconnect(0);
     }
    ?>

<?php include_once("common/footer.php"); ?>

