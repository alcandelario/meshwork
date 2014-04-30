<?php 

    $basepath = base_url()."uploaded_media/photos_profile/";
               
    //format the time 
    $date = date("D, M j   (g:i  a)");
    $time = date("(g:i a)");

    if(isset($profilephoto)){
        $src = $basepath.$profilephoto;
    }
    else {
        $src = base_url()."media/images/default_avatar.png";
    }
    $reply_profile_link = "<img src='".$src."' alt='".$the_user."' ".'title="View '.$the_user."'s Profile\">";
?>


<div class='reply_form_container'>
    <div>
        <div class ='message_container'>

            <?php echo validation_errors(); ?>
            <?php $attributes = array('onsubmit' => "return btn_disable();");?>
            <?php echo form_open('messages/reply/'.$message['messageID'],$attributes) ?>
            <input type="hidden" value="<?php echo $message['messageID'];?>" name="replyID" />
            
            <input type='hidden' name='id' value='<?php echo $id?>' />
<!--            <h6>Reply From <?php// echo $the_user?></h6>
                                            -->
            <div class='reply_form clearfix'>
                <div class='clearfix'>       
                    <div class='msg_col_left'>
                        <div class='avatar'>
                            <?php 
                            if($id !== '1')
                            {
                                echo anchor("users/profile/".$message['id'], $reply_profile_link); 
                            }
                            else 
                            {
                                echo $reply_profile_link;
                            }
                            ?>
     
                        </div>
                    </div>
                
                    <div class='msg_col_right'>
                        <div class='clearfix' id='character_counter'>
                                Remaining:&nbsp;&nbsp;<span class="counter"></span> 
                        </div>
                        <div id='reply_textarea'>
                            <textarea id="reply_text_input" name='message' placeholder='Write a comment (500 Character Limit)'></textarea>
                        </div>
                        <input type="submit" name="submit" id="form_submit_btn"/>
                    </div>
                </div>
            </div>
         </div>
     </div>
       <?php echo form_close();?>
</div>
