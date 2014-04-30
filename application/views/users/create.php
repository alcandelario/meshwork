<?php echo validation_errors(); ?>

<div class='clearfix' style='margin: 0 auto; width:80%;'>
    <h2>Add New Responder</h2>
    <br>

    
<?php echo form_open_multipart('users/create') ?>
    
    <div class='profile_pic_upload' style='position:releative'>
        <?php 
            $src = base_url()."media/images/default_avatar.png";
            echo "<div style='max-width: 150px; margin: 10px;'><img style='border: 1px solid rgba(0,0,0,0.0); border-radius: 50%;' src='".$src."' alt=''></div>";
            
        ?>
        <span><a href=''>Upload New Photo (optional)</a></span>
        <input style='opacity: 0; position: absolute; left:0;' type="file" name="profile_photo" size="20" />
        <br>
        <br>
        <br>
    </div>
    
    <h3>Working for</h3>
        <?php echo form_dropdown('providerID', $provider_select, ''); ?>
        <br>
        <br>
        <br>
    <div>
        
        <?php
            $fields = array(                  'Title' => 'title',
                                         'First Name' => 'first_name',
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
                'About Me' => 'about_me'
            );
                           
            foreach($fields as $placeholder => $field){
                if(stripos($field, "email") === FALSE){
                    $type = "input";
                }
                else{
                    $type = "email";
                }
                    echo  '<input type='.$type.' placeholder="'.$placeholder.'" name="'.$field.'" /><br>';
            }
            foreach($textAreas as $placeholder => $field) {
                echo "<textarea rows='10' cols ='20'name='".$field."' placeholder='".$placeholder."'></textarea><br>"; 
            }
        ?>
    </div>
    <div> 
       <input type='radio' name='has_car' id='yes' value='1'>&nbsp;&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;
<!--       <label for = "yes">Yes</label>-->
       <input type='radio' name='has_car' id='no' value='0'>&nbsp;&nbsp;No&nbsp;&nbsp;&nbsp;&nbsp;
<!--      <label for = "sizeMed">medium</label>-->
      &nbsp;&nbsp;Responder has a vehicle?
    </div>
    <div class='clearfix'>
        <?//=@$issues_template_output?>
        <?//=@$service_areas_template_output?> 
    </div>
     <br><br>
    <input type="submit" name="submit" value="Create New Provider" /> 
</form>
</div>
