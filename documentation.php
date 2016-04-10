<?php require("common/doctype-head.php"); ?>
	<?php require("common/nav-and-title.php");
   ?>

    <h2>Documentation</h2>

    <p>Contents:  <br />
     <a href="#inst">Installation</a>  <br />
     <a href="#start">Starting out</a> <br />
     <a href="#trouble">Troubleshooting</a><br />
</p>

    <a name="inst"></a>
    <fieldset>
    <legend>Installation</legend>
    <p><b>Note</b>: You must have an account to a dedicated MySQL database server. Please refer to your database administrator for setting up a MySQL account.</p>

    <p><b>Step 1</b>: Extract the contents of the <b>SCMS .zip file</b> and upload the files to your web server. </p>
    <p><b>Step 2</b>: Open the site in your Javascript-enabled web browser. You should see a modal box asking for your MySQL account credentials.
    Before entering anything, download the <b>setup.sql file</b> and run the queries in your phpMyAdmin or MySQL server SQL console.</p>
    <p><b>Step 3</b>: Once you have successfully run the queries, enter the MySQL credentials into SCMS to link your database with SCMS. SCMS will use that info to manage your data with the MySQL server,
    so please make sure that that account has the proper privileges.</p>
    <p><b>Step 4</b>: When you have successfully set up the database link, you will be redirected to the Home page. You can now register for a username.</p>
    </fieldset>

    <a name="start"></a>
    <fieldset>
    <legend>Starting Out</legend>
    <p>When you have registered for a username, you can log in using the login panel <b>at the top of the page.</b></p>
    <p>You can now start entering records. Click <b>Records</b> at the top menu.</p>
    <blockquote>
    <b>Note:</b><br />In entering records, you must first enter some <b>courses</b> before you can enter any other data.
    If you wish to enter curriculums, you must first have some courses. If you wish to add subjects, first add a curriculum.
    Once you have entered information on these sections, you can now enter instructor and student records.
    </blockquote>
    <p>You can edit your information and database link settings in the <b>Account</b> menu.</p>
    </fieldset>

    <a name="trouble"></a>
    <fieldset>
    <legend>Troubleshooting</legend>
    <p>In case of errors, problems, or support, you can email at: <a href="mailto:sampleccsmail@antiquespride.edu.ph">CCS Mail</a>.</p>


    </fieldset>



<?php include_once("common/footer.php"); ?>

