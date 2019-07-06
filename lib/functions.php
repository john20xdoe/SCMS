<?php

//collection of functions
function dbconnect($status){
    //include the database username password and hostname
    require("lib/config.php");
    global $db;

    //value passed is 1, meaning connect to db
    if ($status) {
      //connecting, procedural style
      $dblink = mysqli_connect($dbsettings['scmsDbHost'],$dbsettings['scmsDbUser'], $dbsettings['scmsDbPassword']);

      // check connection
      if (!$dblink) {return -1;} //error
      else {return $dblink;} //no errors, returns the link object
    }

    //value passed is 0, meaning disconnect from db
    else {
      //have to check if there is actually a db link, and close it
      if ($dblink) {mysqli_close($dblink);}
      if (isset($db)) {mysqli_close($db);}
    }
}

function conv2text($num){
   //function to convert a number into its ordinal value (e.g. 1 is 'first', 2 is 'second', etc)
  switch ($num) {
    case 1: {return 'First';break;}
    case 2: {return 'Second';break;}
    case 3: {return 'Third';break;}
    case 4: {return 'Fourth';break;}
    case 5: {return 'Fifth';break;}
    // you can as many numbers as you want, but i'll use 5 only, the max number of year levels
  }
}

function showDBLogin($settings,$isFirstTime){
    if ($isFirstTime){
      //first use, ask for login credentials
      ?>
        <div class="popup">

            <form action="" method="POST">
            <h2>First-Use Setup</h2>
            <p class="calloutwarn">This looks like the first time you're using SCMS.</p>
            <p>If your database administrator have not yet set up your database, please download the starting <b>SQL file </b><a href="database/setup.sql">here</a> and run the queries in your MySQL server console.</p>
            <p>After you finished building the SCMS database, please provide SCMS's MySQL server login credentials.</p>
    <p><strong>MySQL hostname:</strong><br/>
    <input maxlength="50" type="text" name="dbhost" value=""></p>
    <p><strong>MySQL username:</strong><br/>
    <input maxlength="50" type="text" name="dbusername" value=""></input></p>
    <p><strong>MySQL password</strong><br/>
    <input type="password" name="dbpassword" value=""></input></p>
    <p><strong>MySQL db name (if provisioned)</strong><br/>
    <input type="text" name="db" value=""></input></p>
    <p><input type="submit" value="Save"/></p> <br /><br />
        <a href="help.php">Help</a>
    </form>
        </div>
      <?php

    }
    else {
      //appears to be having errors in connection
      ?>
      <div class="popup">

      <?php

      // clear dbsettings because cannot connect
    $dbsettings = array(
      "scmsDbHost" => "",
      "scmsDbUser" => "",
      "scmsDbPassword" => "",
      "scmsDb" => ""
    );

    // Now save it to disk, create the 'full' file wrapping it in valid PHP
    $file = "<?php\n\$dbsettings = " . var_export($dbsettings, true) . ";\n?>\n";
    file_put_contents('lib/config.php', $file, LOCK_EX);

      echo '<form action="'.$_SERVER["PHP_SELF"].'" method="POST">
      <h2>Database Setup</h2>
            <p class="calloutwarn">
      The SCMS database cannot be reached. '.mysqli_connect_error().'</p>
    <p><strong>MySQL hostname:</strong><br/>
    <input maxlength="50" type="text" name="dbhost" value="'.$dbsettings['scmsDbHost'].'"></p>
    <p><strong>MySQL username:</strong><br/>
    <input type="text" maxlength="50" name="dbusername" value="'.$dbsettings['scmsDbUser'].'"></input></p>
    <p><strong>MySQL password</strong><br/>
    <input type="password" name="dbpassword" value="'.$dbsettings['scmsDbPassword'].'"></input></p>
    <p><strong>MySQL db name (if provisioned)</strong><br/>
    <input type="text" name="db" value="'.$dbsettings['scmsDb'].'"></input></p>
    <p><input type="submit" value="Save"/></p> <br /><br />
    <a href="help.php">Help</a>
    </form>';

    ?>
      </div>
      <?php
    }

}

//Variables Definition
$info1 = '<p class="calloutinfo">';
$info2= '</p>';
$warn1='<p class="calloutwarn">';
$warn2 = '</p>';
?>
