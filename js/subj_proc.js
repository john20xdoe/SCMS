jQuery(function($) {

    $("#editsubj").change(function(){
       $("#editsubjsubmit").removeAttr("disabled").val("Save");
    });

    $("#editsubjsubmit").click(function(){
    $(this).val("Saving").attr("disabled","disabled");
    var datastring = $("#editsubj").serialize();
     $.ajax({
        type: "POST",
        url: "save.php",
        data: datastring,
        success: function(responseText,err) {
           $("#editsubjsubmit").val(responseText);
        }
     });
    return false;
    });

});