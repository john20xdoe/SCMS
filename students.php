<?php require("common/doctype-head.php"); ?>
<script language="JavaScript" type="text/javascript" src="js/organictab.js"></script>
<script language="JavaScript" type="text/javascript" src="js/student_proc.js"></script>
	<?php require("common/nav-and-title.php");
    require("common/logcheck.php");?>

    <h2>Students</h2>

    <?php
    if ($_SESSION['msg']) { echo $_SESSION['msg'];}
    //then clear the message variable
    $_SESSION['msg'] ='';

    if (isset($_GET['studentid'])){
       $getstudentid = $_GET['studentid'];
       $db = dbconnect(1);
        if ($db == -1){
         printf("Connect failed: %s\n\n ", mysqli_connect_error());
          exit();
        }
        $query = "SELECT COUNT(studentID) FROM Students WHERE studentid='".$getstudentid."'";
        if ($result = mysqli_query($db, $query)) {
          while ($row = mysqli_fetch_row($result)) {
            $hasrecord = $row[0];
          }
        }

        echo '<fieldset id="organic-tabs"><legend>I.D. Number '.$getstudentid.'</legend>';
        if (!($hasrecord)){
            echo $info1.'No records found. Student with ID number <b>'.$getstudentid.'</b> might be unregistered.';
            if (isset($_GET['search'])){ echo '<br /><a href="search.php">Search another. </a> Or,';}
            echo ' <a href="students.php">Add as a new student</a>.'.$info2;
            exit();
        }

        $query = "SELECT * FROM Students JOIN Course WHERE Students.studentID = '".$getstudentid. "' AND course = courseID";
        if ($result = mysqli_query($db, $query)) {
           /* fetch associative array */
        while ($row = mysqli_fetch_row($result)) {
           $currid = $row[6];
        ?>
    		<ul id="explore-nav">
                <li><a rel="page1" href="#">Basic</a></li>
                <li><a rel="page2" href="#">Personal</a></li>
                <li><a rel="page3" href="#">Contact</a></li>
                <li><a rel="page4" href="#">Curriculum</a></li>
            </ul>
        <?php
    	echo '<div id="all-div-wrap">';

//basic information of student
    		echo '<div id="page1"><form id="tab1"><p>This is the student\'s basic information. This represents the student in SCMS.</p>';
               printf("<table class=\"tblForm\"><tr><td><img src=\"images/noimg.jpg\" class=\"aboutimg\" alt=\"Profile Pic\" style=\"clear:none;\" /></td>");
               printf("<td><table class=\"tblForm\"><tr><td><b>Student ID number:</b></td> <td>%s</td></tr>", $row[0]);
               printf("<tr><td><b>Name:</b></td> <td>%s %s %s</td></tr>",$row[2],$row[3],$row[1]);
               printf("<tr><td><b>Taken:</b></td> <td><a href=\"courses.php?courseid=%d\">%s </a>(%s) - %s Year</td></tr>",$row[22],$row[24],$row[23],conv2text($row[8]));
               printf ('<tr><td><label for="enrolmentclassif"><b>Enrolment Classification: </b></label></td> <td><select id="enrolmentclassif" name="enrolmentclassif"><option %s value="regular">regular</option><option %s value="irregular">irregular</option><option %s value="transferee">transferee</option></select></td></tr>',$row[4]=="regular"?"selected='selected'":"",$row[4]=="irregular"?"selected='selected'":"",$row[4]=="transferee"?"selected='selected'":"");
               printf("<tr><td><b><label for=\"isstillactive\">Active:</label></b></td> <td><select name=\"isstillactive\" id=\"isstillactive\"><option %s value=\"1\">Yes, taking classes</option><option %s value=\"0\">No, stopped</option></select></td></tr></td>",$row[21] == 1?"selected='selected'":"",$row[21] == 1?"":"selected='selected'");
               echo '<tr><td><a onclick="return confirm(\'Are you sure you want to delete all of '.$row['2'].' '.$row[1].'`s records?\n All information will be lost. This cannot be undone!\');" href="delete.php?what=stud&studentid='.$_GET['studentid'].'">Delete record.</a></td><td><input name="submitbtn" class="saveinfo" type="submit" value="Save Edits" /></td></tr></table>';
    		echo '</td></tr></table><div class="clr"></div></form></div>';

//personal information of student
        	echo '<div id="page2"><form id="tab2" action=""><p>This is the student\'s personal information. This serves as a background profile.</p>';
               printf("<table class=\"tblForm\"><tr><td><label for='gender'><b>Gender:</b></label></td><td> <select id='gender' name='gender'><option %svalue='m'>Male</option><option %svalue='f'>Female</option></select></td></tr>", ($row[9] == 'm') ? 'selected="selected" ':'',($row[9] == 'f') ? 'selected="selected" ':'');
               printf("<tr><td><label for='civilstatus'><b>Civil Status:</b></label></td> <td><select id='civilstatus' name='civilstatus'><option %svalue='Single'>Single</option><option %svalue='Married'>Married</option><option %svalue='Divorced'>Divorced</option><option %svalue='Widowed'>Widowed</option></select></td></tr>", (strtolower($row[10]) == 'single') ? 'selected="selected" ':'',(strtolower($row[10]) == 'married') ? 'selected="selected" ':'',(strtolower($row[10]) == 'divorced') ? 'selected="selected" ':'',(strtolower($row[10]) == 'widowed') ? 'selected="selected" ':'');
               printf("<tr><td><label for='religion'><b>Religion:</b></label></td><td> <input maxlength='60' type='text' size='%d' maxlength='60' name='religion' id='religion' value='%s' /></td></tr>",strlen($row[11]),$row[11]);
               printf("<tr><td><label for='nat'><b>Nationality:</b></label></td> <td><input maxlength='25' type='text' size='%d' name='nat' id='nat' value='%s' /></td></tr>",strlen($row[12]),$row[12]);
               printf("<tr><td><label for='pOB'><b>Place of Birth:</b></label></td> <td><input maxlength='80' type='text' size='%d' name='pOB' id='pOB' value='%s' /></td></tr>",strlen($row[13]),$row[13]);
               printf("<tr><td><label for='dOB'><b>Date of Birth: </b></label></td>");

               $dobtemp = explode("-",$row[14]);
               $dobyear = $dobtemp[0];
               $dobmon = $dobtemp[1];
               $dobday = $dobtemp[2];
       $nowArray = getdate();
       $day = 31;
       $mon = 12;
       $mons = array('January','February','March','April','May','June','July','August','September','October','November','December');
       $year = $nowArray["year"] - 10;
       $ctr = 1982;
       //build year
       echo '<td><select name="year" id="year">';
        while ($year >= $ctr){
          echo '<option '.(($year == $dobyear) ? 'selected="selected"' : '' ).' value="'.$year.'">'.$year.'</option>';
          $year -= 1;
        }
       echo '</select>';

       $ctr = 1;
       echo '&nbsp;<select name="month" id="month">';
        while ($ctr <= $mon){
          echo '<option '.(($ctr == $dobmon) ? 'selected="selected"' : '' ).' value="'.$ctr.'">'.$mons[$ctr-1].'</option>';
          $ctr += 1;
        }
       echo '</select>';

       $ctr = 1; //build days
       echo '&nbsp;<select name="day" id="day">';
        while ($day >= $ctr){
          echo '<option '.(($ctr == $dobday) ? 'selected="selected"' : '' ).' value="'.$ctr.'">'.$ctr.'</option>';
          $ctr += 1;
        }
       echo '</select></td></tr>';
       echo '<tr><td><label for="highschool"><b>High school graduated from:</b></label> </td><td><input type="text" maxlength="60" name="highschool" id="highschool" value="'.$row[17].'" /></td></tr>';
       echo '<tr><td><label for="highschoolgpa"><b>High school GPA:</b><br />(Grade-Point Average)</label></td> <td><input maxlength="5" type="text" name="highschoolgpa" id="highschoolgpa" value="'.$row[18].'" /></td></tr>';
       echo '<tr><td><label for="scholarship"><b>Scholarship:</b><br />(if any)</label></td> <td><input type="text" maxlength="60" name="scholarship" id="scholarship" value="'.$row[5].'"  /></td></tr>';
       echo '<tr><td colspan="2"><input type="hidden" class="studentid" name="studentid" value="'.$getstudentid.'" /><input name="submitbtn" class="saveinfo" type="submit" value="Save edits" /></td></tr>';
       echo '</table>';
          echo '<div class="clr"></div></form></div>';

//contact information of student
     	echo '<div id="page3"><form id="tab3"  action=""><p>This contains the student\'s contact information. </p>';
               $query2 = "SELECT * FROM StudentsWithSpouse WHERE studentID = '".$getstudentid."'";
               if ($result2 = mysqli_query($db, $query2)) {
                   /* fetch associative array */
                while ($row2 = mysqli_fetch_row($result2)) {
                   $spname = $row2[1];
                   $sprel = $row2[2];
                   $spcontact = $row2[3];
                }
               mysqli_free_result($result2);
               }
                printf("<table class=\"tblForm\">");
                echo '<tr><td><label for="address"><b>Student\'s address:</b> </label></td><td><textarea name="address" id="address">'.$row[15].'</textarea></td></tr>';
                echo '<tr><td><label for="contactno"><b>Student\'s contact number:</b> </label></td> <td><input maxlength="40" id="contactno" name="contactno" type="text" value="'.$row[16].'" /></td></tr>';
                echo '<tr><td><label for="parentguardian"><b>Name of parent/guardian:</b> </label></td> <td><input maxlength="70" id="parentguardian" name="parentguardian" type="text" value="'.$row[19].'" /></td></tr>';
                echo '<tr><td><label for="emergency"><b>Emergency contact number:</b> </label></td> <td><input maxlength="40" id="emergency" name="emergency" type="text" value="'.$row[20].'" /></td></tr>';
                printf("<tr><td><label for=\"spouse\"><b>Name of spouse:</b><br />(if any)</label></td><td> <input id=\"spouse\" name=\"spouse\"type=\"text\" maxlength=\"70\" value=\"%s\" /></td></tr>",$spname);
                echo '<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;-<label for="spouserel"><b>Religion of spouse:</b></label></td><td><input id="spouserel" name="spouserel" maxlength="60" type="text" value="'.$sprel.'" /></td></tr>';
                echo '<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;-<label for="spousecontact"><b>Spouse contact number:</b></label></td><td><input maxlength="40" id="spousecontact" name="spousecontact" type="text" value="'.$spcontact.'" /></td></tr>';
           echo '<tr><td colspan="2"><input class="studentid" type="hidden" name="studentid" value="'.$getstudentid.'" /><input name="submitbtn" class="saveinfo" type="submit" value="Save edits" /></td></tr>';
           echo '</table>';
           echo '<div class="clr"></div></form></div>';


//curriculum information of student
         echo '<div id="page4">';
            //get curriculum of student
               $query2 = "SELECT underCurriculum FROM Students WHERE studentID = '".$getstudentid."'";
               if ($result2 = mysqli_query($db, $query2)) {
                /* fetch associative array */
                while ($row2 = mysqli_fetch_row($result2)) {
                   $getcurrid = $row2[0];
                }
               mysqli_free_result($result2);
               }
            //get info of curriculum of student
               $query2 = "SELECT * FROM Curriculum WHERE currID = ".$getcurrid."";
               if ($result2 = mysqli_query($db, $query2)) {
                /* fetch associative array */
                while ($row2 = mysqli_fetch_row($result2)) {
                   $noOfYears = $row2[5];
                   printf("<p><span rel='%s' id='dstudentid'>%s %s</span> is taking the <b>%s</b> curriculum that was effective on the %s Semester of the Academic Year %d. </p>",$row[0],$row[2], $row[1], $row[23],conv2text($row2[2]),$row2[1]);
                }
               mysqli_free_result($result2);
               }

       echo '<p>The student\'s grades are saved below. Valid grades are saved automatically as you edit, while invalid grades are discarded.</p>';


/////////////////////////////continue here/////////////////////////////////////////////////////////////////////////////

    //populate grade table, student's curriculum checklist/appraisal form
    //first find the max number of sems for each year, using a for- loop
    echo "<dl>";

    for ($yearctr = 1;$yearctr <= $noOfYears;$yearctr++){
       //separate by year level, presented as a data list ( <dl> )
       echo "<dt><h4>".conv2text($yearctr)." Year</h4></dt>";
       echo "<dd>";
         //separate by semester, query no. of semesters of each year
         $query3 = "SELECT MAX(semNo) FROM SubjectByCurr WHERE currID = ".$getcurrid." AND yearLvl=".$yearctr;
         if ($result3 = mysqli_query($db, $query3)) {
                while ($row3 = mysqli_fetch_row($result3)) {
                   $noOfSems = $row3[0];
                }
                mysqli_free_result($result3);
         }
         for ($semctr = 1;$semctr <= $noOfSems;$semctr++) {
             echo "<table><thead>".(($semctr > 2) ? "Summer":conv2text($semctr))." Semester</thead>";
             echo '<tr class="titlerow"><td>Subject</td><td>Descriptive Title</td><td class="withlab">With <br />Lab</td><td class="units">Units</td><td>Prelim</td><td>Final</td></tr>';
             //query for subjects, desctitle, units, and grades, and present ed into a table
               //query for subjects
               $query3 = "SELECT Subjects.* FROM SubjectByCurr JOIN Subjects WHERE SubjectByCurr.currID =".$getcurrid. " AND SubjectByCurr.subjectCode = Subjects.subjectCode AND SubjectByCurr.semNo =".$semctr." AND SubjectByCurr.yearLvl = ".$yearctr;
               if ($result3 = mysqli_query($db, $query3)){
                 $subjctr = 1;
                 while ($row3 = mysqli_fetch_row($result3)){
                   echo '<tr><td>'.$row3[0].'</td><td>'.$row3[1].'</td><td class="withlab">'.($row3[3] ? 'Yes' :'No').'</td><td class="units">'.$row3[2].'</td>';

                   //query for corrresponding prelim and final grade
                       $query4 = "SELECT prelimGrade,finalGrade FROM SubjectsTakenByStudent WHERE subjectCode = '".$row3[0]."' AND studentID ='".$getstudentid."' AND semNo = ".$semctr;
                       if ($result4 = mysqli_query($db,$query4)) {
                         while ($row4 = mysqli_fetch_row($result4)){
                           $prelim = $row4[0];
                           $final = $row4[1];
                         }
                         mysqli_free_result($result4);
                       }
                       //echo grades
                       echo '<td class="prelim"><input type="text" maxlength="5" size="'.((sizeof($prelim)==0)? ('6') : sizeof($prelim)+2).'" class="gradeAutoSave prelimgrade" name="p'.$yearctr.$semctr.$subjctr.'" value="'.$prelim.'" /></td><td class="final"><input maxlength="5" size="'.((sizeof($final)==0)? ('6') : sizeof($final)+2).'" type="text" class="gradeAutoSave finalgrade" name="f'.$yearctr.$semctr.$subjctr.'" value="'.$final.'" /></td>';
                       $prelim = $final = '';  //reset values
                       echo '</tr>';
                   $subjctr++;
                 }
                 mysqli_free_result($result3);
               }
             echo "</table>";
         }
       echo "</dd>";
    }
    echo "</dl>";
////////////////////////////////////////stop here/////////////////////////////////////


         echo '<div class="clr"></div></div>';

    	echo '</div>';
        }
                // free result set
                mysqli_free_result($result);
                dbconnect(0); //disconnect from database
        }
        echo '</fieldset>';

     }
    elseif (isset($_GET['courseid'])){ ?>
 <script language="JavaScript" type="text/javascript">
 /*<![CDATA[*/
$(function() {
    if ($("#newstudentid").val()==''){
        $("#explore-nav li:gt(0) a").fadeOut();
          $("#registernew").attr({"disabled":"disabled"});
    }
    else {
        $("#explore-nav li a").fadeIn();
        $("#registernew").removeAttr("disabled");
        $("input[name=studentid]").val($("#newstudentid").val());
    }

    $("#newstudentid").live("change",function(){
       if ($("#newstudentid").val().length < 8){
        $("#explore-nav li:gt(0) a").fadeOut();
          $("#registernew").attr({"disabled":"disabled"});
       }
       else {
        $("#explore-nav li a").fadeIn();
        $("#registernew").removeAttr("disabled");
         $("input[name=studentid]").val($("#newstudentid").val());
       }
    });

});

 /*]]>*/
 </script>

    <?php
        $getcourseid = $_GET['courseid'];
        //group by courses, showing their year levels
        $db = dbconnect(1);
        if ($db == -1){
         printf("Connect failed: %s\n\n ", mysqli_connect_error());
          exit();
        }


        //build the title
        $query = "SELECT courseInitials,courseName FROM Course where courseID = ".$getcourseid;
            if ($result = mysqli_query($db, $query)){
              /* fetch associative array */
            while ($row = mysqli_fetch_row($result)) {
              $courseInit = $row[0];
              $courseName =$row[1];
              }
            }


        if (isset($_GET['add'])){
          //adding a student; first check if this course has curriculums
           $query = "SELECT COUNT(currID) FROM Curriculum WHERE isReady = 1 AND course = ".$getcourseid;
           if ($result = mysqli_query($db, $query)) {
             while ($row = mysqli_fetch_row($result)) {
               $noOfActiveCurr = $row[0];
             }
             mysqli_free_result($result);
           }
           if ($noOfActiveCurr < 1) {
             echo $warn1.'You have not saved any curriculums for this course (<b>'.$courseName.'</b>) yet.<br /><br /> Please record at least one complete curriculum <a href="curriculums.php">here</a> and activate it before adding any student.'.$warn2;
             exit();
           }
        }
        else{
        //build the year level links
        echo "<fieldset>";
        printf("<legend>Students Under %s</legend>",$courseInit);
         $query = "SELECT DISTINCT sectionYearLvl FROM Students where isStillActive = 1 AND course = ".$_GET['courseid']." order by sectionYearLvl asc";
            if ($result = mysqli_query($db, $query)) {
              /* fetch associative array */
            echo "<p>These are the year levels under <b>".$courseName."</b> that contain <b>active</b> students. Click a year level to view the students' list.</p>";
            while ($row = mysqli_fetch_row($result)) {
              printf("&nbsp;&nbsp;&nbsp;<b>View <a href='students.php?yearlvl=%d&courseinit=%s'>%s Year</a> Students</b><br />",$row[0],$courseInit,conv2text($row[0]));
            }
                // free result set
                mysqli_free_result($result);
           }
           echo '<a href="javascript:;" onclick="$(\'#tab1 #isstillactive\').focus().blur();$(\'#tab1 #newstudentid\').focus();">+ Add a student under '.$courseName.'</a>';

        //build the year level links (inactive)
         $query = "SELECT DISTINCT sectionYearLvl FROM Students where isStillActive = 0 AND course = ".$_GET['courseid']." order by sectionYearLvl asc";
            if ($result = mysqli_query($db, $query)) {
              /* fetch associative array */
            echo "<br /><br /><p>These are the year levels under <b>".$courseName."</b> that contain students that are <b>inactive for some reason</b> (e.g. graduated, dead, transferred, dropped out). Click a year level to view the students' list.</p>";
            while ($row = mysqli_fetch_row($result)) {
              printf("&nbsp;&nbsp;&nbsp;<b>View Inactive <a href='students.php?yearlvl=%d&courseinit=%s&inactive=1'>%s Year</a> Students</b><br />",$row[0],$courseInit,conv2text($row[0]));
            }
                // free result set
                mysqli_free_result($result);
           }

     echo "</fieldset>";
     }
     dbconnect(0); //disconnect from database


    echo '<fieldset id="organic-tabs"><legend>+ Add a student under '.$courseInit.'</legend>';
        $db = dbconnect(1);
        if ($db == -1){
         printf("Connect failed: %s\n\n ", mysqli_connect_error());
          exit();
        }
         ?>
    		<ul id="explore-nav">
                <li><a rel="page1" href="#">Basic</a></li>
                <li><a rel="page2" href="#">Personal</a></li>
                <li><a rel="page3" href="#">Contact</a></li>
                <li><a rel="page4" href="#">Curriculum</a></li>
            </ul>
        <?php
    	echo '<div id="all-div-wrap">';
    		echo '<div id="page1"><form id="tab1" action=""><p>This is the student\'s basic information. This represents the student in SCMS. Please fill up the following information so that additional categories can be shown.</p>';
               printf("<table class=\"tblForm\"><tr><td><img src=\"images/noimg.jpg\" class=\"aboutimg\" alt=\"Profile Pic\" style=\"clear:none;\" /></td>");
               echo '<td><table class="tblForm"><tr><td><b>Student ID number:</b><br />(at least 8 characters)</td> <td><input type="text" name="newstudentid" id="newstudentid" value="" maxlength="20" /></td></tr>';
               echo '<tr><td><label for="firstName"><b>First Name:&nbsp;</b></label></td><td><input class="name" type="text" id="firstName" size="15" maxlength="40" name="firstName" value="" /></td></tr>';
               echo '<tr><td><label for="middleName"><b>Middle Name:&nbsp;</b></label></td><td><input class="name" type="text" size="12" id="middleName" maxlength="40" name="middleName" value="" /></td></tr>';
               echo '<tr><td><label for="lastName"><b>Last Name:&nbsp;</b></label></td><td><input class="name" type="text" size="19" id="lastName" maxlength="40" name="lastName" value="" /></td></tr>';
               printf("<tr><td><b>Taking:</b></td> <td><a href=\"courses.php?courseid=%d\">%s </a> - %s <input type=\"hidden\" name=\"course\" value=\"%d\" /><input type=\"hidden\" name=\"sectionyearlvl\" value=\"%d\" /><input type=\"hidden\" name=\"add\" value=\"%d\" /></td></tr>",$_GET['courseid'],$courseInit, $courseName,$_GET['courseid'],1,1);
               echo '<tr><td><label for="enrolmentclassif"><b>Enrolment Classification: </b></label></td> <td><select id="enrolmentclassif" name="enrolmentclassif"><option value="regular">regular</option><option value="irregular">irregular</option><option value="transferee">transferee</option></select></td></tr>';
               printf("<tr><td><b><label for=\"isstillactive\">Active:</label></b></td> <td><select name=\"isstillactive\" id=\"isstillactive\"><option value=\"1\">Yes, taking classes</option><option value=\"0\">No, stopped</option></select></td></tr>");
              echo '<tr><td><button id="addreset" href="#">Add new (reset all)</button><input type="reset" value="Reset this form" /></td><td><input name="submitbtn" id="registernew" class="saveinfo" type="submit" value="Register" /></td></tr></table>';
    		echo '</td></tr></table><div class="clr"></div></form></div>';

        	echo '<div id="page2"><form id="tab2" action=""><p>This is the student\'s personal information. This serves as a background profile.</p>';
               printf("<table class=\"tblForm\"><tr><td><label for='gender'><b>Gender:</b></label></td><td> <select id='gender' name='gender'><option value='m' selected=\"selected\">Male</option><option value='f'>Female</option></select></td></tr>");
               printf("<tr><td><label for='civilstatus'><b>Civil Status:</b></label></td> <td><select id='civilstatus' name='civilstatus'><option %svalue='Single'>Single</option><option %svalue='Married'>Married</option><option %svalue='Divorced'>Divorced</option><option %svalue='Widowed'>Widowed</option></select></td></tr>", (strtolower($row[10]) == 'single') ? 'selected="selected" ':'',(strtolower($row[10]) == 'married') ? 'selected="selected" ':'',(strtolower($row[10]) == 'divorced') ? 'selected="selected" ':'',(strtolower($row[10]) == 'widowed') ? 'selected="selected" ':'');
               printf("<tr><td><label for='religion'><b>Religion:</b></label></td><td> <input maxlength='60' type='text' size='%d' name='religion' id='religion' value='%s' /></td></tr>",strlen($row[11]),$row[11]);
               printf("<tr><td><label for='nat'><b>Nationality:</b></label></td> <td><input maxlength='25' type='text' size='%d' name='nat' id='nat' value='%s' /></td></tr>",strlen($row[12]),$row[12]);
               printf("<tr><td><label for='pOB'><b>Place of Birth:</b></label></td> <td><input maxlength='80' type='text' size='%d' name='pOB' id='pOB' value='%s' /></td></tr>",strlen($row[13]),$row[13]);
               printf("<tr><td><label for='year'><b>Date of Birth: </b></label></td>");
       $dob = explode("-", $row[14]);
       $dobYear = $dob[0];
       $dobMon = $dob[1];
       $dobDay = $dob[2];
       $nowArray = getdate();
       $day = 31;
       $mon = 12;
       $mons = array('January','February','March','April','May','June','July','August','September','October','November','December');
       $year = $nowArray["year"] - 10;
       $ctr = 1982;
       //build year
       echo '<td><select name="year" id="year">';
        while ($year >= $ctr){
          echo '<option '.(($year == $dobYear) ? 'selected="selected" ' :'') .'value="'.$year.'">'.$year.'</option>';
          $year -= 1;
        }
       echo '</select>';

       $ctr = 1;
       echo '&nbsp;<select name="month" id="month">';
        while ($ctr <= $mon){
          echo '<option '.(($ctr == $dobMon)? 'selected="selected" ' :'') .'value="'.$ctr.'">'.$mons[$ctr-1].'</option>';
          $ctr += 1;
        }
       echo '</select>';

       $ctr = 1; //build days
       echo '&nbsp;<select name="day" id="day">';
        while ($day >= $ctr){
          echo '<option '.(($ctr == $dobDay)? 'selected="selected" ' :'') .'value="'.$ctr.'">'.$ctr.'</option>';
          $ctr += 1;
        }
       echo '</select></td></tr>';
       echo '<tr><td><label for="highschool"><b>High school graduated from:</b></label> </td><td><input type="text" maxlength="60" name="highschool" id="highschool" value="'.$row[17].'" /></td></tr>';
       echo '<tr><td><label for="highschoolgpa"><b>High school GPA:</b><br />(Grade-Point Average)</label></td> <td><input maxlength="5" type="text" name="highschoolgpa" id="highschoolgpa" value="'.$row[18].'" /></td></tr>';
       echo '<tr><td><label for="scholarship"><b>Scholarship:</b><br />(if any)</label></td> <td><input type="text" maxlength="60" name="scholarship" id="scholarship" value="'.$row[5].'"  /></td></tr>';
       echo '<tr><td><input type="reset" value="Reset this form" /></td><td><input type="hidden" name="studentid" value="" /><input name="submitbtn" class="saveinfo" type="submit" value="Save edits" /></td></tr></table>';
	   echo '</td></tr></table><div class="clr"></div></form></div>';

     	echo '<div id="page3"><form id="tab3"  action=""><p>This contains the student\'s contact information. </p>';
                printf("<table class=\"tblForm\">");
                echo '<tr><td><label for="address"><b>Student\'s address:</b> </label></td><td><textarea name="address" id="address"></textarea></td></tr>';
                echo '<tr><td><label for="contactno"><b>Student\'s contact number:</b> </label></td> <td><input maxlength="40" id="contactno" name="contactno" type="text" value="" /></td></tr>';
                echo '<tr><td><label for="parentguardian"><b>Name of parent/guardian:</b> </label></td> <td><input maxlength="70" id="parentguardian" name="parentguardian" type="text" value="" /></td></tr>';
                echo '<tr><td><label for="emergency"><b>Emergency contact number:</b> </label></td> <td><input maxlength="40" id="emergency" name="emergency" type="text" value="" /></td></tr>';
                printf("<tr><td><label for=\"spouse\"><b>Name of spouse:</b><br />(if any)</label></td><td> <input id=\"spouse\" name=\"spouse\"type=\"text\" maxlength=\"70\" value=\"\" /></td></tr>");
                echo '<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;-<label for="spouserel"><b>Religion of spouse:</b></label></td><td><input id="spouserel" name="spouserel" maxlength="60" type="text" value="" /></td></tr>';
                echo '<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;-<label for="spousecontact"><b>Spouse contact number:</b></label></td><td><input maxlength="40" id="spousecontact" name="spousecontact" type="text" value="" /></td></tr>';
               echo '<tr><td><input type="reset" value="Reset this form" /></td><td><input type="hidden" name="studentid" value="" /><input name="submitbtn" class="saveinfo" type="submit" value="Save edits" /></td></tr></table>';
	   echo '</td></tr></table><div class="clr"></div></form></div>';


        	echo '<div id="page4"><form id="tab4" action=""><p>Select the curriculum this student will take.</p>';
          printf("<table class=\"tblForm\">");
           echo '<tr><td><label for="undercurriculum">Curriculums under <b>'.$courseInit .':</b> </label></td>';
           echo '<td>';
            //populate the curriculums for this course
         $db = dbconnect(1);
         if ($db == -1){
         printf("Connect failed: %s\n\n ", mysqli_connect_error());
          exit();
         }
        $query = "SELECT * FROM Curriculum WHERE isReady = 1 AND course = ".$_GET['courseid'];
        echo '<select name="undercurriculum"><option value="">Select a curriculum...</option>';
         if ($result = mysqli_query($db, $query)) {
            while ($row = mysqli_fetch_row($result)){
                printf("<option value=\"%d\">Curriculum Effective <b>%s Sem</b> of A.Y. %d %s</option>",$row[0],conv2text($row[2]),$row[1],(($row[6])? '(major: '.$row[6].')' : ''));
            }
         }
         echo '</select>';
                // free result set
                mysqli_free_result($result);
        echo '</td></tr>';
       echo '<tr><td><input type="reset" value="Reset this form" /></td><td><input type="hidden" name="studentid" value="" /><input name="submitbtn" class="saveinfo" type="submit" value="Save edits" /></td></tr></table>';
	   echo '</td></tr></table><div class="clr"></div></form></div>';


    	echo '</div>';
            dbconnect(0); //disconnect from database

    echo '</fieldset';
    }

    elseif (isset($_GET['yearlvl'])&& isset($_GET['courseinit'])) {
        //show the students
        $db = dbconnect(1);
        if ($db == -1){
         printf("Connect failed: %s\n\n ", mysqli_connect_error());
          exit();
        }

     echo "<fieldset>";
     //build the title
     $query = "SELECT * FROM Course where courseInitials = '".$_GET['courseinit']."'";
        if ($result = mysqli_query($db, $query)){
          /* fetch associative array */
        while ($row = mysqli_fetch_row($result)) {
          printf("<legend>%s (%s) %s</legend>",$row[1],$_GET['yearlvl'],(isset($_GET['inactive']))? '- inactives' : '');
          $courseid = $row[0];
          $courseinit = $row[1];
          }
        }
     mysqli_free_result($result);
     $row = array();


     $inactive = (isset($_GET['inactive'])) ? ' who became inactive during' : ' who are currently in';
     echo "<p>This is an alphabetical list of the students".$inactive." their year ".$_GET['yearlvl']." under the ".$courseinit." program.</p>";
     echo "<p>To view a student's personal information, click his/her name.</p>";

     $inactive = (isset($_GET['inactive'])) ? '0' : '1';
     //build the student list
     $query = "SELECT studentID,lastName, firstName, middleName FROM Students where (isStillActive = ".$inactive.") and (sectionYearLvl = ".$_GET['yearlvl'].") and (course = ".$courseid.") order by lastName asc";
        if ($result = mysqli_query($db, $query)) {
           /* fetch associative array */
        while ($row = mysqli_fetch_row($result)) {
          printf("&nbsp;&nbsp;&nbsp;&bull; <a href='students.php?studentid=%s'>%s, %s %s</a><br />",$row[0],$row[1],$row[2],$row[3]);
          }
         // free result set
         mysqli_free_result($result);
         dbconnect(0); //disconnect from database
        }
    echo "</fieldset>";

    }

    else {
      //this is the default view

       //show all the courses
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
        echo "<legend>Browse by Course</legend>";
        echo "<p>Students are enrolled under the following courses. Click a course for more information about the students enrolled under it.</p>";

        while ($row = mysqli_fetch_row($result)) {
          //printf ("%s (%s)\n", $row[0], $row[1]);
          printf("&bull; <b><a href='students.php?courseid=%s'>%s</a></b> - (%s)<br />",$row[0],$row[1],$row[2]);
          }
       }
       echo "</fieldset><fieldset><legend>Add a student:</legend>";
       echo '<form method="post" action="save.php">';
       echo '<label for="course">Add a student under the course</label>:* <select onchange="if ($(this).val()) {window.location.href = \'students.php?courseid=\'+$(this).val()+\'&add=1\';}" name="course"><option value="">Select a course...</option>';
       $query = "SELECT courseID,courseName FROM Course";
        if ($result = mysqli_query($db, $query)) {
          while ($row = mysqli_fetch_row($result)){
            printf("<option value=\"%d\">%s</option>",$row[0],$row[1]);
          }
          mysqli_free_result($result);
        }
        echo '</select></form>';
        echo "</fieldset>";
        dbconnect(0); //disconnect from database
    }

    ?>

<?php include_once("common/footer.php"); ?>

