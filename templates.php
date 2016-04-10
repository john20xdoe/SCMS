<?php require("common/doctype-head.php"); ?>
	<?php require("common/nav-and-title.php");
    require("common/logcheck.php");?>

    <h2>Templates</h2>
    <p>You can print reports based on preset templates below.</p>

    <?php
    if ($_GET){

      //shorten the var name
      $opt = $_GET['opt'];

      if ($opt == 1){
      //student year list
        echo '<h4>Student List by Year</h4>';
      }
    }
    else {
    ?>

    <fieldset>
    <legend>Students' Curriculum Information</legend>
    <p>These are reports that use CCS student information.</p>
        <ul>
          <li><b>Student Lists</b>
        <ul>
        <li><a href="reports-body.php?opt=1&course=1&year=1">Year List (Section)</a></li>
        <li><a href="reports-body.php?opt=1&course=1&year=1">Course List</a></li>
        </ul>
      </li>

      <li><b>Student Grades</b>
        <ul>
        <li><a href="reports-body.php?opt=3">Individual Gradesheet (Appraisal Sheet)</a></li>
        </ul>
      </li>

      <li><b>Dean's List Qualifiers</b>
        <ul>
        <li><a href="reports-body.php?opt=4">Year List (Section)</a></li>
        <li><a href="reports-body.php?opt=5">Course List</a></li>
        </ul>
      </li>
      </ul>
   </fieldset>

   <fieldset>
   <legend>CCS Department Information</legend>
   <p>These are reports about the College of Computer Studies.</p>
     <ul>
     <li><a href="reports-body.php?opt=6">Curriculum Checklist</a></li>
     <li><a href="reports-body.php?opt=7">List of Courses Offered</a></li>

     </ul>
   </fieldset>

   <?php } ?>
<?php include_once("common/footer.php"); ?>

