<?php require("common/doctype-head.php"); ?>
<script type="text/javascript" src="js/organictab.js"></script>
<script type="text/javascript" src="js/curriculum_proc.js"></script>
<script language="JavaScript" type="text/javascript">
/*<![CDATA[*/
$(document).ready(function() {
   //select the First Year tab as default
   $('#explore-nav li:first-child a').addClass('current');
});
/*]]>*/
</script>
<?php require("common/nav-and-title.php");
    require("common/logcheck.php");
?>

    <h2>Curriculums</h2>

    <?php
    if ($_SESSION['msg']) { echo $_SESSION['msg'];}
    //then clear the message variable
    $_SESSION['msg'] ='';

    if (isset($_GET['currid']) && isset($_GET['courseid'])){   //viewing a specific curriculum
      $getcurrid = $_GET['currid'];
      $getcourseid = $_GET['courseid'];
      $db = dbconnect(1);
        if ($db == -1){
         printf("Connect failed: %s\n\n ", mysqli_connect_error());
          exit();
        }
        //populate curr's details
        //query to get this complete details about this curriculum
        $query = "SELECT * FROM Curriculum JOIN Course WHERE Curriculum.currID = ".$getcurrid." AND Curriculum.course = Course.courseid AND Course.courseid = " .$getcourseid;
        if ($result = mysqli_query($db, $query)) {
           /* fetch associative array */
           while ($row = mysqli_fetch_row($result)){
             echo '<fieldset><legend>Curriculum Details</legend>';
             $noOfYears = $row[5];
             $isReady = $row[4];
             $currstatus = $isReady ? '<b>already in effect. </b>You can view its subjects below.' : '<b>not yet ready</b>. Please continue to add subject courses below.';
             echo '<b>&bull; Curriculum Effective '.conv2text($row[2]).' Semester of A.Y. '.$row[1].'</b><br /><br />';
             echo '<p>This is a curriculum under the program course <b>'.$row[8].'</b>, '.(($row[6]) ? 'majoring in <b>'.$row[6].'</b>, ' : '' ).'planned to be officially effective starting at the '.strtolower(conv2text($row[2])).' semester of the Academic Year of the year '.$row[1].'.</p>';
             echo '<p>This curriculum is '.$currstatus.'</p>';
             echo '</fieldset>';
            }
            mysqli_free_result($result);
            $row = array();
        }

        echo '<fieldset><legend id="currid" rel="'.$getcurrid.'">Curriculum Subjects</legend>';
        //populate curr's subjects
        //first, get the max number of sem's
        for ($yearctr = 1;$yearctr <= $noOfYears;$yearctr++){
         //query for max number of sems this year has
         $query = "SELECT MAX(SubjectByCurr.semNo) FROM Curriculum JOIN Course JOIN SubjectByCurr WHERE Curriculum.currID = ".$getcurrid." AND course = courseid AND SubjectByCurr.yearLvl = ".$yearctr." AND SubjectByCurr.currID = Curriculum.currID AND courseid = ".$getcourseid;
           if ($result = mysqli_query($db, $query)){
             while ($row = mysqli_fetch_row($result)){
               $noOfSems[$yearctr] = $row[0];  //take these max number of sems per year into an array
             }
             mysqli_free_result($result);
             $row = array();
           }
        }
        echo '<div id="organic-tabs">';

        //build the year tabs
        echo '<ul id="explore-nav">';
        for ($yearctr = 1;$yearctr <= $noOfYears;$yearctr++){
           printf("<li><a rel=\"page%d\" href=\"#\" >%s Year</a></li>",$yearctr,conv2text($yearctr));
        }
        echo '</ul>';

        //populate tab pages
        if ($isReady){  //populate subjects only
          echo '<div id="all-div-wrap">';
          for ($yearctr = 1;$yearctr <= $noOfYears;$yearctr++){
             printf("\n<div id=\"page%d\">These are the subjects planned during the <b>%s year</b> of this curriculum. <br /><br />",$yearctr,   strtolower(conv2text($yearctr)));
             for ($semctr = 1;$semctr <= ($noOfSems[$yearctr] = (($noOfSems[$yearctr] > 2) ? $noOfSems[$yearctr] : 2));$semctr++) {
               printf("<table  class=\"tblReady\"><caption>%s Semester</caption>\n",conv2text($semctr));
               echo '<tr class="titlerow"> <td><b>Subject Code*</b></td> <td><b>Subject Title*</b></td>  <td><b>With Lab*</b></td>   <td><b>Credit<br />Units*</b></td> <td><b>Requisites</b></td></tr>';
               $query = "SELECT Subjects.subjectCode,Subjects.descTitle, Subjects.withLab, Subjects.units FROM SubjectByCurr JOIN Subjects WHERE SubjectByCurr.currID = ".$getcurrid." AND SubjectByCurr.yearLvl = ".$yearctr." AND SubjectByCurr.semNo = ".$semctr." AND SubjectByCurr.subjectCode = Subjects.subjectCode";
               if ($result = mysqli_query($db, $query)){
                while ($row = mysqli_fetch_row($result)){
                  echo '<tr class="subject-row">';
                  echo '<td><a href="subjects.php?subjectcode='.$row[0].'">'.$row[0].'</a></td>';  //Subject code
                  echo '<td>'.$row[1].'</td>';   //desc title
                  echo '<td>'.($row[2] ? 'Yes': 'No').'</td>';   //with lab
                  echo '<td>'.$row[3].'</td>';   //units
                  echo '<td>';
                  $query2 = "SELECT PreReqCode FROM PreRequisites WHERE subjectCode = '".$row[0]."' AND currid = ".$getcurrid;
                  if ($result2 = mysqli_query($db, $query2)){
                     while ($row2 = mysqli_fetch_row($result2)){
                       echo $row2[0];
                     }
                     mysqli_free_result($result2);
                  }
                  echo '</td></tr>';  //prereqstrings
                }
                mysqli_free_result($result);
                $row = array();
                }
               printf("</table>");
             }
             printf("</div>");
          }
          echo '</div></div>';
        }
        else { //populate as incomplete edit form
          echo '<div id="all-div-wrap">';
          //then build the divs
          for ($yearctr = 1;$yearctr <= $noOfYears;$yearctr++){
             printf("\n<div id=\"page%d\" rel=\"%d\" class=\"pageform\"><form class=\"frmYear\" action=\"\" >These are the subjects planned during the <b>%s year</b> of this curriculum. <br /><br />",$yearctr,$yearctr,strtolower(conv2text($yearctr)));
             //build semester tables
             for ($semctr = 1;$semctr <= ($noOfSems[$yearctr] = (($noOfSems[$yearctr] > 2) ? $noOfSems[$yearctr] : 2));$semctr++) {
               if ($semctr == 3){ //separate summer sem using an IF for easy removal with jquery
                 printf("<table class=\"tblSem tblSummer\" id=\"year%dsem%d\" rel=\"3\"><caption>Summer Semester <span class=\"delwrap\" style=\"float:right;\"> <a class=\"deletesummer\" href=\"javascript:;\" title=\"Remove table\">&nbsp;<b>x</b>&nbsp;</a></a></span></caption>",$yearctr,$semctr);
               } else {
                 printf("<table class=\"tblSem\" id=\"year%dsem%d\" rel=\"%d\"><caption>%s Semester</caption>\n",$yearctr,$semctr,$semctr,conv2text($semctr));
               }
               $prereqstring = '';
               echo '<tr class="titlerow"> <td><b>Subject Code*</b></td> <td><b>Subject Title*</b></td>  <td><b>With Lab*</b></td>   <td><b>Credit<br />Units*</b></td> <td><b>Requisites</b></td> <td><b>Options</b></td></tr>';
               $query = "SELECT Subjects.subjectCode,Subjects.descTitle, Subjects.withLab, Subjects.units FROM SubjectByCurr JOIN Subjects WHERE SubjectByCurr.currID = ".$getcurrid." AND SubjectByCurr.yearLvl = ".$yearctr." AND SubjectByCurr.semNo = ".$semctr." AND SubjectByCurr.subjectCode = Subjects.subjectCode";
                if ($result = mysqli_query($db, $query)){
                while ($row = mysqli_fetch_row($result)){
                  echo '<tr class="subject-row">';
                  echo '<td><input class="input" rel="subjectcode" type="text" size="9" value="'.$row[0].'" /></td>';  //Subject code
                  echo '<td><textarea class="input" rel="desctitle">'.$row[1].'</textarea></td>';   //desc title
                  echo '<td><select class="input" rel="withlab"><option'.($row[2] ? '': ' selected="selected"').' value="0">No</option><option'.($row[2] ? 'selected="selected"': '').' value="1">Yes</option></select></td>';   //with lab
                  echo '<td><input class="input" rel="units" type="text" size="1" maxlength="2" value="'.$row[3].'" /></td>';   //units
                  $query2 = "SELECT PreReqCode FROM PreRequisites WHERE subjectCode = '".$row[0]."' AND currid = ".$getcurrid;
                  if ($result2 = mysqli_query($db, $query2)){
                     while ($row2 = mysqli_fetch_row($result2)){
                       $prereqstring = $row2[0];
                     }
                     mysqli_free_result($result2);
                  }
                  echo '<td><textarea class="input" cols="17" rel="prereqstring">'.$prereqstring;
                  echo '</textarea><br /></td>';
                  echo '<td><span class="delwrap"><a class="delete" href="javascript:;" title="Remove subject"> Erase</a></span> </td>';         //prerequisites
                  echo '</td>';
                  echo '</tr>';
                }
                mysqli_free_result($result);
                $row = array();
                }
               echo '<tr><td colspan="6" align="right"><a class="addfield" title="Add a subject under this semester"  href="javascript:;">+ Add a subject</a></td></tr>';
               printf("</table>");
              if(($noOfSems[$yearctr] == 2) && ($semctr == 2)){ //show Add summer sem button
                 echo '<span class="delwrap"><a class="addsummer" href="javascript:;" title="Add a Summer Semester">+ Add table for a Summer Sem</a></span>';
              }
             }

             echo '<input type="submit" class="saveyear" onclick="javascript:return false;" id="save'.$yearctr.'" value="Save this year" /><br /><br />';
             echo '<select title="Hold down CTRL for multiple requisites" multiple="multiple" name="reqselect" id="reqselect"></select>';
             printf("</form></div>\n");
          }
          echo '</div>';
          echo '</div>';
                    echo '<form class="activatecurr" action=""><b>&bull; On Curriculum Activation:</b><br /><br />';
          echo '&nbsp;&nbsp;&nbsp;<input type="radio" id="isready1" name="isready" value="1" />';
          echo '<label for="isready1"> <b>Save and activate these plans.</b> I have finished adding every subject. This curriculum is ready. (Note: Activated curriculums cannot be changed or edited anymore.)</label>';
          echo '<br /><br />&nbsp;&nbsp;&nbsp;<input type="radio" id="isready0" name="isready" checked="checked" value="0" />';
          echo '<label for="isready0"> <b>Save only.</b> Save all of my edits only. I haven\'t finished entering all the subjects. (You can still edit this curriculum later, but you cannot add a student under this curriculum yet.)</label>';
          echo '<br /><br /><button id="savecurr" onclick="javascript:return false;">Save All Edits</button><br /><br />';
          echo '</form><br /><div style="clear:both;"></div>';
        }

        echo '</fieldset>';
        dbconnect(0); //disconnect from database
    }

    else {
       // add new curriculum

       echo '<fieldset><legend>+ Add a Curriculum</legend>';
       echo '<p>To add a new curriculum plan into SCMS, first kindly enter the information required below. After clicking <b>save</b>, you will be asked to enter the subjects included in this curriculum. Fields with * are required.</p>';
       echo '<b>Curriculum Details</b>';
       echo '<form method="post" action="save.php">';
       echo '<label for="course">This is a curriculum for the course</label>:* <select name="course"><option value="">Select a course...</option>';
        $db = dbconnect(1);
        if ($db == -1){
         printf("Connect failed: %s\n\n ", mysqli_connect_error());
          exit();
        }
       $query = "SELECT courseID,courseName FROM Course";
        if ($result = mysqli_query($db, $query)) {
          while ($row = mysqli_fetch_row($result)){
            printf("<option value=\"%d\">%s</option>",$row[0],$row[1]);
          }
          mysqli_free_result($result);
        }
        dbconnect(0);
       echo '</select>';

       echo '<br /><label for="newcoursemajor"> majoring in: </label>&nbsp;<select name="newcoursemajor" id="newcoursemajor">';
       $db = dbconnect(1);
        if ($db == -1){
         printf("Connect failed: %s\n\n ", mysqli_connect_error());
          exit();
        }
       $query = "SELECT DISTINCT majorIn FROM Curriculum where majorIn IS NOT NULL";
        if ($result = mysqli_query($db, $query)) {
          while ($row = mysqli_fetch_row($result)){
            printf("<option value=\"%s\">%s</option>",$row[0],$row[0]);
          }
          mysqli_free_result($result);
        }
        dbconnect(0);
       echo '<option selected="selected" value="">(none)</option><option value="#">+ Add new major...</option></select>';
       echo '<br /><br /><b>This curriculum will be officially effective starting:</b><br />';
       echo '<label for="curryear">Academic Year of the year:* </label><input type="text" name="curryear" id="curryear" maxlength="4" size="5" />';
       echo '<label for="currsem"> and semester:* </label><select name="currsem" id="currsem"><option value="1">'.conv2text(1).'</option><option value="2">'.conv2text(2).'</option><option value="3">Summer</option></select>';
       echo '<br /><label for="years">This curriculum will span a number of  <select name="years" id="years"><option value="4">4</option><option value="5">5</option><option value="3">3</option><option value="2">2</option></select>* years.</label>';
       echo '<input type="hidden" name="what" value="curr" />';
	   echo '<br /><input type="submit" id="newcurrsubmt" value="Save curriculum details" /><input type="hidden" name="what" value="curr" /></form>';
       echo '<br /><br /><p>To view the other curriculums, you can browse them by course <a href="courses.php">here</a>.</p>';
    }
    ?>
<?php include_once("common/footer.php"); ?>

