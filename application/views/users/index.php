<div class='phonebook_container clearfix'>
    <div class="clearfix">
        <button class=''><?php echo anchor("providers","See all the Orgs")?></button>
        <BR>
        <BR>
     <?php
       //show the "create new user" 
        if(isset($is_super) || isset($is_admin))
        {
           // echo "<button class='admin_btn'>".anchor("create_user","Add New User")."</button>";
        }
     ?>
    </div>

    <!-- Directory of Users -->
     <div class='phonebook_inner_container clearfix'>
         <div id='user_filter_container'>
             <form action="<?php echo base_url().index_page()."/users/filter"; ?>" method="post" onchange="this.form.submit()">
            <?php 
                     $provider_select = array('default' => '<h6>Only show people from...</h6>') + $provider_select;
              $provider_select['all'] = '<i>SHOW "EVERYONE" AGAIN</i>';
              echo form_dropdown('organization', @$provider_select, @$selected_provider,'onchange="this.form.submit()"'); 
            ?>
            </form>
         </div>
         
 <?php
    $basepath = base_url()."/uploaded_media/photos_profile/";
          
  if(isset($members) && count($members) > 0){
       foreach ($members as $member){
             $ofc_name = $member['name_ofc'];
            $user_name = $member['first_name'].' '.$member['last_name'];
  
            //set the path to the avatar
            if(!empty($member['profilephoto_filepath'])){
                $src = $basepath.$member['profilephoto_filepath'];
            }
            else {
                $src = base_url()."media/images/default_avatar.png";
            }
            
            $title = "title=\"View ".$member['first_name'].' '.$member['last_name']."'s Profile\"";
  ?>

    <div class="member_container clearfix pull-left">
        <div class="clearfix">
            <img class="user_summary" src='<?=$src?>' alt='<?=$user_name?>' title='<?=$user_name?>'>
        </div>
        <div id="user_name">
            <h4><?php echo anchor("users/profile/".$member['id'],$member['first_name'].'<br>'.$member['last_name'],$title) ;?><span class='hide' id='toggle_user_summary'>&#x25B2;</span></h4>
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
  <?php
          }
       }
  ?>
     </div>
     <div class='pagination_container clearfix'>
        <?=@$pagination?>
    </div>
</div>
