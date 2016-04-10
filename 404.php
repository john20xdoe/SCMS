<?php require("common/doctype-head.php"); ?>
	<?php require("common/nav-and-title.php"); ?>

<script type="text/javascript">
jQuery(function($) {
  var x = window.location.href;
  var y = x.split("/");
  var z = y.length;
    $("#falseurl").text("\""+y[z-1]+"\"");
});
</script>
    <h2>Ooops! Are you lost?</h2>

    <p><img width="160px" src="images/404.gif" alt="page not found" />The page you followed <b><span id="falseurl"></span></b> doesn't exist.<br />
    </p>

    <p>You might want to:<br />
     &nbsp;&nbsp;&bull; go back to <a href="javascript:history.back(-1)">where you came from</a>, or<br />
     &nbsp;&nbsp;&bull; go back to Square One at the <a href="index.php">Welcome page</a>, or<br />
     &nbsp;&nbsp;&bull; visit the <a href="help.php">Help section</a>.</p>
<div class="clr"></div>

<?php include_once("common/footer.php"); ?>

