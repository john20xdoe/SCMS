<?php
session_start();
require("common/logcheck.php");
include("lib/functions.php");
   $dataform = "";
   $temp = "";
if ($_GET){
  $what = $_GET['opt'];
  $dataform.='<form id="dataform" action=""><p>Select which students to include.</p>';
 if ($what == 1){ //student list
   $title = "Student List";
   if ($_GET['course'] == 1){
     $temp ='<br /><select name="course" id="course"><option value="">Courses...</option>';
      $db = dbconnect(1);
        if ($db == -1){
         printf("Connect failed: %s\n\n ", mysqli_connect_error());
          exit();
        }
        $query = "SELECT * FROM Course";
        if ($result = mysqli_query($db, $query)) {
        while ($row = mysqli_fetch_row($result)) {
          $temp.="<option value=".$row[0].">".$row[1]."</option>";
        }
        }
        // free result set
                mysqli_free_result($result);
      dbconnect(0);
     $temp.='</select><br />';
     $dataform .=$temp;
   }

   if ($_GET['year'] == 1){
     $temp = '<br /><select name="year" id="year"><option value="">Year Levels...</option>';
     $temp .= '<option value="1">First Year</option>';
     $temp .= '<option value="2">Second Year</option>';
     $temp .= '<option value="3">Third Year</option>';
     $temp .= '<option value="4">Fourth Year</option>';
     $temp .= '<option value="5">Fifth Year</option>';
     $temp.= '</select>';
     $dataform .= $temp;
   }
 }
 elseif ($what == '2'){
   $title = "";
  }
 elseif ($what == '3'){
    $title = "Individual Gradesheet/Appraisal Sheet";

 }
 elseif ($what == '4'){
    $title = "Dean's List Qualifiers - Year";
 }
 elseif ($what == '5'){
    $title = "Deans' List Qualifiers - Course";
 }
 elseif ($what == '6'){
    $title = "List of Courses";
 }
 elseif ($what == '7'){
    $title = "Enrolment Rate";
 }

 //lastly add what option this is in the form
 $dataform.= '<input type="hidden" name="opt" value="'.$_GET['opt'].'" /></form>';
}
else {
  $dataform = '<p>Please select a report template from the <a href="templates.php">Templates</a> page.</p> ';
}

?>

<?php require("common/reports-head.php");
?>

<div id="navbar" class="_print_controls">
<div id="topwrap">
<div id="settings" title="Select whether you want to include the default headers and footers of an office report. Default text are editable.">
<input type="checkbox" checked="checked" name="withhead" id="withhead" /> <label for="withhead">Put Header</label>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="checkbox" checked="checked" name="withfoot" id="withfoot" /> <label for="withfoot">Put Footer</label><br /><br />
<input type="checkbox" checked="checked" name="withlogo" id="withlogo" /> <label for="withlogo">Put CCS Logo</label><br /><br />

<label for="heightx">Height:</label>
<select name="heightx" id="heightx">
   <option value="">Select size...</option>
   <option value="750" selected="selected">11 inches</option>
   <option value="560">8.5 inches</option>
</select>
&nbsp;&nbsp;
<label for="widthx">Width:</label>
<select name="widthx" id="widthx">
   <option value="560" selected="selected">8.5 inches</option>
   <option value="750">11 inches</option>
</select>

</div>


<div id="data" title="Select data to include">
<?php echo $dataform; ?>
</div>

</div>

<div id="controls">
<div style="float:left;">
   <button onclick="var x = confirm('Are you sure you want to abandon this report?');if (x) {window.location.href = 'templates.php'}" class="reportcontrols" id="cancel">Cancel</button>
   <button onclick="(function(){function loadScript(a,b){var c=document.createElement('script');c.type='text/javascript';c.src=a;var d=document.getElementsByTagName('head')[0],done=false;c.onload=c.onreadystatechange=function(){if(!done&amp;&amp;(!this.readyState||this.readyState=='loaded'||this.readyState=='complete')){done=true;b()}};d.appendChild(c)}loadScript('js/jquery.js',function(){loadScript('js/printliminator.js',function(){printlimator()})})})()" class="reportcontrols" id="btnprintor">
   Printliminator</button>
</div>
<div style="float:right;">
   <button class="reportcontrols" id="update">Update Preview</button>
   <button onclick="window.print();" class="reportcontrols" id="print">Print</button>
</div>
</div>
<div class="clr"></div>
 <center><sub> &nbsp;&nbsp;There is no need to remove this box using Printliminator. This will not appear when you print your report.</sub></center>

 </div>

  <div id="outside">
   <div id="inside">
   <img class="logoimg" src="images/ccs_logo_sml.png" alt="" />
      <textarea class="reporthead text">
Republic of the Philippines
College of Computer Studies
University of Antique
Sibalom, Antique



<?php echo strtoupper($title);?>
</textarea>
      <div id="ajaxcontent">
      <textarea class="reportbody text">
[Body of report here]


      </textarea>
      </div>
      <textarea class="reportfoot text">
      Noted by:

      ________________________________
       OIC, College of Computer Studies
      </textarea>
   </div>
 </div>
<?php require("common/reports-foot.php");  ?>