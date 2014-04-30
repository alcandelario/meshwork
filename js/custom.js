var $j = jQuery.noConflict();

$j(document).ready(function() {
    
    $j('form').submit(function()
    {
       document.getElementById('form_submit_btn').disabled = true;
       document.getElementById('form_submit_btn').value = 'Please wait...';
       return true;
    });
    
    $j('#displayedStart').datetimepicker({
                    addSliderAccess: true,
                        controlType: 'select',
                   sliderAccessArgs: { touchonly: false },
                         timeFormat: "h:mm tt",
                           altField: "#event_start",
                      altTimeFormat: "HH:mm",
                   altFieldTimeOnly: false,
                   onSelect: function(selectedDateTime) {
                               var start = $j(this).datetimepicker('getDate');
		               $j('#event_end').datetimepicker('option', 'minDate', new Date(start.getTime()) );
                               $j('#displayedEnd').datetimepicker('option', 'minDate', new Date(start.getTime()) );
                   }
    });

    $j('#displayedEnd').datetimepicker({
                    addSliderAccess: true,
                        controlType: 'select',
                   sliderAccessArgs: { touchonly: false },
                         timeFormat: "h:mm tt",
                           altField: "#event_end",
                      altTimeFormat: "HH:mm",
                   altFieldTimeOnly: false,
                           onSelect: function (selectedDateTime){
		                       var end = jQuery(this).datetimepicker('getDate');
		                       $j('#event_start').datetimepicker('option', 'maxDate', new Date(end.getTime()) );
                                       $j('#displayedStart').datetimepicker('option', 'maxDate', new Date(end.getTime()) );
	                   }
        
    });
     
     $j(".toggle_event_summary, .toggle_provider_summary").click(function(){
         $j('.events_container, .organizations_container').find(".event_all_details, .org_summary ").slideUp(200);
         if($j(this).parent().next('div').is(":visible"))
         {
             $j(this).parent().next('div').slideUp(200);
             $j(this).html("&#x25BC;");
         }
         else 
         {
           $j('.toggle_event_summary,.toggle_provider_summary').html("&#x25BC;");  
           $j(this).parent().next('div').slideDown(200);
           $j(this).html("&#x25B2;")

         }
     });
     
     $j("#admin_nav_container img").click(function() {
        $j("#admin_nav").slideToggle(190); 
     });
     
     $j("#post_text_input,#edit_text_input,#reply_text_input").simplyCountable({
           counter: '.counter',
          maxCount: 500,
         safeClass: "post_safe",
         overClass: "post_over"
     })
});