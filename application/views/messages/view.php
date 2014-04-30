<?php 
    if(isset($message) && count($message) > 0){
            
            // build the name variable
            $msg_from = $message['first_name'].' '.$message['last_name'];

            $basepath = base_url()."uploaded_media/photos_profile/";
            if(!empty($message['profilephoto_filepath'])){
                $src = $basepath.$message['profilephoto_filepath'];
            }
            else {
                $src = base_url()."media/images/default_avatar.png";
            }

             $view_profile_link = "<img src='".$src."' alt='".$msg_from."'".'title="View '.$msg_from."'s Profile\">";

            //**********************NEW******************************
            // make the date a little more user friendly before printing
           $date = date("D,  j   (g:i  a)", strtotime($message['messagedate']));

           // if this is a calendar event, show the calendar icon and link it to the calendar page
            if(isset($message['googlecalendar_id']) && !empty($message['googlecalendar_id']))
            {
                 $date = date("M j@g:i a",  strtotime($message['calendar_date']));
             $calendar = anchor_popup("calendar",'<img title="Event ('.$date.') - '.$message['summary'].'" src="'.base_url()."media/images/calendar.png".'" width=25px>');
            }
            else
            {
                $calendar = NULL;
            }

            // if the message has a "focus", set that variable here
            if(isset($msg_concerns))
            {
                if(isset($msg_concerns[$message['messageID']]))
                {
                   $focus = $msg_concerns[$message['messageID']];
                }
            }   
            
?>
    <div class='messages_container clearfix'>
        <div class='msg clearfix'>
            <div class='clearfix'>
                <div class='msg_col_left'>
                    <div class='avatar'>
                        <?php 
                            if($message['id'] !== '1')
                            {
                                echo anchor("users/profile/".$message['id'], $view_profile_link); 
                            }
                            else 
                            {
                                echo $view_profile_link;
                            }
                            ?>
                    </div>
                </div>
                <div class='msg_col_right'>
                    <div class='clearfix' id='msg_header'>
                        <div class='pull-left'>
                            <h5><?=$message['first_name'].' '.$message['last_name']?></h5>
                            <h6><?=$date?></h6>
                        </div>
                        <div class='pull-right'>
                            <?=@$calendar?>
                            <span id='delete_link'><?=@$delete_link?></span>
                        </div>
                    </div>

                    <div class='clearfix'>
                        <?php echo nl2br($message['message'])?>
                    </div>
                    
                    <div class='clearfix' id='concerns'>
                        Focus: <?=@$focus?>
                    </div>          
                </div>
            </div>
            <!-- print the replies and view/edit links -->
            <div class='clearfix'>
                <div>
                    <a href="#msg_header">Comment</a>
                </div>
                <?php
                    if(stripos($id,$message['id']) !== FALSE)
                    {
                       $view_or_edit = anchor("messages/edit/".$message['messageID'], "Edit"); 
                    }
                    else
                    {
                        $view_or_edit = anchor("messages/reply/".$message['messageID'], "View");
                    }   
                ?>
                <div>
                    <?=@$view_or_edit?>
                </div>
                
             </div>
        </div>
    </div>
<?php 
    }
?>
