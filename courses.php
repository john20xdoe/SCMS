<?php require("common/doctype-head.php"); ?>
	<?php require("common/nav-and-title.php");
    require("common/logcheck.php");?>

    <h2>Courses</h2>

    <?php
    if ($_SESSION['msg']) {
       echo $_SESSION['msg'];
    }
    //then clear the message variable
    $_SESSION['msg'] ='';

    if (isset($_GET['courseid'])){
      $getcourseid = $_GET['courseid'];

      //if course data is changed, then update database
      if ((isset($_POST['editcourseInit'])) && (isset($_POST['editcourseDesc']))){
        $db = dbconnect(1);
        if ($db == -1){
         printf("Connect failed: %s\n\n ", mysqli_connect_error());
          exit();
        }
        $query = "UPDATE Course SET courseInitials = '".$_POST['editcourseInit']."', courseName = '".$_POST['editcourseName']."', courseDesc = '".$_POST['editcourseDesc']."' WHERE courseID = ".$getcourseid;
        mysqli_query($db, $query) or die(mysqli_error($db));
        $_SESSION['msg'] = $info1."The course has been updated sucessfully.".$info2;
        dbconnect(0);
      }

       $db = dbconnect(1);
        if ($db == -1){
         printf("Connect failed: %s\n\n ", mysqli_connect_error());
          exit();
        }
        $query = "SELECT * FROM Course WHERE courseID = ".$getcourseid;
        if ($result = mysqli_query($db, $query)) {
           /* fetch associative array */
        while ($row = mysqli_fetch_row($result)) {
          if  ($getcourseid == $row[0])  {
            $getcourseInit = $row[1];
            $getcourserName = $row[2];
             echo "<fieldset>";
             echo "<legend>".$row[1]."</legend>";
             echo "<b>".$row[2]."</b><br /><br />";
             if ($_SESSION['msg']) { echo $_SESSION['msg'];}
                //then clear the message variable
             $_SESSION['msg'] ='';
             echo "<p>Updating a course's name is only recommended if the College actually has that course's name changed, but still offers the same curriculum(s) under that course. If you are intending to save an all-new course by editing an old one, this will result in anomalies in your curriculum, subject, and student records, and you are advised to not continue.</p>";
             echo 'Forget it, take me back to <a href="courses.php">Courses page</a>.<br /><br />';
             echo '<form id="editcourse" action="courses.php?courseid='.$getcourseid.'" method="post">';
             echo '<label for="editcourseInit">&bull; What are the new initials of the course? * </label><input type="text" maxlength="15" id="editcourseInit" name="editcourseInit" value="'.$row[1].'" />';
             echo '<br /><label for="editcourseName">&bull; What is the course\'s new name? *&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </label><input type="text" maxlength="45" id="editcourseName" name="editcourseName" value="'.$row[2].'" />';
             echo '<br /><label for="editcourseDesc">&bull; A brief description about this course: *</label>&nbsp;<textarea name="editcourseDesc" id="editcourseDesc" rows="5">'.$row[3].'</textarea>';
             echo '<br /><br /><sub>*this field is required</sub>';
             echo '<input type="submit" id="savesubmit" name="savesubmit" value="Save edits" />';
             echo '</form>';
             echo '<br /><span><a href="delete.php?what=course&courseid='.$getcourseid.'">Delete</a> this course.</span>';
             echo "</fieldset>";
             }
          }
                // free result set
                mysqli_free_result($result);
                $row = array();
                dbconnect(0); //disconnect from database
        }


        //populate the curriculums for this course
         $db = dbconnect(1);
         if ($db == -1){
         printf("Connect failed: %s\n\n ", mysqli_connect_error());
          exit();
         }
        $query = "SELECT * FROM Curriculum WHERE course = ".$getcourseid;
         if ($result = mysqli_query($db, $query)) {
           echo "<fieldset>";
           echo "<legend>Manage curriculums under ".$getcourseInit."</legend>";
           echo "<p>The major <b>".$getcourseName."</b> currently offers the following curriculums.</p>";
            while ($row = mysqli_fetch_row($result)){
                $currstatus = $row[4] ? 'already effective' : 'not ready, still unfinished';
                printf("&nbsp;&nbsp; &bull; <a href=\"curriculums.php?courseid=%d&currid=%d\">Curriculum Effective <b>%s Semester</b> of <b>A.Y. %d</b></a> (%s)<br />",$getcourseid,$row[0],conv2text($row[2]),$row[1],$currstatus);
            }
           echo "<br />&nbsp;&nbsp;&nbsp;<a href=\"curriculums.php\">+ Add a new curriculum</a></fieldset>";
         }
        // free result set
                mysqli_free_result($result);
                dbconnect(0); //disconnect from database

     }

    else {
       //show all courses
       //query the database for registered courses then show them
        $db = dbconnect(1);
        if ($db == -1){
         printf("Connect failed: %s\n\n ", mysqli_connect_error());
          exit();
        }
        $query = "SELECT * FROM Course";
        if ($result = mysqli_query($db, $query)) {
           /* fetch associative array */
        echo "<fieldset>";
        echo "<legend>Current courses Offered</legend>";
        echo "<p>The College of Computer Studies currently offers the following courses. To add a new course, use the form below.</p>";
        echo '<p>Clicking a course name lets you edit its details and manage the curriculums under that course.</p>';

        while ($row = mysqli_fetch_row($result)) {
          //printf ("%s (%s)\n", $row[0], $row[1]);
          printf("<dl class=\"coursedetails\"><dt><b><a href='courses.php?courseid=%s'>%s</a></b></dt><dd> - (<b>%s</b>)<br />%s</dd></dl>",$row[0],$row[1],$row[2],$row[3]);
          }
        }
        // free result set
                mysqli_free_result($result);
                dbconnect(0); //disconnect from database
         echo "</fieldset>";
         echo '<br />';
         echo "<fieldset>";
         echo "<br /><legend>+ Add a Course</legend>";
         echo "<p>You can add a new program course by filling up the following form.</p>";
         echo '<form action="save.php" method="post">';
         echo '<label for="newcourseInit">&bull; What are the initials of the new course (e.g. BSCS)? * </label><input type="text" maxlength="30" size="7" id="newcourseInit" name="newcourseInit" value="" />';
         echo '<br/><label for="newcourseName">&bull; What do the initials stand for? * </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" maxlength="60" size="45" id="newcourseName" name="newcourseName" value="" />';
         echo '<br /><label for="newcourseDesc">&bull; A brief description about this course: *</label>&nbsp;<textarea name="newcourseDesc" id="newcourseDesc" rows="2"></textarea>';
         echo '<input type="hidden" id="what" name="what" value="course" />';
         echo '<br/><br /><input type="submit" id="submit" name="submit" value="Save new major" /><input type="reset" value="Clear this form" /><br />';
         echo '<sup>*this field is required</sup>';
         echo '</form>';
         echo "</fieldset>";
     }


    ?>

<?php include_once("common/footer.php"); ?>

