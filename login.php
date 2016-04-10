<?php
// The login script -- First check if the user has requested to log-off
session_cache_limiter('private_no_expire');
$cache_limiter = session_cache_limiter();
$error = '';
$data = '';
session_start();
require("lib/functions.php");
if (isset($_GET['logoff'])) {
    // We need to completely destroy the session.  First the data:
    $_SESSION = array();

    //close the mysql database connection, if any:
    dbconnect(0);

    // If a session cookie exists, tell the browser to destroy it
    //  (give it a time in the past)
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time()-1000, '/');
    }

    // Finally, finalize the session destruction:
    session_destroy();
    $error = '<span class="ok">You have successfully logged out.</span>';
}
// Secondly, see if the user is already logged in.
elseif (isset($_SESSION['valid']) && $_SESSION['valid']) {
    // show logout button
    $data = '<span>You are logged in. <a class="button" href="?logoff=1">Log out <b>'.$_SESSION['user'].'</b></a></span>';
}
// 3rd, see if they attempted to log in:
elseif (isset($_POST['user']) || isset($_POST['pass'])) {
    // save the login form input into variables
    $user = isset($_POST['user']) ? $_POST['user'] : '';
    $pass = isset($_POST['pass']) ? $_POST['pass'] : '';

    //query the database for registered users then match them
    $db = dbconnect(1);
    if ($db == -1){
      printf("Connect failed: %s\n\n Cannot log you in", mysqli_connect_error());
    }
     $query = "SELECT userName, password, userTypeID FROM ScmsAdmin";
        if ($result = mysqli_query($db, $query)) {
           /* fetch associative array */
          while ($row = mysqli_fetch_row($result)) {
          //printf ("%s (%s)\n", $row[0], $row[1]);
            if  ( ($user == $row[0]) && ($pass == $row[1]) )  {
                // user/pass matches, store this fact in the session
                $_SESSION['valid'] = 1;
                $_SESSION['user'] = $user;
                $_SESSION['usertype'] = $row[2];
                $data =  '<span>You are logged in. <a class="button" href="?logoff=1">Log out <b>'.$_SESSION['user'].'</b></a></span>';
                $error = 1;   //tag $error that one username matched.
            }
          }
          $error = $error ? '<span class="ok">Login successful.</span>' : '<span class="error">Incorrect username or password. Please try again.</span.';
          // free result set
                mysqli_free_result($result);
                dbconnect(0);
                unset($db);
                 //disconnect from database
        } else $error = 'Your MySQL server is not responding.';
}
// Otherwise, let's ask them to log in: show the form

if (!(isset($_SESSION['valid']) && $_SESSION['valid'])){  //make sure to show only if not logged in
$data =  '<form action="index.php" method="post">'
.'<input name="user" maxlength="21" type="text" onblur="if(this.value.length==0) this.value=\'Username\';" onfocus="if(this.value == \'Username\') this.value=\'\';" onclick="if(this.value == \'Username\') this.value=\'\';" value="Username" />'
.'<input name="pass" onclick="if(this.value == \'password\') this.value=\'\';" onfocus="if(this.value == \'password\') this.value=\'\';" type="password" value="password" />'
.'<input type="submit" class="button" value="Login">'
.'</form>';
}
if ($error) { $data .= "<span>{$error}</span>"; }
?>