<div class="google_calendar_event_create clearfix">
   <div class='clearfix'>
    <?php  
        
        if(!isset($event_time))
        {
            echo "<h5 class='pull-right'>&nbsp;&nbsp;Click here to create an event on the Calendar</h5>";
            echo form_checkbox('calendar_event', "1", '');
        }
        else
        {
            echo "<h6><i>Optional: Want to edit the calendar event details?</i></h6>";
            echo form_hidden('calendar_event', '1');
        }
        echo "</div>";
        
        // determine the preselected date and time if there was one
          if((isset($event_time) && count($event_time) == 0) || !isset($event_time))
          {
              $selected_start = '';
                $selected_end = '';
          }
          else
          {
              $selected_start = date("m/d/Y g:ia",strtotime($event['start']['dateTime']));
                $selected_end = date("m/d/Y g:ia",strtotime($event['end']['dateTime']));
          }
          
          if(isset($event['location']))
          {
              $event_location = $event['location'];
          }
          else
          {
              $event_location = '';
          }
          
          if(isset($event['summary']))
          {
              $event_summary = $event['summary'];
          }
          else
          {
              $event_summary = '';
          }
    ?>
   
    <div class='clearfix'>
<!--        <h6 class='pull-left'>Summary</h6>-->
        <input class='glow_required glow_required_speed'type="text" name="summary" placeholder="Event Summary" value="<?=$event_summary?>">
        <h6 class='pull-right'>Summary*</h6>      
    </div>     
    <div class='clearfix'>
<!--        <h6 class='pull-left'>Location</h6>-->
        <input type="text" name="address" placeholder="Event location (Optional)" value="<?=$event_location?>">
        <h6 class='pull-right'>Location</h6>
    </div>    
    <div class='clearfix event_date_select'>
        <h6 class=''>Event Date*</h6>
        <input  type="hidden" name="event_start" value='<?=@$selected_start?>' id="event_start"/>
        <input readonly placeholder="Start  (Date & Time)" type="text" name="dummy_start" value='<?=@$selected_start?>' id="displayedStart" />
        
        <br>    
        <input  type="hidden" name="event_end" value='<?=@$selected_end?>' id="event_end"/>
        <input readonly placeholder="End  (Date & Time)" type="text" name="dummy_end" value='<?=@$selected_end?>' id="displayedEnd" />

    </div>

</div>