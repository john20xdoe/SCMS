<?php
//require the functions library
require("lib/functions.php");


$referer='index.php'; //default referrer
$dup=0;

//run script only if there is POST data
if ($_POST){
     //someone deletes their account
     if ($_POST['what'] == 'deleteaccount'){
       $referer = 'deleteaccount.php?success=1';

       //first check database for duplicates
         $db = dbconnect(1);
         if ($db == -1){
           printf("Connect failed: %s\n\n", mysqli_connect_error());
           exit();
           } //errors occurred @ database side

       $query = "SELECT password FROM ScmsAdmin where userName = '".$_SESSION['user']. "'";

       if ($result = mysqli_query($db, $query)) {
          /* fetch associative array */
          while ($row = mysqli_fetch_row($result)) {
                if  ($_POST['delpass'] == $row[0])  {
                 $query = "DELETE from ScmsAdmin where userName = '".$_SESSION['user']."'";
                 mysqli_query($db, $query) or die(mysqli_error($db));
                $_SESSION['msg'] = $info1."The account under <b>".$_SESSION['user']." </b> has been deleted. Go back to the <a href=\"index.php\">main page</a>.".$info2;
                $dup = 1;
                $_SESSION['success'] = 1;
                }
            }
          if ($dup != 1){   //no duplicates
          $_SESSION['msg'] = $warn1."Wrong password.".$warn2;
          $_SESSION['success'] = '';
          $referer = 'deleteaccount.php';
          }
       mysqli_free_result($result);
       }
       dbconnect(0);
     header('Location: '.$referer);
     }

     elseif ($_POST['what'] == 'deletesubject'){
       if ($_POST['subjectcode']){

          $db = dbconnect(1);
          if ($db == -1){
             printf("Connect failed: %s\n\n", mysqli_connect_error());
             exit();
          } //errors occurred @ database side
          $query = "SELECT subjectCode FROM SubjectByCurr JOIN Curriculum WHERE isReady = 0 AND NOT SubjectByCurr.currid = ".$_POST['currid'];
          if ($result = mysqli_query($db, $query)) {
          /* fetch associative array */
            while ($row = mysqli_fetch_row($result)) {
               if  (($_POST['subjectcode'] == $row[0])) {  //subject is also in other curr, dont delete from subjects
                  $dup = 1;                                //delete only from SubjectByCurr, this curriculum
               }
            }
            mysqli_free_result($result);

          $query2 = "SELECT subjectCode,currID,yearLvl,semNo from SubjectByCurr";
          if ($result2 = mysqli_query($db, $query2)){
              while ($row2 = mysqli_fetch_row($result2)){
                if (($_POST['subjectcode'] == $row2[0]) && ($_POST['currid'] == $row2[1]) && ($_POST['yearlvl'] == $row2[2]) && ($_POST['semno'] == $row2[3])){
                   $dup2 = 1;   //match subject in subject by curriculum, ok to delete
                }
              }
              mysqli_free_result($result2);
          }

            if ($dup != 1) {   //meaning no duplicates in other curriculum, ok to delete from subjects table
                $query = "DELETE FROM Subjects WHERE subjectCode = '".$_POST['subjectcode']."'";
                mysqli_query($db, $query) or die(mysqli_error($db));
                echo 'Erased';
            }
            if ($dup2 == 1) {  //meaning delete this only if the subject is still under the curriculum
                $query = "DELETE FROM SubjectByCurr WHERE subjectCode = '".$_POST['subjectcode']."' AND currID = ".$_POST['currid']." AND yearLvl = ".$_POST['yearlvl']." AND semNo = ".$_POST['semno'];
                mysqli_query($db, $query) or die(mysqli_error($db));
                echo 'Erased';
              }
          }
       }

      dbconnect(0);
     }

     elseif ($_POST['what'] == 'deletesummer'){
        $db = dbconnect(1);
        if ($db == -1){
           printf("Connect failed: %s\n\n", mysqli_connect_error());
           exit();
        } //errors occurred @ database side
        $query = "SELECT subjectCode FROM SubjectByCurr JOIN Curriculum WHERE isReady = 0 AND NOT SubjectByCurr.currid = ".$_POST['currid'];
        if ($result = mysqli_query($db, $query)) {
          while ($row = mysqli_fetch_row($result)) {
               if  (($_POST['subjectcode'] == $row[0])) {  //subject is also in other curr, dont delete from subjects
                  $dup = 1;                                //delete only from SubjectByCurr, this curriculum
               }
            }
            mysqli_free_result($result);
        }


        $query = "DELETE FROM SubjectByCurr WHERE currID = ".$_POST['currid']." AND semNo = 3 AND yearLvl = ".$_POST['yearlvl'];
        mysqli_query($db, $query) or die(mysqli_error($db));
        echo 'Erased';

        dbconnect(0);
     }


}  //end of if $_post

//check for $_get data
if ($_GET){
   if ($_GET['what'] == 'stud'){
      if ($_GET['studentid']){
        $db = dbconnect(1);
         if ($db == -1){
           printf("Connect failed: %s\n\n", mysqli_connect_error());
           exit();
           } //errors occurred @ database side

        $query = "DELETE from Students WHERE studentID = '".$_GET['studentid']."'";
        mysqli_query($db, $query) or die(mysqli_error($db));
        $query = "DELETE from StudentsWithSpouse WHERE studentID = '".$_GET['studentid']."'";
        mysqli_query($db, $query) or die(mysqli_error($db));
        $query = "DELETE from SubjectsTakenByStudent WHERE studentID = '".$_GET['studentid']."'";
        mysqli_query($db, $query) or die(mysqli_error($db));
        echo 'All records of student with ID # '.$_GET['studentid'].' have been deleted.';
        $_SESSION['msg'] = $info1.'All records of student with ID <b>#'.$_GET['studentid'].'</b> have been deleted.'.$info2;
       dbconnect(0);
       header('Location: students.php');
      }
   }
 elseif ($_GET['what'] == 'course' && $_GET['courseid']){
       //first check database for duplicates
         $db = dbconnect(1);
         if ($db == -1){
           printf("Connect failed: %s\n\n", mysqli_connect_error());
           exit();
           } //errors occurred @ database side

        $query = "DELETE from Course WHERE courseID = ".$_GET['courseid'];
        mysqli_query($db, $query) or die(mysqli_error($db));
        $_SESSION['msg'] = $info1."The course has been deleted. All other associated data has been affected.".$info2;
        $dup = 1;
       dbconnect(0);
       $referer = 'courses.php';
 }
}
header("Location:".$referer);
?>