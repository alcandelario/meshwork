<?php echo validation_errors(); ?>

<div class='clearfix'>
<!--    
style='margin: 0 auto; width:80%;'>-->
    <h2>Update 
        <?php echo $member['first_name']." ".$member['last_name'] ?> 
        contact info
    </h2>
    <br>

    
<?php echo form_open_multipart('users/update/'.$member['id']) ?>
    
    <div class='profile_pic_upload' style='position:releative'>
        <?php 
            $basepath = base_url()."/uploaded_media/photos_profile/";
            if(!empty($member['profilephoto_filepath'])){
                $src = $basepath.$member['profilephoto_filepath'];
            }
            else {
                $src = base_url()."media/images/default_avatar.png";
            }

            echo "<div style='max-width: 150px; margin: 10px;'>
                     <img style='border: 1px solid rgba(0,0,0,0.0); border-radius: 50%;' 
                          src='".$src."' alt='".$member['first_name']." ".$member['last_name']."'></div>";
            
        ?>
        <span><a href=''>Upload New Photo (optional)</a></span>
        <input style='opacity: 0; position: absolute; left:0;' type="file" name="profile_photo" size="20" />
        <br>
        <br>
        <br>
    </div>
    
    <h3>Working for</h3>
        <?php 
            $name = $member['providerID'];
            echo form_dropdown('providerID', $provider_select, $name); ?>
        <br>
        <br>
        <br>
    
    <div>
        <?php
            $fields = array(             'First Name' => 'first_name',
                                          'Last Name' => 'last_name',
                       'Street (e.g. 300 S. Ashland)' => 'street',
                                               'City' => 'city',
                                              'State' => 'state',
                                                'Zip' => 'zipcode',
                                     'Contact Number' => 'phone',
                                      'Email Address' => 'email',
                                           'Password' => 'password',
                                   'Confirm Password' => 'password_confirm'
            );
            
            $textAreas = array(
                $member['about_me'] => 'about_me'
            );
                           
            foreach($fields as $current_value => $field){
                if(stripos($field, "email") === FALSE){
                    $type = "text";
                }
                else{
                    $type = "text";
                }
                    echo  '<input type='.$type.' name="'.$field.'" value="'.$current_value.'" /><br>';
            }
            foreach($textAreas as $current_value => $field) {
                echo "<textarea rows='10' cols ='20'name='".$field."' placeholder=''>".$current_value."</textarea><br>"; 
            }
        ?>
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
<!--       <label for = "yes">Yes</label>-->
       <input type='radio' name='has_car' id='no' value='0' <?=$no?> >&nbsp;&nbsp;No&nbsp;&nbsp;&nbsp;&nbsp;
<!--      <label for = "sizeMed">medium</label>-->
      &nbsp;&nbsp;Responder has a vehicle?
    </div>
    <div class='clearfix'>
        <?//=@$issues_template_output?>
        <?//=@$service_areas_template_output?> 
    </div>
     <br><br>
    <input type="submit" name="submit" value="Save" /> 
</form>
</div>

