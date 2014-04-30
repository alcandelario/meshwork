<div class="organizations_container"> 
    <BR>
                <?php
                    $begin = "<H4>All community orgs ";
                      $end = "</H4>";
                    if(isset($selected_issue) && isset($provider_filter))
                    {
                        echo $begin."specializing in <span class='highlight_item'>".$selected_issue."</span>".$end;
                    }
                    elseif(isset($selected_area) && isset($provider_filter))
                    {
                        echo $begin."servicing <span class='highlight_item'>".$selected_area."</span>".$end;
                    }
                 ?>
        <BR>
 <div id="provider_filter">
   <form action="<?php echo base_url().index_page()."/providers/filter"; ?>" method="post" onsubmit="
                      return btn_disable();">
            
           <?php if(isset($provider_filter)){echo "<h5 class='pull-left'>Filter by </h5>";}?>
            <?php 
                  $specialties = array('' => 'Org Specialty','all' =>'"SHOW ALL"') + $specialties;
                $service_areas = array('' => 'Org Service Area') + $service_areas;
                if(isset($provider_filter))
                {
                    echo form_dropdown('specialty', @$specialties, '','onchange="this.form.submit()"'); 
                    echo "<BR>";
                    echo form_dropdown('service_area', @$service_areas, '','onchange="this.form.submit()"'); 
                }
            ?>        
           
<!--            <input type="submit" name="submit" id="form_submit_btn"/>-->
    </form>
  </div>
  
 <?php
    //set the size of the headings
    $head_start = '<h4>';
      $head_end = '</h4>';
      
    // display the "new provider" link if the current user is an admin  
    if((isset($is_super) || isset($is_admin)) && !isset($disable_admin_btn))
    {
      //  echo "<button>".anchor("providers/create","Add New Org")."</button>";
    }

    foreach ($providers as $provider)
    {
        // grab all the data we have 
                        $title = "title =\"View ".$provider['name_ofc']." 's profile\"";
        $provider_profile_link = anchor("providers/view/".$provider['providerID'],$provider['name_ofc'],$title);
               $provider_phone = $provider['phone_ofc'];
                      @$street = $provider['street_ofc'];
                        @$city = $provider['city_ofc'];
                       @$state = $provider['state_ofc'];
                         @$zip = $provider['zipcode_ofc'];
                       @$phone = $provider['phone_ofc'];
                       @$email = $provider['email_ofc'];
                     @$website = $provider['website'];
        
          // not sure if this functionality is needed right now          
//        if(isset($is_admin) || isset($is_super))
//        {
//            $active = $provider['provider_active'] + 0;
//            if($active === 1)
//            {
//                $status = 0;
//                $activeStatus = '(deactivate)';
//            }
//            else 
//            {
//                $status = 1;
//                $activeStatus = '(activate)';
//            }
//            echo "<span>".anchor("providers/set_active/".$provider['providerID']."/".$status,$activeStatus)."</span>";
//        }
   ?>
    
    <div class="org_info">
        <ul class="org_header clearfix">
            <li><h3><?=$provider_profile_link?></h3></li>
            <li><h5><a href='tel:<?=$provider_phone?>' title="Give <?=$provider['name_ofc']?> a call"><?=$provider_phone?></a></h5></li>
            <li class='toggle_provider_summary'>&#x25BC;</li>
        </ul>
        <div class="org_summary">
            <div class="org_address">
                <?php
                    if(isset($street))
                    {
                        echo "$street<br>";
                    }
                    if(isset($city))
                    {
                        echo "$city".' '.@$state.'  '.@$zip;
                    }
                ?>
            </div>      

            <div class="org_contact">
                <?php
//                    if(isset($phone))
//                    {
//                        echo "<h5>$phone</h5>";
//                    }
                    if(isset($email))
                    {
                        echo "<h6>Email: <a href='mailto:".$email."' title='Send ".$provider['name_ofc']." an email'>$email</a></h6>";
                    }
                    if(isset($website))
                    {
                        echo "<h6><a target='_blank' href='".$website."' title='Go to ".$provider['name_ofc']."'s homepage (in a new window)'>".$website."</a></h6>";
                    }
                 ?>
            </div>
        </div>
    </div>
    
    <?php } ?>
</div>
