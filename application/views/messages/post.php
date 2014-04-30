<div class='post_container'>
    <div>
        <div class ='message_container'>
            <br><br>
         <h6>*(required)</h6>    
            <?php echo validation_errors(); ?>
            
            <form action="<?php echo base_url().index_page()."/messages/post"?>" method="post" onsubmit="
                      return btn_disable();"> 
         <?php   
            $basepath = base_url()."uploaded_media/photos_profile/";
                if(isset($profilephoto)){
                    $src = $basepath.$profilephoto;
                }
                else {
                    $src = base_url()."media/images/default_avatar.png";
                }
                $post_profile_link = "<img src='".$src."' alt='".$the_user."'".'title="View '.$the_user."'s Profile\">";
                
                // make the date a little more user friendly before printing
               $date = date("D,  j   (g:i  a)");
        ?>
         
            <div class='post clearfix'>
                <div class='clearfix'>       
                    <div class='msg_col_left'>
                        <div class='avatar'>
                            <?php 
                            if($id !== '1')
                            {
                                echo anchor("users/profile/".$id, $post_profile_link); 
                            }
                            else 
                            {
                                echo $post_profile_link;
                            }
                            ?>
                            
                        </div>
                    </div>
                        <div class='msg_col_right'>
                            <div class='clearfix' id='character_counter'>
                                Remaining:&nbsp;&nbsp;<span class="counter"></span> 
                            </div>

                            <div class='clearfix'>
                                <textarea id="post_text_input" name='message' placeholder="*What's on your mind? (500 Character Limit)"></textarea><br>
                            </div>

                            <?php $issues_select = array('' => '') + $issues_select; ?>
                            <div class='clearfix' id='post_focus'><h3>Focus*</h3>
                                          <?php echo form_dropdown('msg_concerns', $issues_select, '','')?>
                            </div>


                        </div>
                </div>
            </div>
            <input type='hidden' name='id' value='<?php echo $id?>' />
            <?=@$google_calendar_form?>
            <?=@$user_multiselect_form?>
            
            <input type="submit" name="submit" value='Submit Post' id="form_submit_btn" /> 
        </div>
        </form>
    </div>
</div>
