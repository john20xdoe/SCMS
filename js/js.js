jQuery(function($) {

      // This helps show the content only when it's all loaded
              // Show the wrapper that contains the content
              $('#inside-content').fadeIn(1000,function (){
                $('#ajax-loader').fadeOut(1000);
           });

//        $('#explore-nav li:first-child a').addClass('current');

   $("#ajax-loader").ajaxStart(function(){
    $(this).fadeIn(2000);
   });

   $("#ajax-loader").ajaxStop(function(){
    $(this).fadeOut(2000);
   });

   $('input[type=text]:not(.gradeAutoSave)').keyup(function(){
     var siz = $(this).val().length;
     if (siz >= $(this).attr("maxlength")){
        siz = $(this).attr("maxlength");
     }
     $(this).attr({size: siz++})
   });

   $('input[type=text]:not(.gradeAutoSave)').bind("paste", function(){$(this).keyup();event.stopPropagation();})

});































