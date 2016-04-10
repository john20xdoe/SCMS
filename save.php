<?php
//require the functions library
require("lib/functions.php");
//session_start();

$referer='index.php'; //default referrer
$dup=$dup2=$dup3=0;

//run script only if there is POST data
if ($_POST){
   //action concerning courses
   if ($_POST['what'] == 'course'){
       //set the referer so we can go back
       $referer = "courses.php";

       //check existence
       if ($_POST['newcourseInit'] && $_POST['newcourseDesc']){
          $newcourseInit = isset($_POST['newcourseInit']) ? $_POST['newcourseInit'] : '';
          $newcourseName = isset($_POST['newcourseName']) ? $_POST['newcourseName'] : '';
          $newcourseDesc = isset($_POST['newcourseDesc']) ? $_POST['newcourseDesc'] : '';

         //trim whitespaces
         $newcourseInit = trim($newcourseInit);
         $newcourseDesc = trim($newcourseDesc);
         $newcourseName = trim($newcourseName);

         //first check database for duplicates
         $db = dbconnect(1);
         if ($db == -1){
           printf("Connect failed: %s\n\n", mysqli_connect_error());
           exit();
           } //errors occurred @ database side

       $query = "SELECT * FROM Course";

       if ($result = mysqli_query($db, $query)) {
          /* fetch associative array */
          while ($row = mysqli_fetch_row($result)) {
            //printf("%s %s %s", $row[0],$row[1],$row[3]);
             if  (($newcourseInit == $row[1]) || ($newcourseName == $row[2]))  {
                $_SESSION['msg'] = $warn1."The course <b>".$newcourseInit." (".$newcourseName.")</b> is not saved because it already exists. Do you want to <a href=\"courses.php?courseid=".$row[0]."\">edit</a>?".$warn2;
                $dup = 1;
             }
            }
          if ($dup != 1){   //no duplicates
          // Save the new course, hurrah
          $query = "INSERT INTO Course VALUES('','".$newcourseInit."','".$newcourseName."','".$newcourseDesc."')";
          mysqli_query($db, $query) or die(mysqli_error($db));
          $_SESSION['msg'] = $info1."The course <b>".$newcourseInit." (".$newcourseName.")</b> has been saved sucessfully.".$info2;
          }
       }


          // free result set
                mysqli_free_result($result);
                dbconnect(0);
                 //disconnect from database
        } else $_SESSION['msg'] = $warn1.'Can\'t new course because you submitted a blank form. Please enter valid information.'.$warn2;

      header('Location: '.$referer);
   }

  //action concerning curriculums
  elseif ($_POST['what'] == 'curr'){
     //new curriculums
     if ($_POST['course'] &&  $_POST['curryear'] && $_POST['currsem']){
       $referer = "curriculums.php";
       //check its existence in the db
         $db = dbconnect(1);
         if ($db == -1){
           printf("Connect failed: %s\n\n", mysqli_connect_error());
           exit();
           } //errors occurred @ database side

         $query = "SELECT Curriculum.* FROM Curriculum JOIN Course where course = courseid";
       if ($result = mysqli_query($db, $query)) {
          /* fetch associative array */
          while ($row = mysqli_fetch_row($result)) {
             if  (($_POST['curryear'] == $row[1]) && ($_POST['currsem'] == $row[2]) && ($_POST['course'] == $row[3]))  {
                $_SESSION['msg'] = $warn1."A curriculum effective on A.Y. of year <b>".$row[1]."</b> (".conv2text($row[2]).") for the course ".$row[4]." already exists. Do you want to <a href=\"curriculums.php?currid=".$row[0]."&mode=edit\">edit</a>?".$warn2;
                $dup = 1;
             }
            }
          // free result set
          mysqli_free_result($result);
          $row = array();

          if ($dup != 1){   //no duplicates
          // Save the new curriculum, hurrah
          $query = "INSERT INTO Curriculum VALUES('','".$_POST['curryear']."','".$_POST['currsem']."',".$_POST['course'].",'0','".$_POST['years'].",'".((isset($_POST['newcoursemajor']))? (",'".$_POST['newcoursemajor']."'") : "").")";
          mysqli_query($db, $query) or die(mysqli_error($db));
              $thiscurrid =mysqli_insert_id($db);
              $referer = "curriculums.php?courseid=".$_POST['course']."&currid=".$thiscurrid;
           $_SESSION['msg'] = $info1."The curriculum plan for A.Y. of year <b>".$_POST['curryear']."</b> (".conv2text($_POST['currsem'])." semester) for the course ".$row[4]." has been created. Please proceed to add subjects.".$info2;
          }
          // free result set
                mysqli_free_result($result);
                dbconnect(0);
                 //disconnect from database
         }else $_SESSION['msg'] = 'You must fill all fields.';

     }
  header('Location: '.$referer);
  }


  //action concerning subjects ,data loaded by ajax
  elseif ($_POST['what'] == 'subject'){
    if ($_POST['subjectcode']){

      $db = dbconnect(1);
      if ($db == -1){
           printf("Connect failed: %s\n\n", mysqli_connect_error());
           exit();
      } //errors occurred @ database side
      $query = "SELECT * FROM Subjects";
       if ($result = mysqli_query($db, $query)) {
          /* fetch associative array */
          while ($row = mysqli_fetch_row($result)) {
             if  (($_POST['subjectcode'] == $row[0]))  {      //duplicate in subjects table
                $oldsubjectcode = $row[0];
                $dup = 1;
             }
            }
          // free result set
          mysqli_free_result($result);
          $row = array();
       $query2 = "SELECT subjectCode, SubjectByCurr.currID, yearLvl,semNo from SubjectByCurr JOIN Curriculum where isReady = 0 AND SubjectByCurr.currID = Curriculum.currID";
       if ($result2 = mysqli_query($db, $query2)){
          while ($row2 = mysqli_fetch_row($result2)){
            if (($_POST['subjectcode'] == $row2[0]) && ($_POST['currid'] == $row2[1]) && ($_POST['yearlvl'] == $row2[2]) && ($_POST['semno'] == $row2[3])){
               $oldsubjectcodeincurr = $row[0];
               $dup2 = 1;   //subject is already in the curriculum
            }
          }
          mysqli_free_result($result2);
        }
         if ($dup == 1){ //edits to subjects?
           $query = "UPDATE Subjects SET descTitle='".$_POST['desctitle']."',units=".$_POST['units'].",withLab=".$_POST['withlab']." WHERE subjectCode='".$oldsubjectcode."'";
           mysqli_query($db, $query) or die(mysqli_error($db));
           echo 'updated';
         }
         if ($dup != 1){   //no duplicates in subjects table
          // Save the new subject, hurrah
          $query = "INSERT INTO Subjects VALUES('".$_POST['subjectcode']."','".rawurldecode($_POST['desctitle'])."',".$_POST['units'].",".$_POST['withlab'].")";
          mysqli_query($db, $query) or die(mysqli_error($db));
         }
          if ($dup2 != 1){
          $query = "INSERT INTO SubjectByCurr VALUES(".$_POST['currid'].",'".$_POST['subjectcode']."', ".intval($_POST['yearlvl']).",".$_POST['semno'].")";
          mysqli_query($db,$query) or die(mysqli_error($db));
          echo 'saved';
          }

          //process prereqstring
          $query = "SELECT * FROM PreRequisites WHERE currID = ".$_POST['currid']." AND subjectCode = '".$_POST['subjectcode']."'";
          if ($result = mysqli_query($db, $query)){
                while ($row = mysqli_fetch_row($result)){
                 if (($row[0] == $_POST['subjectcode']) && ($row[2] == $_POST['currid'])){
                    $dup3 = 1;
                 }
                }
                mysqli_free_result($result);
          }
          if (isset($_POST['prereqstring'])){
              if ($dup3 != 1){
                $query = "INSERT INTO PreRequisites VALUES('".$_POST['subjectcode']."','".$_POST['prereqstring']."',".$_POST['currid'].")";
                mysqli_query($db,$query) or die(mysqli_error($db));
              }
              else {
                $query = "UPDATE PreRequisites SET preReqCode = '".$_POST['prereqstring']."' WHERE subjectCode = '".$_POST['subjectcode']."' AND currID = ".$_POST['currid'];
                mysqli_query($db,$query) or die(mysqli_error($db));
              }
          }else {
             if ($dup3){
                $query = "DELETE FROM PreRequisites WHERE subjectCode = '".$_POST['subjectcode']."' AND currID = ".$_POST['currid'];
                mysqli_query($db,$query) or die(mysqli_error($db));
             }
          }
            dbconnect(0);
      }
    }
  }

   //action concerning save curr
   elseif ($_POST['what'] == 'savecurr'){
     if (isset($_POST['isready']) && isset($_POST['currid'])){
       $db = dbconnect(1);
        if ($db == -1){
           printf("Connect failed: %s\n\n", mysqli_connect_error());
           exit();
        } //errors occurred @ database side

         $query = "UPDATE Curriculum SET isReady = 1 WHERE currID = ". $_POST['currid'];
         mysqli_query($db,$query) or die(mysqli_error($db));
         $_SESSION['msg'] = $info1.'Curriculum has been activated!'.$info2;
       dbconnect(0);
     }
   }

   //action concerning students
   elseif ($_POST['what'] == 'stud'){
     $new = $_POST['add'];
     if (isset($_POST['tab'])){

       if ($_POST['tab'] == 'basic'){
             $db = dbconnect(1);
             if ($db == -1){
                printf("Connect failed: %s\n\n", mysqli_connect_error());
                exit();
             } //errors occurred @ database side
             if ($new){
               $query = "INSERT INTO Students SET studentID = '".$_POST['newstudentid']."', firstName = '".$_POST['firstName']."',lastName = '".$_POST['lastName']."', course=".$_POST['course'].",enrolmentClassif='".$_POST['enrolmentclassif']."', sectionYearLvl = ".$_POST['sectionyearlvl'].", isStillActive=".$_POST['isstillactive'].(isset($_POST['middleName']) ? ",middleName='".$_POST['middleName']."'":" ");
               mysqli_query($db,$query) or die(mysqli_error($db));
               echo 'Registered';
             }
             else {
              $query = "UPDATE Students SET enrolmentClassif = '".$_POST['enrolmentclassif']."',isStillActive = ".$_POST['isstillactive'].(isset($_POST['firstName'])?"firstName=".$_POST['firstName']:"").(isset($_POST['lastName'])?"lastName=".$_POST['lastName']:"").(isset($_POST['middleName'])?"middleName=".$_POST['middleName']:"");
              mysqli_query($db,$query) or die(mysqli_error($db));
              echo 'Updated!';
              }
            dbconnect(0);
       }

       if ($_POST['tab'] == 'personal'){
             $db = dbconnect(1);
             if ($db == -1){
                printf("Connect failed: %s\n\n", mysqli_connect_error());
                exit();
             } //errors occurred @ database side
              $dob = $_POST['year'].'-'.$_POST['month'].'-'.$_POST['day'];
              $query = "UPDATE Students SET gender = '".$_POST['gender']."',civilStatus = '".$_POST['civilstatus']."',religion = '".$_POST['religion']."',nationality = '".$_POST['nat']."',placeOfBirth = '".$_POST['pOB']."',dOB = '".$dob."'".(($_POST['scholarship'])? ",scholarship = '".$_POST['scholarship']."'" : "").(($_POST['highschool'])? ",highSchool = '".$_POST['highschool']."'" : "").($_POST['highschoolgpa']? ",highschoolGPA = ".$_POST['highschoolgpa'] : "")." WHERE studentID = '". $_POST['studentid']."'";
              mysqli_query($db,$query) or die(mysqli_error($db));
              echo 'Updated!';
            dbconnect(0);
       }

       if ($_POST['tab'] == 'contact'){
             $db = dbconnect(1);
             if ($db == -1){
                printf("Connect failed: %s\n\n", mysqli_connect_error());
                exit();
             } //errors occurred @ database side
              if (isset($_POST['spouse']) && $_POST['spouse']){
                $query = "INSERT INTO StudentsWithSpouse VALUES('".$_POST['studentid']."','".$_POST['spouse']."','".$_POST['spouserel']."','".$_POST['spousecontact']."') ON DUPLICATE KEY UPDATE spouseName='".$_POST['spouse']."',spouseReligion='".$_POST['spouserel']."',spouseContactNo='".$_POST['spousecontact']."'";
                mysqli_query($db,$query) or die(mysqli_error($db));
              }  elseif (isset($_POST['spouse']) && $_POST['spouse']==''){
                $query = "DELETE FROM StudentsWithSpouse WHERE studentid = '".$_POST['studentid']."'";
                mysqli_query($db,$query) or die(mysqli_error($db));
              }

             $query = "UPDATE Students SET address = '".$_POST['address']."', contactNo = '".$_POST['contactno']."', parentGuardian = '".$_POST['parentguardian']."', emergencyContactNo = '".$_POST['emergency']."' WHERE studentID = '". $_POST['studentid']."'";
              mysqli_query($db,$query) or die(mysqli_error($db));
              echo 'Updated!';
            dbconnect(0);
       }
       if ($_POST['tab'] == 'curr'){
             $db = dbconnect(1);
             if ($db == -1){
                printf("Connect failed: %s\n\n", mysqli_connect_error());
                exit();
             } //errors occurred @ database side
             $query = "UPDATE Students SET underCurriculum=".$_POST['undercurriculum'];
              mysqli_query($db,$query) or die(mysqli_error($db));
              echo 'Updated!';
             dbconnect(0);
       }

     }
   }

   //action concerning instructors
   elseif ($_POST['what'] == 'inst'){
      //set referer so we can go back
      $referer= 'instructors.php';

      //check existence of all required fields
      if ($_POST['ifname'] && $_POST['ilname'] && $_POST['contracttype'] && $_POST['department']){
         $db = dbconnect(1);
             if ($db == -1){
                printf("Connect failed: %s\n\n", mysqli_connect_error());
                exit();
             } //errors occurred @ database side
             $query = "INSERT INTO Instructors VALUES('','".$_POST['ilname']."','".$_POST['ifname']."','".$_POST['imname']."'".($_POST['employeeid'] ? (",'".$_POST['employeeid']."'") :"").($_POST['department'] ? (",".$_POST['department']) : "").($_POST['contracttype'] ? (",'".$_POST['contracttype']."'") :"").")";
             mysqli_query($db,$query) or die(mysqli_error($db));
             echo 'Instructor Saved';
             $_SESSION['msg'] = $info1.'The new instructor'.$_POST['ifname']." ".$_POST['ilname']."has been recorded succesfully.".$info2;
         dbconnect(0);
      }
      else {$_SESSION['msg'] = $warn1.'You must fill all required fields (*).'.$warn2;}
      header('Location: '.$referer);
   }

   elseif ($_POST['what'] == 'editinst'){
        $referer = "instructors.php?instructorid=".$_POST['instructorid'];
        $db = dbconnect(1);
        if ($db == -1){
                printf("Connect failed: %s\n\n", mysqli_connect_error());
                exit();
        } //errors occurred @ database side

        //update instructor info if edited
        if ($_POST['editifname']) {
          $query = "UPDATE Instructors SET firstName = '".$_POST['editifname']."' WHERE instructorID = ".$_POST['instructorid'];
          mysqli_query($db,$query) or die(mysqli_error($db));
        }

        if ($_POST['editimname']) {
          $query = "UPDATE Instructors SET middleName = '".$_POST['editimname']."' WHERE instructorID = ".$_POST['instructorid'];
          mysqli_query($db,$query) or die(mysqli_error($db));
        }

        if ($_POST['editilname']) {
          $query = "UPDATE Instructors SET lastName = '".$_POST['editilname']."' WHERE instructorID = ".$_POST['instructorid'];
          mysqli_query($db,$query) or die(mysqli_error($db));
        }

        if ($_POST['editemployeeid']) {
          $query = "UPDATE Instructors SET employeeID = '".$_POST['editemployeeid']."' WHERE instructorID = ".$_POST['instructorid'];
          mysqli_query($db,$query) or die(mysqli_error($db));
        }

        if ($_POST['editdepartment']) {
          $query = "UPDATE Instructors SET departmentID = ".$_POST['editdepartment']." WHERE instructorID = ".$_POST['instructorid'];
          mysqli_query($db,$query) or die(mysqli_error($db));
        }

        if ($_POST['editcontracttype']) {
          $query = "UPDATE Instructors SET contractType = '".$_POST['editcontracttype']."' WHERE instructorID = ".$_POST['instructorid'];
          mysqli_query($db,$query) or die(mysqli_error($db));
        }

        dbconnect(0);
        $_SESSION['msg'] = $warn1.'Edits saved.'.$warn2;
        header('Location: '.$referer);
   }

   //action concerning Subjects.php edits
   elseif ($_POST['what'] == 'editsubj'){
     //no referer because Ajax processed
     $db = dbconnect(1);
         if ($db == -1){
           printf("Connect failed: %s\n\n", mysqli_connect_error());
         exit();
     } //errors occurred @ database side
     $subj = urldecode($_POST['subjectcode']);
     if ($_POST['editdesctitle']) {
        $query = "UPDATE Subjects SET descTitle = '".$_POST['editdesctitle']."' WHERE subjectCode = '".$subj."'";
        mysqli_query($db, $query) or die(mysqli_error($db));
     }
     if ($_POST['editunits']) {
        $query = "UPDATE Subjects SET units = ".$_POST['editunits']." WHERE subjectCode = '".$subj."'";
        mysqli_query($db, $query) or die(mysqli_error($db));
     }
     if ($_POST['editwithlab']) {
        $query = "UPDATE Subjects SET withLab = ".$_POST['editwithlab']." WHERE subjectCode = '".$subj."'";
        mysqli_query($db, $query) or die(mysqli_error($db));
     }
    echo 'Saved';
     dbconnect(0);
   }

   //action concerning admins
   elseif ($_POST['what'] == 'admin'){
      //set referer so we can go back
      $referer = 'adminregister.php';

      //check if having duplicates
      //submit has been pressed
       if ($_POST['newuser'] && $_POST['submit']){
          $newuser = isset($_POST['newuser']) ? $_POST['newuser'] : '';

         //first check database for duplicates
         $db = dbconnect(1);
         if ($db == -1){
           printf("Connect failed: %s\n\n", mysqli_connect_error());
           exit();
           } //errors occurred @ database side

       $query = "SELECT userName FROM ScmsAdmin";
        if ($result = mysqli_query($db, $query)) {
          /* fetch associative array */
          while ($row = mysqli_fetch_row($result)) {
            //printf("%s %s %s", $row[0],$row[1],$row[3]);
             if  (($newuser == $row[0]))  {
                echo $warn1."Your desired username <b>".$newuser."</b> is not registered because it is already taken. Please choose another. <br /><a href=\"adminregister.php\">Try again.</a>".$warn2;
                $dup = 1;
             }
            }
          if ($dup != 1){   //no duplicates
          // Save the new username, hurrah
          $query = "INSERT INTO ScmsAdmin VALUES('".$newuser."','".$_POST['newpass']."','".$_POST['fname']."','".$_POST['mname']."','".$_POST['lname']."','".$_POST['usertype']."')";
          mysqli_query($db, $query) or die(mysqli_error($db));
                echo $info1. 'Your username '.$newuser.' have been registered successfully. You can now log in.'. $info2;
          }
       }
          // free result set
                mysqli_free_result($result);
                dbconnect(0);
            //disconnect from database
        }

        elseif ($_POST['action']== 'edit'){
           $referer = 'account.php';
           $db = dbconnect(1);
              if ($db == -1){
              printf("Connect failed: %s\n\n", mysqli_connect_error());
              exit();
              } //errors occurred @ database side

           if ($_POST['fname']){
           $query = "update ScmsAdmin set firstName='".$_POST['fname']."' where userName='".$_SESSION['user']."'";
           mysqli_query($db, $query) or die(mysqli_error($db));
           }
           if ($_POST['mname']){
           $query = "update ScmsAdmin set middleName='".$_POST['mname']."' where userName='".$_SESSION['user']."'";
           mysqli_query($db, $query) or die(mysqli_error($db));
           }
           if ($_POST['lname']){
           $query = "update ScmsAdmin set lastName='".$_POST['lname']."' where userName='".$_SESSION['user']."'";
           mysqli_query($db, $query) or die(mysqli_error($db));
           }
           if (isset($_POST['editpass']) && isset($_POST['editpasscfm']) && $_POST['editpass'] != ''){  //check if passwords changed
              if ($_POST['editpass'] == $_POST['editpasscfm']){   //check if they match
                $query = "update ScmsAdmin set password='".$_POST['editpass']."' where userName='".$_SESSION['user']."'";
                mysqli_query($db, $query) or die(mysqli_error($db));
                $_SESSION['msg'] = $info1.'New password has been set successfully.'.$info2;
              }  else {
                $_SESSION['msg'] = $warn1."The password confirmation does not match.".$warn2;
              }
           }
           dbconnect(0);
        header('Location: '.$referer);
        }

        else {$_SESSION['msg'] = $warn1.'You must fill all required fields (*).';
            $_SESSION['msg'] .= ($_POST['agree']) ? $warn2 : 'You must also agree to be bound by the Terms of Use.'.$warn2;
            }
   }

      //what to do with saving grades part 1
   else if ($_POST['what'] == 'prelimgrade'){
     $db = dbconnect(1);
              if ($db == -1){
              printf("Connect failed: %s\n\n", mysqli_connect_error());
              exit();
              } //errors occurred @ database side
       $query = "INSERT INTO SubjectsTakenByStudent VALUES('".$_POST['studentid']."','".$_POST['subjectcode']."',2009,".$_POST['sem'].",".$_POST['grade'].",DEFAULT)
       ON DUPLICATE KEY UPDATE subjectCode='".$_POST['subjectcode']."',schoolyear=2009,semNo=".$_POST['sem'].",prelimGrade=".$_POST['grade'].",finalGrade=finalGrade";
       mysqli_query($db, $query) or die(mysqli_error($db));
       echo 'saved';
    dbconnect(0);
   }

   //what to do with saving grades part 2
   else if ($_POST['what'] == 'finalgrade'){
      $db = dbconnect(1);
              if ($db == -1){
              printf("Connect failed: %s\n\n", mysqli_connect_error());
              exit();
              } //errors occurred @ database side
      $query = "INSERT INTO SubjectsTakenByStudent VALUES('".$_POST['studentid']."','".$_POST['subjectcode']."',2009,".$_POST['sem'].",DEFAULT,".$_POST['grade'].")
       ON DUPLICATE KEY UPDATE subjectCode='".$_POST['subjectcode']."',schoolyear=2009,semNo=".$_POST['sem'].",prelimGrade=prelimGrade,finalGrade=".$_POST['grade']."";
       mysqli_query($db, $query) or die(mysqli_error($db));
       echo 'saved';
     dbconnect(0);
       echo 'saved';

   }

}  //end of if $_post
//echo $_SESSION['msg'];

?>