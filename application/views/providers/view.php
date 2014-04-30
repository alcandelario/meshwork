<?php 
//                if(isset($is_admin) || isset($is_super))
//                {
//                    $active = $providers['provider_active'] + 0;
//                    if($active === 1)
//                    {
//                        $status = 0;
//                        $activeStatus = '(deactivate)';
//                    }
//                    else 
//                    {
//                        $status = 1;
//                        $activeStatus = '(activate)';
//                    }
//                    $toggle_activate =  anchor("providers/set_active/".$providers['providerID']."/".$status,$activeStatus);
//                }
            if(!empty($providers['specialties']))
            {
                $focus = implode(", ",$providers['specialties']);
            }
            
            if(!empty($providers['service_areas'])){
               $service_areas = implode(", ",$providers['service_areas']);
            }
            
            if(isset($providers['email_ofc']) && !empty($providers['email_ofc']))
            {
                $email = $providers['email_ofc'];
                $title = "Send ".$providers['name_ofc']." an email";
                        
            }
            else
            {
                $email = "No email on file";
                $title = "";
            }
            
            if(isset($providers['philosophy']) && !empty($providers['philosophy']))
            {
                $about_org = $providers['philosophy'];
            }
            
            if(isset($providers['website_ofc']) && !empty($providers['website_ofc']))
            {
                $website = $providers['website_ofc'];
                if(stripos($website,"http") === FALSE)
                {
                    if(stripos($website,"www") === FALSE)
                    {
                        $href = "http://www.".$website;
                    }
                    else
                    {
                        $href = "http://".$website;
                    }
                }
                else 
                {
                    $href = $website;
                }
            }
?>
<div class="org_profile_container">
    
<?php
        if(isset($is_admin) || isset($is_super))
        {
            echo "<button>".anchor("providers/update/".$providers['providerID'],"Edit Org Profile")."</button>";
        }
?>
    <div class="profile_container clearfix">
        <div class='org_name_photo clearfix'>
            <div class='clearfix'>
                <div class='pull-left'>
                    <h2 class="profile_name"><?php echo anchor("providers",$providers['name_ofc'],"title='View all Community Orgs'") ?></h2>
                    <h6><a target="_blank" href='<?=@$href?>' title="Go to <?=$providers['name_ofc']?>'s homepage (new window)"><?=@$website?></a></h6>
                    <h6><em>
                        <form action="<?php echo base_url().index_page()."/users/filter"; ?>" method="post" onchange="this.form.submit()">
                            <input type="hidden" name="organization" value="<?=$providers['providerID']?> ">
                            <input type="submit" name="submit" value="See the team" id="see_team_btn">
                        </form>
                        <?php 
                                
                               // echo anchor("users/filter/".$providers['providerID'],"See the team","title=\"See everyone working with ".$providers['name_ofc'].'"')
                        ?>
                        </em>
                    </h6>
                </div>
<!--                <div class="pull-right">
                    <img class="profile_photo" src='<?//=$src?>' alt='<?//=$full_name?>' title='<?//=$full_name?>' >
                    <div><?//=@$edit_link?></div>
                </div>-->
            </div>
        </div>

        <div class='user_contact clearfix'>
            <div class='pull-left'>
                <h5>
                    <a href='tel:+1<?=$providers['phone_ofc']?>' title="Give <?=$providers['name_ofc']?> a call"><?=$providers['phone_ofc']?></a> 
                </h5>
            </div>
            <div class='pull-right'>
                <h5>
                    <a href='mailto:<?=$email?>' title='<?=$title?>'><?=$email?></a>
                </h5>
            </div>
        </div>
        
        <div class='user_address'>
            <?php 
                if(isset($providers['street_ofc']) && !empty($providers['street_ofc']))
                {
                    echo "<h5>Address</h5>";                
                }
            ?>
            <p>
                <?=@$providers['street_ofc']?><BR>
                <?=@$providers['city_ofc'].' '.@$providers['state_ofc']."  ".@$providers['zipcode_ofc'];?>
            </p>
        </div>

        <div class='user_focus'>
            <?php 
                if(isset($focus) && !empty($focus))
                {
                    echo '<h5>Focus</h5>';
                    echo '<p>'.$focus.'</p>';
                }
            ?>
        </div>

        <div class='user_service_areas'>
            <?php
                if(isset($service_areas) && !empty($service_areas))
                {
                    echo '<h5>Service Areas</h5>';
                    echo '<p>'.$service_areas.'</p>';
                }
            ?>
        </div>

         <div class='user_about clearfix'>
            <?php 
                if(isset($about_org) && !empty($about_org))
                {
                    echo "<h5>About ".$providers['name_ofc']."</h5>";
                    echo '<p>'.$about_org.'</p>';
                }
            ?>
        </div>
    </div>
</div>
