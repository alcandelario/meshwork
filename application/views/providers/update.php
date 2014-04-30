<div class="org_profile_container">
    
    <?php echo validation_errors(); ?>
    <h2>Update profile for <?=$providers['name_ofc']?></h2><br>
    <?php $attributes = array('onsubmit' => "return btn_disable();");?>
    <?php echo form_open('providers/update/'.$providers['providerID'],$attributes) ?>
    
     <div class="profile_container clearfix">
        <div class='org_name_photo clearfix'>
            <div class='clearfix'>
                <div class='pull-left'>
                    <input type="input"     name="name_ofc" value="<?=$providers['name_ofc']?>"  placeholder="Organization's Name" /><br>
                    <input type="input"     name="website_ofc" value="<?=@$providers['website_ofc']?>"  placeholder="Website" />
                </div>
<!--                <div class="pull-right">
                    <img class="profile_photo" src='<?//=$src?>' alt='<?//=$full_name?>' title='<?//=$full_name?>' >
                    <div><?//=@$edit_link?></div>
                </div>-->
            </div>
        </div>

        <div class='user_contact clearfix'>
            <div class='pull-left'>
                <input type="input"     name="phone_ofc" value="<?=$providers['phone_ofc']?>"  placeholder="Org phone number" />
            </div>
            <div class='pull-right'>
                <input type="input"     name="email_ofc" value="<?=$providers['email_ofc']?>"  placeholder="Org email address" />
            </div>
        </div>
        
        <div class='user_address'>
           <input type="input"     name="street_ofc"  value="<?=@$providers['street_ofc']?>"   placeholder="Street Address" /><BR>
           <input type="input"     name="city_ofc"    value="<?=@$providers['city_ofc']?>"     placeholder="City"           /><BR>
           <input type="input"     name="state_ofc"   value="<?=@$providers['state_ofc']?>"    placeholder="State"          /><BR>
           <input type="input"     name="zipcode_ofc" value="<?=@$providers['zipcode_ofc']?>"  placeholder="Zipcode"        /><BR>
        </div>
         
        <div class='user_about clearfix'>
            <h5>About <?=$providers['name_ofc']?></h5>
            <textarea rows='10' cols ='20'name='philosophy' placeholder="The organization's mission"><?=$providers['philosophy']?></textarea><br>
        </div>
         
        <input type="submit" name="submit" value="Update Provider Info" id="form_submit_btn" />  
        <BR><BR>
        <div class='user_focus'>
             <?=@$issues_template_output?>
        </div>

        <div class='user_service_areas'>
            <?=@$service_areas_template_output?> 
        </div>
        <input type="submit" name="submit" value="Update Provider Info" id="form_submit_btn" />  
    </div>
</form>
</div>