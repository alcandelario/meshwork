<?php

// ****************LINKS ONLY VISIBLE TO ADMINISTRATORS OR SUPER USERS**************
      $admin_links = array(
                            'create_user' => '*Add a new user*',
                       'providers/create' => '*Add a new org*',
                              'providers' => 'View all orgs',
                        'change_password' => 'Change Password',
                                 'logout' => 'Logout'
                    );  
      
      $links = array(
                      'providers' => 'View all orgs',
                'users/view/'.$id => 'View Bio',
                 'edit_user/'.$id => 'Edit my Bio',
                'change_password' => 'Change Password',
                         'logout' => 'Logout'
                    );  
      
      // determine which links to display 
      if($this->ion_auth->is_admin())
      {
          $footer_links = $admin_links;
      }
      elseif($this->ion_auth->logged_in()) 
      {
          $footer_links = $links;
      }
      else 
      {
          $footer_links = array();
      }
     
      // insert an icon to represent the administrator menu
           $menu = base_url()."media/images/menu.png";
      
      $menu_icon = "<img height='' src='".$menu."' alt='Administrator Menu' title='More options'>";
     
// print any administrator specific links in a left column
?>
<div class='pull-left' id='admin_nav_container'>
    <div class='clearfix'>
        <?=$menu_icon?>
        <nav class='pull-left clearfix' id='admin_nav'>
            <ul>
            <?php 
                    foreach($footer_links as $link => $name)
                    {
                        echo "<li><h5>".anchor($link,$name)."</h5></li>";
                    }
            ?>
            </ul>
        </nav>
    </div>
</div>
