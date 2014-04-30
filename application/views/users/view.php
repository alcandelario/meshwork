<?php
    // don't show the edit_user link unless the user is a superuser or the user is the owner of the profile being viewed 
    if(isset($is_super) || (stripos($id,$member['id']) !== FALSE) )
    {
        $edit_link = anchor("edit_user/".$member['id'],"Update Profile","title='Update your profile'");
    }
    
    // build the user's full name
    $full_name = $member['first_name']." ".$member['last_name'];
    
    // set the path to the profile photo if the user uploaded one
    $basepath = base_url()."uploaded_media/photos_profile/";
    if(!empty($member['profilephoto_filepath'])){
        $src = $basepath.$member['profilephoto_filepath'];
    }
    else {
        $src = base_url()."media/images/default_avatar.png";
    }
    
    // set a phone placeholder if necessary
    if(empty($member['phone'])){
        $member['phone'] = "No phone on file";
    }

    //build the address if the user has one in the dB
    if(isset($member['street']) && !empty($member['street']))
    {
        $address = $member['street']."<br>";
        $address = $address.$member['city']." ".$member['state'].' '.$member['zipcode']."<br>";
    }
    
    // set the user "has vehicle" text 
    if($member['has_car'] + 0 === 1)
    {
        $vehicle = "Has vehicle";
    }
    else 
    {
        $vehicle = "Doesn't have vehicle";
    }
    
    $vehicle = "<span class='has_car'>(".$vehicle.")</span>";
    $title_ofc = "title=\"View ".$member['name_ofc']."'s Profile\"";
 ?>
<br>
<div class="profile_container clearfix">
    <div class='user_name_photo clearfix'>
        <div class='clearfix'>
            <div class='pull-left'>
                <h2 class="profile_name"><?php echo anchor("users", $full_name,array("title" => "Go back to Everyone"));?></h2>
                <h5><?=$member['title']?></h5>
                <h4><?php echo anchor("providers/view/".$member['providerID'], $member['name_ofc'],$title_ofc);?></h4>
            </div>
            <div class="pull-right">
                <a title="View <?=$full_name?>'s Profile" href="<?php echo base_url().index_page()."/users/profile/".$member['id']; ?>" >
                    <img class="profile_photo" src='<?=$src?>' alt='<?=$full_name?>' title="View <?=$full_name?>'s Profile" >
                </a>
                <div><?=@$edit_link?></div>
            </div>
        </div>
    </div>
    
    <div class='user_contact clearfix'>
        <div class='pull-left'>
            <h5>
                <a href='tel:+1<?=$member['phone']?> ' title='Give <?=$member['first_name'].' '.$member['last_name']?> a call' ><?php echo $member['phone']?></a> 
            </h5>
        </div>
        <div class='pull-right'>
            <h5>
                <a href='mailto:<?=$member['email']?> ' title='Send <?=$member['first_name'].' '.$member['last_name']?> an email'><?php echo $member['email']?></a>
            </h5>
        </div>
<!--            <p style='font-size:10px; margin-top: 0; margin-left: 5px'><?=$vehicle?></p>        
       -->
    </div>
    
    <div class='user_about clearfix'>
        <?php 
            if(isset($member['about_me']) && !empty($member['about_me']))
            {
                echo '<h5>About Me</h5>';
                echo '<p>'.$member['about_me'].'</p>';
            }
        ?>
    </div>
    
    <div class='user_focus'>
        <?php 
            $focus = implode(", ",$focus);
            if(!empty($focus))
            {
                echo '<h5>Focus</h5>';
                echo '<p>'.$focus.'</p>';
            }
        ?>
    </div>
    
    <div class='user_service_areas'>
        <?php
            $service_areas = implode(", ",$service_areas);
            if(!empty($service_areas))
            {
                echo '<h5>Service Areas</h5>';
                echo '<p>'.$service_areas.'</p>';
            }
        ?>
    </div>
    
    <div class='user_address'>
        <?php 
            if(isset($address)  && !empty($address))
            {
                echo '<h5>Address '.$vehicle.'</h5>';
                echo '<p>'.$address.'</p>';
            }
        ?>
        
    </div>
</div>
