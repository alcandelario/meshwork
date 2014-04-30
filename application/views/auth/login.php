<?php
    $logo_src =  base_url()."//media//images//meshwork_logo.png";
?>
<div class='outer_login_container'>
    <div class="login_container">
        <div class='inner_login_container'>
            <div class='login_logo clearfix'>
                <img src='<?=$logo_src?>'>
            </div>
            <h6 style='text-align: center;'>...community leaders, connected.</h6>


    <!--        <h1><?php //echo lang('login_heading');?></h1>
            <p><?php //echo lang('login_subheading');?></p>-->

            <div id="infoMessage"><?php echo $message;?></div>

            <?php echo form_open("login");?>

              <div class="clearfix">
                <h6 class='pull-left'><?php echo lang('login_identity_label', 'identity');?></h6>
                <?php echo form_input($identity);?>
              </div>

              <div class="clearfix">
                <h6 class='pull-left'><?php echo lang('login_password_label', 'password');?></h6>
                <?php echo form_input($password);?>
              </div>

              <div class='clearfix'>
                <h6 class=''>
                  <?php echo lang('login_remember_label', 'remember');?>
                  <?php echo form_checkbox('remember', '1', FALSE, 'id="remember"');?>
                </h6>
              </div>

              <div class="clearfix">
                  <?php echo form_submit('submit', lang('login_submit_btn'));?>
              </div>
            <?php echo form_close();?>
            
            <div class="password_links">
              <h6 class='center_text'><a href="forgot_password"><?php echo lang('login_forgot_password');?></a></h6>
            </div>
            <h2>Meshwork</h2>
        </div>
    </div>
</div>