<div class='events_calendar_container clearfix'>
        <div>
            <?php 
                if(isset($the_user) && stripos($the_user,'guest') === FALSE)
                {
                    echo "<button class=''>".anchor("messages/post","Create a new event")."</button>";
                }
            ?>
        </div>
    <div class='events_container clearfix'>
        
        <h4>Upcoming events...</h4>  
      <?php 
            $events = $events->getItems();
            $i = 0;
            foreach($events as $event)
            {
                    $summary = trim($event->getSummary());
                       $when = $event->getStart();
                      $where = trim($event->getLocation());
                       $link = trim($event->getHtmlLink());
                $description = trim($event->getDescription()); 
                // shorten a few of the fields if they're too big
                if(strlen($summary) > 50)
                {
                    $summary = substr($summary,0,50)."...";
                }
                if(strlen($where) > 50)
                {
                    $where = substr($where,0,50)."...";
                }
                if(strlen($description) > 100)
                {
                        $description = substr($description,0,100)."<a target='_blank' title='Opens in new window' alt='Opens in new window' href='".$link."'>&nbsp;&nbsp;(cont...)</a>";
                }
                $dateTime = $when->getDateTime();
                       
               //massage the date time into a more readable format
               $dateTime = strtotime(str_ireplace("t",' ',$dateTime));
                    $day = date("M j",$dateTime);
                   $time = date("g:i a",$dateTime);
                  $today = date("M j",now());
                
                  if($day === $today)
                  {
                      $when = "<span id='event_today'>Today@$time</span>";
                  }
                  else
                  {
                      $when = $day."@$time";
                  }
                $i++;
                
        ?>
        <div class='event msg'>
            <ul class='clearfix'>
                <li><a target='_blank' title='Opens event in new window' href='<?=$link?>'><?=@$when?></a></li>
                <li><a target='_blank' title='Opens event in new window' href='<?=$link?>'><?=@$summary?></a></li>
                <li class='toggle_event_summary'>&#x25BC;</li>
            </ul>
            <div class='event_all_details'>
                <div id='event_desc'>
                    <h6>Details</h6>
                    <p><?php echo nl2br($description);?></p>
                </div>
                <div id='event_loc'>
                    <h6>Where: <?=@$where?></h6>
                </div>
            </div>
        </div>
        
        <?php
           }
        ?>
        
    </div>
    <div class='calendar_container'>
        <?php
            if(!isset($area_cal_name))
            {
		    $area_cal_name = 'West%20Side%20-%20Lower%20West%20Side';
		     $area_cal_src = 'ngt5nedpe110254snaahn1davg%40group.calendar.google.com';
            }
        ?>    
		<iframe src="https://www.google.com/calendar/embed?title=Meshwork:%20<?=$area_cal_name?>&amp;showPrint=0&amp;showCalendars=0&amp;height=650&amp;wkst=1&amp;bgcolor=%23FFFFFF&amp;src=<?=$area_cal_src?>&amp;color=%232952A3&amp;ctz=America%2FChicago" style=" border-width:0 " width="800" height="650" frameborder="0" scrolling="no"></iframe>
    </div>
</div>
