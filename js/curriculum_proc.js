jQuery(function($) {

//defines
   $('.input').parent('td').click(function(){
     $(this).children('.input').focus();
   });

   var currid = $('#currid').attr('rel');

//functions
   function subjectsNum(yearid) {
     return $('#page'+yearid+ ' .subject-row').length;
   }


//specials for new currs
    $('#newcoursemajor').change(function(){
      if ($('#newcoursemajor').val() == '#'){
        var newmajor = prompt("Add a new course major in: (e.g. \"Database Administration\")", "");
        if (newmajor){
          $('#newcoursemajor > option[value="#"]').before('<option value="'+newmajor+'">'+newmajor+'</option>');
          $('#newcoursemajor > option[value="'+newmajor+'"]').attr("selected","selected");
        }
       else $('#newcoursemajor > option[value=""]').attr("selected","selected");
      }
    });

    $('#newcurrsubmt').click(function(){
      if (($("#curryear").val()=='') || ($("#currsem").val()=='')||($("#years").val()=='')||($("#course").val()=='') || ($("newcoursemajor").val()=='#')){
        alert("Please fill required fields. (marked with *)");
        return false;
      }
    });


//specials for editing
   $('.input').live('change',function(){
      $(this).parent().parent().addClass('edited');
   });

   $("#reqselect").live("blur",function(){
      $("#reqselect").hide();
   });

   $("#reqselect").change(function(){
     var $textarea = $(this).siblings('.input');
     $textarea.val($(this).val());
     $textarea.parent().parent().addClass('edited');
   });

   $('textarea[rel=prereqstring]').live('focusin',function(){
      var $thistd = $(this).parent();
      var $me = $(this);
      var subj = $thistd.parent().children().children('input[rel="subjectcode"]').val();


      //collect all subjects in this curriculum
      var allsubj = new Array;
      $('.input[rel="subjectcode"]').each(function(e){
         allsubj[e] = $(this).val()+ "";
      });

      //make option string
      var optstring = '';
      $.each( allsubj, function(index, value){
       if (value){
         if (value != subj){
         optstring += '<option value="'+value+'">'+value+'</option>';
         }
       }
      });

      optstring += '<option value="">None, remove all</option>';

      $('#reqselect').html(optstring);
      if ($(this).siblings().is('#reqselect')){
      }
      else {
      $('#reqselect').appendTo($thistd).slideDown(100).show();
      }
      return false;
   });

   $(".saveyear").live('click',function(){
       var thisid = $(this).parent().parent().attr("rel");      //also this year's lvl

         //save by subject
         var datastring = '';
         datastring = 'what=subject&currid='+currid+ "&yearlvl=" + thisid;

         $('#page'+thisid+ ' .subject-row.edited .input').each(function(i){

            var $thisparent = $(this).parent();

            var value = encodeURIComponent($(this).val());      //escape characters to preserve symbols
            if (value == ''){
              if($(this).attr("rel") != "prereqstring"){
                $(this).parent().parent().css({background: '#FFEA97'});
                return false;         //stop this function
              }
            }
            $(this).parent().parent().css({background: '#fff'});
              datastring += "&"+ $(this).attr('rel')+ "=" + value;
            if ($(this).attr('rel') == 'prereqstring'){     //means end of one subject row

            //get sem number for this subject
              var semno = $(this).parent().parent().parent().parent().attr('rel');      //table rel attribute, where we secretly saved the sem # with PHP
              datastring += "&semno=" + semno;

            //send ajax request
            $.ajax({
                type: "POST",
                url: "save.php",
                data: datastring,
                success: function(responseText,err) {
                   $("<p>"+responseText+"</p>").appendTo($thisparent.next());
                   setTimeout(function(){
                   $thisparent.next().children().next().fadeOut(1000);
                   $thisparent.parent().removeClass('edited');
                  $thisparent.next().children().next().remove();
                   },3000);
//                  alert(responseText);
                }
              });

              //then reset datastring
              datastring = 'what=subject&currid='+currid+ "&yearlvl=" + thisid;
            }
         }); //end each
    });


   $(".delete").live('click',function(){
    var $thisRow = $(this).parent().parent().parent();
    var semno = $(this).parent().parent().parent().parent().parent().attr("rel");
    var thisid = $(this).parent().parent().parent().parent().parent().parent().parent().attr("rel"); //year

    var datastring = '';
    datastring = 'what=deletesubject&currid='+currid+ "&yearlvl=" + thisid + "&semno=" +semno;

    $thisRow.children('td').children('.input').each(function(a){

          //if not having rel, it means it's not a member of .input class
        var value = encodeURIComponent($(this).val());      //escape characters to preserve symbols
        if (value == ''){
          if($(this).attr("rel") != "prereqstring"){
            $thisRow.fadeOut(1000, function(){
            $thisRow.removeClass('.subject-row').remove();
            });
            return false;         //stop this function
          }
        }
        datastring += "&"+ $(this).attr('rel')+ "=" + value;

        if ($(this).attr('rel') == 'prereqstring'){    //end of row, so stop to send the data

         //send code here
         var y = confirm("Are you sure you want to remove this subject? \n\n This cannot be undone.");
         if (y){
           $.ajax({
                  type: "POST",
                  url: "delete.php",
                  data: datastring,
                  success: function(responseText,err) {
                    $thisRow.fadeOut(1000, function(){
                    $thisRow.removeClass('.subject-row').remove();
                    });
                  }
           });
         }

         //reset datastring
         datastring = 'what=deletesubject&currid='+currid+ "&yearlvl=" + thisid + "&semno=" +semno;
      }
    });      //end each
   });


    $(".deletesummer").live('click',function(){            //**continue
    var $thistbl = $(this).parent().parent().parent();
    var y = confirm("Are you sure you want to remove this level's Summer Semester? \n\nAny subject data you have entered here will be lost.");
    if (y){
       var yearlvl = $(this).parent().parent().parent().parent().parent().attr('rel');
       var datastring = 'what=deletesummer&currid='+currid+'&yearlvl='+yearlvl;
       var tblheight = $thistbl.height();
      //send ajax request
      $.ajax({
          type: "POST",
          url: "delete.php",
          data: datastring,
          success: function(responseText,err) {
            $thistbl.fadeOut(1000, function(){
            $thistbl.children().removeClass('.subject-row').remove();
            var addsummerbtn = '<span class="delwrap"><a class="addsummer" href="javascript:;" title="Add a Summer Semester">+ Add table for a Summer Sem</a></span>';
            $thistbl.before(addsummerbtn);
            $thistbl.remove();
            });
            //alert(responseText+err);
          }
        });
       // return false;
     }
    });

   $(".addfield").live('click',function(){
       var $thisRow = $(this).parent().parent();
   // $thisRow.hide();
       var newField = '<tr class="subject-row">' +
                  '<td><input class="input" rel="subjectcode" type="text" size="9" value="" /></td>' +  //Subject code
                  '<td><textarea class="input" rel="desctitle"></textarea></td>' +   //desc title
                 '<td><select class="input" rel="withlab"><option selected="selected" value="0">No</option><option value="1">Yes</option></select></td>' +   //with lab
                 '<td><input class="input" rel="units" type="text" size="1" maxlength="2" value="0" /></td>' +   //units
                 '<td><textarea class="input" cols="17" rel="prereqstring"></textarea><br /></td>' +
                  '<td><span class="delwrap"><a class="delete" href="javascript:;" title="Remove subject"> Erase</a></span> </td>' +         //prerequisites
                 '</td></tr>';

       $thisRow.before(newField);
       $thisRow.prev().fadeOut(1).fadeIn(1000);
                // Adjust outer wrapper to fit new list snuggly
                var newHeight = $(this).parent().parent().parent().parent().parent().parent().height() + 20; //20 because we added a 10px padding in the css file
                $("#all-div-wrap").animate({
                    height: newHeight
                });
   });

   $(".addsummer").live('click',function(){
       $(this).fadeOut(300);
       var $pageDiv = $(this).parent().parent().parent();
       var $year = $pageDiv.attr("rel");
       var Summer = "<table class=\"tblSem\" id=\"year" + $year + "sem3\" rel=\"3\"><caption>Summer Semester <span class=\"delwrap\" style=\"float:right;\"> <a class=\"deletesummer\" href=\"javascript:;\" title=\"Remove table\">&nbsp;<b>x</b>&nbsp;</a></span></caption><tr class=\"titlerow\"> <td><b>Subject Code</b></td> <td><b>Subject Title</b></td>  <td><b>With Lab</b></td>   <td><b>Credit<br />Units</b></td> <td><b>Requisites</b></td> <td><b>Options</b></td></tr><tr><td colspan=\"6\" align=\"right\"><a class=\"addfield\" title=\"Add a subject under this semester\" href=\"javascript:;\">+ Add a subject</a></td></tr></table>";
       $(this).parent().before($(Summer));
       // Adjust outer wrapper to fit new list snuggly
          var newHeight = $(this).parent().parent().height() + 20; //20 because we added a 10px padding in the css file
          $("#all-div-wrap").animate({
                 height: newHeight
          });
       $(this).remove();
   });

   $('#savecurr').live('click',function() {
     var $thisform = $('.activatecurr');
     var isready = 0;
     if ($thisform.children('#isready1').attr('checked')) {
        isready = 1;
       var y = confirm('Are you sure you want to activate this curriculum? \n\nYou will not be able to modify this curriculum once it\'s activated.');
       if ((y != 1)) {return false;}
     }

      //save by subject
         var datastring = '';
         datastring = 'what=subject&currid='+currid;

      $('.subject-row.edited .input').each(function(i){

            var $thisrow = $(this).parent();
            var value = encodeURIComponent($(this).val());      //escape characters to preserve symbols
            if (value == ''){
              if($(this).attr("rel") != "prereqstring"){
                $(this).parent().parent().css({background: '#FFEA97'});
                return false;         //stop this function
              }
            }
            $(this).parent().parent().css({background: '#fff'});
              datastring += "&"+ $(this).attr('rel')+ "=" + value;

            if ($(this).attr('rel') == 'prereqstring'){     //means end of one subject row
              var thisyear = $thisrow.parent().parent().parent().parent().parent().attr('rel');
              var semno = $(this).parent().parent().parent().parent().attr('rel');      //table rel attribute, where we secretly saved the sem # with PHP
              datastring += "&semno=" + semno + "&yearlvl=" + thisyear;

            //send ajax request
            $.ajax({
                type: "POST",
                url: "save.php",
                data: datastring,
                success: function(responseText,err) {
                   $("<p>"+responseText+"</p>").appendTo($thisrow.next());
                   setTimeout(function(){
                   $thisrow.next().children().next().fadeOut(1000);
                   $thisrow.parent().removeClass('edited');
                   $thisrow.next().children().next().remove();
                   },3000);
                }
              });

              //then reset datastring
              datastring = 'what=subject&currid='+currid;
            }
         }); //end each
         if (isready){
            var thisurl = window.location.href;
            $.post("save.php", { what: "savecurr", isready: isready, currid: currid },
             function(data){
                if (data){
//              $thisform.html('<p class="calloutinfo">'+data+'</p>');}
//              else {
window.location = thisurl;}
            });
         }
   });

}); //end document ready