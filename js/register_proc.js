$(function() {
   $('.error').hide();

   $
   $(".sendbtn").click(function() {
     // validate and process form here
     $('.error').hide();
     $('.hold').hide();

       var newuser = $("input#newuser").val();
       if (newuser == "") {
       $("label#newuser_error").show();
       $("input#newuser").focus();
       return false;
       }

       var newpass = $("input#newpass").val();
       if (newpass == "") {
       $("label#newpass_error").show().html("Field is required");
       $("input#newpass").focus();
       return false;
       }else if (newpass.length < 6){
       $("label#newpass_error").show().html("Must not be less than 6 characters");
       $("input#newpass").focus();
       return false;
       }else if (newpass.length > 15){
       $("label#newpass_error").show().html("Must not be more than 15 characters");
       $("input#newpass").focus();
       return false;
       }

       var newpasscfm = $("input#newpasscfm").val();
       if (newpasscfm != newpass) {
       $("label#newpasscfm_error").show().html("Password confirmation does not match");
       $("input#newpasscfm").focus();
       return false;
       }

       var fname = $("input#fname").val();
       if (fname == "") {
       $("label#fname_error").show();
       $("input#fname").focus();
       return false;
       }

       var lname = $("input#lname").val();
       if (lname == "") {
       $("label#lname_error").show();
       $("input#lname").focus();
       return false;
       }

       var usertype = $("input#usertype").val();
       if (usertype == "") {
       $("label#usertype_error").show();
       $("input#usertype").focus();
       return false;
       }

       var agree = $("input#agree").attr('checked');
       if (!(agree)) {
       $("label#agree_error").show();
       $("input#agree").focus();
       return false;
       }

       var datastring = 'what=';

$('#register form input').each(function(e){
  var inputname = $(this).attr("name");
  var inputvalue = $(this).val();
  datastring += '&' + inputname +'='+ inputvalue;
});
$('#register form select').each(function(e){
  var inputname = $(this).attr("name");
  var inputvalue = $(this).val();
  datastring += '&' + inputname +'='+ inputvalue;
});

 $.ajax({
   type: "POST",
   url: "save.php",
   data: datastring,
   success: function(responseText,err) {
   // alert(responseText+err);
     $('#register').fadeOut(200).html("<div id='responsemsg'></div>").fadeIn(1000);
     $('#responsemsg').html("<h4>Registration Form has been submitted.</h4>")
     .append(responseText)
     .hide()
     .fadeIn(2000);
   }
 });
    return false;
   });



});