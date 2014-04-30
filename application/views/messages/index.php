<div class='messages_container clearfix' >
    <?php 
     // path to user uploaded profile pics
        $basepath = base_url()."uploaded_media/photos_profile/";
        
        if(isset($messages) && count($messages) > 0){
            foreach($messages as $message)
            {
            // build the name variable
                 $msg_from = trim($message['first_name']).' '.trim($message['last_name']);
            
            // make the date a little more user friendly before printing
                 $date = date("D, M j   (g:i  a)", strtotime($message['messagedate']));
               
                 
/********************************************************************************
 * Delete message feature. Supported but removed in initial release (8/16/13)
 ********************************************************************************/              
               
           /*    if(stripos($message['id'],$id) !== FALSE)
               {
                            $img = "<img height='20px' width='25px' src='".base_url()."//media//images//delete.png'>";
                    $delete_link = anchor("messages/delete/".$message['messageID'], $img);
               }
               else
               {
                   $delete_link = NULL;
               }
             */    
/********************************************************************************/                 
               
            // set the avatar image accordingly
                if(!empty($message['profilephoto_filepath']))
                {
                    $src = $basepath.$message['profilephoto_filepath'];
                }
                else 
                {
                    $src = base_url()."media/images/default_avatar.png";
                }
              
            // build the image tag
                $title_name = "title=\"View ".$message['first_name'].' '.$message['last_name']."'s Profile\"";
                $index_profile_link = "<img src='".$src."' alt='".$msg_from."' ".$title_name."'>";
                             
            // if this is a calendar event, show the calendar icon and link it to 
            // the calendar page
                
                if(isset($message['googlecalendar_id']) && !empty($message['googlecalendar_id']))
                {
                     $cal_date = date("M j@g:i a",  strtotime($message['calendar_date']));
                     $calendar = anchor("calendar",'<img title="Event ('.$cal_date.') - '.$message['summary'].'" src="'.base_url()."media/images/calendar.png".'" width=25px>');
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
                    else 
                    {
                        $focus = '';
                    }
                }   
                
                if(isset($reply_count))
                {
                    if(isset($reply_count[$message['messageID']]))
                    {
                        $num_replies = $reply_count[$message['messageID']] + 0;
                        $num_reply_string = "(".$num_replies.")";
                    
                        if($num_replies > 0)
                        {
                            $comment = "Comments ".$num_reply_string;
                        }
                    }
                    else
                    {
                        $comment = "Comment";
                    }
                }    
        ?>
  
        <div class='msg clearfix'>
            <div class='clearfix'>
                <div class='msg_col_left'>
                    <div class='avatar'>
                        <?php 
                            if($message['id'] !== '1')
                            {
                               echo  "<a href='".base_url().index_page()."/users/profile/".$message['id']."' ".$title_name.">".$index_profile_link."</a>"; 
                            }
                            else 
                            {
                                echo $index_profile_link;
                            }
                        ?>
                    
                    </div>
                </div>

                <div class='msg_col_right'>
                    <div class='clearfix' id='msg_header'>
                        <div class='pull-left'>
                            <h5><?=@$message['first_name'].' '.$message['last_name'];?></h5>
                            <h6><?=@$date?></h6>
                        </div>
                        <div class='pull-right'>
                            <?=@$calendar?>
                            <span id='delete_link'><?=@$delete_link?></span>
                        </div>
                    </div>

                    <div class='clearfix'>
                             <?php echo nl2br($message['message']);?>
                    </div>
                    <div class='clearfix' id='concerns'>
                        Focus: <?=@$focus?>
                    </div>
                </div>
            </div>

            <div class='clearfix'>
                <div>
                    <?php echo anchor("messages/reply/".$message['messageID'], $comment,"title='Comment on this post'"); ?>
                </div>
                <?php
                    if(stripos($id,$message['id']) !== FALSE)
                    {
                       $view_or_edit = anchor("messages/edit/".$message['messageID'], "Edit","title='Edit your post'"); 
                    }
                    else
                    {
                        $view_or_edit = anchor("messages/reply/".$message['messageID'], "View", "title='Reply to/view the post'");
                    }   
                ?>
                <div>
                    <?=@$view_or_edit?>
                </div>
                
             </div>
        </div>
<?php
            }
        }
?>
</div> 
<div class='pagination_container clearfix'>
        <?=@$pagination?>
</div>
