jQuery(function($) {
  $val = $(this).val();
  $me = $(this);

    $("#withhead").bind("change",function(){
      if ((this.checked)){
        $("textarea.reporthead").slideDown();
      }else {
      $("textarea.reporthead").slideUp();
      }
    });

    $("#withfoot").bind("change",function(){
      if ((this.checked)){
        $("textarea.reportfoot").slideDown();
      }else {
      $("textarea.reportfoot").slideUp();
      }
    });

    $("#withlogo").bind("change",function(){
      if ((this.checked)){
        $(".logoimg").slideDown();
        $(".reporthead").css({'top':'-100px'});
      }else {
        $(".logoimg").slideUp();
        $(".reporthead").css({'top':'0px'});
      }
    });


    $("#heightx").bind("change",function(){
        $("#outside").css({
          "height": $val +"px"
        })
    });
    $("#widthx").bind("change",function(){
        $("#outside").css({
          "width": $val +"px"
        })
    });

    $("#update").bind("click",function(){
      $("#withhead").trigger("change");
      $("#withfoot").trigger("change");

      var datastring = $("#dataform").serialize();
//alert(datastring);return false;
      $('#update').html('<img src="images/ajax-loader2.gif" alt="Loading" />');

       $.ajax({
       type: "POST",
       url: "reportajax.php",
       data: datastring,
      success: function(responseText,err) {
         $('#ajaxcontent').html(responseText);
         $('#update').html("Update preview");
       }
       });
       return false;

    });


});