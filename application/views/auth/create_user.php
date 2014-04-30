<?php 
        // path to generic avatar image
        $src = base_url()."media/images/default_avatar.png";
?>

<div class="profile_container clearfix">
    <?php echo validation_errors(); ?>
    <?=@$message?>
    <h2>Create new user</h2>
    <?php
        $attributes = array('autocomplete' => 'off', 'onsubmit' => "return btn_disable();");
        echo form_open_multipart('create_user', $attributes);
        
        // display the "make admin" checkbox if the user themself is an admin
        if(isset($is_admin) || isset($is_super))
        {
             echo "<div class='clearfix'>";
             echo '<br><br><h5>Give this user "Admin access"?</h5>';
             echo form_checkbox('make_admin','1')."&nbsp;&nbsp;<span>Yes (usually reserved for the main Org contact)</span>";
             echo "</div>";    
        }
    ?>

    <div class='user_name_photo clearfix'>
        <br><br>
        <h6>*(required)</h6>
        <div class='clearfix'>
            <div class='pull-left'>
                <input class='glow_required glow_required_speed' type = 'text' name = "first_name" placeholder = "First Name *" />
                <input class='glow_required glow_required_speed' type = 'text' name = "last_name"  placeholder = "Last Name *"  />
                <input type = 'text' name = "title" placeholder = "Title" />
                <h3>Working with</h3>
                <?php 
                    $provider_select = array('' => '') + $provider_select;
                    echo form_dropdown('providerID', $provider_select, ''); 
                ?>
            </div>
            <div class="pull-right">
                <img class="profile_photo" src='<?=$src?>' >
                <div>
                    <span><a href=''>Upload New Photo (optional)</a></span>
                    <input style='opacity: 0;position: relative;top: -23px;width: 140px;cursor:pointer;' type="file" name="profile_photo" size="20" />
                </div>
            </div>
        </div>
    </div>
    
    <div class='user_contact clearfix'>
        <div class='pull-left'>
            <input class='glow_required glow_required_speed' type = 'text'  name = "phone" placeholder = "Phone" />
        </div>
        <div class='pull-right'>
            <input class='glow_required glow_required_speed' type = 'email' name = "email" placeholder = "Email *" />
        </div>
     
    </div>
    
    <div class='user_about clearfix'>
       <textarea rows='5' cols ='35'name='about_me' placeholder='About'></textarea>
    </div>
    <div class='clearfix'>       
        <div class='user_address pull-left'>
            <input type = 'text'  name = "street"    placeholder ='Address (eg. 300 S. Ashland Ave)'  /><br>
            <input type = 'text'  name = "city"      placeholder ='City'      /><br>
            <input type = 'text'  name = "state"     placeholder ='State'     /><br>
            <input type = 'text'  name = "zipcode"   placeholder ='Zip Code'  /><br>
        </div>

        <div class='user_pwd pull-left'>
            <br><br>
            <input class='glow_required glow_required_speed' type = password  name = "password"         placeholder = "Password *" />
            <input class='glow_required glow_required_speed' type = password  name = "password_confirm" placeholder = "Confirm Password *" />
        </div>
    </div>
  
    <div> 
       <input type='radio' name='has_car' id='yes' value='1'  > &nbsp;&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;
       <input type='radio' name='has_car' id='no' value='0'   > &nbsp;&nbsp;No&nbsp;&nbsp;&nbsp;&nbsp;
       &nbsp;&nbsp;Has a vehicle?
    </div>
    <input type="submit" name="submit" value="Create" id="form_submit_btn" /> 
    </form>
</div>
