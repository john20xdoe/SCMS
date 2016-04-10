<?php require("common/doctype-head.php"); ?>
<script language="JavaScript" type="text/javascript" src="js/subj_proc.js"></script>
	<?php require("common/nav-and-title.php");
    require("common/logcheck.php");?>

    <h2>Subjects</h2>

    <?php
    if ($_GET['subjectcode']){
      $db = dbconnect(1);
         if ($db == -1){
         printf("Connect failed: %s\n\n ", mysqli_connect_error());
          exit();
         }
        $query = "SELECT * FROM Subjects WHERE subjectCode = '".$_GET['subjectcode']."'";
         if ($result = mysqli_query($db, $query)) {
           echo $warn1."Note that all changes you make will be reflected in the curriculums both active and not, so to avoid confusion later, be careful in editing unless you are sure of what you are doing.".$warn2;
           echo "<fieldset><legend>".$_GET['subjectcode']."</legend>";
           echo '<p>You can update this subject\'s information here. </p>';
           echo "<form id=\"editsubj\" action=\"save.php\"><table><tr class=\"titlerow\"><td>Descriptive Title</td><td>Units</td><td>With Laboratory</td><td></td></tr>";
           while ($row = mysqli_fetch_row($result)){
             printf("<tr><td><input type='text' maxlength='50' name='editdesctitle' value='%s' /></td><td><input type='text' maxlength='2' size='1' name='editunits' value='%d' /></td><td><select name='editwithlab'><option value='0'>No</option><option value='1'>Yes</option></select></td><td><input type=\"submit\" id=\"editsubjsubmit\" value=\"Save\" /></td></tr>",$row[1],$row[2],($row[3]?"Yes":"No"));
            }
           echo '</table>';
           echo '<input type="hidden" name="subjectcode" value="'.$_GET['subjectcode'].'" /><input type="hidden" name="what" value="editsubj" /></form>';
           echo '</fieldset>';
           mysqli_free_result($result);
         }
              dbconnect(0);
    }

    else {
     //populate the curriculums for this course
         $db = dbconnect(1);
         if ($db == -1){
         printf("Connect failed: %s\n\n ", mysqli_connect_error());
          exit();
         }
        $query = "SELECT * FROM Subjects ORDER BY subjectCode ASC";
         if ($result = mysqli_query($db, $query)) {
           echo "<fieldset>";
           echo "<legend>CCS Subjects Glossary</legend>";
           echo '<p>These are the subjects that were included in the curiculums that CCS offers. Clicking a subjet code lets you view other information about it.</p>';
           echo '<table class="subjectsglossary"><tr class="titlerow"><td>Subject Code</td><td>Descriptive Title</td></tr>';
            while ($row = mysqli_fetch_row($result)){
               printf("<tr><td><a href=\"subjects.php?subjectcode=%s\">%s</a></td><td>%s</td></tr>",$row[0],$row[0],$row[1]);
            }
            mysqli_free_result($result);
           echo "</table>";
           echo "</fieldset>";
         }
              dbconnect(0);
     }

     ?>

<?php include_once("common/footer.php"); ?>

