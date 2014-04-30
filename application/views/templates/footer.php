<?php 
      @session_start();
      if(isset($the_user) && stripos($the_user,'guest') === FALSE)
      {
        // path to user uploaded profile pics
       $basepath = base_url()."uploaded_media/photos_profile/";

        if(isset($profilephoto))
        {
            $profile_pic = $basepath.$profilephoto;
        }
        else 
        {
            $profile_pic = base_url()."media/images/default_avatar.png";
        }
        
        // include the following file which displays a menu icon and a <UL> with any "admin-specifc" links in a CSS pop-up menu
        $footer_nav_bar = ROOTDIR.'application'.dir_separator.'views'.dir_separator.'templates'.dir_separator.'footer_nav_bar.php';
        
        
            // create a link to the user's profile page (or wherever) using their profile pic
            $profile_image = '<img height="" src="'.$profile_pic.'" title="'.$the_user.'\'s profile" alt="'.$the_user.'">';
        
        
        if(isset($id) && $id !== '1')
        {
            $footer_profile_link = anchor("users/profile/".$id, $profile_image);
        }
        elseif(isset($id))
        {
            $footer_profile_link = $profile_image;
        }           
      } 
?>
 <!-- end of page wrap container !  -->
</div>
  <footer class="clearfix"> 
    <div class='footer_container'>
        <?php 
            if(isset($the_user)){
                include_once($footer_nav_bar);
            }
        ?>  
        
        <div class='pull-right' id='profile_link'>
            <div class='footer_profile_link clearfix'>
                <span class='rotate'>
                    <h3><?php if(isset($footer_profile_link)){echo "Profile";}?></h3></span>
                <?php if(isset($footer_profile_link)){echo @$footer_profile_link;}?>
            </div>
        </div>
        <div class='upcoming-event-container glow_next_event glow_post_speed'>
            <h5>
            <?php
                if(isset($_SESSION['upcoming_date']) && isset($_SESSION['upcoming_summary']))
                {
                    $time = strtotime($_SESSION['upcoming_date']);
                    $time = date("D M jS @ g:i a",$time);
                    
                    if(strlen($_SESSION['upcoming_summary']) > 30)
                    {
                        $upcoming_summary = substr($_SESSION['upcoming_summary'],0,30)."...";
                    }
                    else 
                    {
                        $upcoming_summary = $_SESSION['upcoming_summary'];
                    }
                    echo "Next Event: ".$time."<br>";
                    echo $upcoming_summary;
                }
            ?>
            </h5>
        </div>
        
        <div class='copyright_logout clearfix'> 
            <div class='' id="site_slogan">
                <h5>Meshwork: community leaders, connected</h5>
            </div>
        </div>
       </div>
      </footer>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
        <script src='<?php echo base_url()?>js/jQuery_datetime_picker/jquery-ui-timepicker-addon.js' type='text/javascript'></script>
        <script src='<?php echo base_url()?>js/jQuery_datetime_picker/jquery-ui-sliderAccess.js' type='text/javascript'></script>
        <script src='<?php echo base_url()?>js/simply_countable/jquery.simplyCountable.js' type='text/javascript'></script>
        <script src='<?php echo base_url()?>js/custom.js' type='text/javascript'></script>        
    </body>
</html>
