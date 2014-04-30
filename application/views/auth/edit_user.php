<?php echo validation_errors(); ?>
<?php 

    // set the path to the profile photo if their is one
    $basepath = base_url()."//uploaded_media//photos_profile//";
    
    if(!empty($member['profilephoto_filepath'])){
        $src = $basepath.$member['profilephoto_filepath'];
    }
    else {
        $src = base_url()."media/images/default_avatar.png";
    }
    
    // don't allow the admin setting unless user is at least an admin themselves
    if(isset($is_admin) || isset($is_super))
    {
        // 
        $id = $user->id + 0;
        if($this->ion_auth->is_admin($id))
        {
            $checked = TRUE;
        }
        else
        {
            $checked = FALSE;
        }
        
    }
    
    // build the full name variable
    $full_name = $member['first_name']." ".$member['last_name'];
    
    // set the 'title' variable if there is one, otherwise, put a placeholder for it
    if(isset($member['title']) && !empty($member['title']))
    {
        $title = 'value = "'.$member['title'].'"';
    }
    else
    {
        $title = 'placeholder = "Title"';
    }

?>

<div class="profile_container clearfix">
    <h2>Update <?=$full_name?> profile </h2>
    <?php
    // initiate our form element
    $attributes = array('autocomplete' => 'off','onsubmit' => "return btn_disable();");
    echo form_open_multipart('edit_user/'.$member['id'], $attributes);
    
    // display the "make admin" checkbox if the user themself is an admin
    if(isset($is_admin) || isset($is_super))
    {
         echo '<br><br><h5>Give this user "Admin access"?</h5>';
         echo form_checkbox('make_admin','1',$checked)."&nbsp;&nbsp;Yes (usually reserved for the main Org contact)";
    } 
    ?>
    <br><br><br>
    <div class='user_name_photo clearfix'>
        <div class='clearfix'>
            <div class='pull-left'>
                <input class='glow_required glow_required_speed' type = 'text' name = "first_name" placeholder = "First Name *" value='<?=$member['first_name']?>'/>
                <input class='glow_required glow_required_speed' type = 'text' name = "last_name"  placeholder = "Last Name *" value='<?=$member['last_name']?>'  />
                <input type = 'text' name = "title" <?=$title?> '/>
                <h3>Working with</h3>
                <?php 
                               $name = $member['providerID_users'];
                    $provider_select = array('' => '') + $provider_select;
                    echo form_dropdown('providerID', $provider_select, $name); 
                ?>
            </div>
            <div class="pull-right">
                <img class="profile_photo" src='<?=$src?>' alt='<?=$full_name?>' title='<?=$full_name?>' >
                <div>
                    <span><a href=''>Upload New Photo (optional)</a></span>
                    <input style='opacity:0; position:relative; bottom:21px; width:140px; cursor:pointer;'type="file" name="profile_photo" size="20" />
                </div>
            </div>
        </div>
    </div>
    
    <div class='user_contact clearfix'>
        <div class='pull-left'>
            <input type = 'text'  placeholder='Phone' name = "phone" value = "<?=$member['phone']?>" />
        </div>
        <div class='pull-right'>
            <input type = 'email' placeholder='Email *' name = "email" value = "<?=$member['email']?>" />
        </div>
     
    </div>
    
    <div class='user_about clearfix'>
       <textarea rows='5' cols ='35'name='about_me' placeholder='About'><?=@$member['about_me']?></textarea>
    </div>
    <div class='clearfix'>       
        <div class='user_address pull-left'>
            <input type = 'text'  name = "street"    placeholder ='Address (eg. 300 S. Ashland Ave)' value = "<?=$member['street']?>" />
            <input type = 'text'  name = "city"      placeholder ='City'     value = "<?=$member['city']?>" />
            <input type = 'text'  name = "state"     placeholder ='State'    value = "<?=$member['state']?>" />
            <input type = 'text'  name = "zipcode"   placeholder ='Zip Code' value = "<?=$member['zipcode']?>" />
        </div>

        <div class='user_pwd pull-left'>
            <br><br>
            <input type = password  name = "password"         placeholder = "(OPTIONAL!) New Password" />
            <input type = password  name = "password_confirm" placeholder = "Confirm Password (if changing it)"     />
        </div>
    </div>
  
    <div> 
       <?php 
           $yes = '';
            $no = '';
            if(($member['has_car'] + 0)  === 1) {
                $yes = "checked";
            }
            else {
                
                $no = "checked";
            }
        ?>
       <input type='radio' name='has_car' id='yes' value='1' <?=$yes?> >&nbsp;&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;
       <input type='radio' name='has_car' id='no' value='0' <?=$no?> >&nbsp;&nbsp;No&nbsp;&nbsp;&nbsp;&nbsp;
       &nbsp;&nbsp;Has a vehicle?
    </div>
      <?php echo form_hidden('id', $user->id);?>
      <?php echo form_hidden($csrf); ?>
    <br>
      <input type="submit" name="submit" value="Update" id="form_submit_btn" /> 
    </form>
</div>
