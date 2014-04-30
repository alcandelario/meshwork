<?php   
        $basepath = base_url()."uploaded_media/photos_profile/";
        
        if(isset($profilephoto)){
            $src = $basepath.$profilephoto;
        }
        else {
            $src = base_url()."media/images/default_avatar.png";
        }
        $title = "title=\"View ".$message['first_name'].' '.$message['last_name']."'s Profile\"";
        $edit_profile_link = "<img src='".$src."' alt='".$the_user."' ".$title.">";

        // make the date a little more user friendly before printing
        $date = date("D,  j   (g:i  a)");
               
        // set up the dropdown box for the Focus variable
        $issues_select = array('' => '') + $issues_select;
        if(isset($issueID))
        {
            $selected_issue = $issueID['issueID'];
        }
        else
        {
            $selected_issue = '';
        }
?>


<div class='post_container'>
    <div>
        <div class ='message_container'>
             
            <h1>Edit this post</h1>
            <?php echo validation_errors(); ?>
            
            <form action="<?php echo base_url().index_page()."/messages/edit/".$message['messageID']; ?>" method="post" onsubmit="
                return btn_disable();">
                
                <input type='hidden' name='id' value='<?php echo $id?>' />
                <br>  
                
            <div class='post clearfix'>
                <div class='clearfix'>       
                    <div class='msg_col_left'>
                        <div class='avatar'>
                            <?php 
                            if($message['id'] !== '1')
                            {
                                echo anchor("users/profile/".$message['id'], $edit_profile_link); 
                            }
                            else 
                            {
                                echo $edit_profile_link;
                            }
                            ?>
                        </div>
                    </div>
                        <div class='msg_col_right'>
                            <div class='clearfix' id='msg_header'>
                                <div class=''>
                                   <h5><?=$message['first_name']." ".$message['last_name']?></h5>
                                        <h6><?=$date?></h6>
                                </div>
                                <div class='clearfix' id='character_counter'>
                                    Remaining:&nbsp;&nbsp;<span class="counter"></span> 
                                </div>
                            </div>

                            <div class='clearfix'>
                                <textarea id="edit_text_input" name='message' placeholder='Post Your Message (500 Character Limit)'><?=@$message['message'];?></textarea><br>
                            </div>

                            <div class='clearfix' id='post_focus'><h3>Focus*</h3>
                                          <?php echo form_dropdown('msg_concerns', $issues_select, $selected_issue)?>
                            </div>


                        </div>
                </div>
            </div>
                
            <?=@$google_calendar_form?>
            <input type="submit" name="submit" id="form_submit_btn" value='Update Post' /> 
        </div>
        </form>
    </div>
    
</div>
