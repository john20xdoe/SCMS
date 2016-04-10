<?php require("common/doctype-head.php"); ?>
<script type="text/javascript" src="js/organictab.js"></script>
	<?php require("common/nav-and-title.php");
    ?>

    <h2>Search</h2>


<?php
    //search handler
    if ($_POST){
        $query = "SELECT studentID, firstName, lastName FROM Students WHERE isStillActive = ".(($_POST['isstillactive']) ? '1' :'0');
        if (isset($_POST['studentid'])){
          header('Location: students.php?studentid='.$_POST['studentid'].'&search=yes');
        }
        elseif ($_POST['fname']){
          $query .= " AND firstName LIKE '".$_POST['fname'][0]."%' ";
        }
        if ($_POST['lname']){
          $query .= " AND lastName LIKE '".$_POST['lname'][0]."%' ";
        }
        if ($_POST['course']){
          $query .= " OR course = ".$_POST['course'];
        }

        $db = dbconnect(1);
        if ($db == -1){
         printf("Connect failed: %s\n\n ", mysqli_connect_error());
          exit();
        }
        if ($result = mysqli_query($db, $query)) {
          echo '<p>Search results for <b>'.$_POST['fname'].' '.$_POST['lname'].'</b>.</p>';
          echo '<table><tr class="titlerow"><td><b>Student ID</b></td> <td><b>Name</b></td></tr>';
          while ($row = mysqli_fetch_row($result)){
            printf("<tr><td><a href=\"students.php?studentid=%s\">%s</a></td><td>%s, %s</td></tr>",$row[0],$row[0],$row[2],$row[1]);
          }
           mysqli_free_result($result);
          echo '</table>';
        }
        dbconnect(0);
    }
    else {
?>


         <fieldset id="organic-tabs">
            <legend>Search Options</legend>
    		<ul id="explore-nav">
                <li><a rel="page1" href="#" class="current">Student</a></li>
                <?php  if ((isset($_SESSION['valid']) && $_SESSION['valid'])) { ?>
                <li><a rel="page2" href="#">Instructor</a></li>
                <li><a rel="page3" href="#">Subject</a></li>
                <li><a rel="page4" href="#">Curriculum</a></li>
                <?php } ?>
            </ul>

    		<div id="all-div-wrap">

    			<div id="page1">
<b>Are you a student? Please enter your Student ID number.</b>
      <form action="search.php" method="post">
         <label for="studentid">Student ID code (e.g. "070001CS"): </label>
         <span class="searchwrap">&nbsp;<input type="text" size="12" maxlength="8" id="studentid" name="studentid" value="" />
         <input type="submit"  value="Search by ID" />
         </span>
      </form>
      <br />
      <br />
<b>Can't remember your ID?</b>
      <form action="search.php" method="post">
         <label for="fname">First name of student: </label>
         <input type="text" size="12" maxlength="40" id="fname" name="fname" value="" />
         <br /><label for="lname">Last name of student: </label>
         <input type="text" size="12" maxlength="40" id="lname" name="lname" value="" />
         <br /><label for="course">Course of student: </label>&nbsp;&nbsp;&nbsp;&nbsp;
        <?php
        $db = dbconnect(1);
        if ($db == -1){
         printf("Connect failed: %s\n\n ", mysqli_connect_error());
          exit();
        }
       $query = "SELECT courseID,courseName FROM Course";
        if ($result = mysqli_query($db, $query)) {
          echo '<select name="course" id="course"><option value="">Any course...</option>';
          while ($row = mysqli_fetch_row($result)){
            printf("<option value=\"%d\">%s</option>",$row[0],$row[1]);
          }
           echo '</select>';
          mysqli_free_result($result);
        }
        dbconnect(0);
         ?>
         <br />
         <label for="isstillactive">Is this student active?</label> <input type="checkbox" id="isstillactive" name="isstillactive" checked="checked" />
         <input type="submit" value="Search by name" />
      </form>
<div class="clr"></div>
    			</div>

                <?php  if ((isset($_SESSION['valid']) && $_SESSION['valid'])) { ?>
        		 <div id="page2">
                    <b>Please enter the instructor's employee ID number.</b>
                     <form action="search.php" method="post">
                    <label for="employeeid">Employee ID: </label>
                       <span class="searchwrap">&nbsp;<input type="text" size="12" maxlength="8" id="employeeid" name="employeeid" value="" />
                       <input type="submit"  value="Search by ID" />
                    </span>
                      </form>
                     <br /><br />
                    <b>Or search by name:</b>
                       <form action="search.php" method="post">
                     <label for="efname">First name of instructor: </label>
                    <input type="text" size="12" maxlength="40" id="efname" name="efname" value="" />
                     <br /><label for="elname">Last name of instructor: </label>
                     <input type="text" size="12" maxlength="40" id="elname" name="elname" value="" />
                     <input type="submit" value="Search by name" />
                        </form>
                     <div class="clr"></div>
        		 </div>

        		 <div id="page3">
                    <form action="search.php" method="post">
                    <label for="subjectcode">All or part of the subject code<br /> (e.g. "COSCI 101"):</label> <input size="7" type="text" name="subjectcode" id="subjectcode" maxlength="25" />
<input type="submit" value="Search by subject code" />
                    </form>
                    <br /><br />
                    <b>Or search by description:</b>   <br />
                    <form action="search.php" method="post">
                    <label for="desctitle">Descriptive Title:</label> <input type="text" maxlength="50" name="desctitle" id="desctitle" />
    <input type="submit" value="Search by description" />
                   </form>
                    <div class="clr"></div>
        		 </div>

        		 <div id="page4">

                   <div class="clr"></div>
        		 </div>

                <?php } ?>
    		 </div> <!-- END List Wrap -->

		 </fieldset> <!-- END Organic Tabs -->


<?php } //end else ?>
<?php include_once("common/footer.php"); ?>

