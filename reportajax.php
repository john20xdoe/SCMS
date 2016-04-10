<?php
//report ajax processor
require("lib/functions.php");
$data ="No Results Found.";
if ($_POST){
       $db = dbconnect(1);
        if ($db == -1){
         printf("Connect failed: %s\n\n ", mysqli_connect_error());
          exit();
        }
    $what = $_POST['opt'];

    if ($what == '1'){
      $query ="SELECT studentID,lastName, firstName, middleName FROM Students WHERE isStillActive = 1 ";

      if ($_POST['course']){
        $query.= " AND course = ".$_POST['course'];
      }
      if ($_POST['year']){
        $query.= " AND sectionYearLvl = ".$_POST['year'];
      }

      $query.= " ORDER BY lastName ASC";

        if ($result = mysqli_query($db, $query)) {
          $data = "\n\n\n";
          while ($row = mysqli_fetch_row($result)){
           $data .= $row[0]."    ".$row[1].", ".$row[2]." ".$row[3]."\n";          }
           mysqli_free_result($result);
        }
    }

    elseif ($what == '6'){
      $query = 'SELECT * FROM Course';
      if ($result = mysqli_query($db, $query)) {
          $data = "\n\n\n";
          while ($row = mysqli_fetch_row($result)){
           $data .= $row[0]." &nbsp;   <b>".$row[1]."</b> - ".$row[2]."<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$row[3]."<br /><br />";          }
           mysqli_free_result($result);
      }
    }
dbconnect(0);
}
echo '<div>'.$data.'</div>';
?>