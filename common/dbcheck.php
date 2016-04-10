<?php
//database check
require("lib/config.php");
if ($dbsettings['scmsDbHost'] && $dbsettings['scmsDbUser']){
    $db = dbconnect(1);
    if ($db == -1) {
    ?>
  <script language="JavaScript" type="text/javascript">
    /*<![CDATA[*/
    jQuery(function($) {
    $(".popup").fadeIn().show();

    $(".popup form input[type=submit]").click(function(){
      if ($("input[name=dbhost]").val() == ''){
         $("input[name=dbhost]").focus();
         return false;
      }
      if ($("input[name=dbusername]").val() == ''){
         $("input[name=dbusername]").focus();
         return false;
      }

      var datastring = $(".popup form").serialize();
//alert(datastring);return false;
      $('.popup form input[type=submit]').html('<img src="images/ajax-loader2.gif" alt="Loading" />');

       $.ajax({
       type: "POST",
       url: "connection.php",
       data: datastring,
      success: function(responseText,err) {
         $('.popup form').html(responseText);
         $('.popup form input[type=submit]').html("Save");
       }
       });
       return false;
    });
    });
    /*]]>*/
    </script>

    <?php
      $showDB = showDBLogin($dbsettings,0);
    }

    dbconnect(0);
}
else {
  ?>

  <script language="JavaScript" type="text/javascript">
    /*<![CDATA[*/
    jQuery(function($) {
    $(".popup").fadeIn().show();

    $(".popup form input[type=submit]").click(function(){
      if ($("input[name=dbhost]").val() == ''){
         $("input[name=dbhost]").focus();
         return false;
      }
      if ($("input[name=dbusername]").val() == ''){
         $("input[name=dbusername]").focus();
         return false;
      }

      var datastring = $(".popup form").serialize();
//alert(datastring);return false;
      $('.popup form input[type=submit]').html('<img src="images/ajax-loader2.gif" alt="Loading" />');

       $.ajax({
       type: "POST",
       url: "connection.php",
       data: datastring,
      success: function(responseText,err) {
         $('.popup form').html(responseText);
         $('.popup form input[type=submit]').html("Save");
       }
       });
       return false;
    });
    });
    /*]]>*/
    </script>
  <?php
  $showDB = showDBLogin($dbsettings,1);
}



?>