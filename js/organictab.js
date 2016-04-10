$(function() {


    $("#explore-nav li a").click(function() {
        
        // Figure out current list via CSS class
        var curDiv = $("#explore-nav li a.current").attr("rel");
        
        // List moving to
        var $newDiv = $(this);
        
        // Set outer wrapper height to height of current inner list
        var curDivHeight = $("#all-div-wrap").height();
        $("#all-div-wrap").height(curDivHeight);
        
        // Remove highlighting - Add to just-clicked tab
        $("#explore-nav li a").removeClass("current");
        $newDiv.addClass("current");
        
        // Figure out ID of new div
        var divID = $newDiv.attr("rel");

        if (divID != curDiv) {
            
            // Fade out current div
            $("#"+curDiv).fadeOut(300, function() {

                // Fade in new list on callback
                $("#"+divID).fadeIn(300);

                // Adjust outer wrapper to fit new list snuggly
                var newHeight = $("#"+divID).height() + 20; //20 because we added a 10px padding in the css file
                $("#all-div-wrap").animate({
                    height: newHeight
                });
            
            });
            
        }        
        
        // Don't behave like a regular link
        return false;
    });

});