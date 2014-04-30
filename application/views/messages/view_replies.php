<div class='replies_container'>

   <?php       
    $basepath = base_url()."uploaded_media/photos_profile/";
    
    // Now display all the replies to this post
    if(isset($replies) && count($replies) > 0){

        foreach($replies as $reply)
        {
            // build the name variable
            $msg_from = $reply['first_name'].' '.$reply['last_name'];
           
            //format the time 
            $date = date("D, M j   (g:i  a)", strtotime($reply['messagedate']));

            if(!empty($reply['profilephoto_filepath'])){
                $src = $basepath.$reply['profilephoto_filepath'];
            }
            else {
                $src = base_url()."media/images/default_avatar.png";
            }
            $replies_profile_link = "<img src='".$src."' alt='".$msg_from."' ".'title="View '.$msg_from."'s Profile\">";

    ?>
        <div class='reply clearfix'>
            <div class='clearfix'>
                <div class='msg_col_left'>
                    <div class='avatar'>
                        <?php
                        if($reply['id'] !== '1')
                            {
                                echo anchor("users/profile/".$reply['id'], $replies_profile_link); 
                            }
                            else 
                            {
                                echo $replies_profile_link;
                            }
                         ?>
                    </div>
                </div>
            
                
                <div class='msg_col_right'>
                    <div class='clearfix' id='msg_header'>
                        <div class='pull-left'>
                            <h5><?php echo $reply['first_name'].' '.$reply['last_name']?></h5>
                            <h6><?=$date?></h6>
                        </div>
                    </div>
                
                    <div class='clearfix'>
                       <?php echo nl2br($reply['message']) ?>
                    </div>
                </div>
            </div>
        </div>
<?php
        }
    }
?>
</div>
