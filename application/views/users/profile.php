 <?php
 // don't show the edit_user link unless the user is a superuser or the user is the owner of the profile being viewed 
    if(isset($is_super) || (stripos($id,$member['id']) !== FALSE) )
    {
        $edit_link = anchor("edit_user/".$member['id'],"Update Profile","title='Update your Bio/Contact info'");
    }
 
    $basepath = base_url()."uploaded_media/photos_profile/";
    $ofc_name = $member['name_ofc'];
   $user_name = $member['first_name'].' '.$member['last_name'];
  
    //set the path to the avatar
    if(!empty($member['profilephoto_filepath'])){
        $src = $basepath.$member['profilephoto_filepath'];
    }
    else {
        $src = base_url()."media/images/default_avatar.png";
    }

    $title = 'title="Bio and contact info for '.$member['first_name'].' '.$member['last_name'].'"';
  ?>
<div class="user_profile_container clearfix">
    <div class='inner_profile_container clearfix'>
        <div class='pull-left'>
            <div id="profile_name">
                <h3><?php echo anchor("users/view/".$member['id'],$member['first_name'].' '.$member['last_name'],$title) ;?></h3>
                <h5><a title='Give <?php echo $member['first_name'].' '.$member['last_name'];?> a call' href="tel:+1<?=$member['phone'];?>" ><?=$member['phone']?></a></h5>
                <br>
                <h6><?php echo anchor("users/view/".$member['id'],'more...',$title) ;?></h6>
            </div>
        </div>
   

        <div class="member_container pull-right clearfix">
            <div class="clearfix">
                <img class="user_summary" src='<?=$src?>' alt='<?=$user_name?>' title='<?=$user_name?>'>
                <div id="profile_edit_link"><?=@$edit_link?></div>
            </div>
            
            <nav class='member_summary clearfix hide'>
    <!--            <span><?=$member['title']?></span>-->
                <ul>
                    <li><a href='tel:+1<?=$member['phone']?>'><?=$member['phone']?></a></li>
                    <li><a href='mailto:<?=$member['email']?>'><?=$member['email']?></a></li>
                 </ul>
                <div>
                    <?php echo anchor("users/view/".$member['id'],"Full Profile");?>
                    <span id="close">X</span>
                </div>  
            </nav>
        </div>
        
    </div>
</div>
