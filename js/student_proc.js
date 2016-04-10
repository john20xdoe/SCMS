var response ='';
var oldgradetemp;
jQuery(function($) {

   //select the first tab as default
   $('#explore-nav li:first-child a').addClass('current');
   var leapyear = 0;

   $('.tblForm input[type!=submit]').focus(function(){
      $(this).parent().parent().css({background: "#ececec"});
   });
   $('.tblForm input').blur(function(){
      $(this).parent().parent().css({background: "transparent"});
   });
   $('.tblForm select').focus(function(){
      $(this).parent().parent().css({background: "#ececec"});
   });
   $('.tblForm select').blur(function(){
      $(this).parent().parent().css({background: "transparent"});
   });
   $('.tblForm textarea').focus(function(){
      $(this).parent().parent().css({background: "#ececec"});
   });
   $('.tblForm textarea').blur(function(){
      $(this).parent().parent().css({background: "transparent"});
   });

   $('#year').change(function(){
       if (($('#year').val())%4 == 0){
          leapyear = 1;
       }
       else {leapyear = 0};
   });

   $('#month').change(function(){
     $('#day').val(1);
     var mon = $(this).val();
         $('#day').children('[value="29"]').show();
         $('#day').children('[value="30"]').show();
         $('#day').children('[value="31"]').show();
      if ((mon == 2)){
         if (leapyear == 0){
         $('#day').children('[value="29"]').hide();
         }
         $('#day').children('[value="30"]').hide();
         $('#day').children('[value="31"]').hide();
      }
      else if ((mon == 9) || (mon == 4) || (mon == 6) || (mon == 11)){
         $('#day').children('[value="30"]').show();
         $('#day').children('[value="31"]').hide();
      }
   });

   $('#all-div-wrap div form input').change(function(){
      var thisform = $(this).parent().parent().parent().parent().parent().attr('id');
      $('#'+thisform + ' input[type="submit"]').val("Save edits");
   });

   $('#all-div-wrap div form textarea').change(function(){
      var thisform = $(this).parent().parent().parent().parent().parent().attr('id');
      $('#'+thisform + ' input[type="submit"]').val("Save edits");
   });
   $('#all-div-wrap div form select').change(function(){
      var thisform = $(this).parent().parent().parent().parent().parent().attr('id');
      $('#'+thisform + ' input[type="submit"]').val("Save edits");
   });

   $("#addreset").live("click",function(){
      $("#tab1 input[type=reset]").trigger("click");
      $("#tab2 input[type=reset]").trigger("click");
      $("#tab3 input[type=reset]").trigger("click");
      $("#tab4 input[type=reset]").trigger("click");
      return false;
   });

    $('.saveinfo').click(function(){
      var thispage = $(this).parent().parent().parent().parent().parent().parent().attr('id');
      var thisform = $(this).parent().parent().parent().parent().parent().attr('id');
      var tab ='';
//       alert(thispage+thisform);

      if (thispage == 'page2'){
       tab = 'personal';
      }
      else if (thispage == 'page3'){
       tab = 'contact';
      }
      else if (thispage == 'page4'){
       tab = 'curr';
      }
      else {
        tab = 'basic';
        thisform = 'page1';
      }
      var datastring = 'what=stud&tab='+tab;
          $('#'+thisform + ' input').each(function(e){
            var inputname = $(this).attr("name");
            var inputvalue = $(this).val();
            datastring += '&' + inputname +'='+ encodeURIComponent(inputvalue);
          });
          $('#'+thisform + ' select').each(function(e){
            var inputname = $(this).attr("name");
            var inputvalue = $(this).val();
            datastring += '&' + inputname +'='+ encodeURIComponent(inputvalue);
          });
          $('#'+thisform + ' textarea').each(function(e){
            var inputname = $(this).attr("name");
            var inputvalue = $(this).val();
            datastring += '&' + inputname +'='+ encodeURIComponent(inputvalue);
          });
     //alert(datastring); return false;
           $.ajax({
             type: "POST",
             url: "save.php",
             data: datastring,
             success: function(responseText) {
              $('#'+thisform + ' input[type="submit"]').val(responseText);
               response = responseText;
             }
           });
      return false;
   });


   //processing for saving prelim and final grades

   //function for Ajax way of saving grades
   function sendToServer(what,studentid,year,sem,subjectcode,grade,field){
     var gradedata= "";
      gradedata = "what=" +what+ "&studentid=" +studentid+ "&year="+year+ "&sem="+sem+ "&subjectcode="+subjectcode+"&grade="+grade;
   //   alert(gradedata);
     $.ajax({
             type: "POST",
             url: "save.php",
             data: gradedata,
             success: function(responseText) {
                //what to do with responseText
                field.css("background-color","#006699").fadeOut(1).fadeIn().delay(1000).css("background-color","transparent");
             }
     });
   }

   //trim all grade inputs
/*   $('.gradeAutoSave').change(function(){
     $(this).val();
   });*/

   //keep old value in limbo
   $('.gradeAutoSave').focus(function(){
     oldgradetemp = $(this).val();
     $(this).val('');
   });

   //save grade to server on loss of focus
   $('.gradeAutoSave').blur(function(){
      if ($(this).val() == '' ){
        // if empty value, revert to previous value
        $(this).val(oldgradetemp);
      }
      else {   //continue to sending
        //first, check range
        if (( ($(this).val() > 5.0) || ($(this).val() < 0.0) )) {
           $(this).val(oldgradetemp);
        } //if okay, prepare for sending
        else {
          //gather data string
            //temp vars
          var dwhat = '';
          var dstudentid = '';
          var dyear = '';
          var dsem = '';
          var dsubjectcode = '';
          var dgrade = '';

             //year,sem numbers, and whether grade is prelim or final("what") are already in the input's name =)
             var tmp = $(this).attr('name');
             if (tmp[0] == 'p') {
                dwhat = 'prelimgrade';
             //get what subject this is --- traverse to the 4th previous td containing the subject code
             dsubjectcode = $(this).parent().prev().prev().prev().prev().text();
             }
             else if (tmp[0] == 'f') {
               dwhat = 'finalgrade';
             //get what subject this is --- traverse to the 5th previous td containing the subject code
             dsubjectcode = $(this).parent().prev().prev().prev().prev().prev().text();

             }

             dyear = tmp[1];
             dsem = tmp[2];

             //get studentid --- hidden as the rel of a span somewhere with id 'dstudentid' =)
             dstudentid = $('#dstudentid').attr('rel');

             //finally the grade itself
             dgrade = $(this).val();

          //now send to server
          sendToServer(dwhat,dstudentid,dyear,dsem,dsubjectcode,dgrade,$(this));
        }
      }

      oldgradetemp = ' ';          //reset oldgrade temp variable
   });



}); //end document ready