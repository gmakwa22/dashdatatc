(function ($) {
    "use strict";
   
 $('#example').DataTable({
     dom: 'Bl<"toolbar">frtip',
     buttons: [
         'copy', 'csv', 'excel', 'pdf', 'print'
        

     ],
     responsive: true,
     select: true,
     
 });
 
  

})(jQuery);
