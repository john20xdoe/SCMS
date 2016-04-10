<?php require("common/doctype-head.php"); ?>
	<?php require("common/nav-and-title.php");
    require("common/logcheck.php");?>


        <?php
    if (($_GET['success'] == 1) && ($_SESSION['success'] == 1)){
        $_SESSION['success'] = (isset($_SESSION['success'])) ? $_SESSION['success'] : '';
        if ($_SESSION['msg']) { echo $_SESSION['msg'];}
        //then clear the message variable
        $_SESSION['msg'] ='';

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
    }
    else {
    ?>

    <h2>Are You Sure<?php echo ", <b>".$_SESSION['user']."</b>"; ?>?</h2>
        <?php
        $_SESSION['success'] = (isset($_SESSION['success'])) ? $_SESSION['success'] : '';
        if ($_SESSION['msg']) { echo $_SESSION['msg'];}
        //then clear the message variable
        $_SESSION['msg'] ='';
        ?>
		<p class="calloutwarn">This operation <b>cannot</b> be undone.<br />
        Deleting your account will not affect the data about students, courses, curriculums, subjects, and instructors that you have entered, but <b>your login and all of your personal
		information will be permanently deleted.</b>
        </p>

    <form action="delete.php" method="post">
			<div>
				<input type="hidden" name="what"
					value="deleteaccount" />
                <label for="delpass">Enter your password:</label>
                <input type="password" id="delpass" name="delpass" value="" /><br />
				<input type="submit" value="I'm sure, delete my account" />
   			</div>
		</form>
    <?php } ?>
<?php
	include_once "common/footer.php";
?>