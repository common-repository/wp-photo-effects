jQuery(document).ready(function () {
   jQuery('.wppe-hover').each(function() {
       jQuery(this).find('img').wrap( "<figure></figure>" );
   });
   
   /**
    Image hover effect with icon and split caption
    **/
   jQuery(".wppe-hover-caption figure").mouseleave(
    function () {
        jQuery(this).removeClass("hover");
    });
});