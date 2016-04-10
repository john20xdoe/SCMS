<?php
require("common/doctype-head.php");
 require("common/nav-and-title.php");
    require("common/dbcheck.php");
    ?>

    <?php
        echo $showdb;
     if (!(isset($_SESSION['valid']) && $_SESSION['valid'])) {?>
    <h2>Login Required</h2>
    <p><img src="images/ccs_logo_index_bw.png" alt="CCS" />You need to login to access the <b>Student Curriculum Management System</b> of the College of Computer Studies.</p>
    <p>If you do not have an account, you can register <a href="adminregister.php" title="Register">here</a>.</p>
    <?php } else {?>
    <h2>Welcome to CCS-SCMS</h2>
    <p><img src="images/ccs_logo_index.png" alt="CCS" />You are now logged in the <b>Student Curriculum Management System</b> of the College of Computer Studies of the University of Antique.</p>
  <ul>    You can now register information about:
  <li>program courses,</li>
  <li>curriculums and the subjects they cover,</li>
  <li>instructors, and</li>
  <li>students and their grades.</li>
  </ul><br />
    <p>Features can be accessed at the top menu. Their sub-features can be found at the side menu after you accessed a feature.</p>
    <p>For more information about using SCMS, please check out its <a href="documentation.php"><b>Documentation</b></a> which can also be found inside the Help feature.</p>
    <?php }

    ?>





<?php include_once("common/footer.php"); ?>

